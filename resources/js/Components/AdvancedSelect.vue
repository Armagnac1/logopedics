<script setup>
import VueMultiselect from 'vue-multiselect';
import { nextTick, ref } from 'vue';

const props = defineProps({
    modelValue: Object,
    options: Array
})

let input = ref(null);
defineExpose({
    focus: () => {
        nextTick().then(() => {
            input.value.$el.focus()
        })
    }
});
</script>

<template>
    <VueMultiselect
        ref="input"
        selectLabel="Нажмите Enter чтобы выбрать"
        selectedLabel="Выбрано"
        deselectLabel="Нажмите Enter чтобы убрать"
        placeholder="Выберите.."
        @update:model-value="$emit('update:modelValue', $event)"
        :options="options"
        :model-value="modelValue"/>
</template>
