<script setup>
import VueDatePicker from '@vuepic/vue-datepicker';
import { nextTick, ref } from 'vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import { dayjs } from '../../translation.config.ts';


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

const formatTime = (time) => `${time.hours < 10 ? '0' + time.hours : time.hours}:${time.minutes < 10 ? '0' + time.minutes : time.minutes}`
const handleCancel = () => {
    input.value.clearValue();
    input.value.closeMenu();
};

</script>

<template>
    <VueDatePicker
        ref="input"
        v-model="value"
        :clearable="false"
        :start-time="startTime"
        cancel-text="$t('common.cancel')"
        format="HH:mm"
        locale="ru"
        select-text="$t('common.ok')"
        teleport
        time-picker
        @update:model-value="$emit('update:modelValue', formatTime(value))"
        @closed="input.selectDate()"
    >
        <template #action-buttons>
            <SecondaryButton @click="handleCancel">
                {{ $t('common.cancel') }}
            </SecondaryButton>
            <PrimaryButton @click="input.selectDate()">
                {{ $t('common.ok') }}
            </PrimaryButton>
        </template>
        <template #action-preview="{ value }">
        </template>
        <template v-if="$slots.trigger" #trigger>
            <slot name="trigger"></slot>
        </template>
    </VueDatePicker>
</template>
