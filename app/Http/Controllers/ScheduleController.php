<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Models\DoctorChamber;
use App\Models\DoctorDetails;
use App\Models\OffDay;
use App\Models\SerializedHistory;
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
        $id = auth()->id();
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
        return Inertia::render('Doctor/Schedule/Index', compact('doctor', 'doctor_chamber', 'day', 'off_days', 'date'));
    }

    public function update(Request $request)
    {
        DB::beginTransaction();
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
            'updated_by' => auth()->id(),
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
            // sendMultipleMessage($data);
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
    {  $doctorDetails =DoctorDetails::where('user_id',auth()->id())->first();
        $slot = Slot::with([
            'modifier' => fn($q) => $q->where('status', 1),
            'doctor_chamber' => fn($q) => $q->with('chamber'),
            'visitation_tracks'=>fn($q)=>$q->whereDate('date', '=', today()),
            'appointments.serializedStatus',
            'appointments.serializedStatus.userInfo',
            'appointments' => fn($q) => $q->whereDate('date', '=', today())->where('is_report','!=',1)->orderBy('sorting_order','asc'),
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
        return Inertia::render('Doctor/Schedule/Visitation', compact('slot', 'date','report','doctorDetails'));
    }

    
    public function start(Request $request, $id)
    {
        VisitationTrack::create([
            'day' => $request->day,
            'date' => $request->date,
            'slot_id' => $request->slot_id,
            'doctor_id' => auth()->id(),
            'note' => $request->note,
            'doctor_chamber_id' => $request->doctor_chamber_id,
        ]);
        return back()->with('message', 'Visitation started');
    }

    public function update_status(Request $request)
    {
        $appointment = Appointment::findOrFail($request->appointment_id);
    
        if ($request->status == 4) {
            $updateStatus = Appointment::where('slot_id', $appointment->slot_id)
                ->where('chamber_id', $appointment->chamber_id)
                ->where('doctor_id', $appointment->doctor_id)
                ->where('is_running', 1)
                ->first();
                
            if (!empty($updateStatus)) {
                $updateStatus->is_running = 0;
                $updateStatus->present = 7;
                if($updateStatus->patient_type_id==1){
                    $updateStatus->patient_seen =1;
                }elseif($updateStatus->patient_type_id==2){
                    $updateStatus->patient_seen =1;
                }elseif($updateStatus->patient_type_id==3){
                    $updateStatus->report_seen =1;
                }elseif($updateStatus->patient_type_id == 1 && $updateStatus->report_type ==2){
                    $updateStatus->report_seen =1;
                }elseif($updateStatus->patient_type_id == 2 && $updateStatus->report_type ==2){
                    $updateStatus->report_seen =1;
                }
                $updateStatus->save();
            }
    
            $data = ['present' => 1,
                     'is_running'=>1,
                     'is_next'=>0,
                     'is_report'=>0,
                     ];
            
            VisitationTrack::where([
                'date' => $appointment->date,
                'slot_id' => $appointment->slot_id,
                'doctor_chamber_id' => $appointment->chamber_id,
                'doctor_id' => $appointment->doctor_id,
            ])->update([
                'current_serial' => $appointment->serial_no,
                'last_serial' => $appointment->serial_no,
                'is_running' => 'yes',
            ]);
            
            $appointment->update($data);
        }elseif($request->status == 0) {
            $storeHistroy = Appointment::where('id', $request->appointment_id)
            
            ->where('present', 1)
            ->first();
            $checkVisitationStatus =VisitationTrack::where([
                'date' => $appointment->date,
                'slot_id' => $appointment->slot_id,
                'doctor_chamber_id' => $appointment->chamber_id,
                'doctor_id' => $appointment->doctor_id,
              
            ])->first();
            if(!empty($storeHistroy)){
                $history = new SerializedHistory();
                $history->appointment_id=$storeHistroy->id;
                $history->status=0;
                $history->doctor_id=$storeHistroy->doctor_id;
                $history->visitation_track_id=$checkVisitationStatus->id;
                $history->updated_date=Carbon::today();
                $history->updated_time=Carbon::now()->format('H:i:s');
                $history->updated_by=auth()->id();
                $history->save();
            }
            $updateStatus = Appointment::where('slot_id', $appointment->slot_id)
                ->where('chamber_id', $appointment->chamber_id)
                ->where('doctor_id', $appointment->doctor_id)
                ->where('is_running', 1)
                ->first();
                
            if (!empty($updateStatus)) {
                $updateStatus->is_running = 0;
                $updateStatus->save();
            }
    
            $data = ['present' => 0,
                     'is_running'=>0,
                     'is_next'=>0,
                     'is_report'=>0,
                     ];
            $checkVisitationStatus =VisitationTrack::where([
                'date' => $appointment->date,
                'slot_id' => $appointment->slot_id,
                'doctor_chamber_id' => $appointment->chamber_id,
                'doctor_id' => $appointment->doctor_id,
                'is_running' =>'yes'
            ])->first();
            if(empty( $checkVisitationStatus)){
                VisitationTrack::where([
                    'date' => $appointment->date,
                    'slot_id' => $appointment->slot_id,
                    'doctor_chamber_id' => $appointment->chamber_id,
                    'doctor_id' => $appointment->doctor_id,
                ])->update([
                    'current_serial' => 'Started',
                    'last_serial' => $appointment->serial_no,
                ]);
            }
         
            
            $appointment->update($data);
        }elseif($request->status == 3) {
           
            $checkVisitationStatus =VisitationTrack::where([
                'date' => $appointment->date,
                'slot_id' => $appointment->slot_id,
                'doctor_chamber_id' => $appointment->chamber_id,
                'doctor_id' => $appointment->doctor_id,
              
            ])->first();
            $storeHistroy = Appointment::where('id', $request->appointment_id)
            
            ->where('present', 1)
            ->first();
                if(!empty($storeHistroy)){
                    $history = new SerializedHistory();
                    $history->appointment_id=$storeHistroy->id;
                    $history->status=3;
                    $history->doctor_id=$storeHistroy->doctor_id;
                    $history->visitation_track_id =$checkVisitationStatus->id;
                    $history->updated_date=Carbon::today();
                    $history->updated_time=Carbon::now()->format('H:i:s');
                    $history->updated_by=auth()->id();
                    $history->save();
                }
                
            
    
            $data = ['present' => 3,
                     'is_running'=>0,
                     'is_next'=>0,
                     'is_report'=>0,
                     ];
            
            VisitationTrack::where([
                'date' => $appointment->date,
                'slot_id' => $appointment->slot_id,
                'doctor_chamber_id' => $appointment->chamber_id,
                'doctor_id' => $appointment->doctor_id,
            ])->update([
               
                'last_serial' => $appointment->serial_no,
            ]);
            
            $appointment->update($data);
        } elseif ($request->status == 5) {
            $data = ['present' => 1,
                 'is_next'=>1,
                 'is_running'=>0,
                 'is_report'=>0,
            ];
   
            // VisitationTrack::where([
            //     'date' => $appointment->date,
            //     'slot_id' => $appointment->slot_id,
            //     'doctor_chamber_id' => $appointment->chamber_id,
            //     'doctor_id' => $appointment->doctor_id,
            // ])->update([
            //     'current_serial' => $appointment->serial_no,
            //     'last_serial' => $appointment->serial_no,
            // ]);
            
            $appointment->update($data);

        } elseif ($request->status == 6) {
            $data = ['present' => 1,
            'is_report'=>1,
            'report_status'=>1,
            'report_type'=>2,
            'is_next'=>0,
            'is_running'=>0,
            'status'=>'old',
       ];

        // VisitationTrack::where([
        // 'date' => $appointment->date,
        // 'slot_id' => $appointment->slot_id,
        // 'doctor_chamber_id' => $appointment->chamber_id,
        // 'doctor_id' => $appointment->doctor_id,
        // ])->update([
        // 'current_serial' => $appointment->serial_no,
        // 'last_serial' => $appointment->serial_no,
        // ]);
        $appointment->update($data);
        } elseif ($request->status == 1) {
            $data = ['present' => $request->status,
            'is_running'=>0,
            'is_report'=>0,
            'is_next'=>0,
               ];
            
               $checkVisitationStatus =VisitationTrack::where([
                'date' => $appointment->date,
                'slot_id' => $appointment->slot_id,
                'doctor_chamber_id' => $appointment->chamber_id,
                'doctor_id' => $appointment->doctor_id,
                'is_running' =>'yes'
            ])->first();
            if(empty( $checkVisitationStatus)){
                VisitationTrack::where([
                    'date' => $appointment->date,
                    'slot_id' => $appointment->slot_id,
                    'doctor_chamber_id' => $appointment->chamber_id,
                    'doctor_id' => $appointment->doctor_id,
                ])->update([
                    'current_serial' => 'Started',
                    'last_serial' => $appointment->serial_no,
                ]);
            }
            
            $appointment->update($data);
        } else {
            $data = ['present' => $request->status];
            
            $checkVisitationStatus =VisitationTrack::where([
                'date' => $appointment->date,
                'slot_id' => $appointment->slot_id,
                'doctor_chamber_id' => $appointment->chamber_id,
                'doctor_id' => $appointment->doctor_id,
                'is_running' =>'yes'
            ])->first();
            if(empty( $checkVisitationStatus)){
                VisitationTrack::where([
                    'date' => $appointment->date,
                    'slot_id' => $appointment->slot_id,
                    'doctor_chamber_id' => $appointment->chamber_id,
                    'doctor_id' => $appointment->doctor_id,
                ])->update([
                    'current_serial' => 'Started',
                    'last_serial' => $appointment->serial_no,
                ]);
            }
            
            $appointment->update($data);
        }
    
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
    public function end_break(Request $request)
    {
        $track = VisitationTrack::findOrFail($request->track_id);
        // dd($request->all());
        $track->update([
            'break_type' => NULL,
            'break_duration' => NULL,
            'current_serial' =>$track->last_serial,
            'break_start_at' => NULL,
        ]);

        return back()->with('message', 'Updated successfully');
    }
        public function save_appointment_all(Request $request){
            $track = VisitationTrack::where('id',$request->track_id)->first();
            $track->current_serial ='Stop';
            $track->update_status='Stop';
            $track->save();
        
            $chamberId = DoctorChamber::where('id',$track->doctor_chamber_id)->first();
            $serializedAppointment=Appointment::where('doctor_id',$track->doctor_id)->where('slot_id',$track->slot_id)->where('chamber_id',$chamberId->chamber_id)->get();
            foreach($serializedAppointment as $data){
                $appointment =Appointment::where('present',0)->where('id',$data->id)->first();
                if(!empty($appointment)){
                    $appointment->present =2;
                 
                    $appointment->save(); 
                }
                $skipAppointment = Appointment::where('present',3)->where('id',$data->id)->first();
               
            if(!empty($skipAppointment)){
                $skipAppointment->present =2;
             
                $skipAppointment->save();
            }
            $runningAppointment = Appointment::where('is_running',1)->where('id',$data->id)->first();
               
            if(!empty($runningAppointment)){
                $runningAppointment->present =7;
                if($runningAppointment->patient_type_id ==1){
                    $runningAppointment->patient_seen =1;
                }elseif($runningAppointment->patient_type_id ==2){
                    $runningAppointment->patient_seen =1;
                }elseif($runningAppointment->patient_type_id ==2){
                    $runningAppointment->report_seen =1; 
                } if($runningAppointment->patient_type_id ==1 && $runningAppointment->report_status ==1){
                    $runningAppointment->report_seen =1;
                }elseif($runningAppointment->patient_type_id ==2 && $runningAppointment->report_status ==1){
                    $runningAppointment->report_seen =1;
                }
               
                $runningAppointment->is_running =0;
            
                $runningAppointment->save();
            }
            $nextAppointment = Appointment::where('is_next',1)->where('id',$data->id)->first();
               
            if(!empty($nextAppointment)){
                $nextAppointment->present =2;
                $nextAppointment->is_next =0;
            
                $nextAppointment->save();
            }
            $nextAppointment = Appointment::where('present',1)->where('id',$data->id)->first();
               
            if(!empty($nextAppointment)){
                $nextAppointment->present =2;
                
            
                $nextAppointment->save();
            }
            // $reportAppointment = Appointment::where('is_report',1)->where('id',$data->id)->first();
               
            // if(!empty($reportAppointment)){
            //     $reportAppointment->present =2;
            //     $reportAppointment->is_report =0;
            
            //     $reportAppointment->save();
            // }
            }

            return redirect()->route('doctor.report.index')->with('message', 'Updated successfully.');
            

            // return back()->with('message', 'Updated successfully');
            
        }

        public function update_appointment_all(Request $request){
            $track = VisitationTrack::where('id',$request->track_id)->first();
            $track->current_serial ='Edit';
            $track->update_status='Edit';
            $track->save();
           

           

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

    public function save_new_appointment(Request $request){
        $track = VisitationTrack::where('id',$request->track_id)->first();
          $chamberId = DoctorChamber::where('id',$track->doctor_chamber_id)->first();
        $count_appoint = Appointment::where([
            'date' => $track->date,
            'doctor_id' => $track->doctor_id,
            'slot_id' => $track->slot_id,
            'chamber_id' => $chamberId->chamber_id,
           
            'status'=>'new',
            
        ])->count();
        $count_soring = Appointment::where([
            'date' => $track->date,
            'doctor_id' => $track->doctor_id,
            'slot_id' => $track->slot_id,
            'chamber_id' => $chamberId->chamber_id,
           
            
        ])->count();
        $numeric_code = substr(str_shuffle(str_repeat($x = '0123456789', ceil(3 / strlen($x)))), 1, 1);
        $alpha_code = substr(str_shuffle(str_repeat($x = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ', ceil(3 / strlen($x)))), 1, 3);

        $track_code = $numeric_code . $alpha_code;
        $exist_code = Appointment::where('tracking_code', $track_code)->first();

        if ($exist_code) {
            dd('code exist!');
        }
        $doctorDetails = DoctorDetails::where('user_id',auth()->id())->first();
        // $fees =0;
        //  if($request->patient_type_id == 1){
        //      $fees = $doctorDetails->new_fees;
        //  }elseif($request->patient_type_id == 2){
        //      $fees = $doctorDetails->old_fees;
        //  }elseif($request->patient_type_id == 3){
        //      $fees = $doctorDetails->report_fees;
        //  }
        if ($request->has('name') && is_array($request->name)) {
            foreach ($request->fees as  $key => $info) {
                if (!empty($info)) {
                    $appointment = new Appointment();
                    $appointment->doctor_id = $track->doctor_id;
                    $appointment->date = $track->date;
                    $appointment->slot_id = $track->slot_id;
                    $appointment->chamber_id = $chamberId->chamber_id;
                    $appointment->present = 0;
                    $appointment->patient_type_id=3;
                    $appointment->is_report = 1;
                    $appointment->report_status = 1;
                    $appointment->report_type = $request->fees[$key] ==1 ? 1:2;
                    $appointment->doctor_id = $track->doctor_id;
                    $appointment->patient_name = $request->name[$key];
                    $appointment->fees = $request->fees[$key] ==1 ? $doctorDetails->report_fees: 0;
                    $appointment->serial_no = ++$count_appoint;
                    $appointment->report_type = $request->fees[$key];
                    $appointment->tracking_code = ++ $track_code;
                    $appointment->sorting_order = ++$count_soring;
                    $appointment->status = 'new';
                  
                    $appointment->save();
                }
            }
        }
        return back()->with('message', "Appontment add successfully");
    }

    public function save_appointment_report(Request $request){
        
     
        $getAppointmentInfo = Appointment::where('id',$request->dataId)->first();
        if(!empty($getAppointmentInfo)){
            $getAppointmentInfoNext = Appointment::where( 'date' , $getAppointmentInfo->date)->where('doctor_id',$getAppointmentInfo->doctor_id)->where('slot_id',$getAppointmentInfo->slot_id)->where('chamber_id',$getAppointmentInfo->chamber_id)->where('is_next',1)->orderBy('sorting_order','desc')->first();
            if(!empty($getAppointmentInfoNext)){
                $getAppointmentInfo->present =1;
                $getAppointmentInfo->is_next =1;
                $getAppointmentInfo->is_report=0;
                $getAppointmentInfo->sorting_order= $getAppointmentInfoNext->sorting_order ;
                $getAppointmentInfo->save();
            }else{
                $getAppointmentInfo->present =1;
                $getAppointmentInfo->is_next =1;
                $getAppointmentInfo->is_report=0;
                $getAppointmentInfo->sorting_order= 1 ;
                $getAppointmentInfo->save();
            }
           
        }
      
        if($getAppointmentInfo->save()){

            // $getAppointmentInfoNext = Appointment::where([
            //     'date' => $getAppointmentInfo->date,
            //     'doctor_id' => $getAppointmentInfo->doctor_id,
            //     'slot_id' => $getAppointmentInfo->slot_id,
            //     'chamber_id' => $getAppointmentInfo->chamber_id,
               
            // ])->where('is_next',1)->orderBy('sorting_order','desc')->first();
            // $getAppointmentInfoNextCount = Appointment::where([
            //     'date' => $getAppointmentInfo->date,
            //     'doctor_id' => $getAppointmentInfo->doctor_id,
            //     'slot_id' => $getAppointmentInfo->slot_id,
            //     'chamber_id' => $getAppointmentInfo->chamber_id,
            //      'is_next'=>1
                
            // ])->orderBy('sorting_order','desc')->count();
            $getAppointmentInfoNext = Appointment::where( 'date' , $getAppointmentInfo->date)->where('doctor_id',$getAppointmentInfo->doctor_id)->where('slot_id',$getAppointmentInfo->slot_id)->where('chamber_id',$getAppointmentInfo->chamber_id)->where('is_next',1)->orderBy('sorting_order','desc')->first();

            $getAllAppointment =Appointment::where([
                'date' => $getAppointmentInfo->date,
                'doctor_id' => $getAppointmentInfo->doctor_id,
                'slot_id' => $getAppointmentInfo->slot_id,
                'chamber_id' => $getAppointmentInfo->chamber_id,
               
                
            ])->where('is_next', '!=',1)->where('present','!=',7)->where('is_running','!=',1)->get();
            $getAppointmentInfoNext1 =$getAppointmentInfoNext->sorting_order;
            // foreach($getAllAppointment as $info){
            //     $getAppointmentInfoNextOld = Appointment::where( 'date' , $getAppointmentInfo->date)->where('doctor_id',$getAppointmentInfo->doctor_id)->where('slot_id',$getAppointmentInfo->slot_id)->where('chamber_id',$getAppointmentInfo->chamber_id)->where('status','!=','new')->orWhere('status','!=','old')->where('is_next',1)->orderBy('sorting_order','desc')->first();
            //     $data = Appointment::where('id',$info->id)->where('id', '>',$getAppointmentInfoNextOld->id)->first();
            //     $data->sorting_order = ++$getAppointmentInfoNext1  ;
            //     $data->save();
            // }

        }
       
        return back()->with('message', "Report Status update");

    }
}
