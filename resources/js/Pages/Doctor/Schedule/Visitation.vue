<template>
    <DoctorLayout title="Visitation">
        <div class="text-center border border-zinc-2px px-2 py-1 items-center justify-center mb-5 md:flex gap-4">
    
            <span class="capitalize">{{ slot.day }}&nbsp;{{dateFormate( currentdate) }}</span>
            <br />
            <span>{{ get(slot, "doctor_chamber.chamber.name") }}</span>
            <span>
                <h2 class="font-bold text-xl text-center">
            <div class="capitalize">
              {{ convert24to12(slot.from_time) }} -
                {{ convert24to12(slot.to_time) }}
            </div>
        </h2>
            </span>
        </div>
        
        

        <div
            v-if="!slot.visitation_tracks && slot.appointments.length"
            class="flex justify-center mt-5"
        >
            <Button.Primary @click="handleStart(slot)" outline>
                Start Visitation Now
            </Button.Primary>
        </div>

        <div
            v-if="slot.visitation_tracks && slot.appointments.length"
            class="py-2 mt-5"
        >
            <div class="flex justify-center mb-5">
                <div
                    class="text-xl font-bold border-b-[1.5px] border-dashed border-slate-400 pb-1 px-5"
                >
                    Serial Ongoing
                </div>
            </div>
            <div class="flex flex-col items-center gap-2 justify-center mb-6">
                <div class="flex flex-1 gap-5">
                    <div
                    class="py-3 px-8 text-2xl border border-slate-400 text-slate-600 font-bold rounded-md shadow"
                >
                    {{ slot.visitation_tracks.current_serial || "Started" }}
                </div>
                <div
                    class="cursor-pointer py-3 px-4  bg-red-500 text-2xl border border-slate-400 text-white font-bold rounded-md shadow"
                v-if="slot.visitation_tracks.break_duration != NULL" @click="endBreak(slot.visitation_tracks)">
                   <span>End Break</span>
                </div>
                </div>
               
                <div v-if="slot.visitation_tracks.break_duration">
                    For
                    <span class="font-bold text-xl text-red-500">
                        {{ slot.visitation_tracks.break_duration }}
                    </span>
                    Minutes
                </div>
                <div v-if="slot.visitation_tracks?.end_at">
                    The {{ slot.visitation_tracks.current_serial }} time is
                    supposed to end at
                    <span class="font-bold text-xl text-red-500">
                        {{ slot.visitation_tracks.end_at }}
                    </span>
                </div>
            </div>

            <div class="flex justify-center mb-6">
                <div
                    class="text-xl font-bold border-b-[1.5px] border-dashed border-slate-400 pb-1 px-5"
                >
                    Manage Serial
                </div>
            </div>
            <div class="relative overflow-x-auto mt-4">
            
<div class="w-full flex justify-between items-center mb-3 mt-1 pl-3">
    <div>
        <!-- <h3 class="text-lg font-semibold text-slate-800">Generated Invoice for this Month</h3>
        <p class="text-slate-500">Overview of the invoices.</p> -->
    </div>
    <div class="ml-3">
        <div class="w-full max-w-sm min-w-[200px] relative">
        <div class="relative">
            <input types="text"
            v-model="search"
            class="bg-white w-full pr-11 h-10 pl-3 py-2 bg-transparent placeholder:text-slate-400 text-slate-700 text-sm border border-slate-200 rounded transition duration-300 ease focus:outline-none focus:border-slate-400 hover:border-slate-400 shadow-sm focus:shadow-md"
            placeholder="Search for patient..."
            />
            <button
            class="absolute h-8 w-8 right-1 top-1 my-auto px-2 flex items-center bg-white rounded "
            type="button"
            >
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="3" stroke="currentColor" class="w-8 h-8 text-slate-600">
                <path stroke-linecap="round" stroke-linejoin="round" d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z" />
            </svg>
            </button>
        </div>
        </div>
    </div>
</div>
 
