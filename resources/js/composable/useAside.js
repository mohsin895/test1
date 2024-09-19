import { ref, computed } from 'vue'
import adminMenu from './adminMenu'
import pharmaceuticalMenu from './pharmaceuticalMenu'
import doctorMenu from './doctorMenu'
import compounderMenu from './compounderMenu'
import hospitalMenu from './hospitalMenu'
import { onMounted } from 'vue'

const menuId = ref(null)

const activeAside = ref('doctor')

const menusObj = {
    admin: adminMenu,
    pharmaceutical: pharmaceuticalMenu,
    doctor: doctorMenu,
    compounder: compounderMenu,
    hospital: hospitalMenu,
}
export const dashboardLinks = {
    admin: 'app.dashboard',
    pharmaceutical: 'pharmaceutical.dashboard',
    doctor: 'doctor.dashboard',
    compounder: 'compounder.dashboard',
    hospital: 'hospitals.dashboard',
}

let menus = computed(() => {
    return menusObj[activeAside.value]
})

// let menus = ref([])

const useAside = () => {

    const handleDropdown = (id) => {
        if(menuId.value != id){
            menuId.value = id
            return
        }
        menuId.value = null
    }
    
    return {
        handleDropdown,
        menuId,
        menus,
        activeAside,
        doctorMenu,
        compounderMenu,
        dashboardLinks
    }
}

export default useAside