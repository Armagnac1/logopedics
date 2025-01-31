<script setup>
import LargeCalendar from '@/Components/LargeCalendar.vue';
import Card from '@/Components/Card.vue';
import TopBarLayout from '@/Layouts/TopBarLayout.vue';
import { dayjs } from '@/translation.config.js';
import SecondaryButton from '@/Components/SecondaryButton.vue';
import { usePage } from '@inertiajs/vue3';

const props = defineProps({
    lessons: Array
})

const page = usePage()
const color = {
    'Частный': '#F51114',
    'Белая Цапля': '#3EBE25',
    'Школа': '#7BD3EA'
}
const events = props.lessons.map((lesson, key) => {

    var tag = lesson.pupil.tags[0]?.name.ru

    return {
        title: lesson.pupil.full_name,
        start: lesson.start_at,
        end: dayjs(lesson.start_at).add(lesson.duration, 'minutes').toDate(),
        url: route('lesson.show', lesson.id),
        color: color[tag]
    };
});
const calendarLink = `${window.location.origin}/calendar/generate_calendar/${page.props.auth.user.id}`;

const copyToClipboard = () => {
    const calendarLink = `${window.location.origin}/calendar/generate_calendar/${page.props.auth.user.id}`;
    navigator.clipboard.writeText(calendarLink).then(() => {
        alert('Ссылка на календарь скопирована в буфер обмена!');
    });
};
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
            <input
                type="text"
                :value="calendarLink"
                @click="copyToClipboard"
                readonly
                class="cursor-pointer border rounded p-2"
            />
        </Card>
    </TopBarLayout>
</template>