<div class="relative flex flex-col w-full h-full  text-gray-700 bg-white shadow-md  bg-clip-border">
  <table class="w-full text-left table-auto min-w-max border border-gray-400">
    <thead>
      <tr class="bg-primary whitespace-nowrap text-sm ">
        <th class="p-3 border border-slate-300 bg-slate-50">
          <p class="block text-sm font-normal leading-none text-slate-500">
            Sl.
          </p>
        </th>
        <th class="p-3 border border-slate-300 bg-slate-50 max-w-[100px] sm:max-w-full">
          <p class="block text-sm font-normal leading-none text-slate-500">
           Name
          </p>
        </th>
        <th class="p-3 border border-slate-300 max-w-[120px] sm:max-w-full bg-slate-50" v-if="slot.visitation_tracks.current_serial =='Stop'">
          <p class="block text-sm font-normal leading-none text-slate-500">
         Status
          </p>
        </th>
        <th class="p-3 border border-slate-300 bg-slate-50" v-else>
          <p class="block text-sm font-normal leading-none text-slate-500">
         Action
          </p>
        </th>
        <th class="p-3 border-b border-slate-300 bg-slate-50">
          <p class="block text-center text-sm font-normal leading-none text-slate-500">
          Details
          </p>
        </th>
        
      </tr>
    </thead>
    <tbody>
      <tr   v-for="(item, index) in filteredAppointments"
      :key="index" :class="{ 'bg-red-500 hover:bg-red-400 text-white ': (item.is_running == 1), 'bg-rose-500 hover:bg-red-400 text-white': (item.is_next == 1) }"
      >
        <td class="p-2 border border-slate-200 py-1">
            <th
        scope="row"
        @click="handleSerial(item)"
        class="border text-[0.517rem] sm:text-base w-6 h-6 md:w-10 md:h-10 grid place-items-center rounded-full text-center justify-center shadow-md shadow-slate-600/30 hover:bg-slate-200 select-none"
        :class="{
             'border-black text-black': getStatus(report.visitation_tracks, item) == 'serialized',
            'border-green-600 text-green-600': getStatus(slot.visitation_tracks, item) == 'present',
            'border-green-800 text-green-800': getStatus(slot.visitation_tracks, item) == 'seen',
            'border-primary text-primary': getStatus(slot.visitation_tracks, item) == 'skip',
            'border-red-500 text-red-600':
                                getStatus(slot.visitation_tracks, item) ==
                                'absent',
           
        }"
    >
       <span>
        <span cl="text-[0.517rem] sm:text-base" v-if="item.status =='old'">R</span>
        <span class="text-[0.517rem] sm:text-base" v-else-if="item.status =='new'">R-{{item.serial_no}}</span>
        <span class="text-[0.517rem] sm:text-base" v-else>  {{ item.serial_no }}</span>
      </span>
    </th>
        </td>
        <td class="p-2 border text-[0.75rem] sm:text-base border-slate-200 py-1 max-w-[100px] sm:max-w-full" :class="{  ' text-white': (item.present == 4) }">
            {{item.patient_name}}
        </td>
        
        <td class="p-2 border text-[0.9rem] md:text-base border-slate-200 py-1 " >
            <span v-if="slot.visitation_tracks.current_serial =='Stop'">
                <span class=" left-0 top-3 text-green-700 font-semibold" v-if="item.present == 7">Present</span>
                <span class=" left-0 top-3  text-red-700 font-semibold" v-else>Absent</span>
            </span>
            <span v-else-if="slot.visitation_tracks.update_status =='Edit'">
                <div class="flex gap-6 md:flex-nowrap flex-wrap rounded" style="font-weight: 400;">
              <div class="w-full text-black">
                <span class="absolute left-0 top-3 opacity-40">{{ placeholder }}</span>
                <select
                  id="countries"
                  v-model="item.selectedType" 
                  @change="handleStatus(item)"
                  class="myInput border border-none focus:outline-none px-0 py-2 block w-full remove-shadow"
                >
                
                <option  value="serialized">Serialized</option>
                  <option  value="present">Present</option>
                  <option  value="absent">Absent</option>
                  <option  value="running">Running</option>
                  <option  value="skip">Skip</option>
                 <option  value="next">Next</option>
                 <option disabled value="seen"> Present & Seen </option>
                 <option  value="report">Report</option>
                 
                </select>
                <span class="customBorder"></span>
              </div>
            </div>
                </span>
            <span v-else-if="item.present== 7">
                <div class="flex gap-6 md:flex-nowrap flex-wrap rounded" style="font-weight: 400;">
              <div class="w-full text-black">
                <span class="absolute left-0 top-3 opacity-40">{{ placeholder }}</span>
                <select
                  id="countries"
                  v-model="item.selectedType" 
                  @change="handleStatus(item)"
                  class="myInput  border border-none focus:outline-none px-0 py-2 block w-full remove-shadow"
                >
                
                <option  value="serialized">Serialized</option>
                  <option  value="present">Present</option>
                  <option  value="running">Running</option>
                  <option  value="skip">Skip</option>
                 <option  value="next">Next</option>
                 <option disabled value="seen"> Present & Seen </option>
                 <option  value="report">Report</option>
                 
                </select>
                <span class="customBorder"></span>
              </div>
            </div>
            </span>
            <span v-else>
                <div class="flex gap-6 md:flex-nowrap flex-wrap rounded" style="font-weight: 400;">
              <div class="w-full text-black">
                <span class="absolute left-0 top-3 opacity-40">{{ placeholder }}</span>
                <select
                  id="countries"
                  v-model="item.selectedType" 
                  @change="handleStatus(item)"
                  class="myInput border border-none focus:outline-none px-0 py-2 block w-full remove-shadow"
                >
                
                <option  value="serialized">Serialized</option>
                  <option  value="present">Present</option>
                  <option  value="running">Running</option>
                  <option  value="skip">Skip</option>
                 <option  value="next">Next</option>
                 <option  value="report">Report</option>
                </select>
                <span class="customBorder"></span>
              </div>
            </div>
            </span>
            
        </td>
        <td class="p-2 border border-slate-200 py-1 ">
            <div class="flex gap-2 items-center justify-center w-full">
                <Button.Info @click="handleSerial(item)" class="!px-1 ">
                        <Icon name="PhEye" size="16" weight="bold" /><span class="text-sm">View</span>
                          </Button.Info>
                          <span v-if="item.serialized_status.length > 0">
                            <Button.Info @click="handleHistory(item)" class="!px-1 ">
                <Icon name="PhInfo" size="16" weight="bold" />
                    </Button.Info>
                          </span>
                          <span v-else>
                             <div class="w-[26px] h-[26px]">

                             </div>
                          </span>
                  
               </div>
        </td>
      
      </tr>
      
    </tbody>
  </table>

