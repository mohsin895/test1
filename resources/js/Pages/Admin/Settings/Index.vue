<template>
    <Auth title="Settings">
        <div class="grid md:grid-cols-2 gap-2">
            <Input.Primary
                type="text"
                v-model="form.company_name"
                label="Company Name"
                :error="form.errors.company_name"
            />
            <Input.Primary
                type="text"
                v-model="form.email"
                label="Company Email"
                :error="form.errors.email"
            />
            <Input.Primary
                type="text"
                v-model="form.phone"
                label="Company Phone"
                :error="form.errors.phone"
            />
            <Input.Primary
                type="text"
                v-model="form.address"
                label="Company Address"
                :error="form.errors.address"
            />
            <Input.Primary
                type="file"
                v-model="form.logo_light"
                label="Light logo"
                :error="form.errors.logo_light"
            />
            <Input.Primary
                type="file"
                v-model="form.logo_dark"
                label="Light Dark"
                :error="form.errors.logo_dark"
            />
            <Input.Primary
                type="file"
                v-model="form.fave_icon"
                label="Fave Icon"
                :error="form.errors.fave_icon"
            />
            <Input.Primary
                type="text"
                v-model="form.facebook"
                label="Facebook"
                :error="form.errors.facebook"
            />
            <Input.Primary
                type="text"
                v-model="form.twitter"
                label="Twitter"
                :error="form.errors.twitter"
            />
            <Input.Primary
                type="text"
                v-model="form.instagram"
                label="Instagram"
                :error="form.errors.instagram"
            />
            <Input.Primary
                type="text"
                v-model="form.youtube"
                label="Youtube"
                :error="form.errors.youtube"
            />
        </div>
        <div class="mt-7 flex items-center justify-end">
            <Button.Primary
                @click="() => handleSave()"
                :loading="form.processing"
            >
                Save
            </Button.Primary>
        </div>
        <div class="grid grid-cols-3 mt-10 bg-slate-300 p-10 gap-5">
            <div v-if="get(props.data, 'logo_dark.path')">
                <div>Logo dark</div>
                <img
                    :src="get(props.data, 'logo_dark.path')"
                    alt=""
                    class="h-[50px]"
                />
            </div>
            <div v-if="get(props.data, 'logo_light.path')">
                <div>Logo light</div>
                <img
                    :src="get(props.data, 'logo_light.path')"
                    alt=""
                    class="h-[50px]"
                />
            </div>
            <div v-if="get(props.data, 'fave_icon.path')">
                <div>Fave icon</div>
                <img
                    :src="get(props.data, 'fave_icon.path')"
                    alt=""
                    class="h-[50px]"
                />
            </div>
        </div>
    </Auth>
</template>

<script setup>
import Auth from "@/Layouts/Auth.vue";
import { Button } from "@/plugins/ui";
import { Input } from "@/plugins/form";
import { onMounted } from "vue";
import { useForm } from "@inertiajs/vue3";
import { get } from "lodash";

const props = defineProps({
    data: {
        type: Array,
        default: [],
    },
});

const form = useForm({
    id: null,
    company_name: "",
    phone: "",
    email: "",
    address: "",
    facebook: "",
    twitter: "",
    instagram: "",
    youtube: "",
    fave_icon: null,
    logo_light: null,
    logo_dark: null,
});

onMounted(() => {

    const {
        id,
        company_name,
        phone,
        email,
        address,
        facebook,
        twitter,
        instagram,
        youtube,
        fave_icon,
        logo_light,
        logo_dark,
    } = props.data;
    form.id = id;
    form.company_name = company_name;
    form.email = email;
    form.phone = phone;
    form.address = address;
    form.facebook = facebook;
    form.twitter = twitter;
    form.instagram = instagram;
    form.youtube = youtube;
});

const handleSave = () => {
    form.post(route("app.settings.save"), {
        onSuccess(e) {},
    });
};
</script>
