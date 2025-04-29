<script setup>
import { ref } from 'vue';

const input = ref('');


</script>
<template>
    <div>
        <label for="icon" class="sr-only">{{ $t('common.search')}}</label>
        <div class="relative w-96">
            <div class="absolute inset-y-0 start-0 flex items-center pointer-events-none z-20 ps-4">
                <font-awesome-icon icon="fa-solid fa-search"/>
            </div>
            <input type="text" id="icon" name="icon"
                   class="py-2 px-4 ps-11 block w-full border-gray-200 rounded-lg text-sm focus:border-blue-500 focus:ring-blue-500 disabled:opacity-50 disabled:pointer-events-none dark:bg-slate-900 dark:border-gray-700 dark:text-gray-400 dark:focus:ring-gray-600"
                   :placeholder="$t('common.search')"
                   autocomplete="off"
            >
        </div>
    </div>
</template>
