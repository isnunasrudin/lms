<template>
    <div class="container flex my-5">
        <div class="m-auto pb-4" style="width: 500px; z-index: 999">
            <div class="text-center py-4 bg-primary m-0 text-white">
                <h1 class="h5">Selamat Datang!</h1>
                <p class="mb-0">{{ student?.name }}</p>
            </div>

            <div class="card">
                <div class="card-body">
                    <p>Pilihan Ujian yang Tersedia: </p>
                    <Link as="button" :href="`/exam/${exam.id}`" class="btn btn-primary d-block text-left w-100 mb-1" v-for="exam in exams" :key="exam">
                        <h2 class="h5 mb-0">{{ exam.name }}</h2>
                        {{ exam.duration }} menit
                    </Link>
                </div>
            </div>

        </div>
    </div>
</template>

<script setup>
import { Link } from '@inertiajs/vue3';


defineProps({
    student: Object,
    exams: Array
})

</script>