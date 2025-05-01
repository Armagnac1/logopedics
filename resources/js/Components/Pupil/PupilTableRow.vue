<script setup>
import { Link } from '@inertiajs/vue3';
import { dayjs } from '@/translation.config.ts';

const props = defineProps({
    pupil: Object
});
</script>

<template>
    <tr>
        <td class="size-px whitespace-nowrap">
            <div class="px-6 py-3">
                <div class="flex items-center gap-x-3">
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
                <div class="flex items-center gap-x-3">
                    <span class="text-xs text-gray-500">
                        {{ pupil.lesson_finished_count }}/{{ pupil.lesson_count }}
                    </span>
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
                    {{ $t('common.toLesson') }}
                </Link>
                <Link
                    v-else
                    :href="route('lesson.create', pupil.id)" class="inline-flex items-center gap-x-1 text-sm text-blue-600 decoration-2 hover:underline font-medium dark:focus:outline-none dark:focus:ring-1 dark:focus:ring-gray-600" draggable="false">
                    {{ $t('common.addLesson') }}
                </Link>
            </div>
        </td>
    </tr>
</template>
