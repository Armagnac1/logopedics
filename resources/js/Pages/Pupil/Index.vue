<script setup lang="ts">
import { defineProps } from 'vue';
import PupilTable from '@/Components/Pupil/PupilTable.vue';
import TopBarLayout from '@/Layouts/TopBarLayout.vue';

// Define the types for the props
interface Pupil {
    id: number;
    full_name: string;
    lesson_finished_count: number;
    lesson_count: number;
    next_lesson?: {
        id: number;
        start_at: string;
    };
}

interface Filters {
    search: string;
}

// Use TypeScript to define the props
const props = defineProps<{
    pupils: {
        data: Pupil[];
        meta: {
            total: number;
            links: any[];
        };
    };
    filters: Filters;
}>();
</script>

<template>
    <TopBarLayout :title="$t('common.pupils')">
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ $t('common.pupils') }}
            </h2>
        </template>
        <PupilTable :pupils="pupils" :filters="filters"/>
    </TopBarLayout>
</template>
