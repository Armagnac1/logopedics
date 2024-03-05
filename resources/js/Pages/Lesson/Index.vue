<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import { Link } from "@inertiajs/vue3";
import Paginator from '@/Components/Paginator.vue';
import Card from '@/Components/Card.vue';

defineProps({
    lessons: Object
})
</script>

<template>
    <AppLayout title="Уроки">
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                Уроки
            </h2>
        </template>

        <Card>
            <div v-for="lesson in lessons.data" :key="lesson.id">
                <Link :href="route('lesson.show', lesson.id)">{{ lesson.title }} - {{ lesson.pupil.first_name }}</Link>
            </div>
            <Paginator :links="lessons.links"></Paginator>
        </Card>

    </AppLayout>
</template>
