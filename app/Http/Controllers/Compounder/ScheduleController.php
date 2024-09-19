<?php

namespace App\Http\Controllers\Compounder;

use App\Models\Appointment;
use App\Http\Controllers\Controller;
use App\Models\DoctorChamber;
use App\Models\OffDay;
use App\Models\Slot;
use App\Models\SlotModifier;
use App\Models\User;
use App\Models\VisitationTrack;
use App\Traits\MediaTraits;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;

class ScheduleController extends Controller
{
    use MediaTraits;
    public function index()
    {
        $compounder = User::find(auth()->id());
        $id = $compounder->doctor_id;
        $doctor = User::find($id);

        $day = strtolower(Carbon::today()->dayName);

        $doctor_chamber = DoctorChamber::with([
            'chamber',
            'slots' => function ($query) use ($day) {
                    return $query->withCount([
                        'appointments' => fn($q) => $q->whereDate('date', now()),
                    ])
                    ->with(['modifier' => fn($q) => $q->with('user')
                            ->whereDate('date', now())
                            ->where('day', $day)
                            ->orderBy('created_at', 'desc'),
                    ])
                    ->where('day', $day);
            },
        ])
            ->where('user_id', $id)
            ->get();
        $off_days = OffDay::whereDate('date', now())->where('day', $day)->get();
        $date = now()->format('Y-m-d');

        $doctor_chamber = collect($doctor_chamber)->filter(function ($item) use ($day) {
            $item->weekdays = in_array($day, $item->weekdays) ? [$day] : [];
            return count($item->weekdays) > 0;
        });
        return Inertia::render('Compounder/Schedule/Index', compact('doctor', 'doctor_chamber', 'day', 'off_days', 'date'));
    }

    public function update(Request $request)
    {
        DB::beginTransaction();
        $compounder = User::find(auth()->id());
        SlotModifier::where([
            'doctor_id' => $request->doctor_id,
            'slot_id' => $request->slot_id,
            'date' => $request->date,
            'day' => $request->day,
            'status' => true,
        ])->update([
            'status' => false,
        ]);
        $slot = SlotModifier::create([
            'slot_id' => $request->slot_id,
            'date' => $request->date,
            'day' => $request->day,
            'doctor_id' => $request->doctor_id,
            'from_time' => $request->from_time,
            'to_time' => $request->to_time,
            'updated_by' => $compounder->doctor_id,
            'status' => true,
        ]);
        $appointments = Appointment::where([
            'slot_id' => $request->slot_id,
            'date' => $request->date,
            'doctor_id' => $request->doctor_id,
        ])->get();

        $smsData = [];
        foreach($appointments as $item) {
            $phone = $item->phone;
            if (valid_phone($phone)) {
                $link = route('track_redirect', $item->tracking_code);
                $smsData[] = [
                    'to' => valid_phone($phone),
                    'message' => 'প্রিয় গ্রাহক, সময় পরিবর্তন হয়েছে।\nনতুন সময়- '.convert24to12($request->from_time).'.\nবিস্তারিত-\n'.$link,
                ];
            }
        }
        $uniqueSmsData = collect($smsData)->unique('to');
        $data = [];
        foreach($uniqueSmsData as $item) {
            $data[] = $item;
        }
        
        if (count($data)) {
            sendMultipleMessage($data);
        }
        // if (count($valid_phone)) {
        //     $unique_phones = collect($valid_phone)->unique()->toArray();
        //     $number_string = implode(',', $unique_phones);
        //     sendMessage($number_string, 'প্রিয় গ্রাহক, সময় পরিবর্তন হয়েছে।\nনতুন সময়-'.convert24to12($request->from_time)).'. বিস্তারিত-\n';
        // }
        DB::commit();

        return back()->with('message', 'Schedule updated successfully');
    }

