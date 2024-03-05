<script setup>
import { dayjs } from '../../translation.config.js';

const props = defineProps({
    lesson: Object
})
</script>

<template>
    <p class="block truncate dark:text-neutral-200">
        {{ lesson.title }}
    </p>
    <p v-if="lesson.start_at" class="block truncate text-gray-400 dark:text-neutral-500">
        {{ dayjs(lesson.start_at).format('LLL') }}
    </p>
    <p v-else class="block truncate text-gray-400 dark:text-neutral-500">
        Не запланирован
    </p>
</template>
