import { ref } from 'vue'
import axios from 'axios'

const useAppointment = () => {
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
            .post(route('doctor.appointments.getAppointments'), payload.value)
            .then(res => {
                appointments.value = res.data || []
                loading.value = false
            })
            .catch(()=> {
                loading.value = false
            })
    }

    return {
        loading,
        payload,
        appointments,
        getAppointments,
    }
}


export default useAppointment