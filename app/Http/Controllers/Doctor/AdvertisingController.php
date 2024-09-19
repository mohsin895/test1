<?php

namespace App\Http\Controllers\Doctor;

use App\Http\Controllers\Controller;
use App\Models\Advertising;
use App\Models\DoctorAdvertise;
use App\Models\Media;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Inertia\Inertia;

class AdvertisingController extends Controller
{
    public function index()
    {
        $doctor = Advertising::with(['doctor_advertise' => function ($query) {
            $query->where('doctor_id', auth()->id());

        }])
            ->whereHas('doctor_advertise', function ($query) {
                $query->where('doctor_id', auth()->id());

            })->get()->map(function ($item) {
            $item->media = Media::whereIn('id', $item->media ?? [])->get() ?? [];
            return $item;
        });

        return Inertia::render('Doctor/Advertising/Index', compact('doctor'));

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

    public function AdvertisingApproved($id)
    {
        $advertisingStatus = DoctorAdvertise::findOrFail($id);

        $doctor = User::with(['doctor_chambers'])->find($advertisingStatus->doctor_id);
        $weekdays = collect($doctor->doctor_chambers)->flatMap(function ($item) {
            return $item->weekdays;
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
        $dates = [];

        $ad_duration_days = (int)$advertisingStatus->advertise->days;
        $weeks = ceil($ad_duration_days / count($weekdays));
        foreach ($weekdays as $day) {
            $dates[] = collect($this->getNextWeekdayDates($days[strtoupper($day)], $weeks));
        }
        $active_dates = collect($dates)->flatMap(fn ($i) => $i)->sortBy('date')->values()->all() ?? [];
        $active_dates = collect($active_dates)->unique()->sort();
        $end_date = $active_dates[$ad_duration_days-1];
        $advertisingStatus->update([
            'status' => 'approved',
            'start_at' => now()->format('Y-m-d'),
            'end_at' => $end_date,
        ]);

        return redirect()->back()->with('message', "Offer has been Approved");
    }

    public function AdvertisingReject($id)
    {
        $doctor = DoctorAdvertise::findOrFail($id);
        $doctor->update(['status' => 'rejected']);
        return redirect()->back()->with('message', "Offer has been Rejected");
    }

    public function FilteredByStatus(Request $request)
    {

        $doctorId = auth()->id();
        $status = $request->status;

        $query = DoctorAdvertise::where('doctor_id', $doctorId)
            ->with(['advertise' => fn($q) => $q->with('created_by')]);
        if ($status !== 'all') {
            if ($status == 'ongoing') {
                $query->whereDate('end_at', '>', now());
            } elseif ($status == 'completed') {
                $query->whereDate('end_at', '<', now());
            } else {
                $query->where('status', $status);
            }
        }

        return $query->get()->map(function ($item) {
            $item->advertise->media = Media::whereIn('id', $item->advertise->media ?? [])->get() ?? [];
            return $item;
        }) ?? [];
    }
}
