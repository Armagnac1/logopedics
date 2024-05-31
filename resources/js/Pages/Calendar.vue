<script setup>
import LargeCalendar from '@/Components/LargeCalendar.vue';
import Card from '@/Components/Card.vue';
import TopBarLayout from '@/Layouts/TopBarLayout.vue';
import { dayjs } from '@/translation.config.js';

const props = defineProps({
    lessons: Array
})
const events = props.lessons.map((lesson, key) => {
    return {
        title: lesson.pupil.full_name,
        start: lesson.start_at,
        end: dayjs(lesson.start_at).add(lesson.duration, 'minutes').toDate(),
        url: route('lesson.show', lesson.id)
    }
})
</script>

<template>
    <TopBarLayout title="Календарь">
        <template #header>
            <h2 class="font-semibold text-xl L dark:text-gray-200 leading-tight">
                Календарь
            </h2>
        </template>
            <Card>
                <LargeCalendar :events="events"></LargeCalendar>
            </Card>
    </TopBarLayout>
</template>