</div>
       </div>

          
            <div class="relative overflow-x-auto mt-8" >
                        

                <div class="flex justify-center mb-2">
                            <div
                                class="text-xl font-bold border-b-[1.5px] border-dashed border-slate-400 pb-1 px-5"
                            >
                                Manage Report
                            </div>
                        </div>
            
            <div class="relative flex flex-col w-full h-full  text-gray-700 bg-white shadow-md  bg-clip-border">


                        <div class="flex justify-between items-center gap-5">
            <!-- Left side: Button -->
            <Button.Primary @click="addNewReportSerial(slot.visitation_tracks)" class="!px-1">
                <Icon name="PhPlus" size="16" weight="bold" />
                <span class="text-sm">Add New Report</span>
            </Button.Primary>

            <!-- Right side: Search input -->
            <div class="relative flex items-center justify-end">
                <input type="text" v-model="searchReport"
                class="bg-white w-full pr-11 h-10 pl-3 py-2 bg-transparent placeholder:text-slate-400 text-slate-700 text-sm border border-slate-200 rounded transition duration-300 ease focus:outline-none focus:border-slate-400 hover:border-slate-400 shadow-sm focus:shadow-md"
                placeholder="Search for patient..." />
                <button
                class="absolute h-8 w-8 right-1 top-1 my-auto px-2 flex items-center bg-white rounded"
                type="button">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="3" stroke="currentColor"
                    class="w-8 h-8 text-slate-600">
                    <path stroke-linecap="round" stroke-linejoin="round"
                    d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z" />
                </svg>
                </button>
            </div>
            </div>

                    
                                
                    
                    
                <table class="w-full text-left table-auto min-w-max mt-5">
                <thead>
                <tr class="bg-primary whitespace-nowrap text-sm ">
                    <th class="p-3 border-b border-slate-300 bg-primary">
                    <p class="block text-sm font-normal leading-none text-white">
                        Serial.
                    </p>
                    </th>
                    <th class="p-3 border-b border-slate-300 bg-primary">
                    <p class="block text-sm font-normal leading-none text-white">
                    Name
                    </p>
                    </th>
                
                    <th class="p-3 border-b border-slate-300 bg-primary">
                    <p class="block text-sm font-normal leading-none text-white">
                    Action
                    </p>
                    </th>
                    
                </tr>
                </thead>
                <tbody v-if="filteredAppointmentsReports.length > 0">
                <tr class="hover:bg-slate-50"   v-for="(item, index) in filteredAppointmentsReports"
                :key="index"  >
                    <td class="p-2 border-b border-slate-200 py-1">
                        <th
                    scope="row"
                    @click="handleSerial(item)"
                    class="border border-black text-black w-7 h-7 grid place-items-center rounded-full text-center shadow-md shadow-slate-600/30 hover:bg-slate-200 select-none" >
                <span v-if="item.status =='old'">
                    R

                </span>
                <span v-else>
                    {{ item.serial_no }}
                </span>
                
                </th>
                    </td>
                    <td class="p-2 border-b border-slate-200 py-1 text-black">
                        {{item.patient_name}}
                    </td>
                
                    <td class="p-2 border-b border-slate-200 py-1">
                        <div class="flex gap-1  w-full " v-if="slot.visitation_tracks.current_serial =='Stop'">
                            <button class="border border-zinc-2px !px-1 py-2 px-4" @click="addnReportSerial(item)"  :disabled="slot.visitation_tracks.current_serial === 'Stop'" >
                                <span>Mark as next</span>
                                    </button>
                        </div>
                        <div class="flex gap-1  w-full" v-else>
                            <Button.Success @click="addnReportSerial(item)" class="!px-1 ">
                                <span>Mark as next</span>
                                    </Button.Success>
                        </div>
                    </td>
                
                </tr>
                
                </tbody>
                
            
            </table>
            <div v-if="!filteredAppointmentsReports.length " class="text-center w-full text-slate-500 font-semibold">
                                    No information found
                                </div>
            </div>
            </div>
            

            <div>
            <div v-if="slot.visitation_tracks.current_serial =='Stop'">

            </div>
            <div v-else>
                <div class="flex justify-center mb-5 mt-20">
                    <div
                        class="text-xl font-bold border-b-[1.5px] border-dashed border-slate-400 pb-1 px-5"
                    >
                        More Options
                    </div>
                </div>
                <div class="flex justify-center gap-3 mt-6">
                    <Button.Primary
                        :outline="
                            !(
                                get(slot, 'visitation_tracks.current_serial') ==
                                'Emergency'
                            )
                        "
                        @click="
                            openBreakPopup(
                                'Emergency',
                                slot.visitation_tracks?.break_duration
                            )
                        "
                    >
                        Emergency
                    </Button.Primary>
                    <Button.Primary
                        :outline="
                            !(
                                get(slot, 'visitation_tracks.current_serial') ==
                                'Break'
                            )
                        "
                        @click="
                            openBreakPopup(
                                'Break',
                                slot.visitation_tracks?.break_duration
                            )
                        "
                    >
                        Break
                    </Button.Primary>
                    <Button.Primary
                        :outline="
                            !(
                                get(slot, 'visitation_tracks.current_serial') ==
                                'Prayer'
                            )
                        "
                        @click="
                            openBreakPopup(
                                'Prayer',
                                slot.visitation_tracks?.break_duration
                            )
                        "
                    >
                        Prayer
                    </Button.Primary>
                </div>
            </div>
                
            </div>
            
        <div class="flex flex-col items-end gap-2 mt-6 justify-end mb-6">
            <div class="flex flex-1 gap-5" v-if="slot.visitation_tracks.current_serial =='Stop'">
                
            <div
                class="cursor-pointer py-1 px-4  bg-indigo-500 text-2xl border border-slate-400 text-white font-bold rounded-md shadow"
            @click="updateAllData(slot.visitation_tracks)">
                <span >Edit Visitation</span>
            </div>
            </div>
            <div class="flex flex-1 gap-5" v-else-if="slot.visitation_tracks.current_serial =='Edit'">
                
                <div
                    class="cursor-pointer py-1 px-4  bg-indigo-500 text-2xl border border-slate-400 text-white font-bold rounded-md shadow"
                @click="saveAllData(slot.visitation_tracks)">
                    <span >Exit</span>
                </div>
                </div>
            <div class="flex flex-1 gap-5" v-else>
                
                <div
                    class="cursor-pointer py-1 px-4  bg-red-500 text-2xl border border-slate-400 text-white font-bold rounded-md shadow"
                @click="saveAllData(slot.visitation_tracks)">
                    <span >Stop Visitation</span>
                </div>
                </div>
                     
            </div>
        </div>
        
        <div v-else class="text-center py-5 text-slate-500 mt-10 text-xl">
            <template v-if="!slot.appointments.length">
                No serial found
            </template>
        </div>

        

        <Modal v-model="selectedSerial">
            <div
                v-if="selectedSerial"
                class="w-[500px] bg-white py-4 px-5 rounded-md"
            >
                <div class="font-bold border-b pb-2 border-slate-300">
                    Appointment information
                </div>
                <div class="py-4 grid gap-2">
                    <div>
                        <span class="font-bold">Name:</span>
                        {{ selectedSerial.patient_name }}
                    </div>
                    <div>
                        <span class="font-bold">Age:</span>
                        {{ selectedSerial.age ?? 'N/A' }}
                    </div>
                    <div>
                        <span class="font-bold">Phone:</span>
                        {{ selectedSerial.phone ?? 'N/A' }}
                    </div>
                    <div>
                        <span class="font-bold">Type:</span>
                        <span
                            class="ml-2 py-0.5 px-3 rounded-xl bg-blue-100 border border-blue-500"
                            >{{
                                get(selectedSerial, "patient_type.name")
                            }}</span
                        >
                    </div>
                    <div>
                        <span class="font-bold">Tracking Code:</span>
                        {{ get(selectedSerial, "tracking_code") }}
                    </div>
                   
                </div>
            </div>
        </Modal>

        <Modal v-model="selectedHistory">
            <div v-if="selectedHistory" class="w-[500px] bg-white rounded-md">
                <div class="p-3 border-b font-semibold text-xl">History</div>
                <div class="py-4 px-3">
                    <ul class="list-disc">
                        <li
                            v-for="(info, index) in selectedHistory.serialized_status " :key="index"
                        >
                          <span>{{ ++index }}.</span>  Changed from <span class="font-semibold text-green-700">Present</span> to  <span v-if="info.status == 3" class="text-red-500">Skip</span><span v-else-if="info.status == 0" class="text-red-500">Serialized</span>
                            by <span class="font-semibold">{{ info.user_info.name }}</span> At {{dateFormatWithMonth(info.updated_date)}} {{ convert24to12(info.updated_time) }}
                            <!-- <strong class="font-semibold">{{ get(modified, 'user.name') }}</strong>
                            At
                            <strong class="font-semibold">{{ modified.created_at }}</strong> -->
                        </li>
                    </ul>
                    <div v-if="!selectedHistory.serialized_status.length" class="text-center text-slate-500 font-semibold">
                        No information found
                    </div>
                </div>
            </div>
        </Modal>
       

        <Modal v-model="breakModal">
            <div
                v-if="breakModal"
                class="md:w-[500px] bg-white py-4 px-5 rounded-md select-none"
            >
                <div class="font-bold border-b pb-2 border-slate-300">
                    <span v-if="breakModal == 'Emergency'">
                        Do you want to have some time for emergency issues?
                    </span>
                    <span v-if="breakModal == 'Prayer'">
                        Do you want to have some time for prayer?
                    </span>
                    <span v-if="breakModal == 'Break'">
                        Do you want to have some time for break?
                    </span>
                </div>
                <div class="py-3">
                    <div
                        class="h-[90px] flex justify-center items-center gap-2 mt-12"
                    >
                        <div class="w-[100px]"></div>
                        <TimeSlider v-model="handleBreakForm.minute" />
                        <div class="w-[100px]">Minutes</div>
                    </div>
                    <div class="flex justify-center mt-16">
                        <Button.Primary
                            @click="handleBreak(slot.visitation_tracks)"
                        >
                            Save
                        </Button.Primary>
                    </div>
                </div>
            </div>
        </Modal>

        <Modal v-model="prescriptionModal">
            <div v-if="selectedSerial">
                <form
                    @submit.prevent="handleUpload"
                    class="bg-white py-8 px-5 w-[400px] md:min-w-[600px]"
                >
                    <Input.File
                        type="file"
                        label="Prescription"
                        @input="(e) => (uploadForm.media = e.target.files[0])"
                        :error="uploadForm.errors.media"
                    />
                    <div class="flex justify-end">
                        <Button.Primary
                            type="submit"
                            :loading="uploadForm.processing"
                            class="mt-4"
                        >
                            Upload
                        </Button.Primary>
                    </div>
                </form>
            </div>
        </Modal>

        <Modal v-model="addNewReport">
            <div v-if="addNewReport" class="w-[500px] bg-white rounded-md">
                <div class="p-3 border-b font-semibold text-xl">Add New Report</div>
                <div class="py-4 px-3">
                    <table class="table-auto w-full border-collapse">
      <thead>
        <tr>
          <th class="border px-4 py-2">Patient Name</th>
          <th class="border px-4 py-2">Fees</th>
          <th class="border px-4 py-2">Actions</th>
        </tr>
      </thead>
      <tbody>
        <tr v-for="(item, index) in rows" :key="index">
          <td class="border px-4 py-2">
            <Input.Primary
                                    v-model="item.name"
                                 
                                    placeholder="Enter patient name"
                                />
           
          </td>
          <td class="border px-4 py-2">
            <label
                                        class="block cursor-pointer relative"
                                        v-if="doctorDetails.report_fees >0" >
                                
                                        <input
                                            type="radio"
                                        
                                            class="peer"
                                          value="1"
                                            v-model="item.fees"
                                        />
                                        Yes
                                        </label>

                                        <label
                                        class="block cursor-pointer relative"
                                    >
                                        <input
                                            type="radio"
                                         
                                            class="peer"
                                         value="2"
                                            v-model="item.fees"
                                        />
                                        No
                                        </label>
           
          </td>
        
          <td class="border px-4 py-2">
            <Button.Success
              @click="addRowAfter(index)" 
             
            >
            <Icon name="PhPlus" size="16" weight="bold" />
            </Button.Success>
            <Button.Danger 
              v-if="rows.length > 1" 
              @click="removeRow(index)" 
              class="ml-4"
            >
            <Icon name="PhMinus" size="16" weight="bold" />
            </Button.Danger>
          </td>
        </tr>
      </tbody>
    </table>
            <div class="justify-end items-end text-right mt-4">
                <Button.Primary
                        @click="addnewAppointment(slot.visitation_tracks)"
                        
                    >
                      Save
                    </Button.Primary>
            </div>
            
                </div>
            </div>
        </Modal>
    </DoctorLayout>
