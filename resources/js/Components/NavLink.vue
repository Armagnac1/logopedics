<script setup>
import { computed } from 'vue';
import { Link } from '@inertiajs/vue3';

const props = defineProps({
    href: String,
    active: Boolean,
});

const classes = computed(() => {
    let classes = 'flex items-center gap-x-3.5 py-2 px-2.5  text-sm text-slate-700 rounded-lg hover:bg-gray-100 dark:focus:outline-none dark:focus:ring-1 dark:focus:ring-gray-600'
    return props.active
        ? classes + ' bg-gray-100 dark:bg-gray-900 dark:text-white'
        : classes + ' dark:hover:bg-gray-900 dark:text-slate-400 dark:hover:text-slate-300'

});
</script>

<template>
    <Link draggable="false" :href="href" :class="classes">
        <slot />
    </Link>
</template>
