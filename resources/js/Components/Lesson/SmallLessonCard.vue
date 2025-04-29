<script setup>

import { dayjs } from '../../translation.config.js';
import { Link } from '@inertiajs/vue3';
import { computed } from 'vue';

const props = defineProps({
    lesson: Object,
    active: Boolean
})
const classes = computed(() => {
    let classes = 'p-3 relative border flex items-center rounded-xl border-gray-200 space-x-4 dark:bg-neutral-800 dark:border-neutral-700'
    return props.active
        ? classes + ' bg-blue-50'
        : classes;
});
</script>

<template>
    <Link :href="route('lesson.show', lesson.id)" replace>
        <div :class="classes">
            <div class="grow truncate">
                <div class="block truncate dark:text-neutral-200">
                    <span v-if="lesson.title">{{ lesson.title }}</span>
                    <span v-else class="text-gray-400 font-normal italic">{{ $t('common.untitled') }}</span>
                </div>
                <p v-if="lesson.start_at" class="block truncate text-gray-400 dark:text-neutral-500">
                    {{ dayjs(lesson.start_at).format('YYYY-MM-DD HH:mm') }}
                </p>
                <p v-else class="block truncate text-gray-400 dark:text-neutral-500">
                    {{ $t('common.notScheduled') }}
                </p>
            </div>
        </div>
    </Link>
</template>
