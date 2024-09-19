import { ref } from "vue"
import { toast } from 'vue3-toastify';
import 'vue3-toastify/dist/index.css';
import { format, parseISO } from 'date-fns'
import Swal from 'sweetalert2'
import { get } from 'lodash'

export const isFullScreen = ref(false)

export const toggleFullScreen = () => {
    isFullScreen.value = !isFullScreen.value
}

export const isValidEmail = (email) => {
    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/
    if(email && email.trim() !== '') {
        email = email.replaceAll(/[\s-]/g, '')
        return emailRegex.test(email)
    }
}

export const dateFormate = (date) => {
    if(!date) return
    const dateObj = parseISO(date);
    return format(dateObj, 'MMM dd, yyyy')
}

export const dateFormateWithDay = (date) => {
    if (!date) return;
    const dateObj = parseISO(date);
    return format(dateObj, 'EEE, dd MMMM yyyy');
  };

  export const dateFormatWithMonth = (date) => {
    if (!date) return;
    const dateObj = parseISO(date);
    return format(dateObj, 'dd-MMM-yyyy'); // '22-Sep-2024'
  };

export const isValidBangladeshiPhoneNumber = (number) => {
    const bangladeshiPhoneNumberRegex = /^(?:\+88|01)?\d{11}$/;
  
    if(number && number.trim() !== '') {
        const cleanedNumber = number.replaceAll(/[\s-]/g, '')
        return bangladeshiPhoneNumberRegex.test(cleanedNumber)
    }
}

export const resizeObserver = (element, cb) => {
    if(!element) return
    const resizeObserver = new ResizeObserver(cb)

    resizeObserver.observe(element)
}

export const pageLoading = ref(false)

let closeTime = 1000
export const notify = {
    'success': (msg) => toast.success(msg, {
                autoClose: closeTime,
            }),
    'error': (msg) => toast.error(msg, {
                autoClose: closeTime,
            }),
    'warning': (msg) => toast.warning(msg, {
                autoClose: closeTime,
            }),
    'info': (msg) => toast.info(msg, {
                autoClose: closeTime,
            })
}

const handleOutsideClick = function(e, el, binding) {
    if(!el.contains(e.target)){
        binding.value()
    }
}

export const assignLocationInfo = (form, item) => {
    if (form, item) {
        form.location_name = get(item, 'location.location_name')
        form.location_id = get(item, 'location.location_id')
        form.lat = get(item, 'location.lat')
        form.lon = get(item, 'location.lon')
    }
}

export const vOutsideClick = {
    mounted: (el, binding) => {
        window.addEventListener('click', (e) => {
            handleOutsideClick(e, el, binding)
        })
    },
    beforeUnmount : () => {
        window.removeEventListener('click', () => {})
    }
}

export const vFocus = {
    mounted: (el, binding) => {
        if (binding.value) {
            el.focus()
        }
        // console.log(binding.value, el);
    }
}

export const getMonitoringType = (arg, standards) => {
    let found = standards.find(item => {
        return String(item.parameters).toLowerCase() == String(arg).toLowerCase()
    })
    return get(found, 'national_standards')
}

export function convert12to24(time12) {
    const timeParts = time12.split(' ');
    if (timeParts.length === 2) {
      const [time, period] = timeParts;
      const [hours, minutes] = time.split(':');
      let hours24 = parseInt(hours, 10);
      if (period === 'PM' && hours24 !== 12) {
        hours24 += 12;
      }
      if (period === 'AM' && hours24 === 12) {
        hours24 = 0;
      }
      const time24 = `${String(hours24).padStart(2, '0')}:${minutes}`;
  
      return time24;
    } else {
      return "Invalid time format";
    }
  }

export function convert24to12(time24) {
    const [hours, minutes] = time24.split(':');
    const hours24 = parseInt(hours, 10);
    const period = hours24 >= 12 ? 'PM' : 'AM';
    const hours12 = hours24 % 12 || 12;
    const time12 = `${hours12}:${minutes} ${period}`;
    return time12;
}


const Helper = {}

Helper.confirm = (msg=null, cb) => {
    let status = false
    Swal.fire({
        title: 'Confirm',
        html: msg ? msg : 'Are you sure to delete?',
        icon: 'warning',
        confirmButtonColor: '#4acb6f',
        cancelButtonColor: '#ef4444',
        confirmButtonText: 'Confirm',
        cancelButtonText: 'No',
        showCancelButton: true,
        showConfirmButton: true
    })

    Swal.getConfirmButton().onclick = () => {
        Swal.clickCancel()
        cb()
    }
}

export default Helper