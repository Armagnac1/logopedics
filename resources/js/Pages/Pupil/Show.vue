<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import BackButton from '@/Components/BackButton.vue';
import Card from '@/Components/Card.vue';
import LearningMaterialItem from '@/Components/LearningMaterial/LearningMaterialItem.vue';


const props = defineProps({
    pupil: Object
})

</script>

<template>
    <AppLayout title="Calendar">
        <template #header>
            <h2 class="font-semibold text-xl L dark:text-gray-200 leading-tight">
                <BackButton/>
                {{ pupil.full_name }}
            </h2>
        </template>
        <div class="grid grid-cols-4 gap-4">
            <Card class="grid row-span-1 col-span-1 place-items-center">
                <h1 class="text-2xl font-bold">{{ pupil.full_name }}</h1>
            </Card>
            <Card class="row-span-1 col-span-3">

            </Card>
            <Card class="row-span-2 col-span-1">
                <h1 class="text-2xl font-bold">Использованные материалы</h1>
                <div v-for="material in pupil.lessons.map(i=>i.learning_materials).flat()" :key="material.id">
                    <LearningMaterialItem :material="material"/>
                </div>
            </Card>
            <Card class="col-span-3">
            </Card>
            <Card class="col-span-3">
            </Card>
        </div>
    </AppLayout>
</template>
