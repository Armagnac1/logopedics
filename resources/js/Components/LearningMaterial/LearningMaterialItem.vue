<script setup>
import { Link } from "@inertiajs/vue3";
import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome';
import Badge from '@/Components/Badge.vue';

defineProps({
    material: Object
})
</script>

<template>

    <Link :href="route('learning_material.show', material.id)">
        <div
            class="mb-3 p-3 relative border flex items-center bg-white rounded-xl border-gray-200 space-x-4 dark:bg-neutral-800 dark:border-neutral-700">
            <!-- Icon -->
            <span
                class="flex items-center bg-white w-12 h-12 border border-gray-200 rounded-lg justify-center dark:bg-neutral-800 dark:border-neutral-700 dark:text-neutral-300">
            <font-awesome-icon icon="fa-solid fa-book"/>
                          </span>
            <!-- End Icon -->

            <div class="grow truncate">
                <p class="block truncate dark:text-neutral-200">
                    {{ material.title }}

                </p>
                <div class="space-x-1">
                    <Badge class="" v-for="tag in material.tags" :key="tag.id" :value="tag.name.ru"/>
                </div>
            </div>
        </div>
    </Link>

</template>
