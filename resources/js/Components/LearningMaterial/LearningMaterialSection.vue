<script setup>
import LearningMaterialItem from '@/Components/LearningMaterial/LearningMaterialItem.vue';

defineProps({
    materials: Object
})
</script>

<template>
    <div>
        <h1>Использованные материалы</h1>
        <div>
            <LearningMaterialItem class="mb-3" v-for="material in materials"
                                  :key="material.id"
                                  :material="material"/>
        </div>
    </div>
</template>
