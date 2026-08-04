<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;
use App\Models\Staff;
use App\Models\Departemen;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class ProfileController extends Controller
{
    protected $modul        = "profile";
    protected $path         = "profile";
    protected $modul_name   = "Profile";
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): View
    {
        $departemens = Departemen::all();

        // Ambil user yang sedang login dan join dengan staff
        $user = $request->user();

        $userWithStaff = DB::table('staffs')
            ->rightJoin('users', 'users.staff_id', '=', 'staffs.id')
            ->where('users.id', $user->id)
            ->select('staffs.*', 'users.email as username', 'users.name as user_name', 'users.role_id as user_role_id')
            ->first();

        return view(
            $this->modul. '.edit',
            [
                'staff'        => $userWithStaff,
                'departemens'  => $departemens,
                'modul'        => $this->modul,
                'modul_path'   => $this->path,
                'modul_name'   => $this->modul_name,
                'modul_type'   => 'Edit',
            ]
        );
    }

    /**
     * Update the user's profile information.
     * Data profile disimpan ke table staff, email & password ke table user.
     * Password hanya diupdate jika diisi.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $user = $request->user();
        $validated = $request->validated();

        // Cek apakah user sudah punya staff_id
        if ($user->staff_id) {
            // Jika sudah punya staff, update staff
            $staff = Staff::find($user->staff_id);
            if ($staff) {
                // Upload photo jika ada file baru
                if ($request->hasFile('photo') && $request->file('photo')->isValid()) {
                    if ($staff->photo && $staff->photo !== 'images/profile/default.png' && file_exists(public_path($staff->photo))) {
                        @unlink(public_path($staff->photo));
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
                    'code'          => $validated['code'] ?? $staff->code,
                    'name'          => $validated['name'],
                    'date_join'     => $validated['date_join'] ?? $staff->date_join,
                    'departemen_id' => $validated['departemen_id'] ?? $staff->departemen_id,
                    'position'      => $validated['position'] ?? $staff->position,
                    'email'         => $validated['email'],
                    'phone'         => $validated['phone'] ?? $staff->phone,
                    'address'       => $validated['address'] ?? $staff->address,
                    'status'        => $validated['status'] ?? $staff->status,
                ]);
            }
        } else {
            // Jika tidak punya staff_id, create staff dulu lalu update users.staff_id
            $staffData = [
                'code'          => $validated['code'] ?? null,
                'name'          => $validated['name'],
                'date_join'     => $validated['date_join'] ?? null,
                'departemen_id' => $validated['departemen_id'] ?? null,
                'position'      => $validated['position'] ?? null,
                'email'         => $validated['email'],
                'phone'         => $validated['phone'] ?? null,
                'address'       => $validated['address'] ?? null,
                'status'        => $validated['status'] ?? null,
            ];

            // Handle photo juga saat create
            if ($request->hasFile('photo') && $request->file('photo')->isValid()) {
                $photo = $request->file('photo');
                $destinationPath = public_path('images/profile');
                if (!file_exists($destinationPath)) {
                    mkdir($destinationPath, 0755, true);
                }
                $randomName = Str::random(20) . '.' . $photo->getClientOriginalExtension();
                $photo->move($destinationPath, $randomName);
                $staffData['photo'] = 'images/profile/' . $randomName;
            } else {
                $staffData['photo'] = 'images/profile/default.png';
            }

            $newStaff = Staff::create($staffData);

            // Update users.staff_id
            $user->staff_id = $newStaff->id;
        }

        // Update table user: email, name, dan password (hanya jika diisi)
        $user->name = $validated['name'];
        $user->email = $validated['email'];

        if ($user->isDirty('email')) {
            $user->email_verified_at = null;
        }

        if (!empty($validated['password'])) {
            $user->password = Hash::make($validated['password']);
        }

        $user->save();

        return Redirect::route('profile.edit')->with('status', 'profile-updated');
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }
}
