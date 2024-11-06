<template>
    <Card class="overflow-hidden">
        <template #header>
            <div class="justify-center flex bg-cover bg-[url('/resources/images/bg.jpg')] px-3 py-5">
                <div class="mx-5 my-auto text-white">
                    <p class="font-bold text-xl">Diperlukan Peramban Khusus</p>
                </div>
            </div>
        </template>
        <template #content>

            <div class="px-6 py-3">

                <Fieldset legend="Info">
                    <div>
                        <span class="font-medium text-surface-500 dark:text-surface-400 text-xs">{{ exam.event.name }}</span>
                        <div class="font-medium">{{ exam.name }}</div>
                    </div>
                    <div class="flex gap-2 mb-2 mt-2">
                        <Badge severity="secondary" :value="`Durasi ${exam.duration} menit`"></Badge>
                        <Badge severity="secondary" :value="exam.from + ' - ' + exam.until"></Badge>
                    </div>


                    <Message v-if="exam.event.enable_ban" size="small" class="mt-3" severity="warn">
                        <b>Hati-Hati !</b> Fitur blokir otomatis diaktifkan. Anda dilarang keluar aplikasi selama ujian berlangsung. Termasuk membuka notifikasi, membuka web lain, atau mengunci layar.
                    </Message>

                </Fieldset>
                
                <Message class="mt-4" severity="error">Mohon maaf, anda harus menggunakan aplikasi <b>Safe Exam Browser (Windows)</b> atau <b>CBT Exam Browser (Android)</b> untuk mengikuti ujian ini. Jika menurut anda ini kesalahan, hubungi admin ujian.</Message>
            </div>

        </template>
    </Card>
</template>

<script setup>

import { router, usePage, useForm, Link } from '@inertiajs/vue3'
import { DataView, Message } from 'primevue';

const props = defineProps({
    exam: Object,
})

</script>