<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import BackButton from '@/Components/BackButton.vue';
import LearningMaterialSection from '@/Components/LearningMaterial/LearningMaterialSection.vue';
import LessonTimelineSection from '@/Components/Lesson/LessonTimelineSection.vue';
import Card from '@/Components/Card.vue';
import LessonTitleCard from '@/Components/Lesson/LessonTitleCard.vue';
import LessonCommentsSection from '@/Components/Lesson/LessonCommentsSection.vue';

const props = defineProps({
    pupilLessons: Array,
    lesson: Object
})

</script>

<template>
    <AppLayout :title="lesson.title">
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                <BackButton/>
                Урок
            </h2>
        </template>

        <div class="grid grid-cols-4 gap-4">
            <Card class="grid row-span-1 col-span-1 place-items-center">
                <h1 class="text-2xl font-bold">{{ lesson.pupil.full_name }}</h1>
            </Card>
            <Card class="row-span-1 col-span-3">
                <LessonTitleCard :lesson="lesson"/>
            </Card>
            <Card class="row-span-2 col-span-1">
                <LessonTimelineSection :pupilLessons="pupilLessons" :currentLessonId="lesson.id"/>
            </Card>
            <Card class="col-span-3">
                <LessonCommentsSection :lesson="lesson"/>
            </Card>
            <Card class="col-span-3">
                <LearningMaterialSection :materials="lesson.learning_materials"/>
            </Card>
        </div>
    </AppLayout>
</template>
