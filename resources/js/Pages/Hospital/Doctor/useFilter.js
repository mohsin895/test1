import axios from "axios"
import { ref } from 'vue'

export const useFilter = () => {
    const loading = ref(false)
    const doctors = ref([])
    const getDoctors = async (payload) => {
        const { data } = await axios.post(route('hospitals.doctors.filter'), payload)
        doctors.value = data?.data || []
        return doctors
    }

    return {
        loading,
        doctors,
        getDoctors,
    }
}