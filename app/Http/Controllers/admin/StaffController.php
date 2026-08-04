<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;

use App\Models\Staff;
use App\Models\Departemen;
use App\Services\ActivityLogger;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class StaffController extends Controller
{
    /**
     * Display a listing of staff.
     */
    protected $modul        = "staff";
    protected $path         = "staff";
    protected $modul_name   = "Staff";
    protected $staff_id;

    public function __construct()
    {
        $this->staff_id = auth()->user()->staff_id;
    }

    public function index()
    {

        if (canAccess($this->modul, $this->staff_id, 'view') == false) {
            return redirect()->route('dashboard');
        }

        $departemens = Departemen::all();

        $staff = Staff::latest()->paginate(10);
   
        return view('staff.index', compact('staff'), [
            'canAdd'        => canAccess($this->modul, $this->staff_id, 'add'),
            'canEdit'       => canAccess($this->modul, $this->staff_id, 'edit'),
            'canDelete'     => canAccess($this->modul, $this->staff_id, 'delete'),
            'canDetail'     => canAccess($this->modul, $this->staff_id, 'detail'),
            'canRecycle'    => canAccess($this->modul, $this->staff_id, 'recycle'),
            'departemens'   => $departemens,
            'modul'         => $this->modul,
            'modul_path'    => $this->path,
            'modul_name'    => $this->modul_name,
            'modul_type'    => 'List'
        ]);

    }

    public function recycle()
    {
        if (canAccess($this->modul, $this->staff_id, 'recycle') == false) {
            if (canAccess($this->modul, $this->staff_id, 'view') == true) {
                return redirect()->route($this->modul.'.index')->with('warning', 'Tidak Memiliki Akses');
            } else {
                return redirect()->route('dashboard');
            }
        }

        $departemens = Departemen::all();

        return view($this->modul.'.recycle',
            [   
                'departemens'   => $departemens,
                'modul'         => $this->modul,
                'modul_path'    => $this->path,
                'modul_name'    => $this->modul_name,
                'modul_type'    => 'Recycle'
            ]);
        
    }

    /**
     * Show the form for creating a new Staff.
     */
    public function create()
    {
        // Group by module: product => [permissions]
        if (canAccess($this->modul, $this->staff_id, 'add') == false) {
            if (canAccess($this->modul, $this->staff_id, 'view') == true) {
                return redirect()->route($this->modul.'index')->with('warning', 'Tidak Memiliki Akses');
            } else {
                return redirect()->route('dashboard');
            }
        }

        $departemens = Departemen::all();

        return view($this->modul.'.create',
            [
                
                'departemens'   => $departemens,
                'modul'         => $this->modul,
                'modul_path'    => $this->path,
                'modul_name'    => $this->modul_name,
                'modul_type'    => 'Create'
            ]
        );
    }

    /**
     * Store a new Staff.
     */
    public function store(Request $request)
    {
        try {
            $request->validate([
                'code'          => 'nullable|string|max:255',
                'date_join'     => 'nullable|date',
                'name'          => 'required|string|max:255',
                'departemen_id' => 'nullable|exists:departemens,id',
                'position'      => 'nullable|string|max:255',
                'email'         => 'required|string|email|max:255',
                'phone'         => 'nullable|string|max:25',
                'address'       => 'nullable|string',
                'photo'         => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
                'status'        => 'nullable|string',
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            // Jika gagal validasi, redirect kembali ke halaman create dengan error message
            return redirect()
                ->back()
                ->withErrors($e->errors())
                ->withInput()
                ->with('error', 'Validasi gagal. Silakan periksa kembali data yang diinput.');
        }

        try {
            // Handle file upload
            $photoPath = 'images/profile/default.png';
            if ($request->hasFile('photo') && $request->file('photo')->isValid()) {
                $photo = $request->file('photo');
                $destinationPath = public_path('images/profile');
                // Create folder if it does not exist
                if (!file_exists($destinationPath)) {
                    mkdir($destinationPath, 0755, true);
                }
                $randomName = Str::random(20) . '.' . $photo->getClientOriginalExtension();
                $photo->move($destinationPath, $randomName);
                $photoPath = 'images/profile/' . $randomName;
            }

            // Temporary code generation if not provided
            $code = $request->filled('code') ? $request->code : strtoupper(Str::random(10));

            $staff = Staff::create([
                'code'         => $code,
                'name'         => $request->name,
                'departemen_id'=> $request->departemen_id,
                'position'     => $request->position,
                'email'        => $request->email,
                'phone'        => $request->phone,
                'address'      => $request->address,
                'date_join'    => $request->date_join,
                'photo'        => $photoPath,
                'status'       => $request->status,
                'is_active'    => $request->has('is_active') ? (bool) $request->is_active : false,
            ]);

            ActivityLogger::log(
                $this->modul,
                'create',
                $staff->getKey(),
                ['name' => $staff->name],
                auth()->id()
            );

            return redirect()->route($this->modul.'.index')->with('success', 'Data has been created successfully.');
            
        } catch (\Exception $e) {
            // Jika gagal simpan (misal: constraint DB, file, dsb.), kembali ke create dengan pesan error
            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Gagal membuat data. Silakan coba lagi. Error: '.$e->getMessage());
        }
    }
    /**
     * Show the form for editing the Staff.
     */
    public function edit(Staff $staff)
    {
        if (canAccess($this->modul, $this->staff_id, 'edit') == false) {
            if (canAccess($this->modul, $this->staff_id, 'view') == true) {
                return redirect()->route($this->modul.'.index')->with('warning', 'Tidak Memiliki Akses');
            } else {
                return redirect()->route('dashboard');
            }
        }


        $departemens = Departemen::all();

        return view($this->modul.'.edit', compact('staff', 'departemens'),
        [
            'modul'       => $this->modul,
            'modul_path'  => $this->path,
            'modul_name'  => $this->modul_name,
            'modul_type'  => 'Edit',
        ]);
    }

    /**
     * Update the Staff.
     */
    public function update(Request $request, Staff $staff)
    {
        try {
            $request->validate([
                'code'          => 'nullable|string|max:255',
                'date_join'     => 'nullable|date',
                'name'          => 'required|string|max:255',
                'departemen_id' => 'nullable|exists:departemens,id',
                'position'      => 'nullable|string|max:255',
                'email'         => 'required|string|email|max:255',
                'phone'         => 'nullable|string|max:25',
                'address'       => 'nullable|string',
                'photo'         => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
                'status'        => 'nullable|string',
            ]);

            // Handle file upload
            if ($request->hasFile('photo') && $request->file('photo')->isValid()) {
                // Delete old photo if it exists and is not default
                if ($staff->photo && $staff->photo !== 'images/profile/default.png' && file_exists(public_path($staff->photo))) {
                    unlink(public_path($staff->photo));
                }

                $photo = $request->file('photo');
                $destinationPath = public_path('images/profile');
                if (!file_exists($destinationPath)) {
                    mkdir($destinationPath, 0755, true);
                }
                $randomName = Str::random(20) . '.' . $photo->getClientOriginalExtension();
                $photo->move($destinationPath, $randomName);
                $staff->photo = 'images/profile/' . $randomName;
            }

            $staff->update([
                'code'         => $request->filled('code') ? $request->code : $staff->code,
                'name'         => $request->name,
                'departemen_id'=> $request->departemen_id,
                'position'     => $request->position,
                'email'        => $request->email,
                'phone'        => $request->phone,
                'address'      => $request->address,
                'date_join'    => $request->date_join,
                'status'       => $request->status,
                'is_active'    => $request->has('is_active') ? (bool) $request->is_active : false,
            ]);

            ActivityLogger::log(
                $this->modul,
                'update',
                $staff->getKey(),
                ['name' => $staff->name],
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
     * Remove the Staff.
     */
    public function destroy($id)
    {
        $staff = Staff::findOrFail($id);

        ActivityLogger::log(
            $this->modul,
            'delete',
            $staff->getKey(),
            [],
            auth()->user() ? auth()->user()->getKey() : null
        );

        $staff->delete();

        return response()->json([
            'success' => true,
            'message' => 'Data has been deleted successfully.'
        ]);
    }

    public function getData(Request $request)
    {

        $query = DB::table('staffs')
            ->leftJoin('departemens', 'staffs.departemen_id', '=', 'departemens.id')
            ->select([
                'staffs.*',
                'departemens.name as departemen'
            ])
            ->whereNull('staffs.deleted_at');

        if ($request->filled('filter_name')) {
    
            $query->where(function($q) use ($request) {
                $q->where('staffs.name', 'like', "%{$request->filter_name}%");
            });
        
        }

        if ($request->filled('filter_departemen')) {
    
            $query->where(function($q) use ($request) {
                $q->where('staffs.departemen_id', "{$request->filter_departemen}");
            });
        
        }
            
        $data = $query->get();

        return DataTables::of($data)
            ->addColumn('mobile_view', function ($row) {
                $photoUrl = $row->photo ? asset($row->photo) : asset('images/profile/default.png');
                return '
                <div class="mobile-expandable">
                    <div class="flex items-center">
                        <div class="avatar flex-shrink-0 size-10">
                            <img class="rounded-full" src="'.$photoUrl.'" alt="avatar">
                        </div>
                        <div style="width:16px;"></div>
                        <div class="fw-bold flex-1">'
                            . (strlen($row->name) > 20 ? e(Str::limit($row->name, 20, '...')) : e($row->name)) .
                        '</div>
                        <div class="flex-shrink-0 flex items-center">
                            <a class="toggle-expand btn btn-xs btn-secondary ml-2 flex items-center justify-center" style="height:28px;">
                                <i class="fa fa-chevron-down"></i>
                            </a>
                        </div>
                    </div>
                    <div class="mobile-details mt-2" style="display:none;">
                        <div class="mobile-meta">
                            <span class="flex items-center mb-1"><i class="fa-solid fa-tag mr-2"></i><span class="fw-bold">'.e($row->code).'</span></span>
                            <span class="flex items-center mb-1"><i class="fa-solid fa-envelope mr-2"></i><span class="fw-bold">'.e($row->email).'</span></span>
                            <span class="flex items-center"><i class="fa-solid fa-users mr-2"></i><span class="fw-bold">'.e($row->departemen).'</span></span>
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
                            </div>
                        </div>
                    </div>
                </div>
                ';
            })
            ->addColumn('action', function ($row) {
                
                $btn = "";

                if (canAccess($this->modul, $this->staff_id, 'edit')) {

                    $btn .= view('components.datatables.button-edit', [
                        
                        'id' => $row->id,
                        'modul' => $this->modul,
                    
                        ])->render();

                }
                
                if (canAccess($this->modul, $this->staff_id, 'delete')) {

                    $btn .= view('components.datatables.button-delete', [
                        'id'    => $row->id,
                        'name'  => $row->name,
                    ])->render();
                
                }

                return $btn;
            })->rawColumns(['action', 'mobile_view'])
            ->make(true);
    }

    public function getDataRecycle(Request $request)
    {

        $query = DB::table('staffs')
            ->leftJoin('departemens', 'staffs.departemen_id', '=', 'departemens.id')
            ->select([
                'staffs.*',
                'departemens.name as departemen'
            ])
            ->whereNotNull('staffs.deleted_at');

        if ($request->filled('filter_name')) {
            
            $query->where(function($q) use ($request) {
                $q->where('staffs.name', 'like', "%{$request->filter_name}%");
            });
        }

        if ($request->filled('filter_departemen')) {
    
            $query->where(function($q) use ($request) {
                $q->where('staffs.departemen_id', "{$request->filter_departemen}");
            });
        
        }
            
        $data = $query->get();

        return DataTables::of($data)
            ->addColumn('mobile_view', function ($row) {
                $photoUrl = $row->photo ? asset($row->photo) : asset('images/profile/default.png');
                return '
                <div class="mobile-expandable">
                    <div class="flex items-center">
                        <div class="avatar flex-shrink-0 size-10">
                            <img class="rounded-full" src="'.$photoUrl.'" alt="avatar">
                        </div>
                        <div style="width:16px;"></div>
                        <div class="fw-bold flex-1">'
                            . (strlen($row->name) > 20 ? e(Str::limit($row->name, 20, '...')) : e($row->name)) .
                        '</div>
                        <div class="flex-shrink-0 flex items-center">
                            <a class="toggle-expand btn btn-xs btn-secondary ml-2 flex items-center justify-center" style="height:28px;">
                                <i class="fa fa-chevron-down"></i>
                            </a>
                        </div>
                    </div>
                    <div class="mobile-details mt-2" style="display:none;">
                        <div class="mobile-meta">
                            <div class="action-mobile">
                                '.view('components.datatables.button-restore', [
                                    'id' => $row->id,
                                    'name' => $row->name,
                                ])->render().'
                            </div>
                        </div>
                    </div>
                </div>
                ';
            })
            ->addColumn('action', function ($row) {
                
                $btn = "";

                $btn .= view('components.datatables.button-restore', [
                    'id' => $row->id,
                    'name' => $row->name,
                ])->render();

                return $btn;
            })->rawColumns(['action', 'mobile_view'])
            ->make(true);
    }

    public function restore($id){

        $data = Staff::onlyTrashed()->findOrFail($id);
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
}
