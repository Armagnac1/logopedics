<script setup>
defineProps({
    type: {
        type: String,
        default: 'submit',
    },
});
</script>

<template>
    <button draggable="false" :type="type" class="inline-flex items-center px-4 py-2 bg-indigo-600 dark:bg-gray-200 border border-transparent rounded-md font-semibold text-xs text-white dark:text-gray-800 uppercase tracking-widest hover:bg-indigo-500 dark:hover:bg-white focus:bg-indigo-500 dark:focus:bg-white active:bg-indigo-900 dark:active:bg-indigo-300 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offindigo-800 transition ease-in-out duration-150">
        <slot />
    </button>
</template>
