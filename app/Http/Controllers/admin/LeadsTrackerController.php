<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;

use App\Models\Customer;
use App\Models\CustomerLead;
use App\Models\LtTargeting;
use App\Models\LtCampaign;
use App\Models\LtAdset;
use App\Models\LtCreative;
use App\Models\LtLead;
use App\Models\Province;
use App\Models\City;
use App\Services\ActivityLogger;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class LeadsTrackerController extends Controller
{
    /**
     * Display a listing of leads.
     */
    protected $modul        = "leads-tracker";
    protected $path         = "leads-tracker";
    protected $modul_name   = "Leads Tracker";
    protected $role_id;
    protected $staff_id;

    public function __construct()
    {
        $this->staff_id = auth()->user()->staff_id ?? auth()->id(); // Fallback if staff_id is null
        $this->role_id = auth()->user()->role_id;
    }

    public function index()
    {
        // if (canAccess($this->modul, $this->role_id, 'view') == false) {
        //     return redirect()->route('dashboard');
        // }

        $provinces  = Province::all();
        $targetings = LtTargeting::orderBy('id')->get();

        return view($this->modul.'.index', [
            'canAdd'        => canAccess($this->modul, $this->role_id, 'add'),
            'canEdit'       => canAccess($this->modul, $this->role_id, 'edit'),
            'canDelete'     => canAccess($this->modul, $this->role_id, 'delete'),
            'canDetail'     => canAccess($this->modul, $this->role_id, 'detail'),
            'canRecycle'    => canAccess($this->modul, $this->role_id, 'recycle'),
            'provinces'     => $provinces,
            'targetings'    => $targetings,
            'modul'         => $this->modul,
            'modul_path'    => $this->path,
            'modul_name'    => $this->modul_name,
            'modul_type'    => 'List'
        ]);
    }

    /* =====================================================
     *  TARGETING CRUD
     * ===================================================== */

    public function targetingStore(Request $request)
    {
        $request->validate([
            'label'    => 'required|string|max:255',
            'area'     => 'required|string|max:255',
            'interest' => 'required|string',
        ]);

        $targeting = LtTargeting::create([
            'label'      => $request->label,
            'area'       => $request->area,
            'interest'   => $request->interest,
            'created_by' => auth()->id(),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Targeting berhasil disimpan.',
            'data'    => $targeting,
        ]);
    }

    public function targetingUpdate(Request $request, $id)
    {
        $request->validate([
            'label'    => 'required|string|max:255',
            'area'     => 'required|string|max:255',
            'interest' => 'required|string',
        ]);

        $targeting = LtTargeting::findOrFail($id);
        $targeting->update([
            'label'    => $request->label,
            'area'     => $request->area,
            'interest' => $request->interest,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Targeting berhasil diperbarui.',
            'data'    => $targeting,
        ]);
    }

    public function targetingDestroy($id)
    {
        $targeting = LtTargeting::findOrFail($id);
        $targeting->delete();

        return response()->json([
            'success' => true,
            'message' => 'Targeting berhasil dihapus.',
        ]);
    }

    public function getTargetingData(Request $request)
    {
        $data = LtTargeting::orderBy('id')->get()->map(function ($t, $idx) {
            return [
                'id'       => $t->id,
                'code'     => 'T' . str_pad($idx + 1, 2, '0', STR_PAD_LEFT),
                'label'    => $t->label,
                'area'     => $t->area,
                'interest' => $t->interest,
            ];
        });

        return response()->json(['success' => true, 'data' => $data]);
    }

    /* =====================================================
     *  OVERVIEW / DASHBOARD STATS
     * ===================================================== */

    public function getOverviewData()
    {
        // --- KPI Leads ---
        $totalLeads = LtLead::count();
        $qualified  = LtLead::whereIn('status', ['Qualified', 'Closed Won'])->count();
        $won        = LtLead::where('status', 'Closed Won')->count();
        $wonRate    = $totalLeads > 0 ? round(($won / $totalLeads) * 100) : 0;

        // --- Total Spend ---
        $totalSpend = LtCreative::sum('spend');

        // --- Leads per Targeting Segment ---
        // Join lt_leads.ref -> lt_creatives.ref -> lt_adsets -> lt_targetings
        $targetings = LtTargeting::orderBy('id')->get();
        $creatives  = LtCreative::all()->keyBy('ref');   // ref => creative
        $adsets     = LtAdset::all()->keyBy('id');        // id => adset

        $segmentCounts = [];
        LtLead::whereNotNull('ref')->get()->each(function ($lead) use ($creatives, $adsets, $targetings, &$segmentCounts) {
            $creative = $creatives->get($lead->ref);
            $adset    = $creative ? $adsets->get($creative->adset_id) : null;
            if ($adset) {
                $tIdx = $targetings->search(fn($t) => $t->id === $adset->targeting_id);
                $key  = $tIdx !== false ? 'T' . str_pad($tIdx + 1, 2, '0', STR_PAD_LEFT) : 'N/A';
            } else {
                $key = 'N/A';
            }
            $segmentCounts[$key] = ($segmentCounts[$key] ?? 0) + 1;
        });

        // --- Spend per Brand ---
        $campaigns = LtCampaign::orderBy('id')->get()->keyBy('id');
        $brandSpend = [];
        LtCreative::with('adset.campaign')->get()->each(function ($cr) use (&$brandSpend, $campaigns, $adsets) {
            $adset = $adsets->get($cr->adset_id);
            $camp  = $adset ? $campaigns->get($adset->campaign_id) : null;
            $key   = $camp ? $camp->brand : 'Other';
            $brandSpend[$key] = ($brandSpend[$key] ?? 0) + (float)$cr->spend;
        });

        // --- Pipeline breakdown ---
        $pipeline = LtLead::selectRaw('status, COUNT(*) as count')
            ->groupBy('status')
            ->pluck('count', 'status');

        return response()->json([
            'success' => true,
            'kpi' => [
                'total_leads' => $totalLeads,
                'qualified'   => $qualified,
                'won_rate'    => $wonRate . '%',
                'total_spend' => $totalSpend,
            ],
            'chart_segment' => [
                'labels' => array_keys($segmentCounts),
                'data'   => array_values($segmentCounts),
            ],
            'chart_brand' => [
                'labels' => array_keys($brandSpend),
                'data'   => array_values($brandSpend),
            ],
            'pipeline' => $pipeline,
        ]);
    }

    /* =====================================================
     *  CAMPAIGN CRUD
     * ===================================================== */

    public function campaignStore(Request $request)
    {
        $request->validate([
            'brand'     => 'required|string|max:20',
            'objective' => 'required|string|max:10',
            'name'      => 'required|string|max:255',
        ]);

        $campaign = LtCampaign::create([
            'brand'      => strtoupper($request->brand),
            'objective'  => $request->objective,
            'name'       => $request->name,
            'created_by' => auth()->id(),
        ]);

        return response()->json(['success' => true, 'message' => 'Campaign berhasil disimpan.', 'data' => $campaign]);
    }

    public function campaignUpdate(Request $request, $id)
    {
        $request->validate([
            'brand'     => 'required|string|max:20',
            'objective' => 'required|string|max:10',
            'name'      => 'required|string|max:255',
        ]);

        $campaign = LtCampaign::findOrFail($id);
        $campaign->update([
            'brand'     => strtoupper($request->brand),
            'objective' => $request->objective,
            'name'      => $request->name,
        ]);

        return response()->json(['success' => true, 'message' => 'Campaign berhasil diperbarui.', 'data' => $campaign]);
    }

    public function campaignDestroy($id)
    {
        LtCampaign::findOrFail($id)->delete();
        return response()->json(['success' => true, 'message' => 'Campaign berhasil dihapus.']);
    }

    public function getCampaignData()
    {
        $data = LtCampaign::orderBy('id')->get()->map(function ($c, $idx) {
            return [
                'id'        => $c->id,
                'code'      => str_pad($idx + 1, 2, '0', STR_PAD_LEFT) . '-' . $c->brand . '-' . $c->objective,
                'brand'     => $c->brand,
                'objective' => $c->objective,
                'name'      => $c->name,
            ];
        });

        return response()->json(['success' => true, 'data' => $data]);
    }

    /* =====================================================
     *  ADSET CRUD
     * ===================================================== */

    public function adsetStore(Request $request)
    {
        $request->validate([
            'campaign_id'  => 'required|integer',
            'targeting_id' => 'required|integer',
            'name'         => 'required|string|max:255',
            'conversion'   => 'required|string',
        ]);

        $adset = LtAdset::create([
            'campaign_id'  => $request->campaign_id,
            'targeting_id' => $request->targeting_id,
            'name'         => $request->name,
            'conversion'   => $request->conversion,
            'created_by'   => auth()->id(),
        ]);

        return response()->json(['success' => true, 'message' => 'Ad Set berhasil disimpan.', 'data' => $adset]);
    }

    public function adsetUpdate(Request $request, $id)
    {
        $request->validate([
            'campaign_id'  => 'required|integer',
            'targeting_id' => 'required|integer',
            'name'         => 'required|string|max:255',
            'conversion'   => 'required|string',
        ]);

        $adset = LtAdset::findOrFail($id);
        $adset->update([
            'campaign_id'  => $request->campaign_id,
            'targeting_id' => $request->targeting_id,
            'name'         => $request->name,
            'conversion'   => $request->conversion,
        ]);

        return response()->json(['success' => true, 'message' => 'Ad Set berhasil diperbarui.', 'data' => $adset]);
    }

    public function adsetDestroy($id)
    {
        LtAdset::findOrFail($id)->delete();
        return response()->json(['success' => true, 'message' => 'Ad Set berhasil dihapus.']);
    }

    public function getAdsetData()
    {
        $adsets = LtAdset::with(['campaign', 'targeting'])->orderBy('id')->get();
        $campaigns  = LtCampaign::orderBy('id')->get();
        $targetings = LtTargeting::orderBy('id')->get();

        $campList = $campaigns->values();
        $targList = $targetings->values();

        $data = $adsets->map(function ($s) use ($campList, $targList) {
            $cIdx = $campList->search(fn($c) => $c->id === $s->campaign_id);
            $tIdx = $targList->search(fn($t) => $t->id === $s->targeting_id);
            $camp = $s->campaign;
            $target = $s->targeting;

            $campCode = ($cIdx !== false && $camp)
                ? str_pad($cIdx + 1, 2, '0', STR_PAD_LEFT) . '-' . $camp->brand . '-' . $camp->objective
                : '??';

            $adsetCode = $campCode . '-T' . str_pad(($tIdx !== false ? $tIdx + 1 : 0), 2, '0', STR_PAD_LEFT);

            return [
                'id'           => $s->id,
                'code'         => $adsetCode,
                'name'         => $s->name,
                'camp_code'    => $campCode,
                'camp_id'      => $s->campaign_id,
                'targeting_id' => $s->targeting_id,
                'targeting_label' => $target ? $target->label : '-',
                'conversion'   => $s->conversion,
            ];
        });

        return response()->json(['success' => true, 'data' => $data]);
    }

    /* =====================================================
     *  CREATIVE (ADS) CRUD
     * ===================================================== */

    private function generateRef(): string
    {
        $chars = 'ABCDEFGHJKLMNPQRSTUVWXYZ23456789';
        do {
            $ref = '';
            for ($i = 0; $i < 4; $i++) $ref .= $chars[random_int(0, strlen($chars) - 1)];
        } while (LtCreative::withTrashed()->where('ref', $ref)->exists());
        return $ref;
    }

    public function creativeStore(Request $request)
    {
        $request->validate([
            'adset_id' => 'required|integer',
            'name'     => 'required|string|max:255',
            'format'   => 'required|string',
            'no'       => 'required|string|max:10',
        ]);

        $creative = LtCreative::create([
            'adset_id'   => $request->adset_id,
            'ref'        => $this->generateRef(),
            'name'       => $request->name,
            'format'     => $request->format,
            'no'         => $request->no,
            'spend'      => 0,
            'created_by' => auth()->id(),
        ]);

        return response()->json(['success' => true, 'message' => 'Ads berhasil disimpan.', 'data' => $creative]);
    }

    public function creativeUpdate(Request $request, $id)
    {
        $request->validate([
            'adset_id' => 'required|integer',
            'name'     => 'required|string|max:255',
            'format'   => 'required|string',
            'no'       => 'required|string|max:10',
        ]);

        $creative = LtCreative::findOrFail($id);
        $creative->update([
            'adset_id' => $request->adset_id,
            'name'     => $request->name,
            'format'   => $request->format,
            'no'       => $request->no,
        ]);

        return response()->json(['success' => true, 'message' => 'Ads berhasil diperbarui.', 'data' => $creative]);
    }

    public function creativeUpdateSpend(Request $request, $id)
    {
        $creative = LtCreative::findOrFail($id);
        $creative->update(['spend' => $request->spend ?? 0]);
        return response()->json(['success' => true, 'message' => 'Spend diperbarui.']);
    }

    public function creativeDestroy($id)
    {
        LtCreative::findOrFail($id)->delete();
        return response()->json(['success' => true, 'message' => 'Ads berhasil dihapus.']);
    }

    public function getCreativeData()
    {
        $creatives = LtCreative::with(['adset.campaign', 'adset.targeting'])->orderBy('id')->get();
        $adsets    = LtAdset::with('campaign')->orderBy('id')->get();

        $data = $creatives->map(function ($cr) use ($adsets) {
            $adset = $cr->adset;
            $camp  = $adset ? $adset->campaign : null;

            $adsetIdx  = $adsets->search(fn($s) => $s->id === $cr->adset_id);
            $adsetCode = ($adsetIdx !== false && $adset && $camp)
                ? str_pad($adsetIdx + 1, 2, '0', STR_PAD_LEFT) . '-' . $camp->brand . '-Ads'
                : '??';

            return [
                'id'         => $cr->id,
                'ref'        => $cr->ref,
                'adset_id'   => $cr->adset_id,
                'adset_code' => $adsetCode,
                'adset_name' => $adset ? $adset->name : '-',
                'name'       => $cr->name,
                'format'     => $cr->format,
                'no'         => $cr->no,
                'spend'      => $cr->spend,
            ];
        });

        return response()->json(['success' => true, 'data' => $data]);
    }

    /* =====================================================
     *  LT LEADS CRUD
     * ===================================================== */

    public function ltLeadStore(Request $request)
    {
        $request->validate([
            'name'   => 'required|string|max:255',
            'ref'    => 'nullable|string|max:10',
            'wa'     => 'nullable|string|max:30',
            'status' => 'nullable|string',
        ]);

        $lead = LtLead::create([
            'lead_date'  => $request->lead_date ?: now()->toDateString(),
            'ref'        => strtoupper($request->ref ?? ''),
            'title'      => $request->title ?? 'Mr.',
            'name'       => $request->name,
            'wa'         => $request->wa,
            'status'     => $request->status ?? 'Fresh Lead',
            'created_by' => auth()->id(),
        ]);

        return response()->json(['success' => true, 'message' => 'Lead berhasil disimpan.', 'data' => $lead]);
    }

    public function ltLeadUpdate(Request $request, $id)
    {
        $request->validate([
            'name'   => 'required|string|max:255',
            'ref'    => 'nullable|string|max:10',
            'wa'     => 'nullable|string|max:30',
            'status' => 'nullable|string',
        ]);

        $lead = LtLead::findOrFail($id);
        $lead->update([
            'lead_date' => $request->lead_date ?: now()->toDateString(),
            'ref'       => strtoupper($request->ref ?? ''),
            'title'     => $request->title ?? 'Mr.',
            'name'      => $request->name,
            'wa'        => $request->wa,
            'status'    => $request->status ?? 'Fresh Lead',
        ]);

        return response()->json(['success' => true, 'message' => 'Lead berhasil diperbarui.', 'data' => $lead]);
    }

    public function ltLeadUpdateStatus(Request $request, $id)
    {
        $lead = LtLead::findOrFail($id);
        $lead->update(['status' => $request->status]);
        return response()->json(['success' => true, 'message' => 'Status diperbarui.']);
    }

    public function ltLeadDestroy($id)
    {
        LtLead::findOrFail($id)->delete();
        return response()->json(['success' => true, 'message' => 'Lead berhasil dihapus.']);
    }

    public function getLtLeadData(Request $request)
    {
        $query = LtLead::orderBy('created_at', 'desc');

        if ($request->filled('q')) {
            $q = $request->q;
            $query->where(function ($sq) use ($q) {
                $sq->where('name', 'like', "%$q%")
                   ->orWhere('wa', 'like', "%$q%");
            });
        }

        $data = $query->get()->map(fn($l) => [
            'id'        => $l->id,
            'lead_date' => $l->lead_date,
            'ref'       => $l->ref,
            'title'     => $l->title,
            'name'      => $l->name,
            'wa'        => $l->wa,
            'status'    => $l->status,
        ]);

        return response()->json(['success' => true, 'data' => $data]);
    }

    public function recycle()
    {
        
        $provinces = Province::all();

        return view($this->modul.'.recycle',
            [   
                'provinces'     => $provinces,
                'modul'         => $this->modul,
                'modul_path'    => $this->path,
                'modul_name'    => $this->modul_name,
                'modul_type'    => 'Recycle'
            ]);
        
    }

    /**
     * Show the form for creating a new Lead.
     */
    public function create()
    {
        $provinces = Province::all();

        return view($this->modul.'.create',
            [
                'provinces'     => $provinces,
                'modul'         => $this->modul,
                'modul_path'    => $this->path,
                'modul_name'    => $this->modul_name,
                'modul_type'    => 'Create'
            ]
        );
    }

    /**
     * Store a new Lead.
     */
    public function store(Request $request)
    {
        try {
            $request->validate([
                'name'          => 'required|string|max:255',
                'email'         => 'nullable|string|email|max:255',
                'phone'         => 'nullable|string|max:25',
                'province'      => 'nullable|string',
                'city'          => 'nullable|string',
                'address'       => 'nullable|string',
                'photo'         => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
                'status'        => 'nullable|string',
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return redirect()
                ->back()
                ->withErrors($e->errors())
                ->withInput()
                ->with('error', 'Validasi gagal. Silakan periksa kembali data yang diinput.');
        }

        try {
            // Handle file upload
            $photoPath = 'images/'.$this->path.'/default.png';
            if ($request->hasFile('photo') && $request->file('photo')->isValid()) {
                $photo = $request->file('photo');
                $destinationPath = public_path('images/'.$this->path);
                if (!file_exists($destinationPath)) {
                    mkdir($destinationPath, 0755, true);
                }
                $randomName = Str::random(20) . '.' . $photo->getClientOriginalExtension();
                $photo->move($destinationPath, $randomName);
                $photoPath = 'images/'.$this->path.'/' . $randomName;
            }

            $code = $request->filled('code') ? $request->code : strtoupper('LD-' . Str::random(8));

            $lead = CustomerLead::create([
                'code'         => $code,
                'name'         => $request->name,
                'source'       => $request->source,
                'interest'     => $request->interest,
                'email'        => $request->email,
                'phone'        => $request->phone,
                'province'     => $request->province,
                'city'         => $request->city,
                'address'      => $request->address,
                'photo'        => $photoPath,
                'status'       => $request->status ?? 'New',
                'created_by'   => auth()->id(),
            ]);

            ActivityLogger::log(
                $this->modul,
                'create',
                $lead->id,
                ['name' => $lead->name],
                auth()->id()
            );

            return redirect()->route($this->modul.'.index')->with('success', 'Data has been created successfully.');
            
        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Gagal membuat data. Silakan coba lagi. Error: '.$e->getMessage());
        }
    }

    /**
     * Show the form for editing the Lead.
     */
    public function edit($id)
    {
        $lead = CustomerLead::findOrFail($id);
        
        $provinces = Province::all();
        $cities = [];
        
        if ($lead->province) {
            $prov = Province::where('name', $lead->province)->first();
            if ($prov) {
                 $cities = City::where('province_id', $prov->id)->orderBy('name')->get();
            }
        }

        return view($this->modul.'.edit', compact('lead'),
        [
            'provinces'   => $provinces,
            'cities'      => $cities,
            'modul'       => $this->modul,
            'modul_path'  => $this->path,
            'modul_name'  => $this->modul_name,
            'modul_type'  => 'Edit',
        ]);
    }

    /**
     * Update the Lead.
     */
    public function update(Request $request, $id)
    {
        $lead = CustomerLead::findOrFail($id);
        
        try {
            $request->validate([
                'name'          => 'required|string|max:255',
                'email'         => 'nullable|string|email|max:255',
                'phone'         => 'nullable|string|max:25',
                'photo'         => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            ]);

            // Handle file upload
            if ($request->hasFile('photo') && $request->file('photo')->isValid()) {
                if ($lead->photo && $lead->photo !== 'images/profile/default.png' && file_exists(public_path($lead->photo))) {
                    unlink(public_path($lead->photo));
                }

                $photo = $request->file('photo');
                $destinationPath = public_path('images/profile');
                if (!file_exists($destinationPath)) {
                    mkdir($destinationPath, 0755, true);
                }
                $randomName = Str::random(20) . '.' . $photo->getClientOriginalExtension();
                $photo->move($destinationPath, $randomName);
                $lead->photo = 'images/profile/' . $randomName;
            }

            $lead->update([
                'name'         => $request->name,
                'source'       => $request->source,
                'interest'     => $request->interest,
                'email'        => $request->email,
                'phone'        => $request->phone,
                'province'     => $request->province,
                'city'         => $request->city,
                'address'      => $request->address,
                'status'       => $request->status,
            ]);

            ActivityLogger::log(
                $this->modul,
                'update',
                $lead->id,
                ['name' => $lead->name],
                auth()->id()
            );

            return redirect()->route($this->modul.'.index')->with('success', 'Data updated successfully.');
        } catch (\Exception $e) {
             return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Gagal update data. Silakan coba lagi. Error: '.$e->getMessage());
        }
    }

    /**
     * Remove the Lead.
     */
    public function destroy($id)
    {
        $lead = CustomerLead::findOrFail($id);
        $lead->delete();

        return response()->json([
            'success' => true,
            'message' => 'Data has been deleted successfully.'
        ]);
    }

    public function getData(Request $request)
    {
        $query = DB::table('customer_leads')
            ->leftJoin('customers', 'customer_leads.id', '=', 'customers.customer_lead_id')
            ->select([
                'customer_leads.*',
                'customers.id as customer_id' 
            ])
            ->whereNull('customer_leads.deleted_at');

        if ($request->filled('filter_province')) {
            $query->where('customer_leads.province', $request->filter_province);
        }
        
        if ($request->filled('filter_city')) {
            $query->where('customer_leads.city', $request->filter_city);
        }

        if ($request->filled('filter_source')) {
            $query->where('customer_leads.source', $request->filter_source);
        }

        if ($request->filled('filter_status')) {
            $query->where('customer_leads.status', $request->filter_status);
        }

        $data = $query->get();

        return DataTables::of($data)
            ->addColumn('mobile_view', function ($row) {
                
                $photoUrl = $row->photo ? asset($row->photo) : asset('images/'.$this->path.'/default.png');
                return '
                <div class="mobile-expandable">
                    <div class="flex items-center justify-between" style="padding:15px;">
                        <div class="avatar flex-shrink-0 size-10">
                            <img class="rounded-full" src="'.$photoUrl.'" alt="avatar">
                        </div>
                        <div style="width:16px;"></div>
                        <div class="fw-bold flex-1">' . e($row->name) . '</div>
                        <a class="toggle-expand btn btn-xs btn-secondary">
                            <i class="fa fa-chevron-down"></i>
                        </a>
                    </div>
                    <div class="mobile-details mt-2" style="display:none;">
                        <div class="mobile-meta" style="padding: 10px 0;">
                            <span class="flex items-center mb-2"><i class="fa-solid fa-magnifying-glass-location mr-2"></i><span class="fw-bold" style="margin-left:8px;">'.e($row->province).' - '.e($row->city).'</span></span>
                            <span class="flex items-center mb-2"><i class="fa-solid fa-hashtag mr-2"></i><span class="fw-bold" style="margin-left:8px;">'.e($row->source).'</span></span>
                            <span class="flex items-center mb-2"><i class="fa-solid fa-heart mr-2"></i><span class="fw-bold" style="margin-left:8px;">'.e($row->interest).'</span></span>
                            <span class="flex items-center mb-2"><i class="fa-solid fa-phone mr-2"></i><span class="fw-bold" style="margin-left:8px;">'.e($row->phone).'</span></span>
                            <span class="flex items-center mb-2"><i class="fa-solid fa-location-dot mr-2"></i><span class="fw-bold" style="margin-left:8px;">'.e($row->address).'</span></span>
                            <br>
                            <div class="action-mobile">
                                ' . view('components.datatables.button-edit', [
                                    'id' => $row->id,
                                    'modul' => $this->modul,
                                ])->render() . '
                                ' . view('components.datatables.button-delete', [
                                    'id' => $row->id,
                                    'name' => $row->name,
                                ])->render() . '
                                ' . (!$row->customer_id ? view('components.datatables.button-move', [
                                    'id' => $row->id,
                                    'name' => $row->name,
                                ])->render() : '') . '
                            </div>
                        </div>
                    </div>
                </div>
                ';
            })
            ->addColumn('action', function ($row) {
                $btn = "";
                
                // Add to Customer Button
                
                if (!$row->customer_id) {
                    $btn .= view('components.datatables.button-move', [
                        'id' => $row->id,
                        'modul' => $this->modul,
                    ])->render();
                }

                $btn .= view('components.datatables.button-edit', [
                    'id' => $row->id,
                    'modul' => $this->modul,
                ])->render();

                $btn .= view('components.datatables.button-delete', [
                    'id'    => $row->id,
                    'name'  => $row->name,
                ])->render();

                return $btn;
            })
            ->rawColumns(['action', 'mobile_view'])
            ->make(true);
    }

    public function getDataRecycle(Request $request)
    {
        $query = DB::table('customer_leads')
            ->select(['customer_leads.*'])
            ->whereNotNull('customer_leads.deleted_at');

        if ($request->filled('filter_province')) {
            $query->where('customer_leads.province', $request->filter_province);
        }
        
        if ($request->filled('filter_city')) {
            $query->where('customer_leads.city', $request->filter_city);
        }

        if ($request->filled('filter_source')) {
            $query->where('customer_leads.source', $request->filter_source);
        }

        if ($request->filled('filter_status')) {
            $query->where('customer_leads.status', $request->filter_status);
        }

        $data = $query->get();

        return DataTables::of($data)
            ->addColumn('action', function ($row) {
                return view('components.datatables.button-restore', [
                    'id' => $row->id,
                    'name' => $row->name,
                ])->render();
            })
            ->make(true);
    }

    public function restore($id){

        $data = CustomerLead::onlyTrashed()->findOrFail($id);
        $data->restore();

        ActivityLogger::log(
            $this->modul,      // modul
            'restore',          // action
            $data->id,         // transaction_id
            ['name' => $data->name], // payload (WAJIB ARRAY)
            auth()->id()        // user_id
        );

        return response()->json([
            'status'  => true,
            'message' => 'Data has been restored successfully.'
        ]);
    }
    
    public function addToCustomer($id)
    {
        try {
            $lead = CustomerLead::findOrFail($id);

            // Check if already exists in customers
            $exists = Customer::where('customer_lead_id', $lead->id)->exists();
            if ($exists) {
                return response()->json(['success' => false, 'message' => 'Lead already converted to Customer.']);
            }

            // Create Customer
            Customer::create([
                'code'              => 'CUST-' . $lead->code, 
                'customer_lead_id'  => $lead->id,
                'name'              => $lead->name,
                'source'            => $lead->source,
                'interest'          => $lead->interest,
                'email'             => $lead->email,
                'phone'             => $lead->phone,
                'province'          => $lead->province,
                'city'              => $lead->city,
                'address'           => $lead->address,
                'photo'             => $lead->photo,
                'status'            => 'Active',
                'is_active'         => true,
                'created_by'        => auth()->id(),
            ]);

            return response()->json(['success' => true, 'message' => 'Lead successfully added to Customers.']);

        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()]);
        }
    }

    public function getCities($province_name)
    {
        $province = Province::where('name', $province_name)->first();
        if (!$province) {
            return response()->json([]);
        }
        
        return response()->json($province->cities()->orderBy('name')->get());
    }
}
