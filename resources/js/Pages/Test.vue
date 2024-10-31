<template>
    <div class="container flex my-5">
        <div class="m-auto pb-4" style="width: 500px; z-index: 999">
            <h1 class="h5 text-center py-4 bg-primary m-0 text-white">SMPN 1 Panggul CBT</h1>
            <form class="p-5 pt-4 text-dark" style="background-color: rgba(255, 255, 255, 0.9);-webkit-backdrop-filter: blur(5px);backdrop-filter: blur(5px);" method="POST" action="" autocomplete="off" @submit.prevent="form.post('/login')">
                <div class="alert alert-warning" v-if="Object.keys(form.errors).length" role="alert">
                    {{ form.errors.nisn ?? form.errors.password }}
                </div>
                <div>
                    <label class="col-form-label">NISN <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" required="" v-model="form.nisn" placeholder="Nomor Induk Siswa Nasional">
                </div>
                <div>
                    <label class="col-form-label">Tanggal Lahir <span class="text-danger">*</span></label>
                    <input type="date" class="form-control" required="" v-model="form.password" placeholder="Masukkan Tanggal Lahir">
                </div>
                <button type="submit" class="btn btn-primary w-100 mt-4">MASUK!</button>
            </form>
        </div>
    </div>
</template>

<script setup>

import { router, usePage, useForm } from '@inertiajs/vue3'
import { onMounted, reactive } from 'vue'

const page = usePage()

const form = useForm({
    nisn: '',
    password: '',
});

</script>