<template>
    <DoctorLayout title="Reports">
        <form class="block" @submit.prevent="fetchAppointments" >
          <div class="flex gap-6 md:flex-nowrap flex-wrap  border rounded p-4 mb-2" style="font-weight: 400;">
                
                <div class="w-full">
                  <span class="absolute left-0 top-3 opacity-40">{{ placeholder }}</span>
                  <select
                      id="countries"
                      v-model="type"
                      @change="handleDayChange"
                      class="myInput border border-none focus:outline-none px-0 py-3 block w-full remove-shadow"
                  >
                      <option value="#" @click="dayReset()">Select Day</option>
                      <option  value="today">Today ({{ dateFormateWithDay(currentdate) }})</option>
                      <option  value="yesterday">Yesterday ({{ dateFormateWithDay(tomorrowdate) }})</option>
                      <option value="all">All Time</option>
                     
                      <option value="particular">
                          Particular Date
                          <span v-if="particularDate" class="font-semibold">
                              ({{ dateFormate(particularDate) }})
                          </span>
                      </option>
                      <option value="custom">Custom <span v-if="startAt"> (<span v-if="startAt" class="font-semibold">{{ dateFormate(startAt) }}</span>
                          <span v-if="endAt" class="font-semibold"> - {{ dateFormate(endAt) }}</span>)</span></option>
                      
                  </select>
                  <span class="customBorder"></span>
                    
                </div>
  
                <div class="w-full">
                  <span class="absolute left-0 top-3 opacity-40">{{ placeholder }}</span>
                  <select
                      id="chambers"
                      v-model="chamber_id"
                      @change="getSlot"
                      @click="fetchAppointments"
                      class="myInput border border-none focus:outline-none px-0 py-3 block w-full remove-shadow"
                  >
                      <option @click="chamberReset()" value="chamber">Select a Chamber</option>
                      <option v-for="(doctorChamber, index) in doctor.doctor_chambers" :key="index" :value="doctorChamber.chamber_id">
                          {{ doctorChamber.chamber.name }}
                      </option>
                  </select>
                  <span class="customBorder"></span>
                </div>
  
                <div class="w-full">
                    <select
                        id="slots"
                        v-model="slot_id"
                        @change="fetchAppointments"
                        class="myInput border border-none focus:outline-none px-0 py-3 block w-full remove-shadow"
                    >
                        <option selected @click="slotReset()" value="slot">Select a Slot</option>
                        <option v-for="(slot, index) in slots" :key="index" :value="slot.id">
                            {{ convert24to12(slot.from_time) }} - {{ convert24to12(slot.to_time) }}
                        </option>
                    </select>
                    <span class="customBorder"></span>
                      
                </div>
            </div>
       
          <span v-if="trackDate >0 && track.slot_id == slot_id">
            <div class="flex mt-8 items-center bg-blue-500 text-center justify-center text-white text-sm font-bold px-4 py-3" role="alert">
  <svg class="fill-current w-6 h-6 p-[2px] mr-2 rounded-full border-2 border-white" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
    <path d="M12.432 0c1.34 0 2.01.912 2.01 1.957 0 1.305-1.164 2.512-2.679 2.512-1.269 0-2.009-.75-1.974-1.99C9.789 1.436 10.67 0 12.432 0zM8.309 20c-1.058 0-1.833-.652-1.093-3.524l1.214-5.092c.211-.814.246-1.141 0-1.141-.317 0-1.689.562-2.502 1.117l-.528-.88c2.572-2.186 5.531-3.467 6.801-3.467 1.057 0 1.233 1.273.705 3.23l-1.391 5.352c-.246.945-.141 1.271.106 1.271.317 0 1.357-.392 2.379-1.207l.6.814C12.098 19.02 9.365 20 8.309 20z"/>
  </svg>
  {{ countSuspection }}, &nbsp;<p>Suspected Movement - <span class="underline cursor-pointer"><Link :href="route('doctor.schedule.visitation', track.slot_id)">Preview Chamber</Link></span>.</p>
