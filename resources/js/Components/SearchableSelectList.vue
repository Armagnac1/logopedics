<script setup>
import { computed, nextTick, ref, watch, onMounted } from 'vue';
import throttle from 'lodash/throttle.js';
import TextInput from '@/Components/TextInput.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';
import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome';

const props = defineProps({
    modelValue: Object,
    filters: {
        type: Object,
        default: () => ({})
    }
})

let selected = ref([])
let search = ref('')
let input = ref(null)
let page = ref(1)
let results = ref({ data: [], links: [] })
let getAISuggestions = ref(true)

onMounted(() => {
    getAISuggestions.value = true;
});

watch(search, throttle(() => {
    page.value = 1;
    getAISuggestions.value = false;
    loadResults();
}, 100));

watch(() => props.filters, () => {
    getAISuggestions.value = false;
}, { deep: true });

const loadResults = () => {
    axios.get(route('learning_material.index', { search: search.value, filters: props.filters, page: page.value, ai: getAISuggestions.value }))
        .then(response => {
            results.value = response.data.learning_materials;
        })
        .catch(error => {
            console.log(error)
        })
}

defineExpose({
    focus: () => {
        page.value = 1;
        loadResults();
        nextTick().then(() => {
            input.value.focus();
        })
    }
});

const emit = defineEmits(['update:modelValue'])

const materials = computed(() => {
    var selectedList = selected.value.map(item => {
        item.isAdded = true;
        return item;
    });
    var resultsList = results.value.data.filter(item => {
        return selected.value.findIndex(i => i.id === item.id) === -1;
    }).slice(0, 10 - selectedList.length);
    return selectedList.concat(resultsList);
})

function switchItem(material) {
    if (material.isAdded) {
        selected.value = selected.value.filter(item => item.id !== material.id)
    } else {
        selected.value.push(material)
    }
    material.isAdded = !material.isAdded
    emit('update:modelValue', selected)
}
</script>

<template>
    <TextInput
        ref="input"
        v-model="search"
        class="mt-1 block w-full"
        type="text"
    />
    <div class="">
        <div v-for="material in materials" :key="material.id"
             :class=" material.isAdded ? 'bg-indigo-500 text-white hover:bg-gray-800' : 'bg-white hover:bg-gray-100'"
             class="group cursor-pointer mb-1 px-3 relative flex items-center rounded gap-4 dark:bg-neutral-800 dark:border-neutral-700"
             @click="switchItem(material);">
            <div class="grow truncate">
                <p class="block truncate leading-1 text-sm font-semibold dark:text-neutral-200">
                    {{ material.title }}
                </p>
                <div class="space-x-1">
                    <ul
                        :class=" material.isAdded ? 'text-white' : 'text-gray-500'">
                        <li v-for="tag in material.tags"
                            :key="tag.id"
                            class="inline-block relative pe-8 text-sm last:pe-0 last-of-type:before:hidden before:absolute before:top-1/2 before:end-3 before:-translate-y-1/2 before:size-1 before:bg-gray-300 before:rounded-full dark:text-neutral-400 dark:before:bg-neutral-600">
                            {{ tag.name.ru }}
                        </li>
                    </ul>
                </div>
            </div>
            <div class="flex">
                <a v-if="material.media[0]" :href="material.media[0].original_url" target="_blank" class="ml-3">
                    <SecondaryButton class="hidden group-hover:block">
                        <FontAwesomeIcon icon="fa-solid fa-eye"/>
                    </SecondaryButton>
                </a>
                <a v-if="material.media[0]" :href="material.media[0].original_url" download>
                    <SecondaryButton class="hidden group-hover:block">
                        <FontAwesomeIcon icon="fa-solid fa-download"/>
                    </SecondaryButton>
                </a>
            </div>
        </div>
        <div class="mt-2">
            <a v-if="results.prev_page_url !== null"
               class="text-sm font-medium text-blue-600 decoration-2 hover:underline cursor-pointer"
               draggable="false"
               @click.prevent="page--; loadResults()">
                {{ $t('common.previous10') }}
            </a>
            <a v-if="results.next_page_url !== null"
               class="float-right text-sm font-medium text-blue-600 decoration-2 hover:underline cursor-pointer"
               draggable="false"
               @click.prevent="page++; loadResults()">
                {{ $t('common.next10') }}
            </a>
        </div>
        <div v-if="materials.length === 0" class="mt-1 text-center text-sm text-gray-500">
            {{ $t('common.nothingFound') }}
        </div>
    </div>
</template>
