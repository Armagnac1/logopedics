<script setup>
import NavLink from '@/Components/NavLink.vue';
import ApplicationMark from '@/Components/ApplicationMark.vue';
import { Link, } from '@inertiajs/vue3';
import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome';
import {checkHasOneOfRoles} from '@/helpers.js';


</script>


<template>
    <div id="application-sidebar"
         class="hs-overlay hs-overlay-open:translate-x-0 -translate-x-full transition-all duration-300 transform hidden fixed top-0 start-0 bottom-0 z-[60] w-64 bg-white border-e border-gray-200 pt-7 pb-10 overflow-y-auto lg:block lg:translate-x-0 lg:end-auto lg:bottom-0 [&::-webkit-scrollbar]:w-2 [&::-webkit-scrollbar-thumb]:rounded-full [&::-webkit-scrollbar-track]:bg-gray-100 [&::-webkit-scrollbar-thumb]:bg-gray-300 dark:[&::-webkit-scrollbar-track]:bg-slate-700 dark:[&::-webkit-scrollbar-thumb]:bg-slate-500 dark:bg-gray-800 dark:border-gray-700">
        <div class="px-6">
            <Link :href="route('home')">
                <ApplicationMark class="block h-9 w-auto"/>
            </Link>
        </div>

        <nav class="hs-accordion-group p-6 w-full flex flex-col flex-wrap" data-hs-accordion-always-open>
            <ul class="space-y-1.5">
                <NavLink v-if="checkHasOneOfRoles(['superadmin','admin','tutor', 'tutor-seller'])"
                         :href="route('pupil.index')" :active="route().current('pupil.index')">
                    <FontAwesomeIcon icon="fa-regular fa-user"/> Ученики
                </NavLink>
                <NavLink v-if="checkHasOneOfRoles(['superadmin','admin','tutor', 'tutor-seller'])"
                         :href="route('calendar.index')" :active="route().current('calendar.index')">
                    <FontAwesomeIcon icon="fa-regular fa-calendar-check"/> Календарь
                </NavLink>
                <NavLink v-if="checkHasOneOfRoles(['superadmin','admin','tutor', 'tutor-seller'])"
                         :href="route('test')" :active="route().current('test')">
                    <FontAwesomeIcon icon="fa-regular fa-eye"/> Видеоконференция
                </NavLink>
                <NavLink v-if="checkHasOneOfRoles(['superadmin', 'admin'])"
                         :href="route('admin.index')"
                         :active="route().current('admin.index')">
                    <FontAwesomeIcon icon="fa-regular fa-chess-king"/> Администрирование
                </NavLink>
            </ul>
        </nav>
    </div>
</template>