</template>


<script setup>
import DoctorLayout from "@/Layouts/DoctorLayout.vue";
import { get } from "lodash";
import { Input } from "@/plugins/form";
import { Table, Button, Modal, Icon } from "@/plugins/tableSchudle";
import Helper, { convert24to12, notify,dateFormate,dateFormatWithMonth } from "@/helper";
import { useForm, Link } from "@inertiajs/vue3";
import { ref, computed } from 'vue';
import TimeSlider from "./fragments/TimeSlider.vue";
const dropdownVisible = ref({});
const today = new Date();
const currentdate = ref(today.toISOString().split('T')[0]);

const props = defineProps({
    slot: {
        type: Object,
        default: {},
    },
    report:{
      type:Object,
      default:{},
    },
    doctorDetails:{
      type:Object,
      default:{},
    },
    date: String,
});

const form = useForm({
    day: null,
    date: null,
    slot_id: null,
    doctor_chamber_id: null,
    note: null,
});

const uploadForm = useForm({
    media: null,
});

const appointment_status_form = useForm({
    appointment_id: null,
    status: null,
});

const handleBreakForm = useForm({
    note: null,
    type: "Emergency",
    track_id: null,
    minute: 1,
});
const handlereportForm=useForm({
dataId: null,
});
const handleNewApponitment = useForm({
  
    track_id: null,
      name: '',
      fees:'',
 
});

