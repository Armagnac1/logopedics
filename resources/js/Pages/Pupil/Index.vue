<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import PupilTable from '@/Components/Pupil/PupilTable.vue';
import TopBarLayout from '@/Layouts/TopBarLayout.vue';

const props = defineProps({
    pupils: Object,
    filters: Object
})
</script>

<template>
    <TopBarLayout title="Ученики">
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                Ученики
            </h2>
        </template>
        <PupilTable :pupils="pupils" :filters="filters"/>
    </TopBarLayout>
</template>
