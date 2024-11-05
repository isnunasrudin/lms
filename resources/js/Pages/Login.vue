<template>
    <div class="flex h-screen w-screen">
        <div class="m-auto">
            <div class="w-[400px] max-w-[100vw] mb-12 px-1">
                <Card class="overflow-hidden">
                    <template #header>
                        <div class="justify-center flex bg-cover bg-[url('/resources/images/bg.jpg')] px-3 py-5">
                            <img src="../../images/logo.png" alt="Logo SMPN 1 Panggul" class="h-20">
                            <div class="mx-5 my-auto">
                                <p class="font-bold mb-1 text-xs">Learning Management System</p>
                                <p class="font-bold text-xl">SMPN 1 PANGGUL</p>
                            </div>
                        </div>
                    </template>
                    <template #title>
                        <p class="text-center">Silahkan Masuk</p>
                    </template>
                    <template #content>
                        <form class="m-4" @submit.prevent="form.post('/login')" autocomplete="off">

                            <Message class="mb-4 text-center" severity="warn" v-if="Object.keys(form.errors)?.length && !form.processing">
                                {{ form.errors.nisn ?? form.errors.password }}
                            </Message>

                            <div class="mb-4">
                                <FloatLabel variant="on">
                                    <InputText :disabled="form.processing" id="on_label" v-model="form.nisn" autocomplete="off" class="w-full" required />
                                    <label for="on_label">NISN</label>
                                </FloatLabel>
                            </div>

                            <div class="mb-4">
                                <FloatLabel variant="on">
                                    <Password :disabled="form.processing" id="on_label" v-model="form.password" autocomplete="off" inputClass="w-full" class="w-full" required toggleMask :feedback="false" />
                                    <label for="on_label">Kata Sandi</label>
                                </FloatLabel>
                            </div>

                            <Button label="Masuk" class="w-full" type="submit" :loading="form.processing" />

                            
                        </form>
                        
                        <p class="text-sm text-center mt-8 text-muted-color">Hak Cipta &copy; 2024 SMPN 1 Panggul</p>

                    </template>
                </Card>
            </div>
        </div>
    </div>
</template>

<script setup>

import { usePage, useForm } from '@inertiajs/vue3'

const page = usePage()

const form = useForm({
    nisn: '',
    password: '',
});

</script>