</div>
          </span>
          
          
            <div class="text-center justify-center">
                <p class="py-4 px-4 font-bold md:text-xl text-base mb-4">Appointments Statics</p>
                <div class="grid lg:grid-cols-4 md:grid-cols-2 gap-6">
               
               <div class="border-2 border-[#5C2586] shadow-lg rounded overflow-hidden">
                   <div class="p-4 py-2 bg-[#5C2586] font-bold text-white text-center">Total Number</div>
                   <div class="p-4 items-center justify-center font-bold flex gap-1">{{totalAppointment + totalAppontmentFromTodayReport}}</div>
               </div>
               <div class="border-2 border-[#E8E600] shadow-lg rounded overflow-hidden">
                   <div class="p-4 py-2 bg-[#E8E600] font-bold text-white text-center">New</div>
                   <div class="p-4 items-center justify-center font-bold flex gap-1">{{totalNewAppointment}}</div>
               </div>
               <div class="border-2 border-[#545454] shadow-lg rounded overflow-hidden">
                   <div class="p-4 py-2 bg-[#545454] font-bold text-white text-center">Old</div>
                   <div class="p-4 items-center justify-center font-bold flex gap-1">{{totalOldAppointment}}</div>
               </div>
               <div class="border-2 border-[#299740] shadow-lg rounded overflow-hidden">
                   <div class="p-4 py-2 bg-[#299740] font-bold text-white text-center"> Report</div>
                   <div class="p-4 items-center justify-center font-bold flex gap-1">{{totalReportAppointment}}</div>
               </div>
           </div>
            </div>
          
            <div class="text-center justify-center">
                <p class="py-4 px-4 font-bold md:text-xl text-base mb-4">Visitation Statics</p>
            <div class="grid lg:grid-cols-4 md:grid-cols-2 gap-6">
                <div class="border-2 border-[#5C2586] shadow-lg rounded overflow-hidden">
                    <div class="p-4 py-2 bg-[#5C2586] font-bold text-white text-center">Present</div>
                    <div class="p-4 items-center justify-center font-bold flex gap-1">{{totalPresentPatient}}</div>
                </div>
                <div class="border-2 border-[#E8E600] shadow-lg rounded overflow-hidden">
                    <div class="p-4 py-2 bg-[#E8E600] font-bold text-white text-center">Absent</div>
                    <div class="p-4 items-center justify-center font-bold flex gap-1">{{totalAbsentPatient}}</div>
                </div>
                <div class="border-2 border-[#545454] shadow-lg rounded overflow-hidden">
                    <div class="p-4 py-2 bg-[#545454] font-bold text-white text-center">Paid Reports</div>
                    <div class="p-4 items-center justify-center font-bold flex gap-1">{{totalPaidReports}}</div>
                </div>
                <div class="border-2 border-[#299740] shadow-lg rounded overflow-hidden">
                    <div class="p-4 py-2 bg-[#299740] font-bold text-white text-center"> Unpaid Reports</div>
                    <div class="p-4 items-center justify-center font-bold flex gap-1">{{totalUnpaidReports}}</div>
                </div>
            </div>
            </div>

            <h4 class="text-center font-bold md:text-xl text-base mb-4 px-4 py-4">Economical Statics</h4>

            <table class="max-w-[600px] w-full mx-auto border-spacing-y-2 border-separate ">
                <tr class="font-bold md:text-lg text-base rounded-xl relative py-1 px-3">
                    <th></th>
                    <th colspan="1" class="sm:table-cell hidden text-center pb-4 pr-4">Number </th>
                    <th colspan="2" class="sm:hidden table-cell text-right pb-4 pr-4">Number</th>
                    <th colspan="2" class="text-right pb-4 w-[70px]">Revenue</th>
                </tr>
                <tr class="rounded-xl relative text-white font-bold">
                    <td class="w-[120px] pl-8 py-3 border-0 bg-red-500 rounded-l-xl">New</td>
                    <td class="text-center bg-red-500 border-0">{{totalNewAppointmentPresent}}</td>
                    <td class="text-right pr-8 bg-red-500 border-0 rounded-r-xl">৳ {{totalNewAmount}}</td>
                </tr>
                <tr class="relative py-1 text-white font-bold">
                    <td class="w-[120px] pl-8 py-3 bg-sky-500 rounded-l-xl">Old</td>
                    <td class="text-center bg-sky-500">{{totalPresentPatientOld}}</td>
                    <td class="text-right pr-8 bg-sky-500 rounded-r-xl">৳ {{ totalOldAmount }}</td>
                </tr>
                <tr class="relative py-1 text-white font-bold">
                    <td class="w-[120px] pl-8 py-3 bg-[#5C2586]  rounded-l-xl">Report</td>
                    <td class="text-center bg-[#5C2586] ">{{totalAllSeenReport}} <br/>({{totalCommonReport}} common)</td>
                    <td class="text-right pr-8 bg-[#5C2586]  rounded-r-xl">৳ {{totalReportAmount}}

                    </td>
                </tr>
                <tr class="relative py-1 text-white font-bold">
                    <td class="w-[120px] pl-8 py-3 bg-[#065016] rounded-l-xl">Total</td>
                    <td class="text-center bg-[#065016]">{{totalNewAppointmentPresent + totalPresentPatientOld + (totalAllSeenReport - totalCommonReport) }}</td>
                    <td class="text-right pr-8 bg-[#065016] rounded-r-xl">৳ {{totalNewAmount + totalOldAmount + totalReportAmount }}</td>
                </tr>
                </table>
        </form>
       
      
        <Modal v-model="dateModal">
          <div class="bg-white min-h-[130px] w-[250px] md:min-w-[650px]">
              <form @submit.prevent="fetchAppointments">
                  <div class="flex w-full gap-3 items-center pt-4 text-center justify-center">
                      <div>
                          <label for="startAt" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Start Date</label>
                          <Input.DatePicker v-model="startAt" placeholder="Select start date" />
                      </div>
                      <div>
                          <label for="endAt" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">End Date</label>
                          <Input.DatePicker v-model="endAt" placeholder="Select end date" />
                      </div>
                      <div>
                          <Button.Primary class="mt-6" @click="fetchAppointments">
                              Search
                          </Button.Primary>
                      </div>
                  </div>
              </form>
          </div>
      </Modal>
      <Modal v-model="showParticularModal" @close="handleClose()">
            <div v-if="showParticularModal" class="bg-white rounded w-[300px] md:w-[500px]">
                <div class="py-3 px-5 font-semibold border-b flex items-center justify-between">
                   
                </div>
                <div class="px-5 py-3">
                    
                  <form @submit.prevent="fetchAppointments">
                  <div class="flex w-full gap-3 items-center text-center justify-center">
                      <div>
                          <label for="particularDate" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Select Particular Date</label>
                          <Input.DatePicker v-model="particularDate" placeholder="Select start date" />
                      </div>
                     
                      <div>
                          <Button.Primary class="mt-6" @click="fetchAppointments">
                              Search
                          </Button.Primary>
                      </div>
                  </div>
              </form>
                    

                </div>
            </div>
        </Modal>
    </DoctorLayout>
  </template>
  
  <script setup>
  import DoctorLayout from "@/Layouts/DoctorLayout.vue";
  import { Table, Button, Modal, Icon } from "@/plugins/ui";
  import { Input } from '@/plugins/form'
 import { useForm, Link } from '@inertiajs/vue3'
  import { ref, watch,onMounted  } from "vue";
  import axios from "axios";
  import Helper, { convert24to12, dateFormate, dateFormateWithDay } from "@/helper";
  
  const formModal = ref(false);
  const dateModal = ref(false);
  
  const today = new Date();
   const offset = 6 * 60 * 60 * 1000; 

