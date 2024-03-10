<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import LargeCalendar from '@/Components/LargeCalendar.vue';
import Card from '@/Components/Card.vue';

const props = defineProps({
    lessons: Array
})
const events = props.lessons.map((lesson, key) => {
    return {
        title: lesson.title,
        date: lesson.start_at,
        url: route('lesson.show', lesson.id)
    }
})
</script>

<template>
    <AppLayout title="Calendar">
        <template #header>
            <h2 class="font-semibold text-xl L dark:text-gray-200 leading-tight">
                Calendar
            </h2>
        </template>
            <Card class="place-items-center">
                <LargeCalendar :events="events"></LargeCalendar>
            </Card>
    </AppLayout>
</template>