    public function visitation($id)
    {
        $slot = Slot::with([
            'modifier' => fn($q) => $q->where('status', 1),
            'doctor_chamber' => fn($q) => $q->with('chamber'),
            'visitation_tracks'=>fn($q)=>$q->whereDate('date', '=', today()),
            'appointments' => fn($q) => $q->whereDate('date', '=', today()),
        ])->findOrFail($id);
        $report = Slot::with([
            'modifier' => fn($q) => $q->where('status', 1),
            'doctor_chamber' => fn($q) => $q->with('chamber'),
            'visitation_tracks'=>fn($q)=>$q->whereDate('date', '=', today()),
            'appointments' => fn($q) => $q->whereDate('date', '=', today())->where('is_report',1),
        ])->findOrFail($id);
        $date = now()->format('Y-m-d');
        if (@$slot->visitation_tracks->break_start_at) {
            $slot->visitation_tracks->end_at = Carbon::parse($slot->visitation_tracks->break_start_at)
                ->addMinutes((int) $slot->visitation_tracks->break_duration)
                ->format('h:i A');
        }

        foreach ($slot->appointments as $appointment) {
            $appointment->selectedType = $appointment->present === 1 && $appointment->is_running === 0 && $appointment->is_report === 0 && $appointment->is_next === 0 ? 'present' :
                ($appointment->present === 3 ? 'skip' :
                ($appointment->present === 0 ? 'serialized' :
                ($appointment->present === 7 ? 'seen' :
                ($appointment->present === 2 ? 'absent' :
                ($appointment->is_running === 1 && $appointment->present === 1 ? 'running' :
                ($appointment->is_report === 1 && $appointment->present === 1 ? 'report' :
                ($appointment->is_next === 1 && $appointment->present === 1 ? 'next' : 'Update Status')))))));
        }
        // return $slot;
        return Inertia::render('Compounder/Schedule/Visitation', compact('slot', 'date','report'));
    }

    public function start(Request $request, $id)
    {
        $compounder = User::find(auth()->id());
        VisitationTrack::create([
            'day' => $request->day,
            'date' => $request->date,
            'slot_id' => $request->slot_id,
            'doctor_id' => $compounder->doctor_id,
            'note' => $request->note,
            'doctor_chamber_id' => $request->doctor_chamber_id,
        ]);
        return back()->with('message', 'Visitation started');
    }

    public function update_status(Request $request)
    {
        $appointment = Appointment::findOrFail($request->appointment_id);

        $status = 1;

        switch ($request->status) {
            case 'absent':
                $status = 0;
                break;
            case 'present':
                $status = 1;
                break;
            case 'skip':
                $status = 2;
                break;

            default:
                $status = 1;
                break;
        }

        $data = [
            'present' => $status,
        ];

        $track = VisitationTrack::where([
            'date' => $appointment->date,
            'slot_id' => $appointment->slot_id,
            'doctor_chamber_id' => $appointment->chamber_id,
            'doctor_id' => $appointment->doctor_id,
        ])->update([
            'current_serial' => $appointment->serial_no,
            'last_serial' => $appointment->serial_no,
        ]);
        // dd($track, $appointment);
        $appointment->update($data);
        return back()->with('message', 'Status updated successfully');
    }

    public function save_break(Request $request)
    {
        $track = VisitationTrack::findOrFail($request->track_id);
        // dd($request->all());
        $track->update([
            'break_type' => $request->type,
            'break_duration' => $request->minute,
            'current_serial' => $request->type,
            'break_start_at' => now(),
        ]);

        return back()->with('message', 'Updated successfully');
    }

    public function prescriptionUpload(Request $request, $id)
    {
        // return

        $app = Appointment::findOrFail($id);

        $data = $request->validate([
            'media' => 'file',
        ]);

        if ($request->hasFile('media')) {

            $opt = [
                'dir' => 'compounders',
            ];
            $res = $this->saveMedia($request->file('media'), $opt);
            if ($res->status) {
                $data['media'] = $res->id;
            }
        }

          $app->update($data);

          return back()->with('message', "Prescription uploaded successfully");

    }
}
