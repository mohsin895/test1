<?php

namespace App\Http\Controllers\Doctor;

use App\Http\Controllers\Controller;
use App\Models\Chamber;
use App\Models\Degree;
use App\Models\Designation;
use App\Models\DoctorChamber;
use App\Models\DoctorDesignations;
use App\Models\Medical;
use App\Models\OffDay;
use App\Models\Role;
use App\Models\Slot;
use App\Models\Specialization;
use App\Models\User;
use App\Traits\MediaTraits;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Inertia\Inertia;

class ConfigurationController extends Controller
{
    use MediaTraits;

    public function index()
    {

        $data = User::where('role', 'doctor')->get();
        $roles = Role::all();

        return Inertia::render('Doctor/Configuration/Index', compact('data', 'roles'));
    }

    public function configurations()
    {
        $id = auth()->id();

        $doctor = User::with(['doctor_details', 'doctor_chambers' => function ($q) {
            return $q->with(['chamber', 'slots']);
        }, 'designations', 'degrees', 'specializations'])->findOrFail($id);

        $designations = Designation::all();
        $specializations = Specialization::all();
        $medicals = Medical::all();
        $degrees = Degree::all();
        $chambers = Chamber::all();
        $week_days = weekdays;

        return Inertia::render('Doctor/Configuration/Configurations', compact('chambers', 'doctor', 'medicals', 'designations', 'specializations', 'degrees', 'week_days'));
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

    public function calender(Request $request)
    {
        $id = auth()->id();
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
        $weeks = $request->weeks ?? 4;
        foreach ($weekdays as $day) {
            $day_and_dates[] = collect($this->getNextWeekdayDates($days[strtoupper($day)], $weeks))->map(function ($item) use ($day) {
                return [
                    'day' => $day,
                    '_date' => $item,
                    'date' => $item,
                ];
            });
        }
        $active_dates = collect($day_and_dates)->flatMap(fn ($i) => $i)->sortBy('date')->values()->all();
        $active_dates = collect($active_dates)->map(function ($item) {
            $item['date'] = Carbon::parse($item['date'])->format('D, d F Y');
            return $item;
        })->unique('_date')->values();

        return Inertia::render('Doctor/Configuration/Calender', compact('doctor', 'active_dates', 'slots', 'weeks'));
    }

    public function save_calender(Request $request)
    {
        $id = auth()->id();
        $doctor = User::findOrFail($id);
        DB::beginTransaction();
        if (!$request->off_days && $request->date && $request->day && !$request->is_full_day) {
            OffDay::where([
                'doctor_id' => $doctor->id,
                'date' => $request->date,
                'day' => $request->day
            ])->delete();
        } else {
            OffDay::updateOrCreate([
                'doctor_id' => $doctor->id,
                'day' => $request->day,
                'date' => $request->date
            ], [
                'doctor_id' => $doctor->id,
                'date' => $request->date,
                'day' => $request->day,
                'chamber_slot' => $request->off_days,
                'is_full_day' => $request->is_full_day || false,
            ]);
        }
        DB::commit();

        return back()->with('message', 'Saved successfully');
    }

    public function save_configurations(Request $request)
    {
        $id = auth()->id();
        $doctor = User::findOrFail($id);

        DB::beginTransaction();

        if ($request->appointmentSlot) {

            foreach ($request->appointmentSlot as $value) {
                Slot::updateOrCreate([
                    'chamber_id' => $value['chamber_id'],
                    'doctor_id' => $id,
                ], [
                    'title' => $value['title'],
                    'day' => $value['day'],
                    'limit' => $value['limit'],
                    'from_time' => $value['from_time'],
                    'to_time' => $value['to_time'],
                    'chamber_id' => $value['chamber_id'],
                    'doctor_id' => $id,
                ]);
            }

            $deletedIds = collect($request->appointmentSlot)->pluck('chamber_id')->unique();
            Slot::where('doctor_id', $id)
                ->whereNotIn('chamber_id', $deletedIds)->delete();
        }

        $doctor->doctor_details()->updateOrCreate(['user_id' => $id], [
            'specialization_ids' => $request->specializations,
            'designations_id' => $request->designations_id,
            'medical_id' => $request->medical_id,
            'degree_info' => $request->degree_info,
            'old_fees' => $request->old_fees,
            'weekdays' => $request->weekdays,
            'new_fees' => $request->new_fees,
            'report_fees' => $request->report_fees,
            'is_combine' => $request->is_combine,
        ]);

        DB::commit();

        return back()->with('message', 'Saved successfully');
    }

    public function save_specializations(Request $request)
    {
        $id = auth()->id();
        $doctor = User::findOrFail($id);
        DB::beginTransaction();
        foreach ($request->specializations as $id) {
            $data = [
                'user_id' => $doctor->id,
                'specialization_id' => $id
            ];
            $doctor->specializations()->updateOrCreate($data, $data);
        }

        $doctor->specializations()->whereNotIn('specialization_id', $request->specializations)->delete();
        DB::commit();
        return back()->with('message', 'Saved successfully');
    }

    public function save_degrees(Request $request)
    {
        $id = auth()->id();
        $doctor = User::findOrFail($id);

        DB::beginTransaction();
        foreach ($request->degree_info as $item) {
            $data = [
                'user_id' => $doctor->id,
                'degree_id' => $item['id']
            ];
            $doctor->degrees()->updateOrCreate($data, [
                ...$data,
                'note' => $item['note'],
            ]);
        }

        $doctor->degrees()->whereNotIn('degree_id', collect($request->degree_info)->pluck('id')->toArray())->delete();

        DB::commit();
        return back()->with('message', 'Saved successfully');
    }

    public function save_fees(Request $request)
    {
        $id = auth()->id();
        $doctor = User::findOrFail($id);

        DB::beginTransaction();

        $doctor->doctor_details()->updateOrCreate([
            'user_id' => $doctor->id
        ], [
            'old_fees' => $request->old_fees,
            'new_fees' => $request->new_fees,
            'report_fees' => $request->report_fees,
        ]);

        DB::commit();
        return back()->with('message', 'Saved successfully');
    }

    public function save_appointment_control(Request $request)
    {
        $id = auth()->id();
        $doctor = User::findOrFail($id);
    if($request->is_fixed == true){
        $request->validate([
            'appointment_end_date' => 'required',
          
            
        ]);
    }elseif($request->is_fixed == false){
        $request->validate([
            'appointment_week_limit' => 'required',
            
            
        ]);
    }
        $doctor->update([
            'appointment_end_date' => $request->appointment_end_date,
            'appointment_week_limit' => $request->appointment_week_limit,
            'is_fixed' => $request->is_fixed
        ]);

        return back()->with('message', 'Saved successfully');
    }

    public function save_weekdays(Request $request)
    {
        $id = auth()->id();
        $doctor = User::findOrFail($id);

        DB::beginTransaction();
        foreach ($request->payload as $payload) {
            $chamber = DoctorChamber::find($payload['id'])->update([
                'weekdays' => $payload['weekdays']
            ]);
            foreach ($payload['weekdays_with_slot'] as $day) {
                foreach ($day['slot'] as $slot) {
                    $slotData = [
                        'from_time' => $slot['from'],
                        'to_time' => $slot['to'],
                        'day' => $slot['day'],
                        'title' => '',
                        'limit' => $slot['limit'],
                        'doctor_chamber_id' => $payload['id'],
                    ];

                    if (isset($slot['id']) && $slot['id']) {
                        $slotData['updated_by'] = auth()->id();
                        Slot::where('id', $slot['id'])->update($slotData);
                    } else {
                        $slotData['created_by'] = auth()->id();
                        Slot::create($slotData);
                    }
                }
            }
        }
        $off_day = OffDay::where('doctor_id', $id)->get();
        if ($off_day) {
            foreach ($off_day as $slot) {
                foreach ($slot->chamber_slot as $day) {
                    $count = Slot::where([
                        'doctor_chamber_id' => $day['doctor_chamber_id'],
                    ])->count();
                    if ($count == count($day['slots'])) {
                        $slot->update(['is_full_day' => true]);
                    } else {
                        $slot->update(['is_full_day' => false]);
                    }
                    $slot_ids = Slot::whereIn('id', $day['slots'])->pluck('id')->toArray();

                    $chamber_slot = collect($slot->chamber_slot)->map(function ($item) use ($day, $slot_ids) {
                        if ($item['doctor_chamber_id'] == $day['doctor_chamber_id']) {
                            $item['slots'] = $slot_ids;
                        }
                        return $item;
                    });

                    $slot->update([
                        'chamber_slot' => $chamber_slot
                    ]);
                }
            }
        }
        DB::commit();
        return back()->with('message', 'Saved successfully');
    }

    public function save_chamber(Request $request)
    {
        $id = auth()->id();
        $doctor = User::findOrFail($id);

        DB::beginTransaction();

        if ($request->remove == 1) {
            DoctorChamber::where([
                'user_id' => $doctor->id,
                'chamber_id' => $request->chamber_id
            ])->delete();
        } else {
            DoctorChamber::updateOrCreate([
                'user_id' => $doctor->id,
                'chamber_id' => $request->chamber_id,
            ], [
                'user_id' => $doctor->id,
                'chamber_id' => $request->chamber_id,
            ]);
        }

        DB::commit();
        return back()->with('message', 'Saved successfully');
    }

    public function save_designations(Request $request)
    {
        $id = auth()->id();
        $doctor = User::findOrFail($id);
        
        DB::beginTransaction();
        DoctorDesignations::updateOrCreate(['user_id' => $doctor->id], [
            'designation_id' => $request->designation_id,
            'user_id' => $doctor->id,
            'medical_id' => $request->medical_id,
        ]);
    
        DB::commit();
        return back()->with('message', 'Saved successfully');
    }

}
