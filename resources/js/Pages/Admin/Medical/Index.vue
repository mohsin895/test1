<template>
    <Auth title="Medicals">
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
                            form.reset();
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
                <h2 class="font-semibold text-black mb-3">
                    {{ form.id ? "Update" : "Create" }} Medical
                </h2>
                <Input.Primary
                    v-model="form.name"
                    :error="form.errors.name"
                    label="Name"
                    required
                />
                <Radio.Group
                    v-model="form.status"
                    :error="form.errors.status"
                    label="Status"
                >
                    <Radio.Input label="Active" :value="1" />
                    <Radio.Input label="Disabled" :value="0" />
                </Radio.Group>
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
        </Modal>
    </Auth>
</template>

<script setup>
import Auth from "@/Layouts/Auth.vue";
import { Table, Button, Modal, Icon } from "@/plugins/ui";
import { Input, Radio } from "@/plugins/form";
import { ref } from "vue";
import { useForm, router } from "@inertiajs/vue3";
import { isEmpty } from "lodash";
import Helper from "@/helper";

document.title = "Statutory project clearance";

const props = defineProps({
    data: {
        type: Array,
        default: [],
    },
});

const formModal = ref(false);
const form = useForm({
    id: null,
    name: null,
    status: 1,
});

const handleEdit = (item) => {
    form.id = item.id;
    form.name = item.name;
    form.status = item.status;
    formModal.value = true;
};

const handleDelete = (id) => {
    Helper.confirm("Are you sure to delete?", () => {
        router.post(route("app.medicals.delete", id));
    });
};

const handleSave = () => {
    form.post(route("app.medicals.save"), {
        onSuccess(e) {
            if (isEmpty(e.props.errors)) {
                form.reset();
                formModal.value = false;
            }
        },
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
        title: "Status",
        key: "status",
        sortable: true,
    },
];

const searchKeys = ["name"];

const theadClass = "!bg-primary !text-white";
const trClass = "hover:bg-gray-100";
</script>
