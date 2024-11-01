<template>
    <div class="container flex my-5">
        <div class="m-auto pb-4" style="max-width: 800px; z-index: 999">
            <div class="py-4 bg-primary m-0 text-white">
                <div class="row mx-4">
                    <div class="col-auto d-none d-lg-block">
                        <img src="/images/logo.png" alt="" height="80px" class="ms-4">
                    </div>
                    <div class="col my-auto">
                        <h1 class="h5">Selamat Datang!</h1>
                        <p class="mb-0">{{ student?.name }}</p>
                    </div>
                    <div class="col-auto my-auto">
                        <Link href="/logout" as="button" class="btn btn-secondary mt-3">Keluar</Link>
                    </div>
                </div>
            </div>
            <form class="p-5 pt-4 text-dark" style="background-color: rgba(255, 255, 255, 0.9);-webkit-backdrop-filter: blur(5px);backdrop-filter: blur(5px);" method="POST" action="" autocomplete="off" @submit.prevent="form.put('')">


                <h5 class="card-title">Mapel: <b>{{ exam.name }}</b></h5>
                <p class="card-text">
                    <span class="badge text-bg-primary me-1">Durasi: {{ exam.duration }} menit</span>
                    <span class="badge text-bg-primary me-1">Pelaksanaan: {{ exam.date }} ({{ exam.from + ' - ' + exam.until }})</span>
                </p>


                <div class="alert alert-warning" v-if="Object.keys(form.errors).length" role="alert">
                    {{ form.errors.token}}
                </div>

                <div>
                    <label class="col-form-label">TOKEN <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" required="" v-model="form.token" placeholder="TOKEN ">
                </div>
                <button type="submit" class="btn btn-primary w-100 mt-4">SUBMIT!</button>
            </form>
        </div>
    </div>
</template>

<script setup>

import { router, usePage, useForm } from '@inertiajs/vue3'
import { onMounted, reactive } from 'vue'

const page = usePage()

const props = defineProps({
    exam: Object,
    grade: Object,
    student: Object,
})

const form = useForm({
    token: '',
});

</script>