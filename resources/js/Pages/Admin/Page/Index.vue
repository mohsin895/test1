<template>
    <Auth title="Pages">
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
                    {{ form.id ? "Update" : "Create" }} Pages
                </h2>
                <div class="grid md:grid-cols-2 gap-2">
                    <Input.Primary
                        type="text"
                        label="Title"
                        v-model="form.title"
                        :error="form.errors.title"
                    />
                    <Input.Primary
                        type="text"
                        label="Description"
                        v-model="form.description"
                        :error="form.errors.description"
                    />
                    <Input.Primary
                        type="text"
                        label="Type"
                        v-model="form.type"
                        :error="form.errors.type"
                    />
                    <Input.Primary
                        type="text"
                        label="Meta Title"
                        v-model="form.meta_title"
                        :error="form.errors.meta_title"
                    />
                    <Input.Primary
                        type="text"
                        label="Type"
                        v-model="form.type"
                        :error="form.errors.type"
                    />
                    <Input.Primary
                        type="file"
                        label="Meta Image"
                        v-model="form.meta_image"
                        :error="form.errors.meta_image"
                    />
                    <div class="col-span-2">
                        <Textarea.Editor
                            type="text"
                            label="Description"
                            v-model="form.description"
                            :error="form.errors.description"
                        />
                    </div>
                    <Input.Primary
                        type="text"
                        label="Meta Title"
                        v-model="form.meta_title"
                        :error="form.errors.meta_title"
                    />
                    <Input.Primary
                        type="text"
                        label="Meta Description"
                        v-model="form.meta_description"
                        :error="form.errors.meta_description"
                    />
                    <Radio.Group
                        v-model="form.status"
                        :error="form.errors.status"
                        label="Status"
                    >
                        <Radio.Input label="Active" :value="1" />
                        <Radio.Input label="Disabled" :value="0" />
                    </Radio.Group>
                    <div class="mt-7 flex items-center justify-end">
                        <div
                            class="mt-7 col-span-2 flex items-center justify-end"
                        >
                            <Button.Primary
                                @click="() => handleSave()"
                                :loading="form.processing"
                            >
                                {{ form.id ? "Update" : "Save" }}
                            </Button.Primary>
                        </div>
                    </div>
                </div>
            </form>
        </Modal>
    </Auth>
</template>

<script setup>
import Auth from "@/Layouts/Auth.vue";
import { Table, Button, Modal, Icon } from "@/plugins/ui";
import { Input, Radio, Textarea, Select } from "@/plugins/form";
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
    title: "",
    description: "",
    status: true,
    type: "",
    meta_title: "",
    meta_description: "",
    meta_image: null,
});

const handleEdit = (item) => {
    form.id = item.id;
    form.title = item.title;
    form.description = item.description;
    form.status = item.status;
    form.type = item.type;
    form.meta_title = item.meta_title;
    form.meta_description = item.meta_description;
    formModal.value = true;
};

const handleDelete = (id) => {
    Helper.confirm("Are you sure to delete?", () => {
        router.post(route("app.pages.delete", id));
    });
};

const handleSave = () => {
    form.post(route("app.pages.save"), {
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
        title: "Title",
        key: "title",
        sortable: true,
    },
    {
        title: "Description",
        key: "description",
        sortable: true,
    },
    {
        title: "Type",
        key: "type",
        sortable: true,
    },
    {
        title: "Status",
        key: "status",
        sortable: true,
    },
];

const searchKeys = ["name", "map_location", "city", "division", "address"];

const theadClass = "!bg-primary !text-white";
const trClass = "hover:bg-gray-100";
</script>
