<?php

namespace App\Http\Controllers\Hospital;

use Carbon\Carbon;
use App\Models\Role;
use App\Models\Slot;
use App\Models\User;
use Inertia\Inertia;
use App\Models\Degree;
use App\Models\OffDay;
use App\Models\Chamber;
use App\Models\Medical;
use App\Models\Designation;
use App\Traits\MediaTraits;
use Illuminate\Http\Request;
use App\Models\DoctorChamber;
use App\Models\Specialization;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;

class DoctorController extends Controller
{
    use MediaTraits;

    public function index()
    {
        // return
        $data = User::whereHas('doctor_chambers.chamber', function($query){
            $query->where('user_id', auth()->id());
        })->where('role', 'doctor')->orderBy('id', 'desc')->get();
        $roles = Role::all();
        $specializations = Specialization::orderBy('created_at', 'desc')->get();

        return Inertia::render('Hospital/Doctor/Index', compact('data', 'roles', 'specializations'));
    }

    public function filter(Request $request) {
        // 
        $query = User::whereHas('doctor_chambers.chamber', function($query){
            $query->where('user_id', auth()->id());
        });
        if ($request->specializations) {
            $query->whereHas('specializations', function ($q) use ($request) {
                $q->whereIn('specialization_id', $request->specializations);
            });
        }
        if ($request->search) {
            $query->where('name', 'like', "%{$request->search}%");
        }

        $data = $query->where('role', 'doctor')->orderBy('id', 'desc')->get();

        return response()->json([
            'data' => $data
        ]);
    }

    public function configurations($id)
    {
        // return
        $doctor = User::with(['designations', 'doctor_details', 'doctor_chambers' => function ($q) {
            return $q->with(['chamber', 'slots']);
        }, 'degrees', 'specializations'])->findOrFail($id);

        $designations = Designation::all();
        $specializations = Specialization::all();
        $medicals = Medical::all();
        $degrees = Degree::all();
        $chambers = Chamber::all();
        $week_days = weekdays;

        return Inertia::render('Hospital/Doctor/Configurations', compact('chambers', 'doctor', 'medicals', 'designations', 'specializations', 'degrees', 'week_days'));
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

    public function calender(Request $request, $id)
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
        $weeks = $request->weeks ?? 12;
        foreach ($weekdays as $day) {
            $day_and_dates[] = collect($this->getNextWeekdayDates($days[strtoupper($day)], $weeks))->map(function ($item) use ($day) {
                return [
                    'day' => $day,
                    '_date' => $item,
                    'date' => $item,
                ];
            });
        }
        $active_dates = collect($day_and_dates)->flatMap(fn($i) => $i)->sortBy('date')->values()->all();
        $active_dates = collect($active_dates)->map(function ($item) {
            $item['date'] = Carbon::parse($item['date'])->format('D, d F Y');
            return $item;
        })->unique('_date')->values();

        return Inertia::render('Hospital/Doctor/Calender', compact('doctor', 'active_dates', 'slots', 'weeks'));
    }

    public function save_calender(Request $request, $id)
    {
        $doctor = User::findOrFail($id);
        DB::beginTransaction();
        if (!$request->off_days && $request->date && $request->day && !$request->is_full_day) {
            OffDay::where([
                'doctor_id' => $doctor->id,
                'date' => $request->date,
                'day' => $request->day,
            ])->delete();
        } else {
            OffDay::updateOrCreate([
                'doctor_id' => $doctor->id,
                'day' => $request->day,
                'date' => $request->date,
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

    public function save_configurations(Request $request, $id)
    {
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

    public function save_specializations(Request $request, $id)
    {
        $doctor = User::findOrFail($id);
        DB::beginTransaction();
        foreach ($request->specializations as $id) {
            $data = [
                'user_id' => $doctor->id,
                'specialization_id' => $id,
            ];
            $doctor->specializations()->updateOrCreate($data, $data);
        }

        $doctor->specializations()->whereNotIn('specialization_id', $request->specializations)->delete();
        DB::commit();
        return back()->with('message', 'Saved successfully');
    }

    public function save_degrees(Request $request, $id)
    {
        $doctor = User::findOrFail($id);

        DB::beginTransaction();
        foreach ($request->degree_info as $item) {
            $data = [
                'user_id' => $doctor->id,
                'degree_id' => $item['id'],
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

    public function save_fees(Request $request, $id)
    {
        $doctor = User::findOrFail($id);

        DB::beginTransaction();

        $doctor->doctor_details()->updateOrCreate([
            'user_id' => $doctor->id,
        ], [
            'old_fees' => $request->old_fees,
            'new_fees' => $request->new_fees,
            'report_fees' => $request->report_fees,
        ]);

        DB::commit();
        return back()->with('message', 'Saved successfully');
    }

    public function save_appointment_control(Request $request, $id)
    {
        $doctor = User::findOrFail($id);

        $doctor->update([
            'appointment_end_date' => $request->appointment_end_date,
            'appointment_week_limit' => $request->appointment_week_limit,
            'is_fixed' => $request->is_fixed,
        ]);

        return back()->with('message', 'Saved successfully');
    }

    public function save_weekdays(Request $request, $id)
    {
        $doctor = User::findOrFail($id);

        DB::beginTransaction();
        foreach ($request->payload as $payload) {
            $chamber = DoctorChamber::find($payload['id'])->update([
                'weekdays' => $payload['weekdays'],
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
                        'chamber_slot' => $chamber_slot,
                    ]);
                }
            }
        }
        DB::commit();
        return back()->with('message', 'Saved successfully');
    }

    public function save_chamber(Request $request, $id)
    {
        $doctor = User::findOrFail($id);

        DB::beginTransaction();

        if ($request->remove == 1) {
            DoctorChamber::where([
                'user_id' => $doctor->id,
                'chamber_id' => $request->chamber_id,
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

    public function save_designations(Request $request, $id)
    {
        $doctor = User::findOrFail($id);
        DB::beginTransaction();
        $doctor->designations()->updateOrCreate(['user_id' => $doctor->id], [
            'designation_id' => $request->designation_id,
            'user_id' => $doctor->id,
            'medical_id' => $request->medical_id,
        ]);
        DB::commit();
        return back()->with('message', 'Saved successfully');
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
            'role' => 'doctor',
            'status' => $request->status ? 1 : 0,
        ];

        if ($request->password) {
            $data['password'] = Hash::make($request->password);
        }

        if ($request->id) {
            $user = User::findOrFail($request->id);

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

            $data['updated_by'] = auth()->id();
            $user->update($data);

            return redirect()->back()->with('message', 'Doctor Information updated successfully');
        }

        if ($request->hasFile('media')) {
            $opt = [
                'dir' => 'user-profile',
            ];
            $res = $this->saveMedia($request->file('media'), $opt);
            if ($res->status) {
                $data['media'] = $res->id;
            }
        }

        $data['created_by'] = auth()->id();

        $doctor = User::create($data);
        $chamber = Chamber::where('user_id', auth()->id())->first();
        if($chamber) {
            DoctorChamber::create([
                'user_id' => $doctor->id,
                'chamber_id' => $chamber->id
            ]);
        }

        return redirect()->back()->with('message', 'Doctor Information save successfully');
    }

    public function delete($id)
    {
        $doctor = User::findOrFail($id);
        $doctor->update([
            'deleted_by' => auth()->id(),
            'deleted_at' => now(),
        ]);

        return redirect()->back()->with('message', 'Doctor Information deleted successfully');
    }
}
