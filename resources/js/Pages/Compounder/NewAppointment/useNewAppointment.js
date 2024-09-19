import { ref } from 'vue'
import axios from 'axios'
import { useForm } from "@inertiajs/vue3";
import { isEmpty, filter } from "lodash";
import Helper from "@/helper";

const getDoctorDates = ref({});
const getDoctorSlots = ref({});

const form = useForm({
    doctor_id: null,
    patient_name: "",
    phone: "",
    age: "",
    patient_type_id: null,
    chamber_id: null,
    date: null,
    slot_id: null,
});

const useNewAppointment = () => {
    const appointments = ref([])
    const loading = ref(false)
    const payload = ref({
        type: null,
        chamber_id: null,
        slot_id: null,
    })

    const getAppointments = () => {
        loading.value = true
        appointments.value = []
        let { type, chamber_id, slot_id } = payload.value
        if (!type && !chamber_id && slot_id) {
            return
        }

        axios
            .post(route('compounder.appointments.getAppointments'), payload.value)
            .then(res => {
                appointments.value = res.data || []
                loading.value = false
            })
            .catch(()=> {
                loading.value = false
            })
    }
    const getDates = async (doctorChamber) => {
        const { data } = await axios.post(
            route("compounder.bookNew.getDates"),
            {
                user_id: doctorChamber.user_id,
                chamber_id: doctorChamber.chamber_id,
            }
        );
        getDoctorDates.value = data;
    };

    const getSlots = async (selectedDoctorChamber, date, day) => {
        const { data } = await axios.post(
            route("compounder.bookNew.getSlots", selectedDoctorChamber.user_id),
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
            form.post(route("compounder.bookNew.save"), {
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
        loading,
        payload,
        appointments,
        handleBookAppointment,
        getAppointments,
        getDates,
        getDoctorDates,
        getDoctorSlots,
        getSlots,
    }
}


export default useNewAppointment