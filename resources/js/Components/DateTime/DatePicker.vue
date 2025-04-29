<script setup>
import VueDatePicker from '@vuepic/vue-datepicker';
import { nextTick, ref } from 'vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import { dayjs } from '../../translation.config.js';


const emit = defineEmits(['update:modelValue'])
const props = defineProps({
    modelValue: String | Array,
    multiple: {
        type: Boolean,
        default: false
    },
    options: Array
})

let input = ref(null);
let value = ref(props.modelValue);


defineExpose({
    focus: () => {
        nextTick().then(() => {
            input.value.openMenu()
        })
    }
});


const formatDate = (value) => {
    function format(date) {
        let converted = dayjs(date)
        return converted.format("L")
    }

    if (Array.isArray(value)) {
        var formatedDates = []
        for (const date of value) {
            formatedDates = [...formatedDates, format(date)]
        }
        return formatedDates
    }
    return format(value)

}


</script>

<template>
    <VueDatePicker
        ref="input"
        v-model="value"
        :clearable="false"
        :enable-time-picker="false"
        :multi-dates="multiple"
        :teleport="true"
        :cancel-text="$t('common.cancel')"
        format="dd/MM/yyyy"
        locale="ru"
        minutes-increment="5"
        :select-text="$t('common.ok')"
        @update:model-value="$emit('update:modelValue', formatDate(value))"
    >
        <template #action-buttons>
            <SecondaryButton @click="input.closeMenu()">
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
