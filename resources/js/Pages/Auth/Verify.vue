<template>
    <div
        class="min-h-screen bg-cover bg-no-repeat bg-fixed bg-bottom bgAnimate relative"
        :style="`background-image: url(${loginBg})`"
    >
        <span class="absolute top-0 left-0 w-full h-full bg-gradient-to-r from-black/70 to-black/30"></span>
        <div class="xsm:px-6">
            <div class="h-screen bg-cover bg-center">
                <div class="xsm:px-6">
                    <div
                        class="min-h-screen flex items-center justify-center relative z-10 pt-10 px-6"
                    >
                        <div
                            class="max-w-[450px] w-full min-h-20 p-8 py-12 rounded-lg clear-left bg-white bg-opacity-[0.08] backdrop-blur-md shadow-lg border-2 border-white/20 text-white flex flex-col gap-4"
                        >
                            <form
                                class="grid gap-3"
                                :action="route('verify')"
                                method="post"
                            >
                                <input type="hidden" name="_token" :value="csrf" />
                                <h1
                                    class="font-semibold text-center md:text-3xl text-xl text-white text-opacity-[0.8]"
                                >
                                    Verification
                                </h1>
                                <h1 class="font-semibold text-center mb-2 text-white text-opacity-[0.8]">
                                    Thanks for signing up! Before getting started, please enter the OTP that we've sent to your phone number.
                                </h1>
                                <label class="grid gap-1">
                                    <span class="text-white text-opacity-[0.8]">OTP</span>
                                    <Input.Primary
                                        type="text"
                                        v-model="form.otp"
                                        name="otp"
                                        placeholder="Enter 4 digit OTP"
                                        autocomplete="off"
                                        input-class="bg-white/10 backdrop-blur border-white/30 focus:border-gray-200 placeholder:text-slate-400 text-slate-100"
                                        :error="form.errors.otp"
                                    />
                                </label>
                                <div class="flex justify-end">
                                    <Button.Primary
                                        :disabled="form.processing"
                                        class="disabled:opacity-70 disabled:pointer-events-none text-white text-opacity-[0.8]"
                                    >
                                        <LoaderComponent
                                            :loading="form.processing"
                                        />
                                        Continue
                                    </Button.Primary>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import loginBg from "@/assets/loginbg.jpeg";
import care from "@/assets/care.webp";
import loginCover from "@/assets/loginCover.jpg";
import Healthcare from "@/assets/Healthcare.jpg";

import LoaderComponent from "@/plugins/ui/LoaderComponent.vue";
import { useForm } from "@inertiajs/vue3";
import { Button } from "@/plugins/ui";
import { Input } from "@/plugins/form";

const props = defineProps({
    csrf: String
})

const form = useForm({
    otp: '',
});

</script>
