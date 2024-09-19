<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Traits\MediaTraits;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Inertia\Inertia;

class AdvertiserController extends Controller
{
    use MediaTraits;

    public function index()
    {
        $advertisers = User::where('role', 'advertiser')->get();
        return Inertia::render('Admin/Advertiser/Index', compact('advertisers'));
    }

    public function save(Request $request)
    {

        $request->validate([
            'name' => 'required',
            'email' => 'required',
            'phone' => 'required',
        ]);

        if (!$request->id) {
            $request->validate([
                'password' => 'required',
            ]);
        }

        $data = [
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'status' => $request->status ? 1 : 0,
            'role' => 'advertiser',
        ];

        if ($request->password) {
            $data['password'] = Hash::make($request->password);
        }

        if ($request->hasFile('media')) {

            $opt = [
                'dir' => 'advertisers',
            ];
            $res = $this->saveMedia($request->file('media'), $opt);
            if ($res->status) {
                $data['media'] = $res->id;
            }
        }
        if ($request->id) {
            $advertiser = User::findOrFail($request->id);
            $opt['delete'] = $advertiser?->media;
            $advertiser->update($data);
            $action = 'updated';

        } else {
            $data['doctor_id'] = auth()->id();
            $advertiser = User::create($data);
            $action = 'created';
        }

        return back()->with('message', "Advertiser $action successfully");

    }

    public function delete($id)
    {
        $advertiser = User::findOrFail($id);
        $this->deleteMedia($advertiser->media);
        $advertiser->delete();
        return back()->with('message', 'Advertiser deleted successfully');
    }
}
