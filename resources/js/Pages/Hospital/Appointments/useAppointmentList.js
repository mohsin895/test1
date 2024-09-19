import { ref } from "vue";
import axios from "axios";
import { useForm } from "@inertiajs/vue3";
import { isEmpty, filter } from "lodash";
import Helper from "@/helper";


const getDoctorDates = ref({});
const getDoctorSlots = ref({});

const sorting = ref({
    designation_ids: [],
    specialization_ids: [],
    degree_ids: [],
});

const form = useForm({
    doctor_id: null,
    patient_name: "",
    phone: "",
    age: "",
    patient_type_id: null,
    chamber_id: null,
    date: null,
    slot_id: null,
    id:null,

});

export const useAppointmentList = () => {
   

    const getDates = async (doctor) => {
      
        const { data } = await axios.post(
            route("hospitals.bookappointment.getDates"),
            {
                user_id: doctor.id,
               
            }
        );
        getDoctorDates.value = data;
    };

    const getSlots = async (selectedDoctorChamber, date, day) => {
        const { data } = await axios.post(
            route("hospitals.bookappointment.getSlots", selectedDoctorChamber.id),
            {
                doctor_chamber_id: selectedDoctorChamber.doctor_chamber_id,
                date: date,
                day: day
            }
        );
        getDoctorSlots.value = data;
    };

    const handleBookAppointment = () => {
        Helper.confirm('Are you sure?', () => {
            form.post(route("hospitals.appointments.update"), {
                onSuccess(e) {
                    if (isEmpty(e.props.errors)) {
                        form.reset();
                    }
                }
            });
        })
    };

    return {
        form,
  
        handleBookAppointment,
        getDates,
        getDoctorDates,
        getDoctorSlots,
        getSlots,
    };
};

export default useAppointmentList