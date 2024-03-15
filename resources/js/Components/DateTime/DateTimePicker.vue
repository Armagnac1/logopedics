<script setup>
import VueDatePicker from '@vuepic/vue-datepicker';
import { nextTick, ref } from 'vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import { dayjs } from '../../translation.config.js';


const emit = defineEmits(['update:modelValue'])
const props = defineProps({
    modelValue: String | Array,
    options: Array
})

let input = ref(null);
let value = ref(props.modelValue);

const clearDate = () => {
    value.value = null;
    input.value.closeMenu();
    emit('update:modelValue', null)
}
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


</script>

<template>
    <VueDatePicker
        ref="input"
        v-model="value"
        :clearable="false"
        format="dd/MM/yyyy HH:mm"
        :start-time="startTime"
        :teleport="true"
        time-picker-inline
        cancelText="Отмена"
        locale="ru"
        minutes-increment="5"
        selectText="ОК"
        @update:model-value="$emit('update:modelValue', dayjs(value).format('YYYY-MM-DD HH:mm:00'))"
    >
        <template #action-extra="{ selectCurrentDate }">
            <SecondaryButton @click="clearDate()">
                Убрать из расписания
            </SecondaryButton>
        </template>
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
