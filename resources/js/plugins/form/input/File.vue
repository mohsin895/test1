<template>
    <div class="w-full relative cursor-pointer">
        <Loader 
            :loading="isLoading" 
            class="absolute top-1/2 left-1/2 w-6 h-6 -ml-3 -mt-3"
        />
        <h3
            v-if="label"
            class="mb-1 block text-black/80"
            :class="[{ 'pointer-events-none opacity-40': isLoading }, labelClass]"
        >
            {{ label }} <span v-if="required" class="text-red-500">*</span>
        </h3>
        <label
            class="w-full h-[225px] bg-white relative border border-dashed rounded flex items-center justify-center"
            :class="[
                isLoading && 'pointer-events-none opacity-40',
                error ? 'border-red-500' : 'border-[#c9c9c9]'
            ]"
        >
            <img
                v-if="!isEmpty(imageUrl || image)"
                :src="imageUrl || image"
                class="absolute top-0 left-0 w-full h-full object-cover object-center opacity-30"
            />
            <input
                type="file"
                :accept="accept"
                class="absolute top-0 left-0 w-full h-full opacity-0 cursor-pointer"
                @input="handleFile"
            />
            <div class="grid gap-2 text-center">
                <Icon name="PhCamera" size="60" class="mx-auto block opacity-50" />

                <h3
                    class="text-lg font-medium text-[#616161]"
                    v-html="title"
                ></h3>
                <h3
                    class="text-lg font-medium text-[#8d8d8d]"
                    v-html="subtitle"
                ></h3>
            </div>
        </label>
        <span v-if="error" class="text-[10px] text-red-500 absolute top-full right-0">
            {{ error }}
        </span>
    </div>
</template>

<script setup>
    import { ref } from 'vue'
    import { isEmpty } from 'lodash'
    import { Loader, Icon } from '@/plugins/ui'

    defineProps({
        label: String,
        imgPreview: String,
        accept: {
            type: String,
            default: '',
        },
        title: {
            type: String,
            default: 'Drag your image here or <span class="text-primary cursor-pointer">Browse</span>',
        },
        subtitle: {
            type: String,
            default: 'Supports JPG, JPEG, PNG',
        },
        labelClass: {
            type: String,
            default: 'fs-13 font-medium text-[#616161] mb-1',
        },
        image: String,
        error: String
    })

    const isLoading = ref(false)
    const emit = defineEmits(['input'])
    const imageUrl = ref('')
    const handleFile = e => {
        isLoading.value = true
        const file = e.target.files[0]
        const reader = new FileReader()
        reader.readAsDataURL(file)
        reader.onload = () => {
            if (file.type.includes('image')) {
                imageUrl.value = reader.result
                isLoading.value = false
                return
            }
            if (file.type.includes('video')) {
                createVideo(reader.result)
                return
            }
        }
        emit('input', e)
    }

    const createVideo = url => {
        const video = document.createElement('video')
        video.src = url
        video.muted = true
        video.autoplay = true

        const canvas = document.createElement('canvas')
        const ctx = canvas.getContext('2d')

        video.onloadeddata = () => {
            const { videoWidth, videoHeight } = video

            canvas.width = videoWidth
            canvas.height = videoHeight

            ctx.drawImage(video, 0, 0, canvas.width, canvas.height)

            imageUrl.value = canvas.toDataURL('image/webp', 0.7)
            isLoading.value = false
        }
    }
</script>