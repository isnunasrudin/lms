<template>
    <Card class="overflow-hidden">
        <template #header>
            <div class="flex bg-cover bg-[url('/resources/images/bg.jpg')] p-5">
                <OverlayBadge :value="exam.questions.length" severity="secondary" class="inline-flex">
                    <Avatar :label="`${active + 1}`" size="xlarge" />
                </OverlayBadge>
                <div class="ml-8 mr-3 my-auto">
                    <p class="font-bold text-xl">{{ exam.name }}</p>
                </div>
            </div>
        </template> 
        <template #content>

            <Dialog v-model:visible="questionListDialog" modal header="Daftar Soal" :style="{ width: '25rem' }">

                <div class="grid grid-cols-5 gap-4">
                    <Button :label="(index + 1)"
                        :severity="jawabans[item.id] !== undefined ? 'primary' : 'secondary'"
                        v-for="(item, index) in exam.questions"
                        :key="item"
                        size="large"
                        @click="questionListDialogSelect(index)">
                    </Button>
                </div>

            </Dialog>
                
            <div class="flex mb-3">
                <Message severity="success" size="small">
                    Sisa Waktu:
                    <vue-countdown :time="waktu" v-slot="{ hours, minutes, seconds }" class="text-bold">
                        {{ String(hours).padStart(2, "0") }}:{{ String(minutes).padStart(2, "0") }}:{{ String(seconds).padStart(2, "0") }}
                    </vue-countdown>
                </Message>
                <Button severity="info" label="Lihat Daftar Soal" size="small" class="ml-auto" icon="pi pi-list" @click="questionListDialog = true" />
            </div>

            <div id="soal">

                <div v-html="question.content" class="my-4"></div>

                <DataView :value="question.options">
                    <template #list="slotProps">
                        <div class="flex flex-col">
                            <div v-for="(item, index) in slotProps.items" :key="index">
                                <div class="py-3 flex gap-2" :class="{ 'border-t border-surface-200 dark:border-surface-700': index !== 0 }">
                                    <RadioButton v-model="option" :inputId="'jawaban-' + index" name="dynamic" :value="index" class="my-auto" />
                                    <label :for="'jawaban-' + index" class="w-full my-auto" v-html="item.value"></label>
                                </div>
                            </div>
                        </div>

                    </template>
                </DataView>
            </div>

        </template>

        <template #footer>

            <div class="grid grid-cols-2 gap-4 mt-5">
                <Button label="Sebelumnya" severity="secondary" outlined class="w-full" @click="backSoal" :disabled="active == 0" />

                <Button v-if="(active + 1) !== exam.questions.length" label="Lanjut" class="w-full" @click="nextSoal" />
                <Button v-else
                    label="Simpan!"
                    class="w-full"
                    @click="finish"
                    severity="danger"
                    :disabled="exam.questions.length != Object.keys(jawabans).length"
                    />
            </div>
        </template>
    </Card>
</template>

<script setup>
import { computed, onMounted, reactive, ref } from 'vue';
import { router } from '@inertiajs/vue3'
import moment from 'moment';
import axios from 'axios';

const questionListDialog = ref(false)
function questionListDialogSelect(index)
{
    active.value = index
    questionListDialog.value = false
}

const active = ref(0)
const props = defineProps({
    exam: Object,
    grade: Object,
    currentJawabans: Object
})

let jawabans = reactive({})
Object.assign(jawabans, props.currentJawabans)

let question = computed(() => props.exam.questions[active.value])
let option = computed({
    set(val){
        jawabans[question.value.id] = val
        antrian.value.push({
            question_id: question.value.id,
            answer: val
        })
    },
    get(){
        return jawabans[question.value.id]
    }
})

let antrian = ref([])

async function backSoal()
{
    active.value--
}

async function nextSoal()
{
    active.value++
}

async function finish()
{
    let data = Object.keys(jawabans).map((key) => ({
            question_id: key,
            answer: jawabans[key]
        }));

    try {
        await axios.post(window.location.toString().replace(/\/?$/, '/') + "finish", {
            data
        }, {
            headers: {
                'Content-Type': 'multipart/form-data'
            }
        })
    } catch (error) {
        
    }
    
    router.visit('/home')

}

//Time
const waktu = ref(moment(props.grade.created_at).add(props.exam.duration, 'm').diff(moment()))

onMounted(() => {

    let busy = false
    let reupdate = setInterval(async () => {
        try {
            if(!busy && antrian.value.length > 0)
            {
                busy = true
                let sementara = antrian.value
                antrian.value = []
                await axios.post('', {
                        data: sementara
                    }, {
                        headers: {
                            'Content-Type': 'multipart/form-data'
                        }
                    })
                busy = false
                
            }
        } catch (error) {
            location.href = ""
        }
    }, 1000)

    let timewatch = setInterval(async() => {

        if(!moment().isSameOrBefore(moment(props.grade.created_at).add(props.exam.duration, 'm')))
        {
            clearInterval(reupdate)
            clearInterval(timewatch)
            await finish()   
        }

    }, 1000)

})

</script>

<style lang="scss">
figure img, #soal img{
    width: 100% !important;
    height: unset !important;
}

#soal p:not(:last-child){
    @apply mb-4;
}
</style>