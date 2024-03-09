<script setup>
const props = defineProps({
    value: String
})
</script>

<template>
    <span class="inline-flex items-center gap-x-1.5 py-1.5 px-3 rounded text-xs font-medium bg-green-500 text-white">{{value}}</span>
</template>
