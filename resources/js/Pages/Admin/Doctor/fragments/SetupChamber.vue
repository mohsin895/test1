<template>
    <Modal v-model="chamberPopup">
        <div class="bg-white rounded-md w-[500px] max-w-screen-sm">
            <div
                class="mb-5 p-5 font-bold text-xl border-b pb-2 sticky top-0 bg-white z-10"
            >
                Select chamber
            </div>
            <div class="px-5 mb-1">
                <Input.Primary v-model="chamberFilter" type="search" />
            </div>
            <div class="p-2 max-h-[200px] overflow-y-auto">
                <div
                    v-for="(item, index) in chambers"
                    :key="index"
                    @click="handleSelect(item)"
                    class="py-1 px-3 hover:bg-slate-200 select-none cursor-pointer"
                >
                    {{ `${index + 1}. ${item.name}` }}
                </div>
            </div>
        </div>
    </Modal>

    <div class="mt-5">
        <Table
            :data="get($page.props, 'doctor.doctor_chambers') || []"
            :config="tableConfig"
            :search-keys="searchKeys"
            :tr-class="trClass"
            :thead-class="theadClass"
        >
            <template #tableHeader>
                <Button.Primary @click="chamberPopup = !chamberPopup">
                    + Add Chamber
                </Button.Primary>
            </template>
            <template #action="{ item }">
                <Button.Danger @click="handleSelect(item, 1)"
                    >Remove</Button.Danger
                >
            </template>
        </Table>
    </div>

    <NextPrev @action="(move) => handleSave(move)" />
</template>

<script setup>
import { useForm, usePage } from "@inertiajs/vue3";
import { useConfig } from "../useConfig";
import { Input, Select } from "@/plugins/form";
import { Button, Modal, Table } from "@/plugins/ui";
import NextPrev from "../NextPrev.vue";
import { onMounted, ref, computed } from "vue";
import { get } from "lodash";
import Helper from "@/helper";

const { handleNextPrev } = useConfig();

const form = useForm({
    chamber_id: null,
    remove: null,
});

const chamberPopup = ref(false);

const chamberFilter = ref("");

let pageProps = usePage().props;
console.log(usePage().props);

const chambers = computed(() => {
    let data = pageProps.chambers || [];
    let filteredData = data.filter((item) => {
        const itemName = String(item.name).toLowerCase();
        const filterValue = String(chamberFilter.value).toLowerCase();
        return itemName.includes(filterValue);
    });

    return filteredData.length ? filteredData : data;
});

onMounted(() => {
    // let pageProps = usePage().props
    // form.weekdays = get(pageProps, 'doctor.doctor_details.weekdays') || []
});

const handleSelect = (item, remove = null) => {
    Helper.confirm(
        `Are you sure to ${remove ? "remove" : "add"} this chamber?`,
        () => {
            let pageProps = usePage().props;
            chamberPopup.value = false;
            form.chamber_id = item.id;
            form.remove = remove;
            form.post(route("app.doctors.save_chamber", pageProps.doctor.id));
        }
    );
};

const handleSave = (move) => {
    // if (move > 0) {
    //     let pageProps = usePage().props
    //     form.post(route('app.doctors.save_weekdays', pageProps.doctor.id), {
    //         onSuccess(e) {
    //             if (Object.keys(e.props.errors).length==0) {
    //                 handleNextPrev(move)
    //             }
    //         }
    //     })
    // } else {
    // }
    handleNextPrev(move);
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
        key: "chamber.name",
        sortable: true,
    },
    {
        title: "City",
        key: "chamber.city",
        sortable: true,
    },
    {
        title: "Address",
        key: "chamber.address",
        sortable: true,
    },
];

const searchKeys = ["name"];

const theadClass = "!bg-primary !text-white";
const trClass = "hover:bg-gray-100";
</script>
