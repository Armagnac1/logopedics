<script setup>
import BackButton from '@/Components/BackButton.vue';
import LearningMaterialSection from '@/Components/LearningMaterial/LearningMaterialSection.vue';
import Card from '@/Components/Card.vue';
import LessonTitleCard from '@/Components/Lesson/LessonTitleCard.vue';
import LessonCommentsSection from '@/Components/Lesson/LessonCommentsSection.vue';
import { Link } from "@inertiajs/vue3";
import TopBarLayout from '@/Layouts/TopBarLayout.vue';
import PupilSmallCard from '@/Components/Pupil/PupilSmallCard.vue';
import DeleteEntityButton from '@/Components/DeleteEntityButton.vue';

const props = defineProps({
    previousLesson: Object,
    nextLesson: Object,
    pupilLessons: Array,
    lesson: Object
})

</script>

<template>
    <TopBarLayout :title="lesson.title">
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                <BackButton/>
                Урок
            </h2>
        </template>

        <div class="grid gap-4 grid-cols-4">
            <Card class="col-span-4">
                <PupilSmallCard :pupil="lesson.pupil"/>
            </Card>

            <Link v-if="previousLesson" :href="route('lesson.show', previousLesson.id)">
                <Card class="col-span-1">
                    <LessonTitleCard :lesson="previousLesson"/>
                </Card>
            </Link>
            <Card v-else class="col-span-1"/>
            <Card class="col-span-2 bg-gray">
                <LessonTitleCard :lesson="lesson" :editable="true"/>
            </Card>

            <Link v-if="nextLesson" :href="route('lesson.show', nextLesson.id)">
                <Card class="col-span-1">
                    <LessonTitleCard :lesson="nextLesson"/>
                </Card>
            </Link>
            <Card v-else class="col-span-1"/>
            <Card class="col-span-4 xl:col-span-2">
                <LearningMaterialSection :lessonId="lesson.id" :usedMaterials="lesson.learning_materials"/>
            </Card>
            <Card class="col-span-4 xl:col-span-2">
                <LessonCommentsSection :lesson="lesson"/>
            </Card>
            <Card class="col-span-4 xl:col-span-4">
                <DeleteEntityButton class="float-right" :entityName="'урок'" :url="route('lesson.destroy', lesson.id)"></DeleteEntityButton>
            </Card>
        </div>

    </TopBarLayout>
</template>
