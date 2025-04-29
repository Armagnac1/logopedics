<script setup>
import LargeCalendar from '@/Components/LargeCalendar.vue';
import Card from '@/Components/Card.vue';
import TopBarLayout from '@/Layouts/TopBarLayout.vue';
import { dayjs } from '@/translation.config.js';

const props = defineProps({
    lessons: Array
});

const color = {
    'Частный': '#F51114',
    'Белая Цапля': '#3EBE25',
    'Школа': '#7BD3EA'
};
const events = props.lessons.map((lesson, key) => {
    var tag = lesson.pupil.tags[0]?.name.ru;

    return {
        title: lesson.pupil.full_name,
        start: lesson.start_at,
        end: dayjs(lesson.start_at).add(lesson.duration, 'minutes').toDate(),
        url: route('lesson.show', lesson.id),
        color: color[tag]
    };
});

</script>

<template>
    <TopBarLayout :title="$t('common.calendar')">
        <template #header>
            <h2 class="font-semibold text-xl L dark:text-gray-200 leading-tight">
                {{ $t("common.calendar") }}
            </h2>
        </template>
        <Card>
            <LargeCalendar :events="events"></LargeCalendar>
        </Card>
    </TopBarLayout>
</template>
