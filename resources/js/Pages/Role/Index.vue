<template>
    <Auth title="Roles">
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
                    class="py-2"
                >
                    <Icon name="PhPlus" size="20" weight="bold" />
                </Button.Info>
            </template>
            <template #td="{ item, key }">
                <div v-if="key == 'document' && get(item.document, 'path')">
                    <a
                        class="text-green-600"
                        :href="`${$page.props.asset}${item.document?.path}`"
                        download
                        >Download</a
                    >
                </div>
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
                    <Button.Danger
                        @click="handleDelete(item)"
                        v-if="item.deletable"
                        class="!px-2"
                    >
                        <Icon name="PhX" size="20" weight="bold" />
                    </Button.Danger>
                </div>
            </template>
        </Table>

        <Modal v-model="formModal">
            <form
                @submit.prevent="handleSave"
                class="bg-white py-8 px-5 w-[300px] md:min-w-[500px] space-y-4"
            >
                <h2 class="mb-3 font-semibold text-black">
                    {{ form.id ? "Update" : "Create" }} Roles
                </h2>
                {{ form.permissions }}
                <Input.Primary
                    v-model="form.title"
                    :error="form.errors.title"
                    label="Title"
                    required
                />
                <div class="grid grid-cols-1 gap-5 lg:grid-cols-2">
                    <div
                        v-for="(item, key) in modules"
                        :key="key"
                        class="p-3 border rounded-md border-slate-400"
                    >
                        Module:
                        <span
                            @click="makeSelect(item, key)"
                            class="font-bold text-blue-600 select-none"
                            >{{ key }}</span
                        >
                        <div class="pl-5">
                            <label
                                v-for="(permission, ind) in item"
                                :key="ind"
                                class="block select-none border px-2 py-0.5 rounded border-slate-300 mb-1 font-semibold"
                            >
                                <input
                                    type="checkbox"
                                    v-model="form.permissions"
                                    name="permissions[]"
                                    :value="`${key}.${permission}`"
                                />
                                {{ permission }}
                            </label>
                        </div>
                    </div>
                    <div class="p-3 border rounded-md border-slate-400">
                        Module: <span class="font-bold text-blue-600">App</span>
                        <div class="pl-5">
                            <label
                                v-for="(item, key) in app"
                                :key="key"
                                class="block select-none border px-2 py-0.5 rounded border-slate-300 mb-1 font-semibold"
                            >
                                <input
                                    type="checkbox"
                                    v-model="form.permissions"
                                    name="permissions[]"
                                    :value="item"
                                />
                                {{ item }}
                            </label>
                        </div>
                    </div>
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
        </Modal>
    </Auth>
</template>

<script setup>
import Auth from "@/Layouts/Auth.vue";
import { Input } from "@/plugins/form";
import { Table, Button, Modal, Icon } from "@/plugins/ui";
import { useForm } from "@inertiajs/vue3";
import { ref } from "vue";
import { isEmpty, each } from "lodash";

const props = defineProps({
    data: {
        type: Array,
        default: [],
    },
    modules: {
        type: Array,
        default: [],
    },
    app: {
        type: Array,
        default: [],
    },
});

const formModal = ref(false);

const form = useForm({
    id: null,
    slug: "",
    permissions: [],
    title: "",
});

const handleEdit = (item) => {
    form.id = item.id;
    form.title = item.title;
    form.permissions = item.permissions ? item.permissions : [];
    formModal.value = true;
};

const handleSave = () => {
    form.post(route("app.roles.save"), {
        onSuccess(e) {
            if (isEmpty(e.props.errors)) {
                form.reset();
                formModal.value = false;
            }
        },
    });
};

const makeSelect = (item, key) => {
    // const shouldDeselect = true;
    // each(item, (entry) => {
    //     if (form.permissions.indexOf(`${key}.${entry}`) == -1) {
    //         form.permissions.push(`${key}.${entry}`);
    //         shouldDeselect = false;
    //     }
    // });
};

const handleDelete = (item) => {};

const tableConfig = [
    {
        title: "Sl",
        cb({ index }) {
            return index + 1;
        },
    },
    {
        title: "Title",
        key: "title",
        sortable: true,
    },
    {
        title: "Slug",
        key: "slug",
        sortable: true,
    },
];

const searchKeys = [
    "project.project_name",
    "location.location_name",
    "location.location_id",
    "location.lat",
    "location.lon",
];

const theadClass = "!bg-primary !text-white";
const trClass = "hover:bg-gray-100";
</script>
