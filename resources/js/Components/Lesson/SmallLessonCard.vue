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
    <div>
        <Link draggable="false" :href="route('lesson.show', lesson.id)" replace>
            <div
                :class="classes">
                <div class="grow truncate">
                    <p class="block truncate dark:text-neutral-200">
                        {{ lesson.title }}
                    </p>
                    <p v-if="lesson.start_at" class="block truncate text-gray-400 dark:text-neutral-500">
                        {{ dayjs(lesson.start_at).format('LLL') }}
                    </p>
                    <p v-else class="block truncate text-gray-400 dark:text-neutral-500">
                        Не запланирован
                    </p>
                </div>
            </div>
        </Link>
    </div>
</template>
