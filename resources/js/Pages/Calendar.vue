<script setup lang="ts">
import { computed } from 'vue';
import LargeCalendar from '@/Components/LargeCalendar.vue';
import Card from '@/Components/Card.vue';
import TopBarLayout from '@/Layouts/TopBarLayout.vue';
import { dayjs } from '@/translation.config.js';
import { defineProps } from 'vue';

interface Lesson {
  pupil: {
    full_name: string;
    tags: { name: { ru: string } }[];
  };
  start_at: string;
  duration: number;
  id: number;
}

const props = defineProps<{ lessons: Lesson[] }>();
const { lessons } = props;

const color: Record<string, string> = {
  'Частный': '#F51114',
  'Белая Цапля': '#3EBE25',
  'Школа': '#7BD3EA'
};

const events = computed(() => props.lessons.map((lesson, key) => {
  var tag = lesson.pupil.tags[0]?.name.ru;

  return {
    title: lesson.pupil.full_name,
    start: lesson.start_at,
    end: dayjs(lesson.start_at).add(lesson.duration, 'minutes').toDate(),
    url: route('lesson.show', lesson.id),
    color: color[tag]
  };
}));

</script>

<template>
  <TopBarLayout :title="$t('common.calendar')">
    <template #header>
      <h2 class="font-semibold text-xl dark:text-gray-200 leading-tight">
        {{ $t("common.calendar") }}
      </h2>
    </template>
    <Card>
      <LargeCalendar :events="events"></LargeCalendar>
    </Card>
  </TopBarLayout>
</template>
