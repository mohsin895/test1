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
                                @submit.prevent="submit"
                            >
                                <h1
                                    class="font-semibold text-center md:text-3xl text-xl text-white text-opacity-[0.8]"
                                >
                                    Register
                                </h1>
                                <h1 class="font-semibold text-center mb-2 text-white text-opacity-[0.8]">
                                    Welcome to SomoyMoto
                                </h1>
                                <label class="grid gap-1">
                                    <span class="text-white text-opacity-[0.8]">Phone</span>
                                    <Input.Primary
                                        type="phone"
                                        v-model="form.phone"
                                        placeholder="Phone"
                                        autocomplete="off"
                                        error-position="left"
                                        error-class="bg-red-500 text-white px-2 rounded-sm"
                                        input-class="bg-white/10 backdrop-blur border-white/30 focus:border-gray-200 placeholder:text-slate-200 text-slate-100"
                                        :error="form.errors.phone"
                                    />
                                </label>
                                <div class="flex justify-center items-center mt-2">
                                    <Button.Primary
                                        :disabled="form.processing"
                                        class="disabled:opacity-70 disabled:pointer-events-none block w-full py-1.5"
                                    >
                                        <LoaderComponent
                                            :loading="form.processing"
                                        />
                                        Continue
                                    </Button.Primary>
                                </div>
                                <div class="mt-3 text-center">
                                    <a :href="route('login')" class="text-white text-opacity-[0.8]">
                                        Already have a doctor or other account? <span class="font-bold">Login</span>
                                    </a>
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
import AuthBg from "@/assets/AuthBg.webp";
import care from "@/assets/care.webp";
import loginCover from "@/assets/loginCover.jpg";
import Healthcare from "@/assets/Healthcare.jpg";

import LoaderComponent from "@/plugins/ui/LoaderComponent.vue";
import { useForm } from "@inertiajs/vue3";
import { Button } from "@/plugins/ui";
import { Input } from "@/plugins/form";

const form = useForm({
    phone: '',
});

const submit = () => {
    form.post(route('register'), {
        onFinish: () => form.reset('password', 'password_confirmation'),
    });
};
</script>


<style>
/* .bgAnimate {
    animation: bgAnimate 3s infinite alternate;
}

@keyframes bgAnimate {
    0% {
        background-position: top;
    }
    100% {
        background-position: center;
    }
} */

</style>