<?php

namespace App\Http\Controllers\Doctor;

use App\Http\Controllers\Controller;
use App\Models\DoctorDetails;
use App\Models\Slot;
use Illuminate\Http\Request;
use App\Models\Appointment;
use App\Models\DoctorChamber;
use App\Models\PatientType;
use Carbon\Carbon;
use App\Models\User;
use Inertia\Inertia;

class BookAppoinmentController extends Controller
{
    public function index()
    {
       
        $doctor = User::with(['doctor_details','doctor_chambers' => function($query) {
                                return $query->with(['slots', 'chamber']);
                            }])->find(auth()->id());
     
        $patientTypes = PatientType::where('status', 1)->get();
        
        return Inertia::render('Doctor/NewAppointment/Index', compact( 'doctor', 'patientTypes'));
    }

    public function getDates(Request $request)
    {
        $doctor = User::with(['doctor_details', 'off_days', 'doctor_chambers' => function ($q) use ($request) {
            return $q->with(['chamber', 'slots'])->where('chamber_id', $request->chamber_id);
        }, 'degrees', 'specializations'])->findOrFail(auth()->id());

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

    public function save(Request $request)
    {
        $request->validate([
            'patient_name' => 'required',
            'phone' => 'required',
            'patient_type_id' => 'required',
            'chamber_id' => 'required',
            'date' => 'required',
            
        ]);

        $numeric_code = substr(str_shuffle(str_repeat($x = '0123456789', ceil(3 / strlen($x)))), 1, 1);
        $alpha_code = substr(str_shuffle(str_repeat($x = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ', ceil(3 / strlen($x)))), 1, 3);

        $track_code = $numeric_code . $alpha_code;
        $exist_code = Appointment::where('tracking_code', $track_code)->first();

        if ($exist_code) {
            dd('code exist!');
        }

        $date = date('Y-m-d', strtotime($request->date));

        $user = User::findOrFail(auth()->id());

        // if ($user->doctor_details->is_combine == 1) {
        //     $count_appoint = Appointment::where(['date' => $date, 'doctor_id' => $user->id, 'chamber_id' => $request->chamber])->count();
        //     $serial = $count_appoint + 1;
        // } else {
        // }
        $doctorDetails = DoctorDetails::where('user_id',auth()->id())->first();
       $fees =0;
       $isRunning =0;
       $reportType=0;
       $reportStatus=0;
       $status =NULL;
        if($request->patient_type_id == 1){
            $fees = $doctorDetails->new_fees;
        }elseif($request->patient_type_id == 2){
            $fees = $doctorDetails->old_fees;
        }elseif($request->patient_type_id == 3){
            $fees = $doctorDetails->report_fees;
            $reportType = $doctorDetails->report_fees > 0 ? 1:2;
            $reportStatus =1;
            $isRunning=1;
            $status='new';
        }
        $count_appoint = Appointment::where([
            'date' => $date,
            'doctor_id' => $user->id,
            'slot_id' => $request->slot_id,
            'chamber_id' => $request->chamber_id,
            'patient_type_id' => $request->patient_type_id,
        ])->count();
        $serial = $count_appoint + 1;
        $count_sorting = Appointment::where([
            'date' => $date,
            'doctor_id' => $user->id,
            'slot_id' => $request->slot_id,
            'chamber_id' => $request->chamber_id,
            
        ])->count();
        $sorting = $count_sorting  + 1;

        $data = [
            'patient_name' => $request->patient_name,
            'phone' => $request->phone,
            'age' => $request->age,
            'patient_type_id' => $request->patient_type_id,
            'chamber_id' => $request->chamber_id,
            'date' => $date,
            'slot_id' => $request->slot_id,
            'tracking_code' => $track_code,
            'doctor_id' => $user->id,
            'serial_no' => $serial,
            'sorting_order' => $sorting,
            'fees' => $fees,
            'is_report' => $isRunning,
            'report_type'=>$reportType,
            'report_status'=>$reportStatus,
            'status' => $status,
        ];

        $data['created_by'] = auth()->id();

        if (valid_phone($request->phone)) {
            $sms_content = 'হ্যালো '.$request->patient_name.', আপনার বুকিং সফল হয়েছে। সিরিয়াল '.$serial.'.\nবিস্তারিত-\n'.route('track_redirect', $track_code);
            sendMessage(valid_phone($request->phone), $sms_content);
        }

        Appointment::create($data);

        return redirect()->route('doctor.bookNew.list')->with('message', 'Appointment Booked Successfully.');

    }

    public function appontmentList()
    {
        $data = Appointment::where('doctor_id',auth()->id())->orderBy('id', 'desc')->get();
        $doctor = User::with(['doctor_details','doctor_chambers','doctor_chambers.chamber'])->find(auth()->id());

        return Inertia::render('Doctor/NewAppointment/AppointmentList', compact('data','doctor'));
    }

    public function getAppointments(Request $request) {
        $query = Appointment::where('doctor_id', auth()->id());

        if ($request->type) {
            if ($request->type == 'today') {
                $query->whereDate('date', date('Y-m-d'));
            }
            if ($request->type == 'upcoming') {
                $query->whereDate('date', '>', now());
            }
            if ($request->type == 'previous') {
                $query->whereDate('date', '<', now());
            }
            if ($request->type == 'tomorrow') {
                $query->whereDate('date', now()->addDay());
            }
            if ($request->type == 'all') {
                $query->get();
            }
        }
        if ($request->startAt) {
            $query->whereDate('date', '>=', $request->startAt);
        }
    
        if ($request->endAt) {
            $query->whereDate('date', '<=', $request->endAt);
        }
        if ($request->particularDate) {
            $query->whereDate('date', $request->particularDate);
        }
        if ($request->chamber_id) {
            $doctor_chamber = DoctorChamber::find($request->chamber_id);
            if ($doctor_chamber) {
                $query->where('chamber_id', $doctor_chamber->chamber_id);
            }
        }

        if ($request->slot_id) {
            $slot=Slot::find($request->slot_id);
            if($slot){
                $query->where('slot_id', $request->slot_id);
            }
           
        }
        

        $query->with(['slot','chamber'])->orderBy('id', 'desc');

        $data = $query->get() ?? [];
        
        return response()->json([
            'data' => $query->get(),
            'length' => count($data)
        ]);
    }
    public function getAppointmentsDateWise(Request $request) {
        $query = Appointment::where('doctor_id', auth()->id());
    
        if ($request->startAt) {
            $query->whereDate('date', '>=', $request->startAt);
        }
    
        if ($request->endAt) {
            $query->whereDate('date', '<=', $request->endAt);
        }
    
     
        $query->orderBy('date', 'desc');
    
        $query->with(['slot','chamber'])->orderBy('id', 'desc');
    
        $data = $query->get() ?? [];
        
        return response()->json([
            'data' => $data,
            'length' => count($data)
        ]);
    }
    
    public function slot(Request $request){
        $slots = Slot::where('doctor_chamber_id',$request->chamber_id)->get();
        return response()->json([
            'slots' => $slots,
           
        ]);
    }
    public function edit($id)
    {
        $appointmentList = Appointment::where('id', $id)->first();
        $patientTypes = PatientType::where('status', 1)->get();
        $doctor = User::with(['doctor_details','doctor_chambers','doctor_chambers.chamber'])->where('id',$appointmentList->doctor_id)->first();
    
        return Inertia::render('Doctor/NewAppointment/Edit', [
            'appointmentList' => $appointmentList,
            'id' => $id,
            'patientTypes'=>$patientTypes,
            'doctor'=>$doctor,
        ]);
    }
    
    public function update(Request $request){
        $dataInfo = Appointment::where('id', $request->id)->first();
        $dataInfo->patient_name=$request->patient_name;
        $dataInfo->phone=$request->phone;
        $dataInfo->age = $request->age;
        $dataInfo->patient_type_id=$request->patient_type_id;
        $dataInfo->save();
        return redirect()->route('doctor.bookNew.list');
    }
    public function delete($id)
    {
        $hospital = Appointment::findOrFail($id);
        $hospital->delete();

        // $hospital->update([
        //     'deleted_by' => auth()->id(),
        //     'deleted_at' => now(),
        // ]);

        return redirect()->back()->with('message', 'Appontment deleted Successfully.');

    }
}
