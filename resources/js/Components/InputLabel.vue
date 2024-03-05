<script setup>
defineProps({
    value: String,
});
</script>

<template>
    <label class="block text-sm font-medium mb-2 dark:text-white">
        <span v-if="value">{{ value }}</span>
        <span v-else><slot /></span>
    </label>
</template>
