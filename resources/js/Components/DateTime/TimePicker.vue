<script setup>
import VueDatePicker from '@vuepic/vue-datepicker';
import { nextTick, ref } from 'vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import { dayjs } from '../../translation.config.js';


const emit = defineEmits(['update:modelValue'])
const props = defineProps({
    modelValue: String,
    options: Array
})

let input = ref(null);
let value = ref(props.modelValue);

const startTime = ref({
    hours: dayjs().add(1, 'h').hour(),
    minutes: 0
})

defineExpose({
    focus: () => {
        nextTick().then(() => {
            input.value.openMenu()
        })
    }
});

const formatTime = (time) => `${time.hours}:${time.minutes < 10 ? '0' + time.minutes : time.minutes}`


</script>

<template>
    <VueDatePicker
        ref="input"
        v-model="value"
        :clearable="false"
        :start-time="startTime"
        cancelText="Отмена"
        format="HH:mm"
        locale="ru"
        selectText="ОК"
        teleport
        time-picker
        @update:model-value="$emit('update:modelValue', formatTime(value))"
    >
        <template #action-buttons>
            <SecondaryButton @click="input.closeMenu()">
                Отмена
            </SecondaryButton>
            <PrimaryButton @click="input.selectDate()">
                OK
            </PrimaryButton>
        </template>
        <template #action-preview="{ value }">
        </template>
        <template v-if="$slots.trigger" #trigger>
            <slot name="trigger"></slot>
        </template>
    </VueDatePicker>
</template>