const selectedSerial = ref(false);
const breakModal = ref(false);
const prescriptionModal = ref(false);
const selectedHistory=ref(false);
const type=ref();
const search = ref("");
const searchReport=ref("");
const visibleStart = ref(0);  
const visibleCount = ref(2); 
const visibleCountData = ref(1); 
const showAllAppointments = ref(false);
const addNewReport = ref(false)
const rows=ref([{ name: '',fees:2 }]); 
const loadPrevious = () => {
    showAllAppointments.value = true;
};

function addRowAfter(index) {
    
      this.rows.splice(index + 1, 0, { name: '', fees:2});
    }
   function removeRow(index) {
     
      this.rows.splice(index, 1);
    }
const visibleAppointments = computed(() => {
    const currentSerial = props.slot.visitation_tracks?.current_serial;

    if (showAllAppointments.value) {
        return props.slot.appointments;
    }

    return props.slot.appointments.filter((appointment) => {
        return appointment.serial_no >= currentSerial;
    });
});

const hasMoreAppointments = computed(() => {
    return visibleStart.value + visibleCount.value < props.slot.appointments.length;
});
const loadMoreData = () => {
    if (hasMoreAppointments.value) {
        visibleStart.value += visibleCountData.value;
    }
};
const filteredAppointments = computed(() => {
    if (!search.value) {
        return props.slot.appointments;
    }
    return props.slot.appointments.filter(appointment => 
    appointment.patient_name.toLowerCase().includes(search.value.toLowerCase()) ||
    appointment.serial_no.toString().toLowerCase().includes(search.value.toLowerCase()) ||
    appointment.present.toString().toLowerCase().includes(search.value.toLowerCase())
    );
});
const filteredAppointmentsReports = computed(() => {
    if (!searchReport.value) {
        return props.report.appointments;
    }
    return props.report.appointments.filter(appointment => 
    appointment.patient_name.toLowerCase().includes(searchReport.value.toLowerCase()) ||
    appointment.serial_no.toString().toLowerCase().includes(searchReport.value.toLowerCase()) ||
    appointment.present.toString().toLowerCase().includes(searchReport.value.toLowerCase())
    );
});
const handleSerial = (serial) => {
    selectedSerial.value = serial;
};
const handleHistory = (serial) => {
    selectedHistory.value = serial;
  
};
const openBreakPopup = (type, duration) => {
    breakModal.value = type;
    handleBreakForm.minute = +duration || 1;
};

