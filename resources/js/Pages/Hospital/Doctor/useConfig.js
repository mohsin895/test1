import { ref } from 'vue'
import SetupSpecialization from '@/Pages/Hospital/Doctor/fragments/SetupSpecialization.vue'
import SetupDesignation from '@/Pages/Hospital/Doctor/fragments/SetupDesignation.vue'
import SetupDegree from '@/Pages/Hospital/Doctor/fragments/SetupDegree.vue'
import SetupFees from '@/Pages/Hospital/Doctor/fragments/SetupFees.vue'
import SetupWeekDays from '@/Pages/Hospital/Doctor/fragments/SetupWeekDays.vue'
import SetupChamber from '@/Pages/Hospital/Doctor/fragments/SetupChamber.vue'

const activeTab = ref(0)

const loading = ref(false)

export const useConfig = () => {

    const steps = [
        { id: 0, title: 'Specialization', component: SetupSpecialization },
        { id: 1, title: 'Designation', component: SetupDesignation },
        { id: 2, title: 'Degree', component: SetupDegree },
        { id: 3, title: 'Fees', component: SetupFees },
        { id: 4, title: 'Chambers', component: SetupChamber },
        { id: 5, title: 'Days & Slots', component: SetupWeekDays },
    ];

    const handleNextPrev = (moveNumber) => {
        if(moveNumber > 0 && activeTab.value < steps.length - 1) {
            activeTab.value += moveNumber
        }
        if(moveNumber < 0 && activeTab.value > 0) {
            activeTab.value += moveNumber
        }
    }

    return {
        activeTab,
        steps,
        loading,
        handleNextPrev
    }
    
}