const bangladeshTime = new Date(today.getTime() + offset);

const currentdate = ref(bangladeshTime.toISOString().split('T')[0]);

const tomorrow = new Date(bangladeshTime);
tomorrow.setDate(bangladeshTime.getDate() + 1); 

const tomorrowdate = ref(tomorrow.toISOString().split('T')[0]);
  
  const props = defineProps({
    doctor: { type: Object, default: {} },
    track: { type: Object, default: {} },  
    chamber: {type:Object, default:{}},
    countSuspection:{type:Object,default:{}},
    patientTypes: { type: Array, default: [] },
});

const track = props.track || {};
  const chamber = props.chamber || {};
  const type = ref(" ");
  const chamber_id = ref('chamber'); 
  const slot_id = ref('slot');
  const slots = ref([]);
  const appointments = ref([]);
  const startAt = ref("");
  const endAt = ref("");
  const particularDate=ref("");
  const showParticularModal = ref(false)

//   report Start
const totalAppointment =ref(0);
const totalNewAppointment =ref(0);
const totalOldAppointment = ref(0);
const totalReportAppointment=ref(0);
const totalPresentPatient=ref(0);
const totalAbsentPatient=ref(0);
const totalPaidReports = ref(0);
const totalUnpaidReports =ref(0);
const totalNewAppointmentPresent=ref(0);
const totalNewAmount=ref(0);
const totalPresentPatientOld=ref(0);
const totalOldAmount=ref(0);
const totalAllSeenReport=ref(0);
const totalCommonReport=ref(0);
const totalReportAmount=ref(0);
const totalAppontmentFromTodayReport=ref(0);
// report End
 
  const trackDate=ref('');
  
  const handleClose = () => {
     
    showParticularModal.value = false
     
  }
  const fetchAppointments = async () => {
    try {
        const response = await axios.post(route("doctor.report.get"), {
            type: type.value,
            chamber_id: chamber_id.value,
            slot_id: slot_id.value,  
            startAt: startAt.value,
            endAt: endAt.value,
            particularDate:particularDate.value,
        });
  
        // appointments.value = response.data.data;
        let dataInfo = response.data;
        totalAppointment.value = dataInfo.totalNumber;
        totalNewAppointment.value = dataInfo.totalNewAppointment;
        totalOldAppointment.value = dataInfo.totalOldAppointment;
        totalReportAppointment.value = dataInfo.totalReportAppointment;
        totalPresentPatient.value = dataInfo.totalPresentPatient;
        totalAbsentPatient.value = dataInfo.totalAbsentPatient;
        totalPaidReports.value = dataInfo.totalPaidReports;
        totalUnpaidReports.value=dataInfo.totalUnpaidReports;
        totalNewAppointmentPresent.value=dataInfo.totalNewAppointmentPresent;
        totalNewAmount.value = dataInfo.totalNewAmount;
        totalPresentPatientOld.value=dataInfo.totalPresentPatientOld;
        totalOldAmount.value = dataInfo.totalOldAmount;
        totalAllSeenReport.value=dataInfo.totalAllSeenReport;
        totalCommonReport.value = dataInfo.totalCommonReport;
        totalReportAmount.value = dataInfo.totalReportAmount;
        trackDate.value=dataInfo.trackDate;
        totalAppontmentFromTodayReport.value = dataInfo.totalAppontmentFromTodayReport;
       
        if (dateModal.value) {
            dateModal.value = false;
          //   type.value = ""; 
        }
        if (showParticularModal.value) {
            showParticularModal.value = false;
          //   type.value = ""; 
        }
       
        
    } catch (error) {
        console.error("Error fetching appointments:", error);
    }
  };
  
  const getSlot = async () => {
    if (chamber_id.value) {
        try {
            const response = await axios.post(route("doctor.bookNew.slot"), {
                chamber_id: chamber_id.value,
            });
            slots.value = response.data.slots;
        } catch (error) {
            console.error("Error fetching slots:", error);
        }
    }
  };
  

  
  const handleDayChange = () => {
  
    startAt.value = "";
    endAt.value = "";
    particularDate.value = "";
  
  
    if (type.value === "custom") {
      dateModal.value = true; 
    } else if (type.value === "particular") {
      showParticularModal.value = true;
    } else {
      
      fetchAppointments();
     
    }
  };
  
  const chamberReset =()=>{
      chamber_id.value="";
  }
  const slotReset =()=>{
      slot_id.value="";
  }
  
  onMounted(() => {
    if (track?.date === currentdate.value) {
        type.value = 'today';
    } else if (track?.date === tomorrowdate.value) {
        type.value = 'yesterday';
    }
  
    chamber_id.value = chamber?.chamber_id;
    slot_id.value = track?.slot_id
    
    fetchAppointments();
    getSlot();
});
  </script>
  <style scoped>
  .customBorder{
      display: block;
      border-bottom: 1px solid #0002;
      position: relative;
  }
  .customBorder::before{
      content: '';
      position: absolute;
      bottom: 0;
      left: 0;
      width: 0px;
      border-bottom: 1px solid red;
      transition: 0.3s ease-in-out;
  }
  
  .myInput:focus + .customBorder::before{
      width: 100%;
  }
  .remove-shadow{
      box-shadow: none;
  }
  
  select option.font-semibold {
      font-weight: 600;
  }
  
  </style>