const addNewReportSerial = () => {
    addNewReport.value = type;
   
};
const handleBreak = (track) => {
    if (!track?.id) return;
    handleBreakForm.track_id = track.id;
    handleBreakForm.type = breakModal.value;

    handleBreakForm.post(route("doctor.schedule.save_break"), {
        onFinish() {
            handleBreakForm.reset();
            breakModal.value = false;
        },
    });
};
const endBreak = (track) => {
    if (!track?.id) return;
    handleBreakForm.track_id = track.id;
    handleBreakForm.type = breakModal.value;

    handleBreakForm.post(route("doctor.schedule.end_break"), {
        onFinish() {
            handleBreakForm.reset();
           
        },
    });
};
const saveAllData = (track) => {
    if (!track?.id) return;
    handleBreakForm.track_id = track.id;
   

    Helper.confirm('Are you sure?', () => {
        handleBreakForm.post(route("doctor.schedule.save_appointment_all"), {
                onSuccess(e) {
                    if (isEmpty(e.props.errors)) {
                        handleBreakForm.reset();
                    }
                }
            });
        })
    // handleBreakForm.post(route("doctor.schedule.save_appointment_all"), {
    //     onFinish() {
    //         handleBreakForm.reset();
        
    //     },
    // });
};

const updateAllData = (track) => {
    if (!track?.id) return;
    handleBreakForm.track_id = track.id;
   

    Helper.confirm('Are you sure?', () => {
        handleBreakForm.post(route("doctor.schedule.update_appointment_all"), {
                onSuccess(e) {
                    if (isEmpty(e.props.errors)) {
                        handleBreakForm.reset();
                    }
                }
            });
        })
    // handleBreakForm.post(route("doctor.schedule.save_appointment_all"), {
    //     onFinish() {
    //         handleBreakForm.reset();
        
    //     },
    // });
};

