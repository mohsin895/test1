<template>
    <DoctorLayout title="Compounder Lists">
        <div class="pb-5 flex justify-between">
            <span
                >Total Compounder:
                <span class="font-bold">{{ compounders.length }}</span></span
            >
            <Button.Primary @click="handleOpen"> Create </Button.Primary>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-5 gap-4">
            <div
                v-for="item in compounders"
                :key="item.id"
                class="border border-slate-200 shadow pt-5 pb-4 rounded relative"
            >
                <div class="grid place-content-center pt-4 pb-2">
                    <div
                        class="w-16 h-16 border border-slate-300 shadow rounded-full overflow-hidden"
                    >
                        <img
                            src="/frontend/avatar.jpeg"
                            class="block w-full h-full object-cover object-center"
                            alt=""
                        />
                    </div>
                </div>
                <div class="text-center">
                    {{ item.name }}
                </div>
                <div class="text-center">
                    {{ item.phone }}
                </div>
                <div class="text-center mt-1">
                    <span class="inline-flex items-center py-0.5 px-2">
                        <span
                            class="h-2 w-2 rounded-full mr-1"
                            :class="{
                                'bg-green-500': +item.status == 1,
                                'bg-red-500': +item.status == 0,
                            }"
                        ></span>
                        <span
                            :class="{
                                'text-green-500': +item.status === 1,
                                'text-red-500': +item.status === 0,
                            }"
                        >
                            {{ item.status === 1 ? "Active" : "Deactivated" }}
                        </span>
                    </span>
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

                <span
                    class="absolute top-0 right-0 mt-2 mr-2 rounded-full px-2 py-2 border shadow-md cursor-pointer"
                    @click="openModal(item)"
                >
                    <Icon name="PhEye" size="16" />
                </span>
                <ChamberSlotModal
                    :selectedChamber="item"
                    :groupedSlotsByChamber="groupedSlotsByChamber"
                />
            </div>
        </div>
        <Modal v-model="formModal">
            <form
                @submit.prevent="handleSave"
                class="bg-white py-8 px-5 w-[400px] md:min-w-[600px]"
            >
                <h2 class="font-semibold text-black mb-3">
                    {{ form.id ? "Update" : "Create" }} Compounder
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
                    <div class="col-span-2">
                        <h2 class="mb-1">
                            <div>Chambers</div>
                        </h2>
                        <div
                            v-for="(doctor_chamber, index) in chambers"
                            :key="index"
                        >
                            <div class="flex flex-wrap items-center">
                                <label class="mr-4">
                                    <input
                                        type="radio"
                                        v-model="form.chamber_id"
                                        :value="doctor_chamber.chamber_id"
                                        class="mr-2"
                                        @change="selectChamber(doctor_chamber)"
                                    />
                                    {{ doctor_chamber.chamber.name }}
                                </label>
                            </div>
                        </div>
                        <div v-if="selectedChamber" class="mt-2">
                            <h2 class="mb-1">
                                <div>Select Slots</div>
                            </h2>
                            <div class="flex flex-wrap space-x-2">
                                <div
                                    v-for="(
                                        slot, index
                                    ) in selectedChamber.slots"
                                    :key="index"
                                >
                                    <label class="flex items-center">
                                        <input
                                            type="checkbox"
                                            name="slot_ids[]"
                                            :value="slot.id"
                                            v-model="form.slot_ids"
                                        />
                                        <span class="ml-2">
                                            {{ convert24to12(slot.from_time) }}
                                            -
                                            {{ convert24to12(slot.to_time) }}
                                        </span>
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
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
    </DoctorLayout>
</template>

<script setup>
import DoctorLayout from "@/Layouts/DoctorLayout.vue";
import { router, useForm } from "@inertiajs/vue3";
import { isEmpty, get } from "lodash";
import { ref, toRaw } from "vue";
import { Table, Button, Modal, Icon } from "@/plugins/ui";
import { Input, Radio } from "@/plugins/form";
import Helper, { convert24to12 } from "@/helper";
import DoctorLogo from "@/assets/doctor.png";
import { useCompounder } from "./useCompounder.js";
import ChamberSlotModal from "./ChamberSlotModal.vue";

const { formModal, chamberSlotModal } = useCompounder();

const props = defineProps({
    chambers: {
        type: Array,
        default: [],
    },
    compounders: {
        type: Array,
        default: [],
    },
    groupedSlotsByChamber: {
        type: Object,
        default: {},
    },
});

const selectedChamber = ref(null);

const findChamber = (id) => {
    return props.chambers.find((item) => item.chamber_id == id);
};

const selectChamber = (doctorChamber) => {
    selectedChamber.value = doctorChamber;
};

const getSlots = (compounder_details) => {
    let detailsArr = toRaw(compounder_details) || [];
    return detailsArr.map((item) => item.slot_id);
};

const form = useForm({
    id: null,
    name: "",
    email: "",
    phone: "",
    media: null,
    password: "",
    chamber_id: null,
    slot_ids: [],
    status: 1,
});

const handleEdit = (item) => {
    form.id = item.id;
    form.name = item.name;
    form.email = item.email;
    form.phone = item.phone;
    form.chamber_id = get(item, "compounder_details[0].chamber_id");
    selectedChamber.value = findChamber(
        get(item, "compounder_details[0].chamber_id")
    );
    form.status = get(item, "compounder_details[0].status");
    form.slot_ids = getSlots(item.compounder_details);
    formModal.value = true;
};

const handleSave = () => {
    form.post(route("doctor.compounder.save"), {
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
        router.post(route("doctor.compounder.delete", id));
    });
};
const handleOpen = () => {
    formModal.value = true;
    form.reset();
    selectedChamber.value = null;
};

const openModal = () => {
    chamberSlotModal.value = true;
};
</script>
