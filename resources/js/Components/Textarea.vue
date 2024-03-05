<script setup>
import { onMounted, ref } from 'vue';
import InputLabel from '@/Components/InputLabel.vue';

const props = defineProps({
    modelValue: String,
    rows: Number,
    label: String,
    description: String
});

defineEmits(['update:modelValue']);

const input = ref(null);

onMounted(() => {
    if (input.value.hasAttribute('autofocus')) {
        input.value.focus();
    }
});

defineExpose({ focus: () => input.value.focus() });
</script>

<template>
    <div>
        <InputLabel :value="props.label"></InputLabel>
        <textarea
            ref="input"
            class="py-3 px-4 block w-full border-gray-200 rounded-lg text-sm focus:border-blue-500 focus:ring-blue-500 disabled:opacity-50 disabled:pointer-events-none dark:bg-slate-900 dark:border-gray-700 dark:text-gray-400 dark:focus:ring-gray-600"
            :rows="rows ?? 3"
            @input="$emit('update:modelValue', $event.target.value)"
            aria-describedby="hs-textarea-helper-text"
        >{{modelValue}}</textarea>
        <p v-if="description" class="text-xs text-gray-500 mt-2" id="hs-textarea-helper-text">{{props.description}}</p>
    </div>
</template>
