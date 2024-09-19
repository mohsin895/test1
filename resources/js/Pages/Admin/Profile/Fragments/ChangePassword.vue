<template>
    <div class="max-w-md space-y-2">
        <h1 class="text-xl">Change Password</h1>
        <Input.Primary
            type="password"
            id="current_password"
            label="Current Password"
            v-model="form.current_password"
            :error="form.errors.current_password"
        />
        <Input.Primary
            type="password"
            id="password"
            label="New Password"
            v-model="form.password"
            :error="form.errors.password"
        />
        <Input.Primary
            type="password"
            id="password_confirmation"
            label="Confirm Password"
            v-model="form.password_confirmation"
            :error="form.errors.password_confirmation"
        />
        <Button.Primary
            @click="() => handleChangePassword()"
            :loading="form.processing"
        >
            Update
        </Button.Primary>
    </div>
</template>

<script setup>
import { Button } from "@/plugins/ui";
import { Input } from "@/plugins/form";
import { useForm, router } from "@inertiajs/vue3";

const form = useForm({
    id: null,
    password: "",
    password_confirmation: "",
    current_password: "",
});

const handleChangePassword = () => {
    form.post(route("app.profile.changePassword"), {
        onSuccess(e) {
            if (!Object.keys(e.props.errors).length) {
                form.reset();
            }
        },
    });
};
</script>
