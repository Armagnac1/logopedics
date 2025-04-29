<script setup>
import LearningMaterialItem from '@/Components/LearningMaterial/LearningMaterialItem.vue';
import Modal from '@/Components/Modal.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import AddLearningMaterialModal from '@/Components/LearningMaterial/AddLearningMaterialModal.vue';
import { ref } from 'vue';

const props = defineProps({
    usedMaterials: Object,
    lessonId: Number
})

const showModal = ref(false)
const modal = ref(null)

</script>

<template>
    <div>
        <div class="py-1.5 pb-5 px-1 border-b border-gray-100 justify-between align-middle flex items-center">
            <h3 class="text-xl font-semibold text-gray-800 dark:text-gray-200">{{ $t('common.materials') }}</h3>
            <PrimaryButton class="" @click="showModal = true; modal.onOpen()">
                {{ $t('common.add') }}
            </PrimaryButton>
        </div>
        <div class="divide-y divide-gray-100">
            <LearningMaterialItem v-for="material in usedMaterials" :key="material.id"
                                  :material="material"/>
        </div>
        <Modal :show="showModal" @close="showModal = false">
            <AddLearningMaterialModal ref="modal" :lessonId="lessonId" @close="showModal = false"/>
        </Modal>
    </div>
</template>
