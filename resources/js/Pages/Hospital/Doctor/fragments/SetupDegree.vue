<template>
    <ul class="grid w-full gap-6 grid-cols-1 md:grid-cols-4">
        <li v-for="(item, index) in defaultPayload" :key="index">
            <input
                type="checkbox"
                @change="handleCheck(item, $page.props.degrees[index])"
                :id="index"
                :checked="item.id"
                class="hidden peer"
            />
            <label
                :for="index"
                class="select-none inline-flex items-center justify-between w-full p-5 text-gray-500 bg-white border-2 border-gray-200 rounded-lg cursor-pointer peer-checked:border-primary hover:text-gray-600 peer-checked:text-gray-600 hover:bg-slate-100"
            >
                <div class="block">
                    <div class="w-full text-lg font-semibold">
                        {{ item.name }}
                    </div>
                    <div v-if="item.id">
                        <input
                            type="text"
                            v-model="item.note"
                            placeholder="Institution"
                            class="border py-1 px-2 rounded w-full mt-0.5"
                        />
                    </div>
                </div>
            </label>
        </li>
    </ul>

    <NextPrev @action="(move) => handleSave(move)" />
</template>

<script setup>
import { useForm, usePage } from "@inertiajs/vue3";
import { useConfig } from "../useConfig";
import { Select, Input } from "@/plugins/form";
import NextPrev from "../NextPrev.vue";
import { onMounted, ref } from "vue";
import { get } from "lodash";

const { handleNextPrev } = useConfig();

const defaultPayload = ref([]);

const form = useForm({
    degree_info: [],
});

const handleCheck = (payloadItem, degreeItem) => {
    payloadItem.id = payloadItem.id ? null : degreeItem.id;
};

const preparePayload = (degrees, pageProps) => {
    let olds = get(pageProps, "doctor.degrees") || [];

    defaultPayload.value = degrees.map((item) => {
        let obj = {
            name: item.name,
            id: null,
            note: "",
        };

        let found = olds.find((i) => i.degree_id == item.id);
        if (found) {
            obj.id = found.id;
            obj.note = found.note;
        }
        return obj;
    });
};

onMounted(() => {
    let pageProps = usePage().props;
    let degrees = get(pageProps, "degrees") || [];
    console.log(degrees);
    preparePayload(degrees, pageProps);
});

const handleSave = (move) => {
    if (move > 0) {
        let pageProps = usePage().props;
        form.degree_info = defaultPayload.value.filter((item) => !!item.id);
        form.post(route("hospitals.doctors.save_degrees", pageProps.doctor.id), {
            onSuccess(e) {
                if (Object.keys(e.props.errors).length == 0) {
                    handleNextPrev(move);
                }
            },
        });
    } else {
        handleNextPrev(move);
    }
};
</script>
