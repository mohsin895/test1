<template>
    <Auth title="All Contacts">
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
                <h2 class="font-semibold text-black mb-3">View Contact</h2>
                <div class="grid md:grid-cols-2 gap-2">
                    <Input.Primary
                        type="text"
                        label="Name"
                        v-model="form.name"
                        :error="form.errors.name"
                    />
                    <Input.Primary
                        type="text"
                        label="Phone"
                        v-model="form.phone"
                        :error="form.errors.phone"
                    />
                    <Input.Primary
                        type="text"
                        label="Email"
                        v-model="form.email"
                        :error="form.errors.email"
                    />
                    <Input.Primary
                        type="text"
                        label="Reason"
                        v-model="form.reason"
                        :error="form.errors.reason"
                    />
                    <Textarea.Primary
                        label="Message"
                        v-model="form.message"
                        :error="form.errors.message"
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
    name: "",
    phone: "",
    email: "",
    message: "",
    reason: "",
});

const handleEdit = (item) => {
    form.id = item.id;
    form.name = item.name;
    form.phone = item.phone;
    form.email = item.email;
    form.message = item.message;
    form.reason = item.reason;
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
        title: "Name",
        key: "name",
        sortable: true,
    },
    {
        title: "Phone",
        key: "phone",
        sortable: true,
    },
    {
        title: "Email",
        key: "email",
        sortable: true,
    },
    {
        title: "Message",
        key: "message",
        sortable: true,
    },
    {
        title: "Reason",
        key: "reason",
        sortable: true,
    },
];

const searchKeys = ["name", "phone", "email", "message", "reason"];

const theadClass = "!bg-primary !text-white";
const trClass = "hover:bg-gray-100";
</script>