const addnewAppointment = (track) => {
    if (!track?.id) return;
    handleNewApponitment.track_id = track.id;
    handleNewApponitment.name = rows.value.map(field => field.name);
    handleNewApponitment.fees = rows.value.map(field => field.fees);
   
    handleNewApponitment.post(route("doctor.schedule.save_new_appointment"), {
        onFinish() {
            handleNewApponitment.reset();
            rows.value = [{ name: '' }];
            addNewReport.value=false;
        
        },
    });
};


const addnReportSerial = (track) => {
    if (!track?.id) return;
    handlereportForm.dataId = track.id;
   

    Helper.confirm('Are you sure?', () => {
        handlereportForm.post(route("doctor.schedule.save_appointment_report"), {
                onSuccess(e) {
                    if (isEmpty(e.props.errors)) {
                        handlereportForm.reset();
                    }
                }
            });
        })
    // handleBreakForm.post(route("doctor.schedule.save_appointment_all"), {
    //     onFinish() {
    //         handleBreakForm.reset();
        
    //     },
    // });
};

const handleBookAppointment = () => {
        Helper.confirm('Are you sure?', () => {
            form.post(route("doctor.bookNew.save"), {
                onSuccess(e) {
                    if (isEmpty(e.props.errors)) {
                        form.reset();
                    }
                }
            });
        })
    };
