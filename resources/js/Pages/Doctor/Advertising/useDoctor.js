import { ref } from "vue";
import axios from "axios";
import { router, useForm } from "@inertiajs/vue3";
import Helper from "@/helper";


const filteredAdvertise = ref([]);
const loadingAdvertise = ref(false)

const form = useForm({
    status: 'all',
});

const useDoctor = () => {
    const getAdsByStatus = async () => {
        loadingAdvertise.value = true
        const { data } = await axios.post(
            route("doctor.advertising.FilteredByStatus", {
                status: form.status,
            })
        );
        loadingAdvertise.value = false
        filteredAdvertise.value = data;
    };

    const handleApprove = (id) => {
        Helper.confirm("Are you sure to approve?", () => {
            router.post(route("doctor.advertising.AdvertisingApproved", id));
        })
        getAdsByStatus()
    };

    const handleReject = (id) => {
        const confirmed = confirm("Are you sure to reject?");
        if (confirmed) {
            router.post(route("doctor.advertising.AdvertisingReject", id));
        }
        getAdsByStatus()
    };

    return {
        getAdsByStatus,
        form,
        filteredAdvertise,
        loadingAdvertise,
        handleApprove,
        handleReject,
    };
};

export default useDoctor;
