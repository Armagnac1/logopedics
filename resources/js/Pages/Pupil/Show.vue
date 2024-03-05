<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import { dayjs } from '@/translation.config.js';
import { Link } from '@inertiajs/vue3';
import BackButton from '@/Components/BackButton.vue';



defineProps({
    pupil: Object
})
</script>

<template>
    <AppLayout :title="pupil.full_name">
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                <BackButton/>
                {{ pupil.full_name }}
            </h2>
        </template>
        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white light:bg-gray-800 overflow-hidden shadow-xl sm:rounded-lg">
                    <div v-for="lesson in pupil.lessons" key="lesson.id">
                        <Link :href="route('lesson.show', lesson.id)">
                            {{ dayjs(lesson.start_at).format('LLL') }} - {{ lesson.title }}
                        </Link>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
