import { ref } from "vue";
import axios from "axios";


const form = ref({
    designation_ids: [],
    specialization_ids: [],
    degree_ids: [],
});
const filteredDoctors = ref({});
const search = ref();

export const useAdvertising = () => {
    const loading = ref(false);
    const getDoctors = async (url) => {
        loading.value = true;
        const { data } = await axios.post(
            url ||
                route("pharmaceutical.advertisings.filterDoctors", {
                    search: search.value,
                }),
            form.value
        );
        filteredDoctors.value = data;
        loading.value = false;
        console.log(filteredDoctors.value);
    };

    return {
        form,
        search,
        getDoctors,
        filteredDoctors,
        loading,
    };
};
