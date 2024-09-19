<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Chamber;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Inertia\Inertia;

class ChamberController extends Controller
{
    public function index()
    {

        $data = Chamber::orderBy('id', 'desc')->get();

        return Inertia::render('Admin/Chamber/Index', compact('data'));
    }

    public function save(Request $request)
    {

        $request->validate([
            'name' => 'required',
            'lat' => 'required',
            'lon' => 'required',
            'city' => 'required',
            'division' => 'required',
            'address' => 'required',
        ]);

        $data = [
            'name' => $request->name,
            'lat' => $request->lat,
            'lon' => $request->lon,
            'city' => $request->city,
            'division' => $request->division,
            'featured' => $request->featured ? 1 : 0,
            'address' => $request->address,
            'settings' => $request->settings,
            'map' => $request->map
        ];

        // if($request->email && $request->password && !$request->id) {
        //     $exist = User::where('email', $request->email)->first();
        //     if(!$exist) {
        //         $user = User::create([
        //             'name' => $request->name,
        //             'email' => $request->email,
        //             'password' => Hash::make($request->password),
        //             'role' => 'hospital',
        //             'phone' => $request->phone,
        //         ]);
        //         $data['user_id'] = $user->id;
        //     }
        // }

        if ($request->id) {

            $chamber = Chamber::findOrFail($request->id);
            $data['updated_by'] = auth()->id();
            $chamber->update($data);

            return redirect()->back()->with('message', 'Chamber updated Successfully.');

        } else {
            $data['created_by'] = auth()->id();
    
            $chamber = Chamber::create($data);
            if($request->email && $request->password && !$request->id) {
                $exist = User::where('email', $request->email)->first();
                if(!$exist) {
                    $this->createHospital($chamber, $request);
                }
            }
            return redirect()->back()->with('message', 'Chamber save Successfully.');
        }
    }

    private function createHospital(Chamber $chamber, $request) {
        $user = User::create([
            'name' => $request->name,
            'phone' => $request->phone,
            'email' => $request->email,
            'role' => 'hospital',
            'password' => Hash::make($request->password),
        ]);
        $chamber->update([
            'user_id' => $user->id
        ]);
    }

    public function delete($id)
    {
        $hospital = Chamber::findOrFail($id);

        $hospital->update([
            'deleted_by' => auth()->id(),
            'deleted_at' => now(),
        ]);

        return redirect()->back()->with('message', 'Chamber deleted Successfully.');

    }
}
