<script setup>
import VueMultiselect from 'vue-multiselect';
import { ref } from 'vue';

const props = defineProps({
    modelValue: Object,
    options: Array
})

const ss = (e) => console.log(e)
</script>

<template>
    <VueMultiselect
        select-label="Нажмите Enter чтобы выбрать"
        deselectLabel="Нажмите Enter чтобы убрать"
        placeholder="Выберите.."
        @update:model-value="$emit('update:modelValue', $event)"
        :options="options"
        :model-value="modelValue"/>
</template>