const getStatus = (track, appointment) => {
    let status = "";
   
        if (appointment.present == 0) {
            status = "serialized";
        }
        if (appointment.present == 1) {
            status = "present";
        }
        if (appointment.present == 2) {
            status = "absent";
        }
        if (appointment.present == 3) {
            status = "skip";
        }
        if (appointment.present == 4) {
            status = "next";
        }
        if (appointment.present == 5) {
            status = "report";
        }
        if (appointment.present == 7) {
            status = "seen";
        }
    
    return status;
};

const handleStatus = (item) => {
    let status = '';
    let color = '';
    if (item.selectedType === 'serialized') {
        status = 0;
      
    } else if (item.selectedType === 'present') {
        status = 1;
        color = 'text-green-600'; 
    } else if (item.selectedType === 'absent') {
        status = 2;
    }else if (item.selectedType === 'skip') {
        status = 3;
        color = 'text-primary';  
    }else if (item.selectedType === 'running') {
        status = 4;
        color = 'text-red-600';  
    }else if (item.selectedType === 'next') {
        status = 5;
    }else if (item.selectedType === 'report') {
        status = 6;
    }else if (item.selectedType === 'seen') {
        status = 7;
    }

    const message = `Are you sure you want to mark this appointment as <span class='${color} font-bold' style='font-size: 1.65rem;'>${item.selectedType}</span>?`;

    Helper.confirm(message, () => {
        appointment_status_form.appointment_id = item.id;
        appointment_status_form.status = status;

        appointment_status_form.post(route("doctor.schedule.update_status"), {
            onFinish() {
                showAllAppointments.value = false;
                visibleStart.value = 0; 
                //loadMoreData(); 
            },
        });
    });
};


const handleStart = (slot) => {
    Helper.confirm("Are you sure to start visitation?", () => {
        form.day = slot.day;
        form.date = props.date;
        form.slot_id = slot.id;
        form.doctor_chamber_id = slot.doctor_chamber_id;
        form.post(route("doctor.schedule.start", slot.id), {
            onFinish() {
                form.reset();
            },
        });
    });
};

const handleUploadModal = (selectedSerial) => {
  
    form.id = selectedSerial.id;
    uploadForm.media = null; 
    prescriptionModal.value = true; 
};
const handleUpload = () => {
    uploadForm.post(route("doctor.schedule.prescription-upload", form.id), {
        onFinish() {
            uploadForm.reset();
            prescriptionModal.value = false;
        },
    });
};

const tableConfig = [
   
    
];

const searchKeys = ["patient_name", "phone", "age", "date", "patient_type.name"];

const theadClass = "!bg-primary !text-white m";

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