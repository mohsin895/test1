<template>
    <Auth title="Doctors">
        <Table
            :config="tableConfig"
            :data="data"
            :trClass="trClass"
            :searchKeys="searchKeys"
            :theadClass="theadClass"
        >
            <template #tableHeader>
                <Button.Info
                    @click="
                        () => {
                            formModal = true;
                        }
                    "
                >
                    <Icon name="PhPlus" size="20" weight="bold" />
                </Button.Info>
            </template>
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
                    <Button.Primary :to="route('app.doctors.configurations', item.id)" class="!px-2">
                        <Icon name="PhGear" size="20" weight="bold" />
                    </Button.Primary>
                    <Button.Info :to="route('app.doctors.calender', item.id)" class="!px-2">
                        <Icon name="PhCalendar" size="20" weight="bold" />
                    </Button.Info>
                    <Button.Danger @click="handleDelete(item)" class="!px-2">
                        <Icon name="PhX" size="20" weight="bold" />
                    </Button.Danger>
                </div>
            </template>
        </Table>

        <Modal v-model="formModal" @close="selectedItem = null">
            <Create v-if="formModal" :selectedItem="selectedItem" />
        </Modal>
    </Auth>
</template>

<script setup>
import Auth from "@/Layouts/Auth.vue";
import { Table, Button, Modal, Icon } from "@/plugins/ui";
import { ref } from "vue";
import { router } from "@inertiajs/vue3";
import Helper from "@/helper";
import Create from "./fragments/Create.vue";

const props = defineProps({
    data: {
        type: Array,
        default: [],
    },
    roles: {
        type: Array,
        default: [],
    },
});

const formModal = ref(false);
const selectedItem = ref(null);

const handleEdit = (item) => {
    selectedItem.value = item;
    formModal.value = true;
};

const handleDelete = (id) => {
    Helper.confirm("Are you sure to delete?", () => {
        router.post(route("app.doctors.delete", id));
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
        title: "Email",
        key: "email",
        sortable: true,
    },
    {
        title: "Phone",
        key: "phone",
        sortable: true,
    },
    {
        title: "Status",
        key: "status",
        sortable: true,
    },
];

const searchKeys = ["name"];

const theadClass = "!bg-primary !text-white";
const trClass = "hover:bg-gray-100";
</script>
