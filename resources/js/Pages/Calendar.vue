<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import LargeCalendar from '@/Components/LargeCalendar.vue';

const props = defineProps({
    lessons: Array
})
console.log(props.lessons);
const events = props.lessons.map((lesson, key) => {
    return {
        title: lesson.title, date: lesson.start_at
    }
})
console.log(events);
</script>

<template>
    <AppLayout title="Calendar">
        <template #header>
            <h2 class="font-semibold text-xl L dark:text-gray-200 leading-tight">
                Calendar
            </h2>
        </template>
        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white light:bg-gray-800 overflow-hidden shadow-xl sm:rounded-lg">
                    <LargeCalendar :events="events"></LargeCalendar>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
