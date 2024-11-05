<template>
    <Card class="overflow-hidden">
        <template #header>
            <div class="justify-center flex bg-cover bg-[url('/resources/images/bg.jpg')] px-3 py-5">
                <div class="mx-5 my-auto">
                    <p class="font-bold text-3xl">Masukkan Token</p>
                </div>
            </div>
        </template>
        <template #content>

            <form class="px-6 py-3" @submit.prevent="form.put('')">

                <Message class="mb-4 text-center" severity="warn" v-if="Object.keys(form.errors)?.length && !form.processing">
                    {{ form.errors.token }}
                </Message>

                <Fieldset legend="Info">
                    <div>
                        <span class="font-medium text-surface-500 dark:text-surface-400 text-xs">{{ exam.event.name }}</span>
                        <div class="font-medium">{{ exam.name }}</div>
                    </div>
                    <div class="flex gap-2 mb-2 mt-2">
                        <Badge severity="secondary" :value="`Durasi ${exam.duration} menit`"></Badge>
                        <Badge severity="secondary" :value="exam.from + ' - ' + exam.until"></Badge>
                    </div>
                </Fieldset>

                <FloatLabel variant="on" class="my-5">
                    <InputText size="large" :disabled="form.processing" id="on_label" v-model="form.token" autocomplete="off" class="w-full" required />
                    <label for="on_label">TOKEN</label>
                </FloatLabel>

                <Button icon="pi pi-play-circle" label="Mulai Ujian" class="w-full block mt-2" :disabled="exam.enable" type="submit" ></Button>
                
                <div class="text-right text-xs mt-1" v-if="exam.enable">
                    Kesempatan: <b>{{ exam.attempt - exam.percobaan }}x</b>
                </div>
            </form>

        </template>
    </Card>
</template>

<script setup>

import { router, usePage, useForm, Link } from '@inertiajs/vue3'
import { DataView } from 'primevue';

const props = defineProps({
    exam: Object,
})

const form = useForm({
    token: null,
});

</script>