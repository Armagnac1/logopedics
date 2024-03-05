<script setup>
import { useForm } from '@inertiajs/vue3';
import Textarea from '@/Components/Textarea.vue';
import InputError from '@/Components/InputError.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';

const props = defineProps({
    lesson: Object
})

const commentsForm = useForm({
    tutor_comments: props.lesson.tutor_comments,
    homework_comments: props.lesson.homework_comments
})

const submit = () => {
    commentsForm.put(route('lesson.update', props.lesson.id))
}
</script>
<template>
    <form class="space-y-5 " @submit.prevent="submit">
        <div>
            <Textarea
                :label="'Комментарии к домашнему заданию'"
                :rows="9"
                v-model="commentsForm.homework_comments"
            />
            <InputError class="mt-2" :message="commentsForm.errors.homework_comments"/>
        </div>
        <div>
            <Textarea
                :label="'Комментарии учителя'"
                :rows="9"
                v-model="commentsForm.tutor_comments"
            />
            <InputError class="mt-2" :message="commentsForm.errors.tutor_comments"/>
        </div>
        <div class="flex justify-end items-center gap-x-2 pt-4 sm:px-7 border-t dark:border-gray-700">
            <PrimaryButton class="ms-4" :class="{ 'opacity-25': commentsForm.processing }"
                           :disabled="commentsForm.processing">
                Сохранить
            </PrimaryButton>
        </div>
    </form>
</template>
