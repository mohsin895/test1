<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Models\PatientType;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Inertia\Inertia;

class BookAppointmentController extends Controller
{
    public function index()
    {
        $patientTypes = PatientType::where('status', 1)->get();
        return Inertia::render('Admin/BookAppointment/Index', compact('patientTypes'));
    }

    public function filterDoctors(Request $request)
    {
        $query = User::with(['doctor_details', 'doctor_chambers' => function ($q) {
            return $q->with(['slots']);
        },
            'off_days'])->where('role', 'DOCTOR');
        if ($request->search) {
            $query->where('name', 'like', "%{$request->search}%");
        }

        $doctors = $query->paginate(12);
        return $doctors ?? [];
    }

    public function save(Request $request)
    {
        $request->validate([
            'patient_name' => 'required',
            'phone' => 'required',
            'patient_type_id' => 'required',
            'chamber_id' => 'required',
            'date' => 'required',
            // 'slot_id' => 'required',
        ]);

        $numeric_code = substr(str_shuffle(str_repeat($x = '0123456789', ceil(3 / strlen($x)))), 1, 1);
        $alpha_code = substr(str_shuffle(str_repeat($x = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ', ceil(3 / strlen($x)))), 1, 3);

        $track_code = $numeric_code . $alpha_code;
        $exist_code = Appointment::where('tracking_code', $track_code)->first();

        if ($exist_code) {
            dd('code exist!');
        }

        $date = date('Y-m-d', strtotime($request->date));

        $user = User::findOrFail($request->doctor_id);

        // if ($user->doctor_details->is_combine == 1) {
        //     $count_appoint = Appointment::where(['date' => $date, 'doctor_id' => $user->id, 'chamber_id' => $request->chamber])->count();
        //     $serial = $count_appoint + 1;
        // } else {
        // }
        $count_appoint = Appointment::where([
            'date' => $date,
            'doctor_id' => $user->id,
            'slot_id' => $request->slot_id,
            'chamber_id' => $request->chamber_id,
            'patient_type_id' => $request->patient_type_id,
        ])->count();
        $serial = $count_appoint + 1;

        $data = [
            'patient_name' => $request->patient_name,
            'phone' => $request->phone,
            'age' => $request->age,
            'patient_type_id' => $request->patient_type_id,
            'chamber_id' => $request->chamber_id,
            'date' => $date,
            'slot_id' => $request->slot_id,
            'tracking_code' => $track_code,
            'doctor_id' => $request->doctor_id,
            'serial_no' => $serial,
        ];

        $data['created_by'] = auth()->id();

        if (valid_phone($request->phone)) {
            $sms_content = 'হ্যালো '.$request->patient_name.', আপনার বুকিং সফল হয়েছে। সিরিয়াল '.$serial.'.\nবিস্তারিত-\n'.route('track_redirect', $track_code);
            sendMessage(valid_phone($request->phone), $sms_content);
        }

        Appointment::create($data);

        return redirect()->back()->with('message', 'Appointment Booked Successfully.');

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

    public function getDates(Request $request)
    {
        $doctor = User::with(['doctor_details', 'off_days', 'doctor_chambers' => function ($q) use ($request) {
            return $q->with(['chamber', 'slots'])->where('chamber_id', $request->chamber_id);
        }, 'degrees', 'specializations'])->findOrFail($request->user_id);

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
            return $q->with(['chamber', 'slots']);
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
}
