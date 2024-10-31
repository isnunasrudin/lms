<template>
    <div class="container flex my-5">
        <div class="m-auto pb-4" style="max-width: 800px; z-index: 999">
            <div class="text-center py-4 bg-primary m-0 text-white">
                <h1 class="h5">Selamat Datang!</h1>
                <p class="mb-0">{{ student?.name }}</p>
            </div>

            <div class="card">
                <div class="card-body">
                    <p>Pilihan Ujian yang Tersedia: </p>

                    <div class="card" v-for="exam in exams" :key="exam">
                        <div class="card-body">
                            <h5 class="card-title">Mapel: <b>{{ exam.name }}</b></h5>
                            <p class="card-text">
                                <span class="badge text-bg-primary me-1">Durasi: {{ exam.duration }} menit</span>
                                <span class="badge text-bg-primary me-1">Pelaksanaan: {{ exam.date }} ({{ exam.from + ' - ' + exam.until }})</span>
                                <span class="badge text-bg-primary me-1">Percobaan: {{ exam.percobaan }} dari {{ exam.attempt }}</span>
                            </p>
                        </div>
                        <div class="card-footer">
                            <Link v-if="exam.enable" as="button" :href="`/exam/${exam.id}`" class="btn btn-primary d-block text-left w-100 mb-1">
                                MULAI UJIAN
                            </Link>

                            <button v-else class="btn btn-primary d-block text-left w-100 mb-1" disabled>
                                Ujian Saat Ini Tidak Tersedia!
                            </button>
                        </div>
                    </div>
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