<script setup>
import ConfirmationModal from '@/Components/ConfirmationModal.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';
import { router, useForm } from '@inertiajs/vue3';
import { ref } from 'vue';
import DangerButton from '@/Components/DangerButton.vue';

const props = defineProps({
    entityName: String,
    url: String
});

const form = useForm({})

var confirmingUserDeletion = ref(false)

const submit = () => {
    form.delete(props.url, {
        onSuccess: () => {
        }
    })
}

</script>

<template>
    <div>
        <DangerButton @click="confirmingUserDeletion = true">Удалить {{ entityName }}</DangerButton>
        <ConfirmationModal :show="confirmingUserDeletion" @close="confirmingUserDeletion = false">
            <template #title>
                Удалить {{ entityName }}
            </template>

            <template #content>
                Вы уверены что хотите удалить {{ entityName }}?
            </template>

            <template #footer>
                <SecondaryButton @click.native="confirmingUserDeletion = false">
                    Отмена
                </SecondaryButton>

                <DangerButton :class="{ 'opacity-25': form.processing }" :disabled="form.processing" class="ml-2"
                              @click.native="submit">
                    Удалить {{ entityName }}
                </DangerButton>
            </template>
        </ConfirmationModal>
    </div>
</template>
