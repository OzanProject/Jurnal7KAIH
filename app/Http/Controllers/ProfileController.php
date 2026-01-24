<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): View
    {
        $user = $request->user();
        $availableParents = [];

        if ($user->role === 'siswa') {
            $availableParents = \App\Models\User::where('role', 'orang_tua')
                ->where('school_id', $user->school_id)
                ->get();
        }

        return view('profile.edit', [
            'user' => $user,
            'availableParents' => $availableParents,
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $data = $request->validated();
        $user = $request->user();

        if ($request->hasFile('avatar')) {
             if ($user->avatar) {
                 \Illuminate\Support\Facades\Storage::disk('public')->delete($user->avatar);
             }
             $data['avatar'] = $request->file('avatar')->store('avatars', 'public');
        }

        $user->fill($data);

        if ($user->isDirty('email')) {
            $user->email_verified_at = null;
        }

        $user->save();

        if ($user->role === 'siswa' && $user->student) {
            $user->student->update([
                'nisn' => $request->nisn,
                'gender' => $request->gender,
                'tempat_lahir' => $request->tempat_lahir,
                'tanggal_lahir' => $request->tanggal_lahir,
                'alamat' => $request->alamat,
                'nama_ayah' => $request->nama_ayah,
                'pekerjaan_ayah' => $request->pekerjaan_ayah,
                'nama_ibu' => $request->nama_ibu,
                'pekerjaan_ibu' => $request->pekerjaan_ibu,
                'no_hp_ortu' => $request->no_hp_ortu,
            ]);

            // Handle Parent Linking
            if ($request->has('parent_user_id')) {
                if ($request->parent_user_id) {
                    \App\Models\ParentModel::updateOrCreate(
                        ['student_id' => $user->student->id],
                        ['user_id' => $request->parent_user_id]
                    );
                } else {
                    \App\Models\ParentModel::where('student_id', $user->student->id)->delete();
                }
            }
        }

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
