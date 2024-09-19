import { ref } from "vue";
import axios from "axios";
import { useForm } from "@inertiajs/vue3";
import { isEmpty, filter } from "lodash";
import Helper from "@/helper";

const search = ref("");
const filteredDoctors = ref({});
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

export const useBookAppointment = () => {
    const getDoctors = async (url) => {
        const { data } = await axios.post(
            url ||
                route("app.bookappointment.filterDoctors", {
                    search: search.value,
                })
        );
        filteredDoctors.value = data;
    };

    const getDates = async (doctorChamber) => {
        const { data } = await axios.post(
            route("app.bookappointment.getDates"),
            {
                user_id: doctorChamber.user_id,
                chamber_id: doctorChamber.chamber_id,
            }
        );
        getDoctorDates.value = data;
    };

    const getSlots = async (selectedDoctorChamber, date, day) => {
        const { data } = await axios.post(
            route("app.bookappointment.getSlots", selectedDoctorChamber.user_id),
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
            form.post(route("app.bookappointment.save"), {
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
        getDoctors,
        search,
        filteredDoctors,
        handleBookAppointment,
        getDates,
        getDoctorDates,
        getDoctorSlots,
        getSlots,
    };
};
