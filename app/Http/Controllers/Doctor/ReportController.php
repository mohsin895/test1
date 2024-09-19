<?php

namespace App\Http\Controllers\Doctor;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Models\DoctorChamber;
use App\Models\SerializedHistory;
use App\Models\Slot;
use App\Models\VisitationTrack;
use Carbon\Carbon;
use App\Models\User;
use Inertia\Inertia;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function index()
    {   $countSuspection=0;
        $chamber =0;
        $track = VisitationTrack::where('doctor_id', auth()->id())->orderBy('id','desc')->first();
        $data = Appointment::where('doctor_id',auth()->id())->orderBy('id', 'desc')->get();
        $doctor = User::with(['doctor_details','doctor_chambers','doctor_chambers.chamber'])->find(auth()->id());
        if(!empty($track)){
            $countSuspection = SerializedHistory::where('visitation_track_id',$track->id)->count('id');
            $chamber = DoctorChamber::where('id',$track->doctor_chamber_id)->first();
        }
       
        return Inertia::render('Doctor/Report/Index', compact('data','doctor','track','chamber','countSuspection'));
    }

    public function report(Request $request) {
       
       
        $totalReportAmount =0;
       
        // ** Appointment Statics Starts **
        $totalNumber =0;
        $totalNewAppointment =0;
        $totalOldAppointment =0;
        $totalReportAppointment=0;
        $totalReportAppointment=0;
        $totalAppontmentFromTodayReport =0;
        $trackDate=0;
        //All Appointments 
        if ($request->type == 'today') {
            $trackDate = VisitationTrack::where('doctor_id', auth()->id())->whereDate('date', date('Y-m-d'))->count();
        }elseif($request->type == 'yesterday'){
            $trackDate = VisitationTrack::where('doctor_id', auth()->id())->whereDate('date', '=', date('Y-m-d', strtotime('-1 day')))->count();
        }
        $query = Appointment::where('doctor_id', auth()->id());

        if ($request->type) {
            if ($request->type == 'today') {
                $query->whereDate('date', date('Y-m-d'));
            }
           
            if ($request->type == 'yesterday') {
                $query->whereDate('date', '=', date('Y-m-d', strtotime('-1 day')));
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

        $totalNumber = $query->count('id') ?? [];

        //New Appointments

        $queryNewAppointment = Appointment::where('doctor_id', auth()->id())->where('patient_type_id',1);

        if ($request->type) {
            if ($request->type == 'today') {
                $queryNewAppointment->whereDate('date', date('Y-m-d'));
            }
           
            if ($request->type == 'yesterday') {
                $queryNewAppointment->whereDate('date', '=', date('Y-m-d', strtotime('-1 day')));
            }
            if ($request->type == 'all') {
                $queryNewAppointment->get();
            }
        }
        if ($request->startAt) {
            $queryNewAppointment->whereDate('date', '>=', $request->startAt);
        }
    
        if ($request->endAt) {
            $queryNewAppointment->whereDate('date', '<=', $request->endAt);
        }
        if ($request->particularDate) {
            $queryNewAppointment->whereDate('date', $request->particularDate);
        }
        if ($request->chamber_id) {
            $doctor_chamber = DoctorChamber::find($request->chamber_id);
            if ($doctor_chamber) {
                $queryNewAppointment->where('chamber_id', $doctor_chamber->chamber_id);
            }
        }

        if ($request->slot_id) {
            $slot=Slot::find($request->slot_id);
            if($slot){
                $queryNewAppointment->where('slot_id', $request->slot_id);
            }
           
        }
        

        $queryNewAppointment->with(['slot','chamber'])->orderBy('id', 'desc');

        $totalNewAppointment = $queryNewAppointment->count('id') ?? [];

        $queryNewAppointmentReport = Appointment::where('doctor_id', auth()->id())->where('patient_type_id',1)->where('report_status',1);

        if ($request->type) {
            if ($request->type == 'today') {
                $queryNewAppointmentReport->whereDate('date', date('Y-m-d'));
            }
           
            if ($request->type == 'yesterday') {
                $queryNewAppointmentReport->whereDate('date', '=', date('Y-m-d', strtotime('-1 day')));
            }
            if ($request->type == 'all') {
                $queryNewAppointmentReport->get();
            }
        }
        if ($request->startAt) {
            $queryNewAppointmentReport->whereDate('date', '>=', $request->startAt);
        }
    
        if ($request->endAt) {
            $queryNewAppointmentReport->whereDate('date', '<=', $request->endAt);
        }
        if ($request->particularDate) {
            $queryNewAppointmentReport->whereDate('date', $request->particularDate);
        }
        if ($request->chamber_id) {
            $doctor_chamber = DoctorChamber::find($request->chamber_id);
            if ($doctor_chamber) {
                $queryNewAppointmentReport->where('chamber_id', $doctor_chamber->chamber_id);
            }
        }

        if ($request->slot_id) {
            $slot=Slot::find($request->slot_id);
            if($slot){
                $queryNewAppointmentReport->where('slot_id', $request->slot_id);
            }
           
        }
        

        $queryNewAppointmentReport->with(['slot','chamber'])->orderBy('id', 'desc');

        $totalNewAppointmentReport = $queryNewAppointmentReport->count('id') ?? [];

        //Old Appointments

        $queryOldAppointment = Appointment::where('doctor_id', auth()->id())->where('patient_type_id',2);

        if ($request->type) {
            if ($request->type == 'today') {
                $queryOldAppointment->whereDate('date', date('Y-m-d'));
            }
          
            if ($request->type == 'yesterday') {
                $queryOldAppointment->whereDate('date', '=', date('Y-m-d', strtotime('-1 day')));
            }
            if ($request->type == 'all') {
                $queryOldAppointment->get();
            }
        }
        if ($request->startAt) {
            $queryOldAppointment->whereDate('date', '>=', $request->startAt);
        }
    
        if ($request->endAt) {
            $queryOldAppointment->whereDate('date', '<=', $request->endAt);
        }
        if ($request->particularDate) {
            $queryOldAppointment->whereDate('date', $request->particularDate);
        }
        if ($request->chamber_id) {
            $doctor_chamber = DoctorChamber::find($request->chamber_id);
            if ($doctor_chamber) {
                $queryOldAppointment->where('chamber_id', $doctor_chamber->chamber_id);
            }
        }

        if ($request->slot_id) {
            $slot=Slot::find($request->slot_id);
            if($slot){
                $queryOldAppointment->where('slot_id', $request->slot_id);
            }
           
        }
        

        $queryOldAppointment->with(['slot','chamber'])->orderBy('id', 'desc');

        $totalOldAppointment = $queryOldAppointment->count('id') ?? [];


        $queryOldAppointmentReport = Appointment::where('doctor_id', auth()->id())->where('patient_type_id',2)->where('report_status',1);

        if ($request->type) {
            if ($request->type == 'today') {
                $queryOldAppointmentReport->whereDate('date', date('Y-m-d'));
            }
          
            if ($request->type == 'yesterday') {
                $queryOldAppointmentReport->whereDate('date', '=', date('Y-m-d', strtotime('-1 day')));
            }
            if ($request->type == 'all') {
                $queryOldAppointmentReport->get();
            }
        }
        if ($request->startAt) {
            $queryOldAppointmentReport->whereDate('date', '>=', $request->startAt);
        }
    
        if ($request->endAt) {
            $queryOldAppointmentReport->whereDate('date', '<=', $request->endAt);
        }
        if ($request->particularDate) {
            $queryOldAppointmentReport->whereDate('date', $request->particularDate);
        }
        if ($request->chamber_id) {
            $doctor_chamber = DoctorChamber::find($request->chamber_id);
            if ($doctor_chamber) {
                $queryOldAppointmentReport->where('chamber_id', $doctor_chamber->chamber_id);
            }
        }

        if ($request->slot_id) {
            $slot=Slot::find($request->slot_id);
            if($slot){
                $queryOldAppointmentReport->where('slot_id', $request->slot_id);
            }
           
        }
        

        $queryOldAppointmentReport->with(['slot','chamber'])->orderBy('id', 'desc');

        $totalOldAppointmentReport = $queryOldAppointmentReport->count('id') ?? [];
        
         //Appontment Reports


        $queryReportAppointment = Appointment::where('doctor_id', auth()->id())->where('patient_type_id',3);

        if ($request->type) {
            if ($request->type == 'today') {
                $queryReportAppointment->whereDate('date', date('Y-m-d'));
            }
           
            if ($request->type == 'yesterday') {
                $queryReportAppointment->whereDate('date', '=', date('Y-m-d', strtotime('-1 day')));
            }
            if ($request->type == 'all') {
                $queryReportAppointment->get();
            }
        }
        if ($request->startAt) {
            $queryReportAppointment->whereDate('date', '>=', $request->startAt);
        }
    
        if ($request->endAt) {
            $queryReportAppointment->whereDate('date', '<=', $request->endAt);
        }
        if ($request->particularDate) {
            $queryReportAppointment->whereDate('date', $request->particularDate);
        }
        if ($request->chamber_id) {
            $doctor_chamber = DoctorChamber::find($request->chamber_id);
            if ($doctor_chamber) {
                $queryReportAppointment->where('chamber_id', $doctor_chamber->chamber_id);
            }
        }

        if ($request->slot_id) {
            $slot=Slot::find($request->slot_id);
            if($slot){
                $queryReportAppointment->where('slot_id', $request->slot_id);
            }
           
        }
        

        $queryReportAppointment->with(['slot','chamber'])->orderBy('id', 'desc');

        $totalReportAppointment = $queryReportAppointment->count('id') ?? [];

                // ** Appointment Statics End **

        // ** Visitation Statics Start**
        $totalPresentPatient =0;
        $totalAbsentPatient=0;
        $totalPaidReports=0;
        $totalUnpaidReports=0;
        
       // Present Appointments
       
        $queryPresentPatient = Appointment::where('doctor_id', auth()->id())->where('patient_seen',1);

        if ($request->type) {
            if ($request->type == 'today') {
                $queryPresentPatient->whereDate('date', date('Y-m-d'));
            }
           
            if ($request->type == 'yesterday') {
                $queryPresentPatient->whereDate('date', '=', date('Y-m-d', strtotime('-1 day')));
            }
            if ($request->type == 'all') {
                $queryPresentPatient->get();
            }
        }
        if ($request->startAt) {
            $queryPresentPatient->whereDate('date', '>=', $request->startAt);
        }
    
        if ($request->endAt) {
            $queryPresentPatient->whereDate('date', '<=', $request->endAt);
        }
        if ($request->particularDate) {
            $queryPresentPatient->whereDate('date', $request->particularDate);
        }
        if ($request->chamber_id) {
            $doctor_chamber = DoctorChamber::find($request->chamber_id);
            if ($doctor_chamber) {
                $queryPresentPatient->where('chamber_id', $doctor_chamber->chamber_id);
            }
        }

        if ($request->slot_id) {
            $slot=Slot::find($request->slot_id);
            if($slot){
                $queryPresentPatient->where('slot_id', $request->slot_id);
            }
           
        }
        

        $queryPresentPatient->with(['slot','chamber'])->orderBy('id', 'desc');

        $totalPresentPatient = $queryPresentPatient->count('id') ?? [];

        //Absent Appointments

       
        
        $queryAbsentPatient = Appointment::where('doctor_id', auth()->id())->where('present',2);

        if ($request->type) {
            if ($request->type == 'today') {
                $queryAbsentPatient->whereDate('date', date('Y-m-d'));
            }
           
            if ($request->type == 'yesterday') {
                $queryAbsentPatient->whereDate('date', '=', date('Y-m-d', strtotime('-1 day')));
            }
            if ($request->type == 'all') {
                $queryAbsentPatient->get();
            }
        }
        if ($request->startAt) {
            $queryAbsentPatient->whereDate('date', '>=', $request->startAt);
        }
    
        if ($request->endAt) {
            $queryAbsentPatient->whereDate('date', '<=', $request->endAt);
        }
        if ($request->particularDate) {
            $queryAbsentPatient->whereDate('date', $request->particularDate);
        }
        if ($request->chamber_id) {
            $doctor_chamber = DoctorChamber::find($request->chamber_id);
            if ($doctor_chamber) {
                $queryAbsentPatient->where('chamber_id', $doctor_chamber->chamber_id);
            }
        }

        if ($request->slot_id) {
            $slot=Slot::find($request->slot_id);
            if($slot){
                $queryAbsentPatient->where('slot_id', $request->slot_id);
            }
           
        }
        

        $queryAbsentPatient->with(['slot','chamber'])->orderBy('id', 'desc');

        $totalAbsentPatient = $queryAbsentPatient->count('id') ?? [];
        

        $queryPaidReport = Appointment::where('doctor_id', auth()->id())->where('present',7)->where('patient_type_id',3)->where('report_type',1);

        if ($request->type) {
            if ($request->type == 'today') {
                $queryPaidReport->whereDate('date', date('Y-m-d'));
            }
           
            if ($request->type == 'yesterday') {
                $queryPaidReport->whereDate('date', '=', date('Y-m-d', strtotime('-1 day')));
            }
            if ($request->type == 'all') {
                $queryPaidReport->get();
            }
        }
        if ($request->startAt) {
            $queryPaidReport->whereDate('date', '>=', $request->startAt);
        }
    
        if ($request->endAt) {
            $queryPaidReport->whereDate('date', '<=', $request->endAt);
        }
        if ($request->particularDate) {
            $queryPaidReport->whereDate('date', $request->particularDate);
        }
        if ($request->chamber_id) {
            $doctor_chamber = DoctorChamber::find($request->chamber_id);
            if ($doctor_chamber) {
                $queryPaidReport->where('chamber_id', $doctor_chamber->chamber_id);
            }
        }

        if ($request->slot_id) {
            $slot=Slot::find($request->slot_id);
            if($slot){
                $queryPaidReport->where('slot_id', $request->slot_id);
            }
           
        }
        

        $queryPaidReport->with(['slot','chamber'])->orderBy('id', 'desc');

        $totalPaidReports = $queryPaidReport->count('id') ?? [];



        $queryUnpaidReport = Appointment::where('doctor_id', auth()->id())->where('present',7)->where('report_seen',1)->where('report_type',2);

        if ($request->type) {
            if ($request->type == 'today') {
                $queryUnpaidReport->whereDate('date', date('Y-m-d'));
            }
            
            if ($request->type == 'yesterday') {
                $queryUnpaidReport->whereDate('date', '=', date('Y-m-d', strtotime('-1 day')));
            }
            if ($request->type == 'all') {
                $queryUnpaidReport->get();
            }
        }
        if ($request->startAt) {
            $queryUnpaidReport->whereDate('date', '>=', $request->startAt);
        }
    
        if ($request->endAt) {
            $queryUnpaidReport->whereDate('date', '<=', $request->endAt);
        }
        if ($request->particularDate) {
            $queryUnpaidReport->whereDate('date', $request->particularDate);
        }
        if ($request->chamber_id) {
            $doctor_chamber = DoctorChamber::find($request->chamber_id);
            if ($doctor_chamber) {
                $queryUnpaidReport->where('chamber_id', $doctor_chamber->chamber_id);
            }
        }

        if ($request->slot_id) {
            $slot=Slot::find($request->slot_id);
            if($slot){
                $queryUnpaidReport->where('slot_id', $request->slot_id);
            }
           
        }
        

        $queryUnpaidReport->with(['slot','chamber'])->orderBy('id', 'desc');

        $totalUnpaidReports = $queryUnpaidReport->count('id') ?? [];

          // ** Visitation Statics End**

        //   ** Economical Statics Start **

        $totalNewAmount =0;
        $totalNewAppointmentPresent=0;

        $totalOldAmount =0;
        $totalPresentPatientOld=0;
        $totalCommonReport =0;
        $totalAllSeenReport=0;

           //New Patient amount

                $queryNewAppointmentPresent = Appointment::where('doctor_id', auth()->id())->where('patient_type_id',1)->where('patient_seen',1);

                if ($request->type) {
                    if ($request->type == 'today') {
                        $queryNewAppointmentPresent->whereDate('date', date('Y-m-d'));
                    }
                    
                    if ($request->type == 'yesterday') {
                        $queryNewAppointmentPresent->whereDate('date', '=', date('Y-m-d', strtotime('-1 day')));
                    }
                    if ($request->type == 'all') {
                        $queryNewAppointmentPresent->get();
                    }
                }
                if ($request->startAt) {
                    $queryNewAppointmentPresent->whereDate('date', '>=', $request->startAt);
                }

                if ($request->endAt) {
                    $queryNewAppointmentPresent->whereDate('date', '<=', $request->endAt);
                }
                if ($request->particularDate) {
                    $queryNewAppointmentPresent->whereDate('date', $request->particularDate);
                }
                if ($request->chamber_id) {
                    $doctor_chamber = DoctorChamber::find($request->chamber_id);
                    if ($doctor_chamber) {
                        $queryNewAppointmentPresent->where('chamber_id', $doctor_chamber->chamber_id);
                    }
                }

                if ($request->slot_id) {
                    $slot=Slot::find($request->slot_id);
                    if($slot){
                        $queryNewAppointmentPresent->where('slot_id', $request->slot_id);
                    }
                    
                }
                

                $queryNewAppointmentPresent->with(['slot','chamber'])->orderBy('id', 'desc');

          $totalNewAppointmentPresent = $queryNewAppointmentPresent->count('id') ?? [];

        $queryNewAmount = Appointment::where('doctor_id', auth()->id())->where('patient_type_id',1)->where('patient_seen',1);

        if ($request->type) {
            if ($request->type == 'today') {
                $queryNewAmount->whereDate('date', date('Y-m-d'));
            }
            
            if ($request->type == 'yesterday') {
                $queryNewAmount->whereDate('date', '=', date('Y-m-d', strtotime('-1 day')));
            }
            if ($request->type == 'all') {
                $queryNewAmount->get();
            }
        }
        if ($request->startAt) {
            $queryNewAmount->whereDate('date', '>=', $request->startAt);
        }
    
        if ($request->endAt) {
            $queryNewAmount->whereDate('date', '<=', $request->endAt);
        }
        if ($request->particularDate) {
            $queryNewAmount->whereDate('date', $request->particularDate);
        }
        if ($request->chamber_id) {
            $doctor_chamber = DoctorChamber::find($request->chamber_id);
            if ($doctor_chamber) {
                $queryNewAmount->where('chamber_id', $doctor_chamber->chamber_id);
            }
        }

        if ($request->slot_id) {
            $slot=Slot::find($request->slot_id);
            if($slot){
                $queryNewAmount->where('slot_id', $request->slot_id);
            }
           
        }
        

        $queryNewAmount->with(['slot','chamber'])->orderBy('id', 'desc');

        $totalNewAmount = $queryNewAmount->sum('fees') ?? [];

        //Old  Amount

        $queryPresentPatientOld = Appointment::where('doctor_id', auth()->id())->where('patient_seen',1)->where('patient_type_id',2);

        if ($request->type) {
            if ($request->type == 'today') {
                $queryPresentPatientOld->whereDate('date', date('Y-m-d'));
            }
           
            if ($request->type == 'yesterday') {
                $queryPresentPatientOld->whereDate('date', '=', date('Y-m-d', strtotime('-1 day')));
            }
            if ($request->type == 'all') {
                $queryPresentPatientOld->get();
            }
        }
        if ($request->startAt) {
            $queryPresentPatientOld->whereDate('date', '>=', $request->startAt);
        }
    
        if ($request->endAt) {
            $queryPresentPatientOld->whereDate('date', '<=', $request->endAt);
        }
        if ($request->particularDate) {
            $queryPresentPatientOld->whereDate('date', $request->particularDate);
        }
        if ($request->chamber_id) {
            $doctor_chamber = DoctorChamber::find($request->chamber_id);
            if ($doctor_chamber) {
                $queryPresentPatientOld->where('chamber_id', $doctor_chamber->chamber_id);
            }
        }

        if ($request->slot_id) {
            $slot=Slot::find($request->slot_id);
            if($slot){
                $queryPresentPatientOld->where('slot_id', $request->slot_id);
            }
           
        }
        

        $queryPresentPatientOld->with(['slot','chamber'])->orderBy('id', 'desc');

        $totalPresentPatientOld = $queryPresentPatientOld->count('id') ?? [];

     
        $queryOldAmount = Appointment::where('doctor_id', auth()->id())->where('patient_type_id',2)->where('patient_seen',1);

        if ($request->type) {
            if ($request->type == 'today') {
                $queryOldAmount->whereDate('date', date('Y-m-d'));
            }
          
            if ($request->type == 'yesterday') {
                $queryOldAmount->whereDate('date', '=', date('Y-m-d', strtotime('-1 day')));
            }
            if ($request->type == 'all') {
                $queryOldAmount->get();
            }
        }
        if ($request->startAt) {
            $queryOldAmount->whereDate('date', '>=', $request->startAt);
        }
    
        if ($request->endAt) {
            $queryOldAmount->whereDate('date', '<=', $request->endAt);
        }
        if ($request->particularDate) {
            $queryOldAmount->whereDate('date', $request->particularDate);
        }
        if ($request->chamber_id) {
            $doctor_chamber = DoctorChamber::find($request->chamber_id);
            if ($doctor_chamber) {
                $queryOldAmount->where('chamber_id', $doctor_chamber->chamber_id);
            }
        }

        if ($request->slot_id) {
            $slot=Slot::find($request->slot_id);
            if($slot){
                $queryOldAmount->where('slot_id', $request->slot_id);
            }
           
        }
        

        $queryOldAmount->with(['slot','chamber'])->orderBy('id', 'desc');

        $totalOldAmount = $queryOldAmount->sum('fees') ?? [];

        //Report Amount

        $queryReportAmount = Appointment::where('doctor_id', auth()->id())->where('patient_seen',1)->where('patient_type_id',3)->where('report_type',1);

        if ($request->type) {
            if ($request->type == 'today') {
                $queryReportAmount->whereDate('date', date('Y-m-d'));
            }
           
            if ($request->type == 'yesterday') {
                $queryReportAmount->whereDate('date', '=', date('Y-m-d', strtotime('-1 day')));
            }
            if ($request->type == 'all') {
                $queryReportAmount->get();
            }
        }
        if ($request->startAt) {
            $queryReportAmount->whereDate('date', '>=', $request->startAt);
        }
    
        if ($request->endAt) {
            $queryReportAmount->whereDate('date', '<=', $request->endAt);
        }
        if ($request->particularDate) {
            $queryReportAmount->whereDate('date', $request->particularDate);
        }
        if ($request->chamber_id) {
            $doctor_chamber = DoctorChamber::find($request->chamber_id);
            if ($doctor_chamber) {
                $queryReportAmount->where('chamber_id', $doctor_chamber->chamber_id);
            }
        }

        if ($request->slot_id) {
            $slot=Slot::find($request->slot_id);
            if($slot){
                $queryReportAmount->where('slot_id', $request->slot_id);
            }
           
        }
        

        $queryReportAmount->with(['slot','chamber'])->orderBy('id', 'desc');

        $totalReportAmount = $queryReportAmount->sum('fees') ?? [];

    //   *** Common Report **
        $queryCommonReport = Appointment::where('doctor_id', auth()->id())->where('report_seen',1)->where('patient_seen',1)->where('present',7);

        if ($request->type) {
            if ($request->type == 'today') {
                $queryCommonReport->whereDate('date', date('Y-m-d'));
            }
          
            if ($request->type == 'yesterday') {
                $queryCommonReport->whereDate('date', '=', date('Y-m-d', strtotime('-1 day')));
            }
            if ($request->type == 'all') {
                $queryCommonReport->get();
            }
        }
        if ($request->startAt) {
            $queryCommonReport->whereDate('date', '>=', $request->startAt);
        }
    
        if ($request->endAt) {
            $queryCommonReport->whereDate('date', '<=', $request->endAt);
        }
        if ($request->particularDate) {
            $queryCommonReport->whereDate('date', $request->particularDate);
        }
        if ($request->chamber_id) {
            $doctor_chamber = DoctorChamber::find($request->chamber_id);
            if ($doctor_chamber) {
                $queryCommonReport->where('chamber_id', $doctor_chamber->chamber_id);
            }
        }

        if ($request->slot_id) {
            $slot=Slot::find($request->slot_id);
            if($slot){
                $queryCommonReport->where('slot_id', $request->slot_id);
            }
           
        }
        

        $queryCommonReport->with(['slot','chamber'])->orderBy('id', 'desc');

        $totalCommonReport= $queryCommonReport->count('id') ?? [];


        $queryAllSeenReport = Appointment::where('doctor_id', auth()->id())->where('present',7)->where('report_seen',1);

        if ($request->type) {
            if ($request->type == 'today') {
                $queryAllSeenReport->whereDate('date', date('Y-m-d'));
            }
           
            if ($request->type == 'yesterday') {
                $queryAllSeenReport->whereDate('date', '=', date('Y-m-d', strtotime('-1 day')));
            }
            if ($request->type == 'all') {
                $queryAllSeenReport->get();
            }
        }
        if ($request->startAt) {
            $queryAllSeenReport->whereDate('date', '>=', $request->startAt);
        }
    
        if ($request->endAt) {
            $queryAllSeenReport->whereDate('date', '<=', $request->endAt);
        }
        if ($request->particularDate) {
            $queryAllSeenReport->whereDate('date', $request->particularDate);
        }
        if ($request->chamber_id) {
            $doctor_chamber = DoctorChamber::find($request->chamber_id);
            if ($doctor_chamber) {
                $queryAllSeenReport->where('chamber_id', $doctor_chamber->chamber_id);
            }
        }

        if ($request->slot_id) {
            $slot=Slot::find($request->slot_id);
            if($slot){
                $queryAllSeenReport->where('slot_id', $request->slot_id);
            }
           
        }
        

        $queryAllSeenReport->with(['slot','chamber'])->orderBy('id', 'desc');

        $totalAllSeenReport= $queryAllSeenReport->count('id') ?? [];


        $totalAppontmentFromTodayReport =$totalNewAppointmentReport + $totalOldAppointmentReport;

        //   ** Economical Statics End **
        
        return response()->json([
            'totalNumber' => $totalNumber,
            'totalNewAppointment'=>$totalNewAppointment,
            'totalOldAppointment'=>$totalOldAppointment,
            'totalReportAppointment'=>$totalReportAppointment,
            'totalPresentPatient'=>$totalPresentPatient,
            'totalAbsentPatient'=>$totalAbsentPatient,
            'totalNewAmount'=>$totalNewAmount,
            'totalNewAppointmentPresent'=>$totalNewAppointmentPresent,
            'totalOldAmount'=>$totalOldAmount,
            'totalPaidReports'=>$totalPaidReports,
            'totalUnpaidReports' =>$totalUnpaidReports,
            'totalReportAmount'=>$totalReportAmount,
            'totalPresentPatientOld'=>$totalPresentPatientOld,
            'totalCommonReport'=>$totalCommonReport,
            'totalAllSeenReport'=>$totalAllSeenReport,
            'trackDate'=>$trackDate,
            'totalAppontmentFromTodayReport'=>$totalAppontmentFromTodayReport,
          
            
        ]);
    }
}
