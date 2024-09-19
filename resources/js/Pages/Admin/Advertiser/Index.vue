<template>
    <Auth title="Advertisers">
        <div class="pb-5 flex justify-between">
            <span
                >Total Advertisers:
                <span class="font-bold">{{ advertisers.length }}</span></span
            >
            <Button.Primary
                @click="
                    () => {
                        formModal = true;
                        form.reset();
                    }
                "
                >Create
            </Button.Primary>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-3 xl:grid-cols-4 space-x-3">
            <div
                v-for="item in advertisers"
                :key="item.id"
                class="border border-slate-200 shadow-md pt-3 pb-3 rounded relative"
            >
                <div class="grid place-content-center pt-4 pb-2">
                    <div
                        class="w-16 h-16 border border-slate-300 shadow rounded-full overflow-hidden"
                    >
                        <Img
                            :src="get(item, 'media.path')"
                            class="block w-full h-full object-cover object-center"
                            alt=""
                            fallBack="/frontend/avatar.jpeg"
                        />
                    </div>
                </div>
                <div class="text-center">
                    {{ item.name }}
                </div>
                <div class="text-center">
                    {{ item.phone }}
                </div>
                <div class="text-center">
                    {{ item.email }}
                </div>

                <div class="flex justify-center mt-3 gap-3">
                    <Button.Primary @click="handleEdit(item)">
                        <Icon
                            name="PhPencilSimpleLine"
                            size="20"
                            weight="bold"
                        />
                    </Button.Primary>
                    <Button.Danger @click="handleDelete(item.id)">
                        <Icon name="PhX" size="20" weight="bold" />
                    </Button.Danger>
                </div>
            </div>
        </div>

        <Modal v-model="formModal">
            <form
                @submit.prevent="handleSave"
                class="bg-white py-8 px-5 w-[400px] md:min-w-[600px]"
            >
                <h2 class="font-semibold text-black mb-3">
                    {{ form.id ? "Update" : "Create" }} Advertiser
                </h2>
                <div class="grid md:grid-cols-2 gap-2">
                    <Input.Primary
                        v-model="form.name"
                        :error="form.errors.name"
                        label="Name"
                        required
                    />
                    <Input.Primary
                        v-model="form.email"
                        :error="form.errors.email"
                        label="Email"
                        required
                    />
                    <Input.Primary
                        v-model="form.phone"
                        :error="form.errors.phone"
                        label="Phone"
                        required
                    />
                    <Input.Primary
                        v-model="form.password"
                        :error="form.errors.password"
                        label="Password"
                        required
                    />
                    <Input.File
                        type="file"
                        label="Image"
                        @input="(e) => (form.media = e.target.files[0])"
                        :error="form.errors.media"
                    />
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
        </Modal>
    </Auth>
</template>

<script setup>
import Auth from "@/Layouts/Auth.vue";
import { Table, Button, Modal, Icon, Img } from "@/plugins/ui";
import { Input } from "@/plugins/form";
import { ref } from "vue";
import { useForm, router } from "@inertiajs/vue3";
import { isEmpty, get } from "lodash";
import Helper from "@/helper";

const props = defineProps({
    advertisers: {
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
    password: "",
    media: null,
    status: 1,
});

const handleEdit = (item) => {
    form.id = item.id;
    form.name = item.name;
    form.phone = item.phone;
    form.email = item.email;
    formModal.value = true;
};
const handleSave = () => {
    form.post(route("app.advertisers.save"), {
        onSuccess(e) {
            if (isEmpty(e.props.errors)) {
                form.reset();
                formModal.value = false;
            }
        },
    });
};
const handleDelete = (id) => {
    Helper.confirm("Are you sure to delete?", () => {
        router.post(route("app.advertiser.delete", id));
    });
};

</script>
