<template>
    <div class="max-w-md mt-8 space-y-2">
        <h1 class="text-xl">Profile Information</h1>
        <Input.Primary
            type="text"
            id="name"
            label="Name"
            v-model="form.name"
            :error="form.errors.name"
        />
        <Input.Primary
            type="email"
            label="Email"
            v-model="form.email"
            :error="form.errors.email"
        />
        <Input.Primary
            type="text"
            label="Phone"
            v-model="form.phone"
            :error="form.errors.phone"
        />
        <Input.Primary
            type="file"
            label="Profile Image"
            @input="(e) => (form.media = e.target.files[0])"
            :error="form.errors.media"
        />
        <Button.Primary
            @click="() => handleProfileInfo()"
            :loading="form.processing"
        >
            Update
        </Button.Primary>
    </div>
</template>

<script setup>
import { Button } from "@/plugins/ui";
import { Input } from "@/plugins/form";
import { useForm } from "@inertiajs/vue3";
import { onMounted } from "vue";

const props = defineProps({
    user: {
        type: Array,
        default: [],
    },
});

const form = useForm({
    id: null,
    name: "",
    email: "",
    phone: "",
    media: null,
});

onMounted(() => {
    const { id, name, email, phone } = props.user;
    form.id = id;
    form.name = name;
    form.email = email;
    form.phone = phone;
});

const handleProfileInfo = () => {
    form.post(route("app.profile.changeProfileInfo"));
};
</script>
