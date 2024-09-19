<template>
    <DoctorLayout title="Appointments">
        <form class="w-full mx-auto" @submit.prevent="fetchAppointments" >
            <div class="md:flex items-center justify-center w-full gap-3">
                <div>
                    <select
                        id="countries"
                        v-model="type"
                        @change="handleDayChange"
                        class="bg-gray-50 border lg:min-w-[335px] md:min-w-[235px] md:mb-0 mb-2 border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                    >
                        <option selected @click="dayReset()">Select Day</option>
                        <option value="today">Today ({{ dateFormateWithDay(currentdate) }})</option>
                        <option value="tomorrow">Tomorrow ({{ dateFormateWithDay(tomorrowdate) }})</option>
                        <option value="upcoming">Upcoming</option>
                        <option value="previous">Previous</option>
                        <option value="particular">Particular Date <span v-if="particularDate" class="font-semibold">({{ dateFormate(particularDate) }})</span></option>
                        <option value="custom">Customised <span v-if="startAt"> (<span v-if="startAt" class="font-semibold">{{ dateFormate(startAt) }}</span>
                          <span v-if="endAt" class="font-semibold"> - {{ dateFormate(endAt) }}</span>)</span><span v-else>Date</span></option>
                       
                    </select>
  
                    
                </div>
  
                <div>
                    <select
                        id="chambers"
                        v-model="chamber_id"
                        @change="getSlot"
                        @click="fetchAppointments"
                        class="bg-gray-50 border md:mb-0 mb-2 border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                    >
                        <option selected @click="chamberReset()" value="chamber">Select a Chamber</option>
                        <option v-for="(doctorChamber, index) in doctor.doctor_chambers" :key="index" :value="doctorChamber.chamber_id">
                            {{ doctorChamber.chamber.name }}
                        </option>
                    </select>
                </div>
  
                <div>
                    <select
                        id="slots"
                        v-model="slot_id"
                        @click="fetchAppointments"
                        class="bg-gray-50 lg:min-w-[335px] md:min-w-[235px] border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                    >
                        <option selected @click="slotReset()" value="slot">Select a Slot</option>
                        <option v-for="(slot, index) in slots" :key="index" :value="slot.id">
                            {{ convert24to12(slot.from_time) }} - {{ convert24to12(slot.to_time) }}
                        </option>
                    </select>
                </div>
            </div>
        </form>
  
        <!-- Appointment Table -->
        <div v-if="appointments.length">
              <Table
                  :config="tableConfig"
                  :data="appointments.data"
                  :trClass="trClass"
                  :searchKeys="searchKeys"
                  :theadClass="theadClass"
              >
                  <template #tableHeader>
                      <Button.Primary
                          outline
                          @click="handleBack(activeTab, activeSlot)"
                          class="mr-2 mb-2"
                      >
                          Back
                      </Button.Primary>
                  </template>
                  <template #td="{ item, key }">
                      <span :class="key == 'status' ? 'text-red-500' : ''">
                          {{ item[key] }}
                      </span>
                  </template>
                  <template #action="{ item }">
                      <div class="flex gap-0.5 justify-center w-full">
                          <Button.Warning @click="handleEdit(item)" class="!px-2">
                              <Icon
                                  name="PhPencilSimpleLine"
                                  size="20"
                                  weight="bold"
                              />
                          </Button.Warning>
                          <Button.Danger @click="handleDelete(item)" class="!px-2">
                              <Icon name="PhX" size="20" weight="bold" />
                          </Button.Danger>
                      </div>
                  </template>
              </Table>
          </div>
          <div v-if="!appointments.length && activeSlot" class="text-center text-slate-500 min-h-[100px] grid place-content-center">
              <span v-if="loading">
                  Loading...
              </span>
              <span v-else>
                  No record found
              </span>
          </div>
        <div v-if="appointments.length > 0" class="relative overflow-x-auto mt-4">
       
            <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
                <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                    <tr>
                        <th scope="col" class="px-6 py-3">Patient name</th>
                        <th scope="col" class="px-6 py-3">Phone</th>
                        <th scope="col" class="px-6 py-3">Age</th>
                        <th scope="col" class="px-6 py-3">Date</th>
                        <th scope="col" class="px-6 py-3">Type</th>
                        <th scope="col" class="px-6 py-3">Details</th>
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
                    </tr>
                </tbody>
            </table>
        </div>
        <div v-else class="text-center mt-4 text-gray-500">
            No appointments found
        </div>
  
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
  import { ref } from "vue";
  import axios from "axios";
  import  { convert24to12, dateFormate, dateFormateWithDay } from "@/helper";
  
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
  
  const type = ref("today");
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
    if (type.value === "custom") {
        dateModal.value = true; 
        particularDate.value="";
    } else if(type.value === "particular"){
      showParticularModal.value = true;
      startAt.value = "";
      endAt.value = "";
    }else {
      startAt.value = "";
        endAt.value = "";
        particularDate.value="";
        fetchAppointments(); 
    }
  };
  const chamberReset =()=>{
      chamber_id.value="";
  }
  const slotReset =()=>{
      slot_id.value="";
  }
  // const dayReset=()=>{
  //     startAt.value = "";
  //       endAt.value = "";
  //       particularDate.value="";
  //       type.value="";
  // }
  
  const tableConfig = [
      {
          title: "Sl",
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
          key: "date",
          sortable: true,
      },
      {
          title: "Slot info",
          cb({item}){
              let from_time = ''
              let to_time = ''
              try { from_time = convert24to12(get(item, 'slot.from_time')) } catch (error) {}
              try { to_time = convert24to12(get(item, 'slot.to_time')) } catch (error) {}
              return `${from_time} - ${to_time}`
          },
          sortable: true,
      },
  ];
  
  const searchKeys = ["patient_name", "phone", "age", "date", "patient_type.name"];
  
  const theadClass = "!bg-primary !text-white";
  const trClass = "hover:bg-gray-100";
  </script>
  