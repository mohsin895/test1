import { isValidEmail, isValidBangladeshiPhoneNumber } from "@/helper"
import { ref } from 'vue'
import { getCurrentInstance, computed, useAttrs } from "vue"

export function useForm()
{
  const uid = computed(() => {
    const attrs = useAttrs()
    const instance = getCurrentInstance()
    return attrs?.id ? attrs.id : "uid-" + instance.uid
  })

  const errorMsgs = ref({})

  const isValid = (rules, state) => {
    let validationStatus = true

    for (let field in rules) {
      const validationTypes = rules[field].split('|')
      for (let item of validationTypes) {
        const status = _processValidations[item](field, state.value)
        if(validationStatus){
          validationStatus = status
        }
        if (!validationStatus) break
      }
    }

    return validationStatus
  }


  const _formatText = (text) => {
    return text.replaceAll('_', ' ').replaceAll('-', ' ').toLowerCase().replace(/^\w/, (c) => c.toUpperCase())
  }

  const _processValidations = {
    required: (fieldName, state) => {
      const fieldValue = state[fieldName]?.trim()
      if (!fieldValue) {
        errorMsgs.value[fieldName] = `${_formatText(fieldName)} field is required`
        return false
      }
      errorMsgs.value[fieldName] = ''
      return true
    },
    email: (fieldName, state) => {
      const fieldValue = state[fieldName]?.trim()
      if (!fieldValue) return false
      if (!isValidEmail(fieldValue)) {
        errorMsgs.value[fieldName] = 'Email is not valid'
        return false
      }
      errorMsgs.value[fieldName] = ''
      return true
    },
    phone: (fieldName, state) => {
      const fieldValue = state[fieldName]?.trim()
      if (!fieldValue) return false
      if (!isValidBangladeshiPhoneNumber(fieldValue)) {
        errorMsgs.value[fieldName] = 'Phone number is not valid'
        return false
      }
      errorMsgs.value[fieldName] = ''
      return true
    },
    confirmation: (fieldName, state) => {
      const fieldValue = state[fieldName]?.trim()
      if (!fieldValue) return false
      if (fieldValue !== state['password']) {
        errorMsgs.value[fieldName] = 'Passwords do not match.'
        return false
      }
      errorMsgs.value['confirm_password'] = ''
      return true
    },
  }

  return {
    uid,
    errorMsgs,
    isValid
  }
}