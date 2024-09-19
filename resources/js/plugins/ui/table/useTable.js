import { ref } from 'vue'
import { get } from 'lodash'
import Fuse from 'fuse.js'

export const data = ref([])
export const cloneData = ref([])
export const showSearchInput = ref(true)

let orderBy = 'asc'
export const sortData = (key) => {
    if (orderBy === 'asc') {
        data.value.sort((a, b) => {
            const valueA = get(a, key)
            const valueB = get(b, key)

            if (!isNaN(+valueA) && !isNaN(+valueB)) {
                return valueA - valueB
            } else if (valueA instanceof Date && valueB instanceof Date) {
                return valueA.getTime() - valueB.getTime()
            } else {
                return valueA.toString().localeCompare(valueB.toString())
            }
        })

        orderBy = 'desc'
    } else {
        data.value.sort((a, b) => {
            const valueA = get(a, key)
            const valueB = get(b, key)

            if (!isNaN(+valueA) && !isNaN(+valueB)) {
                console.log('number')
                return valueB - valueA
            } else if (valueA instanceof Date && valueB instanceof Date) {
                return valueB.getTime() - valueA.getTime()
            } else {
                return valueB.toString().localeCompare(valueA.toString())
            }
        })

        orderBy = 'asc'
    }
}


export const options = ref({
    isCaseSensitive: false,
    includeScore: false,
    findAllMatches: false,
    threshold: 0.2,
    keys: []
})

export const searchData = (e) => 
{
    const query = e.target.value
    let _query = query.toLowerCase().replace(/\s+/g, '')

    if(!_query){
        data.value = cloneData.value
        return
    }
    const fuse = new Fuse(cloneData.value, options.value);
    const result = fuse.search(_query)
    data.value = result.map(item => item.item)
}