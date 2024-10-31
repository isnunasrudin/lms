<template>
    <div class="container flex my-5">
        <div class="m-auto pb-4" style="max-width: 800px; z-index: 999">
            <div class="text-center py-4 bg-primary m-0 text-white">
                <h1 class="h5">{{ exam?.name }} [ {{ (active + 1) +'/'+ (exam?.questions?.length) }} ]</h1>
                <p class="mb-0">{{ student?.name }}</p>
            </div>

            <div class="card">
                <div class="card-body" id="soal">
                    <div v-html="currentQuestion.content"></div>

                    <legend class="mt-4">Jawaban</legend>
                    <div class="ps-5 pt-2 form-check border-bottom border-3 mb-3" style="background-color: #ddd;" v-for="(soal, index) in currentQuestion.options" :key="soal">
                        <input class="form-check-input" type="radio" :value="index" :id="`option-${index}`" v-model="currentOption">
                        <label class="form-check-label" style="cursor: pointer;" :for="`option-${index}`">
                            <span v-html="soal.value"></span>
                        </label>
                    </div>
                </div>
                <div class="card-footer">
                    <div class="row">
                        <div class="col-auto">
                            <button class="btn btn-secondary" :disabled="active <= 0" @click="active--">Kembali</button>
                        </div>
                        <div class="col flex">
                            <div class="alert alert-info p-2 text-center fw-bold">
                                <vue-countdown :time="waktu" v-slot="{ hours, minutes, seconds }">
                                    <small>{{ hours }} jam, {{ minutes }} menit, {{ seconds }} detik.</small>
                                </vue-countdown>
                            </div>
                        </div>
                        <div class="col-auto">
                            <button v-if="(active + 1) < props.exam.questions.length" class="btn btn-primary" @click="active++">Selanjutnya</button>
                            <a :href="url + '/finish'" v-else class="btn btn-success">Finish!</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { computed, onMounted, reactive, ref, watch, watchEffect } from 'vue';
import { router, usePage } from '@inertiajs/vue3'
import axios from 'axios'
import moment from 'moment';

const page = usePage()

const props = defineProps({
    exam: Object,
    grade: Object,
    currentJawabans: Object
})

let jawabans = reactive({})
let url = ref(location.href.replace(/\/$/, ''))

let active = ref(0)
let currentQuestion = computed(() => props.exam.questions[active.value])
let currentOption = computed({
    set(val){
        jawabans[currentQuestion.value.id] = val
    },
    get(){
        return jawabans[currentQuestion.value.id]
    }
})

watch(jawabans, async (current)=>{

    await axios.post('', {
        question_id: currentQuestion.value.id,
        answer: currentOption.value
    }, {
        headers: {
            'Content-Type': 'multipart/form-data'
        }
    })

})

const waktu = ref(moment(props.grade.created_at).add(props.exam.duration, 'm').diff(moment()))

onMounted(() => {
    Object.assign(jawabans, props.currentJawabans)
})



// let busy = false
// let dirty = false

// onMounted(() => {
//     setInterval(async function(){

//         if(!busy && dirty)
//         {
//             busy = true

//             await axios.post('', {

//                 jawabans 

//             }, {
//                 headers: {
//                     'Content-Type': 'multipart/form-data'
//                 }
//             })

//             busy = false
//             dirty = false
//         }

//     }, 5000)
// })
</script>

<style>
figure img, #soal img{
    width: 100% !important;
    height: unset !important;
}
</style>