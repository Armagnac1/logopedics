<script setup>
import EditableLabel from '@/Components/EditableLabel.vue';
import EditableTimepicker from '@/Components/EditableTimepicker.vue';
import { dayjs } from '../../translation.config.ts';

const props = defineProps({
    lesson: Object,
    editable: Boolean
});
</script>

<template>
    <EditableLabel v-if="editable" v-model="lesson.title" :route_url="route('lesson.update', lesson.id)" field="title"/>
    <div v-else>
        <span v-if="lesson.title">{{ lesson.title }}</span>
        <span v-else class="text-gray-400 font-normal italic">{{ $t('common.untitled') }}</span>
    </div>
    <p class="block truncate text-gray-400 dark:text-neutral-500">
        <EditableTimepicker v-if="editable" v-model="lesson.start_at" :route_url="route('lesson.update', lesson.id)" field="start_at"/>
        <span v-else>{{ lesson.start_at ? dayjs(lesson.start_at).format('D MMM, LT') : $t('common.notScheduled') }}</span>
    </p>
</template>
