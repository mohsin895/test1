import { ref } from "vue";


const formModal = ref(false);
const chamberSlotModal = ref(false);

export const useCompounder = () => {

    return {
        formModal,
        chamberSlotModal,
    };
}