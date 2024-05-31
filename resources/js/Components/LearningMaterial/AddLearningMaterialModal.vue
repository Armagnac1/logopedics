<script setup>
import { nextTick, reactive, ref, watch } from 'vue';
import { useForm } from '@inertiajs/vue3';
import SecondaryButton from '@/Components/SecondaryButton.vue';
import InputError from '@/Components/InputError.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import Checkbox from '@/Components/Checkbox.vue';
import AdvancedSelect from '@/Components/AdvancedSelect.vue';
import SearchableSelectList from '@/Components/SearchableSelectList.vue';

const props = defineProps({
    lessonId: Number
})

const tagsOptions = ref([])
const onlyNotUsed = ref(true)
const filtersInitialState = { tags: [], onlyNotUsed: true, lessonId: props.lessonId}
const filters = reactive({...filtersInitialState})


let searchInput = ref(null)

const form = useForm({
    materials: [],
    lessonId: props.lessonId
})

const loadOptions = () => {
    axios.get(route('tags.index'))
        .then(response => {
            tagsOptions.value = response.data.tags.map((i) =>
                ({ name: i.name.ru, id: i.id }))
        })
}
const submit = () => {
    form.post(route('lesson_learning_material.store'), {
        preserveScroll: true,
        onSuccess: () => {
            form.reset()
            emit('close')
        }
    })
}

defineExpose({
    onOpen: () => {
        form.reset()
        Object.assign(filters, filtersInitialState)
        loadOptions()
        nextTick().then(() => {
            searchInput.value.focus()
        })
    }
});

watch(filters, () => {
    nextTick().then(() => {
        searchInput.value.focus()
    })
})
const emit = defineEmits(['close'])

</script>

<template>
    <form class="p-5" @submit.prevent="submit">
        <div class="py-1.5 pb-5 px-1 border-b border-gray-100 justify-between align-middle flex items-center">
            <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-200">Прикрепить материал</h3>
        </div>
        <div
            class="grid sm:grid-cols-12 gap-2 sm:gap-4 py-8 first:pt-0 last:pb-0 border-t first:border-transparent border-gray-200 dark:border-neutral-700 dark:first:border-transparent">
            <div class="sm:col-span-12">
                <h2 class="text-lg font-semibold text-gray-800 dark:text-neutral-200">
                    Фильтры
                </h2>
            </div>
            <div class="sm:col-span-4">
                <label class="inline-block text-sm font-medium text-gray-500 mt-2.5 dark:text-neutral-500"
                       for="tags">
                    Группа
                </label>
            </div>
            <div class="sm:col-span-8">
                <AdvancedSelect id="tags" v-model="filters.tags" :multiple="true" :options="tagsOptions"
                                label="name"
                                placeholder=""
                                track-by="id"/>
            </div>
            <div class="sm:col-span-4">
                <label class="inline-block text-sm font-medium text-gray-500 mt-1 dark:text-neutral-500"
                       for="onlyNotUsed">
                    Не использованные
                </label>
                <div class="hs-tooltip inline-block">
                    <button class="hs-tooltip-toggle ms-1" type="button">
                        <svg class="inline-block size-3 text-gray-400 dark:text-neutral-600"
                             fill="currentColor" height="16" viewBox="0 0 16 16" width="16"
                             xmlns="http://www.w3.org/2000/svg">
                            <path
                                d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14zm0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16z"></path>
                            <path
                                d="m8.93 6.588-2.29.287-.082.38.45.083c.294.07.352.176.288.469l-.738 3.468c-.194.897.105 1.319.808 1.319.545 0 1.178-.252 1.465-.598l.088-.416c-.2.176-.492.246-.686.246-.275 0-.375-.193-.304-.533L8.93 6.588zM9 4.5a1 1 0 1 1-2 0 1 1 0 0 1 2 0z"></path>
                        </svg>
                    </button>
                    <span
                        class="hs-tooltip-content hs-tooltip-shown:opacity-100 hs-tooltip-shown:visible opacity-0 transition-opacity inline-block absolute invisible w-40 text-center z-10 py-1 px-2 bg-gray-900 text-xs font-medium text-white rounded shadow-sm dark:bg-neutral-700 hidden"
                        data-popper-placement="top"
                        role="tooltip"
                        style="position: fixed; inset: auto auto 0px 0px; margin: 0px; transform: translate3d(49.5px, -656.5px, 0px);">
                                        Показывать только те материалы, которые ранее не использовались в уроках этого ученика
            </span>
                </div>
            </div>
            <div class="sm:col-span-8 content-center">
                <Checkbox id="onlyNotUsed" v-model="filters.onlyNotUsed" :checked="filters.onlyNotUsed"/>
            </div>
        </div>
        <div class="grid border-t py-8 gap-2 sm:gap-4">
            <h2 class="text-lg font-semibold text-gray-800 dark:text-neutral-200">
                Материал
            </h2>
            <SearchableSelectList ref="searchInput" v-model="form.materials"
                                  :filters="filters"/>
            <InputError :message="form.errors.materials" class="mt-2"/>

        </div>
        <div class="flex justify-end items-center gap-x-2 pt-4 border-t dark:border-gray-700">
            <SecondaryButton @click="$emit('close')">
                Отмена
            </SecondaryButton>
            <PrimaryButton :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                Добавить
            </PrimaryButton>
        </div>
    </form>
</template>
