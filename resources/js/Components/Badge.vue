<script setup>
const props = defineProps({
    value: String
})
</script>

<template>
    <span class="inline-flex items-center gap-x-1.5 py-1.5 px-3 rounded-full text-xs font-medium bg-gray-500 text-white">{{value}}</span>
</template>
