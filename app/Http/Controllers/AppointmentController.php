<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Models\Chamber;
use App\Models\PatientType;
use App\Models\Slot;
use App\Models\User;
use App\Models\VisitationTrack;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
use stdClass;

class AppointmentController extends Controller
{
    public function index(Request $request)
    {
        $doctor = User::with([
            'doctor_details',
            'specializations',
            'designations',
            'degrees',
            'doctor_chambers' => function ($q) {
                return $q->with(['slots']);
            },
            'off_days',
        ])->findOrFail($request->id);
        // , 'slots', '', '', '', '', 'off_days'
        $doc_slug = Str::slug($doctor->name);
        $types = PatientType::where('status', 1)->get();

        // if(!empty($doctor_details->chamber_ids)) {
        //     $chamber_ids = (array) json_decode($doctor_details->chamber_ids);
        //     $chambers = Chamber::whereIn('id', $chamber_ids)->get();
        // } else {
        //     $chambers = [];
        // }

        return view('frontend.bookAppointment', compact('doctor', 'doc_slug', 'types'));
    }

    public function getCode() {
        $numeric_code = substr(str_shuffle(str_repeat($x = '0123456789', ceil(3 / strlen($x)))), 1, 1);
        $alpha_code = substr(str_shuffle(str_repeat($x = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ', ceil(3 / strlen($x)))), 1, 3);

        return $numeric_code . $alpha_code;
    }

    public function storeAppointment(Request $request, $id)
    {
        $request->validate([
            'patient_name' => 'required',
            'phone' => 'required',
            'age' => 'required',
            'patient_type' => 'required',
            'chamber' => 'required',
            'date' => 'required',
            'slot' => 'required',
        ]);

        $user = User::findOrFail($id);

        $track_code = $this->getCode();

        $exist_code = Appointment::where('tracking_code', $track_code)->first();

        while ($exist_code) {
            $track_code = $this->getCode();
            $exist_code = Appointment::where('tracking_code', $track_code)->first();
        }

        $date = date('Y-m-d', strtotime($request->date));

        // if ($user->doctor_details->is_combine == 1) {
        //     $count_appoint = Appointment::where([
        //         'date' => $date,
        //         'doctor_id' => $user->id,
        //         'slot_id' => $request->slot,
        //         'chamber_id' => $request->chamber,
        //         'patient_type_id' => $request->patient_type,
        //     ])->count();

        //     $serial = $count_appoint + 1;
        // } else {
        // }
        $count_appoint = Appointment::where([
            'date' => $date,
            'doctor_id' => $user->id,
            'slot_id' => $request->slot,
            'chamber_id' => $request->chamber,
            'patient_type_id' => $request->patient_type,
        ])->count();
        $serial = $count_appoint + 1;

        $data = [
            'patient_name' => $request->patient_name,
            'phone' => $request->phone,
            'age' => $request->age,
            'patient_type_id' => $request->patient_type,
            'chamber_id' => $request->chamber,
            'date' => $date,
            'slot_id' => $request->slot,
            'tracking_code' => $track_code,
            'doctor_id' => $user->id,
            'serial_no' => $serial,
        ];
        if (valid_phone($request->phone)) {
            $sms_content = 'হ্যালো '.$request->patient_name.', আপনার বুকিং সফল হয়েছে। সিরিয়াল '.$serial.'.\nবিস্তারিত-\n'.route('track_redirect', $track_code);
            sendMessage(valid_phone($request->phone), $sms_content);
            saveLog($sms_content, 'booking.log');
        }
        Appointment::create($data);

        return redirect()->route('appointmentTrack', $track_code)->with('message', 'Appointment Booked Successfully!');
    }

    public function getNextWeekdayDates($weekday, $count = 12)
    {
        $today = Carbon::today();
        $daysUntilNextWeekday = ($weekday - $today->dayOfWeek + 7) % 7;
        $nextDates = [];
        for ($i = 0; $i < $count; $i++) {
            $nextDate = $today->copy()->addDays($daysUntilNextWeekday + 7 * $i);
            $nextDates[] = $nextDate->toDateString();
        }
        return $nextDates;
    }

    public function getDates(Request $request, $id)
    {
        $doctor = User::with(['doctor_details', 'off_days', 'doctor_chambers' => function ($q) {
            return $q->with(['chamber', 'slots']);
        }, 'degrees', 'specializations'])->findOrFail($id);

        $weekdays = collect($doctor->doctor_chambers)->flatMap(function ($item) {
            return $item->weekdays;
        });

        $slots = collect($doctor->doctor_chambers)->flatMap(function ($item) {
            return $item->slots;
        });

        $days = [
            'SATURDAY' => Carbon::SATURDAY,
            'SUNDAY' => Carbon::SUNDAY,
            'MONDAY' => Carbon::MONDAY,
            'TUESDAY' => Carbon::TUESDAY,
            'WEDNESDAY' => Carbon::WEDNESDAY,
            'THURSDAY' => Carbon::THURSDAY,
            'FRIDAY' => Carbon::FRIDAY,
        ];

        $day_and_dates = [];
        $weeks = 4;
        if ($doctor->is_fixed && $doctor->appointment_week_limit) {
            $weeks = $doctor->appointment_week_limit;
        } else {
            $date_diff = now()->diffInWeeks($doctor->appointment_end_date);
            $weeks = $date_diff + 1;
        }

        foreach ($weekdays as $day) {
            $day_and_dates[] = collect($this->getNextWeekdayDates($days[strtoupper($day)], $weeks))
                ->filter(function ($item) use ($doctor) {
                    if ($doctor->appointment_end_date) {
                        return Carbon::parse($item)->lte($doctor->appointment_end_date);
                    }
                    return true;
                })
                ->map(function ($item) use ($day) {
                    return [
                        'day' => $day,
                        '_date' => $item,
                        'date' => $item,
                    ];
                });
        }
        $active_dates = collect($day_and_dates)->flatMap(fn($i) => $i)->sortBy('date')->values()->all();
        $active_dates = collect($active_dates)->map(function ($item) use ($doctor) {
            $item['date'] = Carbon::parse($item['date'])->format('l, d F Y');
            $filtered_off_day = collect($doctor->off_days)->filter(function ($d) use ($item) {
                return $d->date == $item['_date'] && $d->day == $item['day'];
            })->first();

            $item['full_off_day'] = $filtered_off_day ? (bool) $filtered_off_day->is_full_day : false;
            return $item;
        })->unique('_date')->values();

        return $active_dates;
    }

    public function getSlots(Request $request, $id)
    {
        $doctor = User::with(['doctor_details', 'off_days', 'doctor_chambers' => function ($q) {
            return $q->with(['chamber', 'slots' => fn($q) => $q->with('modifier')->orderBy('created_at', 'DESC')]);
        }, 'degrees', 'specializations'])->findOrFail($id);

        $slots = collect($doctor->doctor_chambers)->flatMap(function ($item) {
            return $item->slots;
        })->filter(function($item) use($request) {
            return $item->day == $request->day;
        })->map(function ($item) use ($request, $doctor) {
            $chamber_off_days = collect($doctor->off_days)
                ->filter(function ($i) use ($request, $item) {
                    if ($i->day == $request->day && $i->date == $request->date) {
                        $filtered_chamber_slot = collect($i->chamber_slot)
                            ->filter(function ($j) use ($item) {
                                return $j['doctor_chamber_id'] == 1 && in_array($item->id, $j['slots']);
                            });
                        if (count($filtered_chamber_slot)) {
                            return true;
                        }
                    }
                    return false;
                });
            $item->off_slot = count($chamber_off_days) > 0;
            $item->booked = Appointment::where([
                'slot_id' => $item->id,
                'doctor_id' => $doctor->id,
                'chamber_id' => $request->chamber_id,
                'date' => $request->date,
            ])->count();
            return $item;
        });

        $op_arr = [];
        foreach (collect($slots) as $item) {
            $op_arr[] = $item;
        }
        return $op_arr;
    }

    public function appointmentTrack($code = null)
    {
        $appointment = Appointment::with([
                        'slot' => function($query) {
                            return $query->with(['modifier' => fn($q) => $q->orderBy('created_at', 'desc')]);
                        },
                        'doctor_chamber' => fn($q) => $q->with('chamber'),
                        'chamber',
                        'doctor',
                    ])
                    ->where('tracking_code', $code)->first();
        if (!$appointment) {
            abort(404);
        }
        $track = null;
        $track = VisitationTrack::where([
            'slot_id' => $appointment->slot->id,
            'date' => $appointment->date,
            'day' => $appointment->slot->day,
        ])->first();
        if (@$track) {
            $track->end_at = Carbon::parse($track->break_start_at)
                ->addMinutes((int) $track->break_duration)
                ->format('h:i A');
        }

        $changed_time = '';

        try {
            $modifier = collect($appointment->slot->modifier)->first();
            $changed_time = convert24to12($modifier->from_time);
        } catch (\Throwable $th) {}
        
        if (request()->ajax()) {
            return response()->json(compact('appointment', 'track', 'changed_time'));
        }
        return view('frontend.appointmentDetails', compact('appointment', 'track', 'changed_time'));
    }

    public function appointmentTrackAjax($code = null)
    {
        $appointment = Appointment::with([
            'slot' => function($query) {
                return $query->with(['modifier' => fn($q) => $q->orderBy('created_at', 'desc')]);
            },
            'doctor_chamber' => fn($q) => $q->with('chamber'),
            'doctor',
        ])
            ->where('tracking_code', $code)->first();
        $track = null;
        $track = VisitationTrack::where([
            'slot_id' => $appointment->slot->id,
            'date' => $appointment->date,
            'day' => $appointment->slot->day,
        ])->first();
        if (@$track) {
            $track->end_at = Carbon::parse($track->break_start_at)
                ->addMinutes((int) $track->break_duration)
                ->format('h:i A');
        }
        $changed_time = '';

        try {
            $modifier = collect($appointment->slot->modifier)->first();
            $changed_time = convert24to12($modifier->from_time);
        } catch (\Throwable $th) {}

        return response()->json(compact('appointment', 'track', 'changed_time'));
    }

    public function mapDistance($userLat, $userLng, $destinationLat, $destinationLng)
    {
        $apiKey = 'AIzaSyDhIlCdeDI_Im1XrLOxjocXsCCbWmK-_2M';
        $url = "https://maps.googleapis.com/maps/api/distancematrix/json";
        $op = new stdClass;
        $op->status = false;

        try {
            $response = Http::get($url, [
                'origins' => $userLat . ',' . $userLng,
                'destinations' => $destinationLat . ',' . $destinationLng,
                'key' => $apiKey,
            ]);
            
            $data = $response->json();
            
            if ($data['status'] == 'OK') {
                $op->status = true;
                $op->data = $data['rows'];
            }
        } catch (\Throwable $th) {}

        return $op;
    }

    public function getDistance(Request $request)
    {
        $appointment = Appointment::with(['doctor_chamber'])->where('tracking_code', $request->code)->first();
        
        $distance = '';
        $duration = '';
        try {
            $lat = @$appointment->doctor_chamber->chamber->lat;
            $lon = @$appointment->doctor_chamber->chamber->lon;
            $response = $this->mapDistance($request->lat, $request->lon, $lat, $lon);
            if ($response->status) {
                $distance = @$response->data[0]['elements'][0]['distance']['text'];
                $duration = @$response->data[0]['elements'][0]['duration']['text'];
            }
        } catch (\Throwable $th) {}
        return response()->json(compact('distance', 'duration'));
    }

}
