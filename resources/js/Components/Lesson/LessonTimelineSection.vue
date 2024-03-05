<script setup>

import SmallLessonCard from '@/Components/Lesson/SmallLessonCard.vue';

defineProps({
    pupilLessons: Object,
    currentLessonId: Number
})
</script>

<template>
    <div class="space-y-2">
        <SmallLessonCard :active="currentLessonId === lesson.id" v-for="lesson in pupilLessons" :key="lesson.id" :lesson="lesson"/>
    </div>

</template>
