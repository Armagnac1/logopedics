<script setup>
import Paginator from '@/Components/Paginator.vue';
import PupilTableRow from '@/Components/Pupil/PupilTableRow.vue';
import { ref, watch } from 'vue';
import { Link, router } from '@inertiajs/vue3'
import throttle from 'lodash/throttle'
import PrimaryButton from '@/Components/PrimaryButton.vue';
import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome';
import { checkHasOneOfRoles } from '@/helpers.js';

const props = defineProps({
    pupils: Object,
    filters: Object
})


let search = ref(props.filters.search)

watch(search, throttle(value => {
    router.get(route('pupil.index'), { search: value },
        {
            preserveState: true,
            replace: true
        })
}, 700))
</script>

<template>
    <div
        class="bg-white border border-gray-200 rounded-xl shadow-sm overflow-x-auto dark:bg-slate-900 dark:border-gray-700">
        <!-- Header -->
        <div
            class="px-6 py-4 grid gap-3 md:flex md:justify-between md:items-center border-b border-gray-200 dark:border-gray-700">
            <div>
                <div class="relative">
                    <div
                        class="absolute inset-y-0 start-0 flex items-center pointer-events-none z-20 ps-4">
                        <font-awesome-icon icon="fa-solid fa-search"/>
                    </div>
                    <input type="text" id="icon" name="icon" v-model="search"
                           class="py-2 px-4 ps-11 block w-full border-gray-200 rounded-lg text-sm focus:border-blue-500 focus:ring-blue-500 disabled:opacity-50 disabled:pointer-events-none dark:bg-slate-900 dark:border-gray-700 dark:text-gray-400 dark:focus:ring-gray-600"
                           :placeholder="$t('common.search')">
                </div>
            </div>

            <div v-if="checkHasOneOfRoles(['superadmin','admin', 'tutor-seller'])">
                <div class="inline-flex gap-x-2">
                    <Link draggable="false" :href="route('pupil.create')">
                        <PrimaryButton class="py-2 px-3 inline-flex items-center gap-x-2">
                            <font-awesome-icon icon="fa-solid fa-plus"/>
                            {{ $t('common.add_student') }}
                        </PrimaryButton>
                    </Link>
                </div>
            </div>
        </div>
        <!-- End Header -->

        <!-- Table -->
        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
            <thead class="bg-gray-50 dark:bg-slate-800">
            <tr>
                <th scope="col" class="ps-6 text-start">
                    <div class="flex items-center gap-x-2">
                    <span class="text-xs font-semibold uppercase tracking-wide text-gray-800 dark:text-gray-200">
                      {{ $t('common.name') }}
                    </span>
                    </div>
                </th>

                <th scope="col" class="px-6 py-3 text-start">
                    <div class="flex items-center gap-x-2">
                    <span class="text-xs font-semibold uppercase tracking-wide text-gray-800 dark:text-gray-200">
                      {{ $t('common.lessons_conducted_total') }}
                    </span>
                    </div>
                </th>

                <th scope="col" class="px-6 py-3 text-start">
                    <div class="flex items-center gap-x-2">
                    <span class="text-xs font-semibold uppercase tracking-wide text-gray-800 dark:text-gray-200">
                      {{ $t('common.next_lesson') }}
                    </span>
                    </div>
                </th>

                <th scope="col" class="px-6 py-3 text-end"></th>
            </tr>
            </thead>

            <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
            <PupilTableRow v-for="pupil in pupils.data" :key="pupil.id" :pupil="pupil"/>
            </tbody>
        </table>
        <!-- End Table -->

        <!-- Footer -->
        <div
            class="px-6 py-4 grid gap-3 md:flex md:justify-between md:items-center border-t border-gray-200 dark:border-gray-700">
            <div>
                <p class="text-sm text-gray-600 dark:text-gray-400">
                    <span class="font-semibold text-gray-800 dark:text-gray-200">{{ pupils.meta.total }}</span>
                    {{ $t('common.results') }}
                </p>
            </div>

            <div>
                <Paginator :links="pupils.meta.links"></Paginator>
            </div>
        </div>
        <!-- End Footer -->
    </div>
</template>
