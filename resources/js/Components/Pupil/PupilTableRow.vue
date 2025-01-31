<script setup>

import { Link } from '@inertiajs/vue3';
import { dayjs } from '@/translation.config.js';

const props = defineProps({
    pupil: Object
})
</script>

<template>
    <tr>
        <!--        <td class="size-px whitespace-nowrap">
                    <div class="ps-6 py-3">
                        <label for="hs-at-with-checkboxes-1" class="flex">
                            <input type="checkbox"
                                   class="shrink-0 border-gray-300 rounded text-blue-600 focus:ring-blue-500 disabled:opacity-50 disabled:pointer-events-none dark:bg-slate-900 dark:border-gray-600 dark:checked:bg-blue-500 dark:checked:border-blue-500 dark:focus:ring-offset-gray-800"
                                   id="hs-at-with-checkboxes-1">
                            <span class="sr-only">Checkbox</span>
                        </label>
                    </div>
                </td>-->
        <td class="size-px whitespace-nowrap">
            <div class="px-6 py-3">
                <div class="flex items-center gap-x-3">
<!--                    <img :src="pupil.profile_photo_path" alt="Аватар" class="inline-block size-[38px] rounded-full">-->
                    <div class="grow">
                        <span class="block text-sm font-semibold text-gray-800 dark:text-gray-200">
                            <Link :href="route('pupil.show', pupil.id)" draggable="false">
                                {{ pupil.full_name }}
                            </Link>
                        </span>
                    </div>
                </div>
            </div>
        </td>
        <td class="size-px whitespace-nowrap">
            <div class="px-6 py-3">
                    <span
                        class="py-1 px-1.5 inline-flex items-center gap-x-1 text-xs font-medium bg-teal-100 text-teal-800 rounded-full dark:bg-teal-500/10 dark:text-teal-500">
                      <svg class="size-2.5" fill="currentColor" height="16" viewBox="0 0 16 16"
                           width="16" xmlns="http://www.w3.org/2000/svg">
                        <path
                            d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0zm-3.97-3.03a.75.75 0 0 0-1.08.022L7.477 9.417 5.384 7.323a.75.75 0 0 0-1.06 1.06L6.97 11.03a.75.75 0 0 0 1.079-.02l3.992-4.99a.75.75 0 0 0-.01-1.05z"/>
                      </svg>
                      Активный
                    </span>
            </div>
        </td>
        <td class="size-px whitespace-nowrap">
            <div class="px-6 py-3">
                <div class="flex items-center gap-x-3">
                    <span
                        class="text-xs text-gray-500">{{ pupil.lesson_finished_count }}/{{ pupil.lesson_count }}</span>
                    <div class="flex w-full h-1.5 bg-gray-200 rounded-full overflow-hidden dark:bg-gray-700">
                        <div :aria-valuenow="pupil.lesson_finished_count / pupil.lesson_count * 100"
                             :style="'width: ' + pupil.lesson_finished_count / pupil.lesson_count * 100 +'%'"
                             aria-valuemax="100"
                             aria-valuemin="0" class="flex flex-col justify-center overflow-hidden bg-gray-800 dark:bg-gray-200"
                             role="progressbar"></div>
                    </div>
                </div>
            </div>
        </td>
        <td class="size-px whitespace-nowrap">
            <div class="px-6 py-3">
                <span v-if="pupil.next_lesson"
                      class="text-sm text-gray-500">{{ dayjs(pupil.next_lesson.start_at).format('D MMM, LT') }}</span>
            </div>
        </td>
        <td class="size-px whitespace-nowrap">
            <div class="px-6 py-1.5">

                <Link
                    v-if="pupil.next_lesson"
                    :href="route('lesson.show', pupil.next_lesson.id)" class="inline-flex items-center gap-x-1 text-sm text-blue-600 decoration-2 hover:underline font-medium dark:focus:outline-none dark:focus:ring-1 dark:focus:ring-gray-600" draggable="false">
                    К занятию
                </Link>
                <Link
                    v-else
                    :href="route('lesson.create', pupil.id)" class="inline-flex items-center gap-x-1 text-sm text-blue-600 decoration-2 hover:underline font-medium dark:focus:outline-none dark:focus:ring-1 dark:focus:ring-gray-600" draggable="false">
                    Добавить занятие
                </Link>
            </div>
        </td>
    </tr>
</template>
