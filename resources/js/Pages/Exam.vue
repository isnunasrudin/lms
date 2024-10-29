<template>
    <div class="container flex my-5">
        <div class="m-auto pb-4" style="width: 500px; z-index: 999">
            <div class="text-center py-4 bg-primary m-0 text-white">
                <h1 class="h5">{{ exam?.name }}</h1>
                <p class="mb-0">{{ student?.name }}</p>
            </div>

            <div class="card">
                <div class="card-body">
                    <div v-html="currentQuestion.content"></div>

                    <legend class="mt-4">Jawaban</legend>
                    <div class="form-check" v-for="(soal, index) in currentQuestion.options" :key="soal">
                        <input class="form-check-input" type="radio" :value="index" :id="`option-${index}`" v-model="currentOption">
                        <label class="form-check-label" style="cursor: pointer;" :for="`option-${index}`">
                            <span v-html="soal.value"></span>
                        </label>
                    </div>
                </div>
                <div class="card-footer">
                    <div class="row">
                        <div class="col">
                            <button class="btn btn-secondary" :disabled="active <= 0" @click="active--">Kembali</button>
                        </div>
                        <div class="col-auto">
                            <button v-if="(active + 1) < props.exam.questions.length" class="btn btn-primary" @click="active++">Selanjutnya</button>
                            <button v-else class="btn btn-success">Finish!</button>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</template>

<script setup>
import { computed, ref, watchEffect } from 'vue';


const props = defineProps({
    exam: Object,
    grade: Object
})

let active = ref(0)
let currentQuestion = computed(() => props.exam.questions[active.value])
let currentOption = ref(null)

watchEffect(() => {
    
})

</script>