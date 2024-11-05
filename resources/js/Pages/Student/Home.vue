<template>
    <Card class="overflow-hidden">
        <template #header>
            <div class="justify-center flex bg-cover bg-[url('/resources/images/bg.jpg')] px-3 py-5">
                <div class="mx-5 my-auto text-white">
                    <p class="font-bold text-3xl">Daftar Tes Tersedia</p>
                </div>
            </div>
        </template>
        <template #content>

            <DataView :value="exams">
                <template #list="slotProps">
                    <div class="flex flex-col">
                        <div v-for="(item, index) in slotProps.items" :key="index">
                            <div class="px-6 py-3" :class="{ 'border-t border-surface-200 dark:border-surface-700 !mt-5': index !== 0 }">
                                <div>
                                    <span class="font-medium text-surface-500 dark:text-surface-400 text-sm">{{ item.event.name }}</span>
                                    <div class="text-lg font-medium">{{ item.name }}</div>
                                </div>
                                <div class="flex gap-2 mb-2 mt-2">
                                    <Badge severity="secondary" :value="`Durasi ${item.duration} menit`"></Badge>
                                    <Badge severity="secondary" :value="item.from + ' - ' + item.until"></Badge>
                                </div>
                                <Link :href="`/exam/${item.id}`">
                                    <Button icon="pi pi-play-circle" label="Mulai Ujian" class="w-full block mt-2" :disabled="!item.enable"></Button>
                                </Link>
                                <div class="text-right text-xs mt-1">
                                    Kesempatan: <b>{{ item.attempt - item.percobaan }}x</b>
                                </div>
                            </div>
                        </div>
                    </div>

                </template>
            </DataView>

        </template>
    </Card>
</template>

<script setup>

import { router, usePage, useForm, Link } from '@inertiajs/vue3'
import { DataView } from 'primevue';

const props = defineProps({
    exams: Array
})

</script>