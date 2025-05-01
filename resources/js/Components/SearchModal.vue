<script setup>
import { nextTick, ref, watch } from 'vue';
import { dayjs } from '../translation.config.ts';
import throttle from 'lodash/throttle.js';
import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome';

const props = defineProps({
    default: {
        pupils: [],
        learningMaterials: [],
        lessons: []
    }
});

let search = ref('');
let input = ref(null);

watch(search, throttle(value => {
    loadResults(value);
}, 100));

let results = ref({
    pupils: [],
    learningMaterials: [],
    lessons: []
});

const loadResults = (input) => {
    axios.get(route('search', { search: input }))
        .then(response => {
            results.value = response.data.result;
        })
        .catch(error => {
            console.log(error);
        });
};

defineExpose({
    focus: () => {
        loadResults('');
        nextTick().then(() => {
            input.value.focus();
        });
    }
});
</script>

<template>
    <div class="search-modal">
        <!-- Input -->
        <div class="relative">
            <div class="absolute inset-y-0 flex items-center ps-3.5">
                <svg class="flex-shrink-0" fill="none" height="24" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" viewBox="0 0 24 24" width="24">
                    <circle cx="11" cy="11" r="8"></circle>
                    <path d="m21 21-4.3-4.3"></path>
                </svg>
            </div>
            <input ref="input"
                   v-model="search"
                   class="p-3 ps-10 block w-full bg-white border-transparent border-b-gray-200 text-sm focus:outline-none focus:ring-0 focus:border-transparent focus:border-b-gray-200 kko9e dark:bg-neutral-800 dark:border-transparent dark:border-b-neutral-700 dark:text-neutral-400 dark:placeholder:text-neutral-400"
                   :placeholder="$t('common.searchPlaceholder')" type="text">
        </div>
        <!-- End Input -->

        <div v-if="results.pupils && results.pupils.length > 0" class="pb-4">
            <span class="block text-gray-400">{{ $t('common.pupils') }}</span>
            <!-- List Group -->
            <ul class="-mx-2.5">
                <li v-for="pupil in results.pupils" :key="pupil.id" class="hover:bg-indigo-600 focus:bg-indigo-600 hover:text-white focus:text-white">
                    <a :href="route('pupil.show', pupil.id)" class="py-2 px-3 flex items-center gap-x-3">
                        {{ pupil.full_name }}
                    </a>
                </li>
            </ul>
            <!-- End List Group -->
        </div>

        <div v-if="results.lessons && results.lessons.length > 0" class="pb-4">
            <span class="block text-gray-400">{{ $t('common.lessons') }}</span>
            <!-- List Group -->
            <ul class="-mx-2.5">
                <li v-for="lesson in results.lessons" :key="lesson.id" class="hover:bg-indigo-600 focus:bg-indigo-600 hover:text-white focus:text-white">
                    <a :href="route('lesson.show', lesson.id)" class="py-2 px-3 flex items-center gap-x-2">
                        <span class="flex items-center bg-white w-8 h-8 border border-gray-200 rounded-lg justify-center">
                            <font-awesome-icon icon="fa-regular fa-file-lines"/>
                        </span>
                        <div class="text-sm">
                            <span v-if="lesson.title">{{ lesson.title }}</span>
                            <span v-else class="text-gray-400 font-normal italic">{{ $t('common.noData') }}</span>
                        </div>
                        {{ dayjs(lesson.starts_at).format('DD.MM.YYYY HH:mm') }}
                    </a>
                </li>
            </ul>
            <!-- End List Group -->
        </div>

        <div v-if="results.learningMaterials && results.learningMaterials.length > 0" class="pb-4">
            <span class="block text-gray-400">{{ $t('common.learningMaterials') }}</span>
            <!-- List Group -->
            <ul class="-mx-2.5">
                <li v-for="learningMaterial in results.learningMaterials" :key="learningMaterial.id" class="hover:bg-indigo-600 focus:bg-indigo-600 hover:text-white focus:text-white">
                    <a :href="route('learning_material.show', learningMaterial.id)" class="py-2 px-3 flex items-center gap-x-3">
                        <span class="flex items-center bg-white w-8 h-8 border border-gray-200 rounded-lg justify-center">
                            <font-awesome-icon icon="fa-regular fa-lightbulb"/>
                        </span>
                        <span class="text-sm line-clamp-1">
                            {{ learningMaterial.title }}
                        </span>
                    </a>
                </li>
            </ul>
            <!-- End List Group -->
        </div>

        <div v-if="!results.pupils.length && !results.lessons.length && !results.learningMaterials.length" class="text-center text-sm text-gray-400">
            {{ $t('common.noData') }}
        </div>
    </div>
</template>
