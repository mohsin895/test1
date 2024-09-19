<template>
    <form >
        <div class="grid md:grid-cols-2 gap-2">
            <Input.Primary
                type="text"
                label="Experience"
                v-model="form.experience"
                :error="form.errors.experience"
            />
            <Input.Primary
                type="text"
                label="Age"
                v-model="form.age"
                :error="form.errors.age"
            />
            <Input.Primary
                type="text"
                label="MBDC"
                v-model="form.bmdc"
                :error="form.errors.bmdc"
            />
            <Radio.Group
                v-model="form.publish_bmdc"
                :error="form.errors.publish_bmdc"
                label="Publish BMDC"
            >
                <Radio.Input label="Yes" :value="1" />
                <Radio.Input label="No" :value="0" />
            </Radio.Group>
        </div>
    </form>
</template>

<script setup>
import { Input, Radio } from '@/plugins/form'
import { useForm, router } from "@inertiajs/vue3";
import { isEmpty } from "lodash";

</script>