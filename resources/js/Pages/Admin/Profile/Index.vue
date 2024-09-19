<template>
    <Auth title="Profile">
        <ChangePassword />
        <ProfileInformation :user="user" />
    </Auth>
</template>

<script setup>
import Auth from "@/Layouts/Auth.vue";
import ChangePassword from "@/Pages/Admin/Profile/Fragments/ChangePassword.vue";
import ProfileInformation from "@/Pages/Admin/Profile/Fragments/ProfileInformation.vue";

const props = defineProps({
    user: {
        type: Array,
        default: [],
    },
});
</script>
