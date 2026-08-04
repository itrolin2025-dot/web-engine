<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;

use App\Models\Permissions;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class PermissionsController extends Controller
{
    /**
     * Display a listing of the permissions.
     */
    public function index()
    {
        // Group permissions by module (product → create/edit/delete)
        $modules = Permissions::orderBy('module')
            ->get()
            ->groupBy('module');

        return view('permissions.index', compact('modules'));
    }

    /**
     * Show the form for creating a new permission.
     */
    public function create()
    {
        return view('permissions.create');
    }

    /**
     * Store a newly created permission.
     */
    public function store(Request $request)
    {
        $request->validate([
            'module' => 'required|string',
            'name'   => 'required|string',
        ]);

        Permissions::create([
            'module' => $request->module,
            'name'   => $request->name,
            'slug'   => strtolower($request->module) . '.' . strtolower(Str::slug($request->name)),
        ]);

        return redirect()->route('permissions.index')->with('success', 'Permission created successfully.');
    }

    /**
     * Show the form for editing the permission.
     */
    public function edit(Permissions $permission)
    {
        return view('permissions.edit', compact('permission'));
    }

    /**
     * Update the specified permission.
     */
    public function update(Request $request, Permissions $permission)
    {
        $request->validate([
            'module' => 'required|string',
            'name'   => 'required|string',
        ]);

        $permission->update([
            'module' => $request->module,
            'name'   => $request->name,
            'slug'   => strtolower($request->module) . '.' . strtolower(Str::slug($request->name)),
        ]);

        return redirect()->route('permissions.index')->with('success', 'Permission updated successfully.');
    }

    /**
     * Remove the specified permission.
     */
    public function destroy(Permissions $permission)
    {
        $permission->roles()->detach();
        $permission->delete();

        return redirect()->route('permissions.index')->with('success', 'Permission deleted successfully.');
    }
}
