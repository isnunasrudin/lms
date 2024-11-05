<template>
    <div class="bg-emphasis w-full flex fixed z-50 px-5 py-1 shadow">
        <img src="../../images/logo.png" alt="Logo SMPN 1 Panggul" class="h-16 p-2">
        <div class="my-auto max-sm:hidden block">
            <p class="text-xs">Learning Management System</p>
            <h1 class="font-bold text-2xl">SMPN 1 PANGGUL</h1>
        </div>
        <div class="my-auto max-sm:block hidden">
            <p class="text-xs">{{ student.name }}</p>
            <h1 class="font-bold text-2xl">Kelas {{ student.rombel.name }}</h1>
        </div>
        <Link href="/logout" class="my-auto ml-auto">
            <Button label="Keluar" severity="contrast" icon="pi pi-sign-out" />
        </Link>
    </div>
    <div class="flex h-screen w-screen ">
        <div class="m-auto">
            <div class="w-[400px] max-w-[100vw] overflow-hidden my-32 px-1">
                <slot />
            </div>
        </div>
    </div>
</template>

<script setup>

import { Link } from '@inertiajs/vue3';

const props = defineProps({
    student: Object
})

</script>