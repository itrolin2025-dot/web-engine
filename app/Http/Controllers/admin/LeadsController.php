<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;

use App\Models\Customer;
use App\Models\CustomerLead;
use App\Models\Province;
use App\Models\City;
use App\Services\ActivityLogger;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class LeadsController extends Controller
{
    /**
     * Display a listing of leads.
     */
    protected $modul        = "leads";
    protected $path         = "leads";
    protected $modul_name   = "Leads";
    protected $role_id;
    protected $staff_id;

    public function __construct()
    {
        $this->staff_id = auth()->user()->staff_id ?? auth()->id(); // Fallback if staff_id is null
        $this->role_id = auth()->user()->role_id;
    }

    public function index()
    {

        // Permission check omitted for brevity if needed, but good to keep structure
        if (canAccess($this->modul, $this->role_id, 'view') == false) {
            return redirect()->route('dashboard');
        }

        $provinces = Province::all();

        return view('leads.index', [
            'canAdd'        => canAccess($this->modul, $this->role_id, 'add'),
            'canEdit'       => canAccess($this->modul, $this->role_id, 'edit'),
            'canDelete'     => canAccess($this->modul, $this->role_id, 'delete'),
            'canDetail'     => canAccess($this->modul, $this->role_id, 'detail'),
            'canRecycle'    => canAccess($this->modul, $this->role_id, 'recycle'),
            'provinces'     => $provinces,
            'modul'         => $this->modul,
            'modul_path'    => $this->path,
            'modul_name'    => $this->modul_name,
            'modul_type'    => 'List'
        ]);

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
