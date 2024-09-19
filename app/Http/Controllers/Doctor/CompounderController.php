<?php

namespace App\Http\Controllers\Doctor;

use App\Models\User;
use Inertia\Inertia;
use App\Models\Chamber;
use App\Traits\MediaTraits;
use Illuminate\Http\Request;
use App\Models\DoctorChamber;
use App\Models\CompounderDetails;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;

class CompounderController extends Controller
{
    use MediaTraits;
    public function index()
    {
        // return
        $compounders = User::with(['compounderDetails' => function ($query) {
            return $query->with(['chamber', 'slot']);
        }])
            ->where('role', 'compounder')
            ->where('doctor_id', auth()->id())
            ->get();

        $groupedSlotsByChamber = [];
        foreach ($compounders as $compounder) {
            foreach ($compounder->compounderDetails as $detail) {
                $chamberId = $detail->chamber->id;
                $slot = [
                    'slot_id' => $detail->slot->id,
                    'from_time' => $detail->slot->from_time,
                    'to_time' => $detail->slot->to_time,
                ];
                $chamberData = [
                    'chamber_id' => $detail->chamber->id,
                    'name' => $detail->chamber->name,
                ];

                $groupedSlotsByChamber[$chamberId]['chamber'] = $chamberData;
                $groupedSlotsByChamber[$chamberId]['slots'][] = $slot;
            }
        }
        // $compounders->map(function ($item) {

        //     $item->chamber_slots = [];
        //     $uniqueChamber = collect($item->compounderDetails)->unique('chamber_id')
        //         ->map(function ($chamber) use ($item) {
        //             $chamber->slots = collect($item->compounderDetails)
        //                 ->where('chamber_id', $chamber->chamber_id);
        //             dd($chamber->slots);
        //             return $chamber;
        //         });

        //     return $item;
        // });

        // return
        // $chambers = Chamber::whereHas('doctor_chamber')->with(['doctor_chamber' => function($query) {
        //     $query->where('user_id', auth()->id())
        //         ->with(['slots']);
        // }])->get();
        $chambers = DoctorChamber::where('user_id', auth()->id())
            ->with(['slots'])->get();
        return Inertia::render('Doctor/Compounder/Index', compact('compounders', 'chambers', 'groupedSlotsByChamber'));
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
            'role' => 'compounder',
        ];

        if ($request->password) {
            $data['password'] = Hash::make($request->password);
        }

        if ($request->hasFile('media')) {

            $opt = [
                'dir' => 'compounders',
            ];
            $res = $this->saveMedia($request->file('media'), $opt);
            if ($res->status) {
                $data['media'] = $res->id;
            }
        }
        if ($request->id) {
            $compounder = User::findOrFail($request->id);
            $opt['delete'] = $compounder?->media;
            $compounder->update($data);
            $action = 'updated';
            foreach ($request->slot_ids as $slot_id) {
                $compounder->compounderDetails()->create([
                    'chamber_id' => $request->chamber_id,
                    'slot_id' => $slot_id,
                    // 'status' => $request->status ? 1 : 0,
                ]);
            }

        } else {
            $data['doctor_id'] = auth()->id();
            $compounder = User::create($data);

            $action = 'created';
            foreach ($request->slot_ids as $slot_id) {
                $compounder->compounderDetails()->create([
                    'chamber_id' => $request->chamber_id,
                    'slot_id' => $slot_id,
                    // 'status' => $request->status ? 1 : 0,
                ]);
            }
        }

        return back()->with('message', "Compounder $action successfully");

    }

    public function delete($id)
    {
        $compounder = User::findOrFail($id);
        $compounder->compounderDetails()->delete();
        $compounder->delete();
        return back()->with('message', 'Compounder deleted successfully');
    }

}
