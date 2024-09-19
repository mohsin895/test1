<template>
    <DoctorLayout title="Appointments">
        <form class="block" @submit.prevent="fetchAppointments" >
          <div class="flex gap-6 md:flex-nowrap flex-wrap rounded p-4 mb-2" style="font-weight: 400;">
                
                <div class="w-full">
                  <span class="absolute left-0 top-3 opacity-40">{{ placeholder }}</span>
                  <select
                      id="countries"
                      v-model="type"
                      @change="handleDayChange"
                      class="myInput border border-none focus:outline-none px-0 py-3 block w-full remove-shadow"
                  >
                      <option value="all" @click="dayReset()">Select Day</option>
                      <option value="today">Today ({{ dateFormateWithDay(currentdate) }})</option>
                      <option value="tomorrow">Tomorrow ({{ dateFormateWithDay(tomorrowdate) }})</option>
                      <option value="upcoming">Upcoming</option>
                      <option value="previous">Previous</option>
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
                        @click="fetchAppointments"
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
        </form>
        <div v-if="appointments.length > 0" class="relative overflow-x-auto mt-4">
       
        <Table
              :config="tableConfig"
              :data="appointments"
              :trClass="trClass"
              :searchKeys="searchKeys"
              :theadClass="theadClass"
          >
        
             
              <template #action="{ item }">
                  <div class="flex gap-2 justify-center w-full">
                      <Button.Info @click="viewAppointment(item)" class="!px-2">
                          <Icon name="PhEye" size="20" weight="bold" />
                            </Button.Info>
                      <Button.Warning @click="handleAppointmentEdit(item.id)" class="!px-2">
                          <Icon
                              name="PhPencilSimpleLine"
                              size="20"
                              weight="bold"
                          />
                      </Button.Warning>
                      <Button.Danger @click="handleDelete(item.id)" class="!px-2">
                          <Icon name="PhX" size="20" weight="bold" />
                      </Button.Danger>
                  </div>
              </template>
          </Table>
      </div>
        <div v-else class="text-center mt-4 text-orange-400">
            No appointment found!
        </div>
        <!-- <div v-if="appointments.length > 0" class="relative overflow-x-auto mt-4">
       
            <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
                <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                    <tr>
                        <th scope="col" class="px-6 py-3">Patient name</th>
                        <th scope="col" class="px-6 py-3">Phone</th>
                        <th scope="col" class="px-6 py-3">Age</th>
                        <th scope="col" class="px-6 py-3">Date</th>
                        <th scope="col" class="px-6 py-3">Type</th>
                        <th scope="col" class="px-6 py-3">Details</th>
                        <th scope="col" class="px-6 py-3">Status</th>
                        <th scope="col" class="px-6 py-3">Action</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="appointment in appointments" :key="appointment.id" class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
                        <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                            {{ appointment.patient_name }}
                        </th>
                        <td class="px-6 py-4">{{ appointment.phone }}</td>
                        <td class="px-6 py-4">{{ appointment.age }}</td>
                        <td class="px-6 py-4">{{ dateFormateWithDay(appointment.date) }}</td>
                        <td class="px-6 py-4">{{ appointment.patient_type.name }}</td>
                        <td class="px-6 py-4">
                            <Button.Info @click="viewAppointment(appointment)">
                                View
                            </Button.Info>
                        </td>
                        <td class="px-6 py-4">
                           <span v-if="appointment.present ==3" class="font-semibold"> - </span>
                           <span v-else-if="appointment.present ==0" class="font-semibold"> absent </span>
                           <span v-else-if="appointment.present ==1" class="font-semibold"> present </span>
                           <span v-else-if="appointment.present ==2" class="font-semibold"> skip </span>
                        </td>
                        <td class="px-6 py-4">
                              <div class="flex gap-5">
                                  <Button.Warning  class="!px-2"  @click="handleAppointmentEdit(appointment.id)"  >
                          <Icon
                              name="PhPencilSimpleLine"
                              size="20"
                              weight="bold"
                          />
                      </Button.Warning>
                              <Button.Danger @click="handleDelete(appointment.id)" class="!px-2">
                          <Icon name="PhX" size="20" weight="bold" />
                      </Button.Danger>
                              </div>
                          
                          </td>
                    </tr>
                </tbody>
            </table>
        </div>
        <div v-else class="text-center mt-4 text-orange-400">
            No appointment found!
        </div> -->
  
        <!-- Appointment Details Modal -->
        <Modal v-model="formModal">
            <div class="bg-white py-8 px-5 w-[300px] md:min-w-[500px]">
                <h2 class="font-semibold text-black mb-3">View Appointment</h2>
                <div>
                    <p><strong>Chamber:</strong> {{ selectedAppointment?.chamber.name }}</p>
                    <p><strong>Date:</strong> {{ selectedAppointment?.date }}</p>
                    <p>
                        <strong>Slot:</strong> 
                        {{ selectedAppointment?.slot.from_time ? convert24to12(selectedAppointment.slot.from_time) : 'N/A' }} 
                        - 
                        {{ selectedAppointment?.slot.to_time ? convert24to12(selectedAppointment.slot.to_time) : 'N/A' }}
                    </p>
                </div>
            </div>
        </Modal>
  
        <!-- Custom Date Search Modal -->
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
  import { useForm, router } from "@inertiajs/vue3";
  import { ref, watch,onMounted  } from "vue";
  import axios from "axios";
  import Helper, { convert24to12, dateFormate, dateFormateWithDay } from "@/helper";
  
  const formModal = ref(false);
  const dateModal = ref(false);
  
  const today = new Date();
  const currentdate = ref(today.toISOString().split('T')[0]);
  const tomorrow = new Date(today);
  tomorrow.setDate(today.getDate() + 1);
  const tomorrowdate = ref(tomorrow.toISOString().split('T')[0]);
  
  const props = defineProps({
    doctor: {
        type: Object,
        default: {},
    },
    patientTypes: {
        type: Array,
        default: [],
    },
  });
  
  const type = ref("all");
  const chamber_id = ref('chamber'); 
  const slot_id = ref('slot');
  const slots = ref([]);
  const appointments = ref([]);
  const selectedAppointment = ref(null);
  const startAt = ref("");
  const endAt = ref("");
  const particularDate=ref("");
  const showParticularModal = ref(false)
  
  const handleClose = () => {
     
    showParticularModal.value = false
     
  }
  const fetchAppointments = async () => {
    try {
        const response = await axios.post(route("doctor.bookNew.getAppointments"), {
            type: type.value,
            chamber_id: chamber_id.value,
            slot_id: slot_id.value,  
            startAt: startAt.value,
            endAt: endAt.value,
            particularDate:particularDate.value,
        });
  
        appointments.value = response.data.data;
  
       
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
  
  const viewAppointment = (appointment) => {
    selectedAppointment.value = appointment;
    formModal.value = true;
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
  
  const handleDelete = async (id) => {
      Helper.confirm("Are you sure to delete?", async () => {
        try {
          await router.post(route("doctor.bookNew.delete", id));
          fetchAppointments();
        } catch (error) {
          console.error("Error deleting appointment:", error);
          alert("Failed to delete the appointment. Please try again.");
        }
      });
    };
    
    const handleAppointmentEdit = (id) => {
      router.visit(route("doctor.bookNew.edit", id));
    };
  // const dayReset=()=>{
  //     startAt.value = "";
  //       endAt.value = "";
  //       particularDate.value="";
  //       type.value="";
  // }
  
  const tableConfig = [
      {
          title: "Serial",
          cb({ index }) {
              return index + 1;
          },
      },
      {
          title: "Patient Name",
          key: "patient_name",
          sortable: true,
      },
      {
          title: "Phone",
          key: "phone",
          sortable: true,
      },
      {
          title: "Age",
          key: "age",
          sortable: true,
      },
      {
          title: "Date",
          cb({ item }) {
              return dateFormateWithDay(item.date);
          },
          sortable: true,
      },
      {
          title: "Type",
          key: "patient_type.name",
          sortable: true,
      },
     
      {
          title: "Status",
          cb({ item }) {
              switch(item.present) {
                  case 0: return "Absent";
                  case 1: return "Present";
                  case 2: return "Skip";
                  default: return "-";
              }
          },
          sortable: true,
      },
      
  ];
  
  const searchKeys = ["patient_name", "phone", "age", "date", "patient_type.name"];
  
  const theadClass = "!bg-primary !text-white";
  const trClass = "hover:bg-gray-100";
  onMounted(() => {
      fetchAppointments();
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