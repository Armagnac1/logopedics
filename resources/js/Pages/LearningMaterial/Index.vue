<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import { Link } from "@inertiajs/vue3";
import Paginator from '@/Components/Paginator.vue';

defineProps({
    pupils: Object
})
</script>

<template>
    <AppLayout title="Calendar">
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                Ученики
            </h2>
        </template>
        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white light:bg-gray-800 overflow-hidden shadow-xl sm:rounded-lg">
                    <div v-for="pupil in pupils.data" :key="pupil.id">
                        <Link :href="route('pupil.show', pupil.id)">{{ pupil.full_name }}</Link>
                    </div>
                    <Paginator :links="pupils.links"></Paginator>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
