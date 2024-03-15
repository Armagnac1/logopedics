<script setup>
import { Link, router } from "@inertiajs/vue3";
import SecondaryButton from '@/Components/SecondaryButton.vue';

const props = defineProps({
    material: Object
})

const deleteMaterial = () => {
    axios.delete(route('lesson_learning_material.destroy', props.material.pivot.id))
        .then(() => {
            router.reload({ preserveScroll: true })
        })
}

</script>

<template>

    <div
        class="group py-5 relative flex items-center bg-white rounded-xl gap-4 dark:bg-neutral-800 dark:border-neutral-700">
        <div class="grow truncate">
            <Link :href="route('learning_material.show', material.id)">
                <p class="block truncate leading-6 text-sm font-semibold dark:text-neutral-200">
                    {{ material.title }}
                </p>
                <div class="space-x-1">
                    <ul class="text-sm text-gray-500">
                        <li class="inline-block relative pe-8 text-sm last:pe-0 last-of-type:before:hidden before:absolute before:top-1/2 before:end-3 before:-translate-y-1/2 before:size-1 before:bg-gray-300 before:rounded-full dark:text-neutral-400 dark:before:bg-neutral-600"
                            v-for="tag in material.tags" :key="tag.id">
                            {{ tag.name.ru }}
                        </li>
                    </ul>

                </div>
            </Link>
        </div>
        <div class="flex">
            <SecondaryButton class="hidden group-hover:block"
                             @click="deleteMaterial">
                Убрать
            </SecondaryButton>
        </div>
    </div>

</template>
