<template>
    <Auth title="Appointments">
        <Table
            :config="tableConfig"
            :data="data"
            :trClass="trClass"
            :searchKeys="searchKeys"
            :theadClass="theadClass"
        >
            <template #td="{ item, key }">
                <span :class="key == 'status' ? 'text-red-500' : ''">
                    {{ item[key] }}
                </span>
            </template>
            <template #action="{ item }">
                <div class="flex gap-0.5 justify-center w-full">
                    <Button.Warning @click="handleEdit(item)" class="!px-2">
                        <Icon
                            name="PhPencilSimpleLine"
                            size="20"
                            weight="bold"
                        />
                    </Button.Warning>
                    <Button.Danger @click="handleDelete(item)" class="!px-2">
                        <Icon name="PhX" size="20" weight="bold" />
                    </Button.Danger>
                </div>
            </template>
        </Table>

        <Modal v-model="formModal">
            <form
                @submit.prevent="handleSave"
                class="bg-white py-8 px-5 w-[300px] md:min-w-[500px]"
            >
                <h2 class="font-semibold text-black mb-3">View Appointment</h2>
                <div class="grid md:grid-cols-2 gap-2">
                    <Input.Primary
                        type="text"
                        label="Patient Name"
                        v-model="form.patient_name"
                        :error="form.errors.patient_name"
                    />
                    <Input.Primary
                        type="text"
                        label="Phone"
                        v-model="form.phone"
                        :error="form.errors.phone"
                    />
                    <Input.Primary
                        type="text"
                        label="Age"
                        v-model="form.age"
                        :error="form.errors.age"
                    />
                    <Input.Primary
                        type="text"
                        label="Date"
                        v-model="form.date"
                        :error="form.errors.date"
                    />
                    <Input.Primary
                        type="text"
                        label="Patient Type"
                        v-model="form.patient_type_id"
                        :error="form.errors.patient_type_id"
                    />
                </div>
            </form>
        </Modal>
    </Auth>
</template>

<script setup>
import Auth from "@/Layouts/Auth.vue";
import { Table, Button, Modal, Icon } from "@/plugins/ui";
import { Input, Textarea } from "@/plugins/form";
import { ref } from "vue";
import { useForm, router } from "@inertiajs/vue3";
import { isEmpty } from "lodash";
import Helper from "@/helper";

const props = defineProps({
    data: {
        type: Array,
        default: [],
    },
});

const formModal = ref(false);
const form = useForm({
    id: null,
    patient_name: "",
    phone: "",
    age: "",
    date: null,
    patient_type_id: null,
});

const handleEdit = (item) => {
    form.id = item.id;
    form.patient_name = item.patient_name;
    form.phone = item.phone;
    form.age = item.age;
    form.date = item.date;
    form.patient_type_id = item.patient_type?.name;
    formModal.value = true;
};

const handleDelete = (id) => {
    Helper.confirm("Are you sure to delete?", () => {
        router.post(route("app.contacts.delete", id));
    });
};

const tableConfig = [
    {
        title: "Sl",
        cb({ index }) {
            return index + 1;
        },
    },
    {
        title: "Patient Name",
        key: "patient_name",
        sortable: true,
    },
    {
        title: "Phone",
        key: "phone",
        sortable: true,
    },
    {
        title: "Age",
        key: "age",
        sortable: true,
    },
    {
        title: "Date",
        key: "date",
        sortable: true,
    },
    // {
    //     title: "Patient Type",
    //     key: "patient_type.name",
    //     sortable: true,
    // },
];

const searchKeys = ["patient_name", "phone", "age", "date", "patient_type.name"];

const theadClass = "!bg-primary !text-white";
const trClass = "hover:bg-gray-100";
</script>
