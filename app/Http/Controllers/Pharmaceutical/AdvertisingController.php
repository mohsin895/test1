<?php

namespace App\Http\Controllers\Pharmaceutical;

use App\Http\Controllers\Controller;
use App\Models\Advertising;
use App\Models\Appointment;
use App\Models\Degree;
use App\Models\Designation;
use App\Models\DoctorAdvertise;
use App\Models\DoctorChamber;
use App\Models\Media;
use App\Models\Slot;
use App\Models\Specialization;
use App\Models\User;
use App\Traits\MediaTraits;
use Illuminate\Http\Request;
use Inertia\Inertia;

class AdvertisingController extends Controller
{
    use MediaTraits;
    public function index()
    {
        $designations = Designation::all();
        $specializations = Specialization::all();
        $degrees = Degree::all();
        return Inertia::render('Pharmaceutical/Advertising/Index', compact('designations', 'specializations', 'degrees'));
    }

    public function filterDoctors(Request $request)
    {
        $query = User::with(['advertisings', 'doctor_details', 'designations', 'degrees'])
            ->where('role', DOCTOR);

        if ($request->designation_ids) {
            $query->whereHas('designations', function ($q) use ($request) {
                $q->whereIn('designation_id', $request->designation_ids);
            });
        }

        if ($request->specialization_ids) {
            $query->whereHas('specializations', function ($q) use ($request) {
                $q->whereIn('specialization_id', $request->specialization_ids);
            });
        }

        if ($request->degree_ids) {
            $query->whereHas('degrees', function ($q) use ($request) {
                $q->whereIn('degree_id', $request->degree_ids);
            });
        }

        if ($request->search) {
            $query->where('name', 'like', "%{$request->search}%");
        }

        $query->where(function ($q) {
            $q->has('advertisings', '<', 10)->orWhereDoesntHave('advertisings');
        });

        $doctors = $query->paginate(12);

        return $doctors ?? [];

    }

    public function sendRequest(Request $request)
    {
        $request->validate([
            'days' => 'required',
        ]);

        $data = [
            'days' => $request->days,
            'offer_price' => $request->offer_price,
            'main_post' => $request->main_post,
            'special_medicines' => json_encode($request->special_medicines),
            'created_by' => auth()->id(),
        ];

        $uploadedMediaIds = [];

        if ($request->media) {
            $media = $request->media ?? [];
            $opt = [
                'dir' => 'advertising',
            ];
            foreach ($media as $file) {
                $res = $this->saveMedia($file, $opt);

                if ($res->status) {
                    $uploadedMediaIds[] = $res->id;
                }
            }
            $uploadedMediaIds = $uploadedMediaIds;
        }

        $data['media'] = $uploadedMediaIds;
        $advertise = Advertising::create($data);

        foreach ($request->doctor_ids as $doctorId) {
            $data = [
                'advertise_id' => $advertise->id,
                'status' => $request->status,
            ];

            User::findOrFail($doctorId)->advertisings()->create($data);
        }

        return redirect()->back()->with('message', "Advertising request has sent");

    }

    public function getAllRequest()
    {
        $advertises = Advertising::with('doctor_advertise')->get()->map(function ($item) {
            $item->media = Media::whereIn('id', $item->media ?? [])->get() ?? [];
            return $item;
        });
        return Inertia::render('Pharmaceutical/Advertising/AdvertisingList', compact('advertises'));
    }

    public function getDoctorPrescriptionList(Request $request, $id)
    {
        $doctorAdvertise = DoctorAdvertise::where('status', 'approved')
            ->with([
                'advertise' => function ($q) {
                    $q->with('created_by');
                },

            ])->find($id);

        $doctorId = $doctorAdvertise->doctor_id;
        $doctorChamber = DoctorChamber::where('user_id', $doctorId)->get() ?? [];
        $doctorChamber = collect($doctorChamber)->map(function ($item) use($doctorAdvertise) {
            $slots = Slot::where('doctor_chamber_id', $item->chamber_id ?? [])->get() ?? [];
            
            $item->slots = collect($slots)->map(function ($slot) use($doctorAdvertise) {
                $appointments = Appointment::with('media_file')
                                    ->where('slot_id', $slot->id ?? [])
                                    ->where('date', '>=', $doctorAdvertise->start_at)
                                    ->where('date', '<=', $doctorAdvertise->end_at)
                                    ->get() ?? [];
                $slot->appointments = $appointments;
                return $slot;
            }) ?? [];

            return $item;
        }) ?? [];

        $doctorAdvertise->totalAppointments = collect($doctorChamber)->sum(function($item) {
            return collect($item->slots)->sum(function($slot) {
                return count($slot->appointments);
            });
        });
        return Inertia::render('Pharmaceutical/Advertising/PrescriptionList', compact('doctorAdvertise', 'doctorChamber'));
    }

}
