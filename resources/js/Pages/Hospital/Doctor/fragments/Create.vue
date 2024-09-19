<template>
    <form
        @submit.prevent="handleSave"
        class="bg-white py-8 px-5 w-[300px] md:min-w-[800px]"
    >
        <h2 class="font-semibold text-black mb-3">
            {{ form.id ? "Update" : "Create" }} Doctors Information
        </h2>
        <div class="grid md:grid-cols-2 gap-2">
            <Input.Primary
                type="text"
                label="Name"
                v-model="form.name"
                :error="form.errors.name"
            />
            <Input.Primary
                type="text"
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
                type="text"
                label="Experience"
                v-model="form.experience"
                :error="form.errors.experience"
            />
            <Input.Primary
                type="number"
                label="Age"
                v-model="form.age"
                :error="form.errors.age"
            />
            <Input.Primary
                type="text"
                label="BMDC"
                v-model="form.bmdc"
                :error="form.errors.bmdc"
            />
            <Input.Primary
                type="file"
                label="Media"
                @input="(e) => (form.media = e.target.files[0])"
                :error="form.errors.media"
            />
            <Input.Primary
                type="password"
                label="Password"
                v-model="form.password"
                :error="form.errors.password"
            />
            <Radio.Group
                v-model="form.publish_bmdc"
                :error="form.errors.publish_bmdc"
                label="Publish BMDC"
            >
                <Radio.Input label="Yes" :value="1" />
                <Radio.Input label="No" :value="0" />
            </Radio.Group>
            <Radio.Group
                v-model="form.status"
                :error="form.errors.status"
                label="Status"
            >
                <Radio.Input label="Active" :value="1" />
                <Radio.Input label="Disabled" :value="0" />
            </Radio.Group>
        </div>

        <div class="flex justify-end">
            <Button.Primary
                type="submit"
                :loading="form.processing"
                class="mt-4"
            >
                {{ form.id ? "Update" : "Save" }}
            </Button.Primary>
        </div>
    </form>
</template>

<script setup>
import { Button, Modal, Icon } from "@/plugins/ui";
import { Input, Radio } from "@/plugins/form";
import { ref, onMounted } from "vue";
import { useForm } from "@inertiajs/vue3";
import { isEmpty, get } from "lodash";
import Helper from "@/helper";

const props = defineProps({
    selectedItem: {
        type: Object,
        default: {},
    },
});

const formModal = ref(false);

const form = useForm({
    id: null,
    name: "",
    email: "",
    phone: "",
    experience: "",
    age: null,
    bmdc: "",
    publish_bmdc: 1,
    media: null,
    password: "",
    status: 1,
});

const handleSave = () => {
    form.post(route("hospitals.doctors.save"), {
        onSuccess(e) {
            if (isEmpty(e.props.errors)) {
                form.reset();
                formModal.value = false;
            }
        },
    });
};

onMounted(() => {
    if (!isEmpty(props.selectedItem)) {
        form.id = get(props.selectedItem, "id");
        form.name = get(props.selectedItem, "name");
        form.email = get(props.selectedItem, "email");
        form.phone = get(props.selectedItem, "phone");
        form.age = get(props.selectedItem, "age");
        form.bmdc = get(props.selectedItem, "bmdc");
        form.publish_bmdc = get(props.selectedItem, "publish_bmdc");
        form.experience = get(props.selectedItem, "experience");
        form.media = get(props.selectedItem, "media");
        form.status = get(props.selectedItem, "status");
    }
});
</script>
