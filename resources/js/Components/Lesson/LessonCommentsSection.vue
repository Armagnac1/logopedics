<script setup>
import { useForm } from '@inertiajs/vue3';
import Textarea from '@/Components/Textarea.vue';
import InputError from '@/Components/InputError.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';

const props = defineProps({
    lesson: Object
});

const commentsForm = useForm({
    tutor_comments: props.lesson.tutor_comments,
    homework_comments: props.lesson.homework_comments
});
</script>

<template>
    <form class="space-y-5" @submit.prevent="submit">
        <div>
            <Textarea
                v-model="commentsForm.homework_comments"
                :label="$t('common.homeworkComments')"
                :rows="9"
            />
            <InputError :message="commentsForm.errors.homework_comments" class="mt-2"/>
        </div>
        <div>
            <Textarea
                v-model="commentsForm.tutor_comments"
                :label="$t('common.tutorComments')"
                :rows="9"
            />
            <InputError :message="commentsForm.errors.tutor_comments" class="mt-2"/>
        </div>
        <div class="flex justify-end items-center gap-x-2 pt-4 sm:px-7 border-t dark:border-gray-700">
            <PrimaryButton :class="{ 'opacity-25': commentsForm.processing }" :disabled="commentsForm.processing"
                           class="ms-4">
                {{ $t('common.save') }}
            </PrimaryButton>
        </div>
    </form>
</template>
