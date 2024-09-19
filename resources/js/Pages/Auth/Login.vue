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
                                @submit.prevent="handleLogin"
                            >
                                <h1
                                    class="font-semibold text-center md:text-3xl text-white text-opacity-[0.8] text-xl"
                                >
                                    Login
                                </h1>
                                <h1 class="font-semibold text-center mb-2 text-white text-opacity-[0.8]">
                                    Welcome to SomoyMoto
                                </h1>
                                <label class="grid gap-1">
                                    <span class="text-white text-opacity-[0.8]">Email</span>
                                    <Input
                                        type="email"
                                        v-model="form.email"
                                        placeholder="Email"
                                        autocomplete="off"
                                        input-class="bg-white bg-opacity-[0.07] backdrop-blur border-white/30 focus:border-gray-200"
                                        :error="form.errors.email"
                                    />
                                </label>
                                <label class="grid gap-1">
                                    <span class="text-white text-opacity-[0.8]">Password</span>
                                    <Input
                                        type="password"
                                        input-class="bg-white bg-opacity-[0.07] backdrop-blur border-white/30 focus:border-gray-200"
                                        v-model="form.password"
                                        placeholder="Password"
                                        autocomplete="off"
                                        :error="form.errors.password"
                                    />
                                </label>
                                <div class="flex justify-end">
                                    <Button.Primary
                                        :disabled="form.processing"
                                        class="disabled:opacity-70 disabled:pointer-events-none"
                                    >
                                        <LoaderComponent
                                            :loading="form.processing"
                                        />
                                        Login
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
import { Input } from "@/library/form";

const form = useForm({
    email: "",
    password: "",
});

const handleLogin = () => {
    form.post(route('login'), {
        onFinish: () => form.reset('password'),
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