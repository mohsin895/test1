<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Traits\MediaTraits;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Inertia\Inertia;

class ProfileController extends Controller
{
    use MediaTraits;

    public function index()
    {

        $user = User::first();

        return Inertia::render('Admin/Profile/Index', compact('user'));

    }

    public function changePassword(Request $request)
    {

        $request->validate([
            'password' => 'required|confirmed',
            'current_password' => 'required',
        ]);

        $user = User::find(auth()->user()->id);

        if (! Hash::check($request->current_password, $user->password)) {
            return redirect()->back()->withErrors(['current_password' => 'The provided current password does not match your current password.']);
        }

        $user->update([
            'password' => Hash::make($request->password),
        ]);

        return redirect()->back()->with('message', 'Password updated successfully.');

    }

    public function changeProfileInfo(Request $request)
    {

        $request->validate([
            'name' => 'required',
            'email' => 'required',
        ]);

        $data = [
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'status' => $request->status ? 1 : 0,
        ];

        $user = User::find(auth()->user()->id);

        if ($request->hasFile('media')) {
            $opt = [
                'dir' => 'user-profile',
            ];
            if ($user) {
                $opt['delete'] = $user->media;
            }
            $res = $this->saveMedia($request->file('media'), $opt);
            if ($res->status) {
                $data['media'] = $res->id;

            }
        }

        $user->update($data);

        return redirect()->back()->with('message', 'Profile updated successfully.');

    }
}
