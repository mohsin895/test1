<template>
    <Auth title="Chambers">
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
                class="bg-white py-8 px-5 w-[300px] md:min-w-[800px]"
            >
                <h2 class="font-semibold text-black mb-3">
                    {{ form.id ? "Update" : "Create" }} Chambers
                </h2>
                <div class="grid md:grid-cols-2 gap-2">
                    <Input.Primary
                        type="text"
                        label="Name"
                        v-model="form.name"
                        :error="form.errors.name"
                    />
                    
                    <Input.Primary
                        type="text"
                        label="City"
                        v-model="form.city"
                        :error="form.errors.city"
                    />
                    <Input.Primary
                        type="text"
                        label="Division"
                        v-model="form.division"
                        :error="form.errors.division"
                    />
                    <Input.Primary
                        type="text"
                        label="Address"
                        v-model="form.address"
                        :error="form.errors.address"
                    />
                    <Input.Primary
                        type="text"
                        label="Map Latitude"
                        v-model="form.lat"
                        :error="form.errors.lat"
                    />
                    <Input.Primary
                        type="text"
                        label="Map Longitude"
                        v-model="form.lon"
                        :error="form.errors.lon"
                    />
                    <Input.Primary
                        type="text"
                        label="Email"
                        v-model="form.email"
                        :error="form.errors.email"
                    />
                    <Input.Primary
                        type="text"
                        label="Password"
                        v-model="form.password"
                        :error="form.errors.password"
                    />
                    <Input.Primary
                        type="text"
                        label="Phone"
                        v-model="form.phone"
                        :error="form.errors.phone"
                    />
                    <Input.Primary
                        type="text"
                        label="Embaded Map"
                        wrapper-class="col-span-2"
                        v-model="form.map"
                        :error="form.errors.map"
                    />
                    <div class="col-span-2 flex items-start">
                        <Switch
                            label="Is Featured?"
                            v-model="form.featured"
                            :error="form.errors.featured"
                        />
                    </div>
                </div>
                <div class="mt-7 flex items-center justify-end">
                    <Button.Primary
                        type="submit"
                        :loading="form.processing"
                    >
                        {{ form.id ? "Update" : "Save" }}
                    </Button.Primary>
                </div>
                <!-- <div class="mt-5"> -->
                    <!-- <div v-html="form.map_location"></div> 24.7501689 -->
                    <!-- <iframe :src="`https://maps.google.com/maps?q=${data.settings.longtide},${data.settings.lattitude}&z=19&output=embed`" height="400" class='w-full' allowFullScreen="" loading="lazy" referrerPolicy="no-referrer-when-downgrade"></iframe> -->

                    <!-- <iframe :src="`https://maps.google.com/maps?q=${form.lon},${form.lat}&z=14&output=embed`" height="400" class='w-full' allowFullScreen="" loading="lazy" referrerPolicy="no-referrer-when-downgrade"></iframe> -->
                <!-- </div> -->
                <div v-if="form.map" class="grid gap-2">
                    <div>Google map</div>
                    <div v-html="form.map"></div>
                </div>
            </form>
        </Modal>

        
    </Auth>
</template>

<script setup>
import Auth from "@/Layouts/Auth.vue";
import { Table, Button, Modal, Icon } from "@/plugins/ui";
import { Input } from "@/plugins/form";
import { ref, onMounted } from "vue";
import { useForm, router } from "@inertiajs/vue3";
import { isEmpty } from "lodash";
import Helper from "@/helper";
import Switch from '@/library/form/Switch.vue'

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
    lat: "",
    featured: false,
    lon: "",
    city: "",
    phone: '',
    map: '',
    email: '',
    password: '',
    division: "",
    address: "",
});

// const embeddedCode = '<iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d248457.94843466715!2d-0.24168122895529235!3d51.52877184119212!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x487604ce2359e293%3A0x14162f827b24d1c5!2sLondon%2C%20UK!5e0!3m2!1sen!2sus!4v1643287764170!5m2!1sen!2sus" width="600" height="450" style="border:0;" allowfullscreen="" loading="lazy"></iframe>';

// // Regular expression pattern to extract latitude and longitude from src attribute
// const regex = /!3d(-?\d+\.\d+)!4d(-?\d+\.\d+)/;

// // Extracting latitude and longitude using regex
// const match = embeddedCode.match(regex);

// if (match) {
//     const latitude = parseFloat(match[1]);
//     const longitude = parseFloat(match[2]);
//     console.log('Latitude:', latitude);
//     console.log('Longitude:', longitude);
// } else {
//     console.log('Latitude and longitude not found in the embedded code.');
// }

// onMounted(() => {
//     let script = document.createElement('script')
//     script.setAttribute('src', 'https://maps.googleapis.com/maps/api/js?key=AIzaSyDhIlCdeDI_Im1XrLOxjocXsCCbWmK-_2M&v=weekly')
//     script.setAttribute('defer', '')
//     document.querySelector('head').append(script)
//     script.onload = function() {
//         const myLatlng = { lat: -25.363, lng: 131.044 };
//         const map = new google.maps.Map(document.getElementById("map"), {
//             zoom: 4,
//             center: myLatlng,
//         });
//         // Create the initial InfoWindow.
//         let infoWindow = new google.maps.InfoWindow({
//             content: "Click the map to get Lat/Lng!",
//             position: myLatlng,
//         });

//         infoWindow.open(map);
//         console.log(infoWindow);
//         // Configure the click listener.
//         map.addListener("click", (mapsMouseEvent) => {
//             // Close the current InfoWindow.
//             infoWindow.close();
//             // Create a new InfoWindow.
//             infoWindow = new google.maps.InfoWindow({
//             position: mapsMouseEvent.latLng,
//             });
//             infoWindow.setContent(
//             JSON.stringify(mapsMouseEvent.latLng.toJSON(), null, 2),
//             );
//             infoWindow.open(map);
//         });
//     }
// })

const handleEdit = (item) => {
    form.id = item.id;
    form.name = item.name;
    form.lat = item.lat;
    form.lon = item.lon;
    form.featured = item.featured;
    form.city = item.city;
    form.division = item.division;
    form.address = item.address;
    formModal.value = true;
};

const handleDelete = (id) => {
    Helper.confirm("Are you sure to delete?", () => {
        router.post(route("app.chambers.delete", id));
    });
};

const handleSave = () => {
    form.post(route("app.chambers.save"), {
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
        title: "City",
        key: "city",
        sortable: true,
    },
    {
        title: "Division",
        key: "division",
        sortable: true,
    },
    {
        title: "Address",
        key: "address",
        sortable: true,
    },
    {
        title: "Featured",
        key: "featured",
        sortable: true,
    },
];

const searchKeys = ["name", "map_location", "city", "division", "address"];

const theadClass = "!bg-primary !text-white";
const trClass = "hover:bg-gray-100";
</script>
