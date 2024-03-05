<script setup>
import { ref } from 'vue';
import { Head, Link, router } from '@inertiajs/vue3';
import ApplicationMark from '@/Components/ApplicationMark.vue';
import Banner from '@/Components/Banner.vue';
import DropdownLink from '@/Components/DropdownLink.vue';
import NavLink from '@/Components/NavLink.vue';

defineProps({
    title: String
});

const showingNavigationDropdown = ref(false);

const switchToTeam = (team) => {
    router.put(route('current-team.update'), {
        team_id: team.id
    }, {
        preserveState: false
    });
};

const logout = () => {
    router.post(route('logout'));
};
</script>

<template>
    <body class="bg-gray-50 dark:bg-slate-900">
    <!-- ========== HEADER ========== -->
    <Head :title="title"/>

    <Banner/>
    <header
        class="sticky top-0 inset-x-0 flex flex-wrap sm:justify-start sm:flex-nowrap z-[48] w-full bg-white border-b text-sm py-2.5 sm:py-4 lg:ps-64 dark:bg-gray-800 dark:border-gray-700">
        <nav class="flex basis-full items-center w-full mx-auto px-4 sm:px-6 md:px-8" aria-label="Global">
            <div class="me-5 lg:me-0 lg:hidden">
                <Link :href="route('dashboard')">
                    <ApplicationMark class="block h-9 w-auto"/>
                </Link>
            </div>

            <div class="w-full flex items-center justify-end ms-auto sm:justify-between sm:gap-x-3 sm:order-3">
                <div class="sm:hidden">
                    <button type="button"
                            class="w-[2.375rem] h-[2.375rem] inline-flex justify-center items-center gap-x-2 text-sm font-semibold rounded-full border border-transparent text-gray-800 hover:bg-gray-100 disabled:opacity-50 disabled:pointer-events-none dark:text-white dark:hover:bg-gray-700 dark:focus:outline-none dark:focus:ring-1 dark:focus:ring-gray-600">
                        <svg class="flex-shrink-0 size-4" xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                             viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                             stroke-linecap="round" stroke-linejoin="round">
                            <circle cx="11" cy="11" r="8"/>
                            <path d="m21 21-4.3-4.3"/>
                        </svg>
                    </button>
                </div>

                <div class="hidden sm:block">
                    <label for="icon" class="sr-only">Search</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 start-0 flex items-center pointer-events-none z-20 ps-4">
                            <font-awesome-icon icon="fa-solid fa-search"/>
                        </div>
                        <input type="text" id="icon" name="icon"
                               class="py-2 px-4 ps-11 block w-full border-gray-200 rounded-lg text-sm focus:border-blue-500 focus:ring-blue-500 disabled:opacity-50 disabled:pointer-events-none dark:bg-slate-900 dark:border-gray-700 dark:text-gray-400 dark:focus:ring-gray-600"
                               placeholder="Поиск">
                    </div>
                </div>

                <div class="flex flex-row items-center justify-end gap-2">
                    <button type="button"
                            class="w-[2.375rem] h-[2.375rem] bell justify-center items-center gap-x-2 text-sm font-semibold rounded-full border border-transparent text-gray-800 hover:bg-gray-100 disabled:opacity-50 disabled:pointer-events-none dark:text-white dark:hover:bg-gray-700 dark:focus:outline-none dark:focus:ring-1 dark:focus:ring-gray-600">
                        <font-awesome-icon icon="fa-solid fa-bell"/>
                    </button>
                    <div class="hs-dropdown relative inline-flex [--placement:bottom-right]">
                        <button id="hs-dropdown-with-header" type="button"
                                class="w-[2.375rem] h-[2.375rem] inline-flex justify-center items-center gap-x-2 text-sm font-semibold rounded-full border border-transparent text-gray-800 hover:bg-gray-100 disabled:opacity-50 disabled:pointer-events-none dark:text-white dark:hover:bg-gray-700 dark:focus:outline-none dark:focus:ring-1 dark:focus:ring-gray-600">
                            <img class="inline-block size-[38px] rounded-full ring-2 ring-white dark:ring-gray-800"
                                 :src="$page.props.auth.user.profile_photo_path"
                                 alt="Image Description">
                        </button>

                        <div
                            class="hs-dropdown-menu transition-[opacity,margin] duration hs-dropdown-open:opacity-100 opacity-0 hidden min-w-60 bg-white shadow-md rounded-lg p-2 dark:bg-gray-800 dark:border dark:border-gray-700"
                            aria-labelledby="hs-dropdown-with-header">
                            <div class="py-3 px-5 -m-2 bg-gray-100 rounded-t-lg dark:bg-gray-700">
                                <p class="text-sm font-medium text-gray-800 dark:text-gray-300">
                                    {{ $page.props.auth.user.name }}</p>
                            </div>
                            <div class="mt-2 py-2">
                                <DropdownLink as="button" :href="route('profile.show')">
                                    Профиль
                                </DropdownLink>

                                <div class="border-t border-gray-200 dark:border-gray-600"/>

                                <!-- Authentication -->
                                <form @submit.prevent="logout">
                                    <DropdownLink as="button">
                                        Выйти
                                    </DropdownLink>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </nav>
    </header>
    <!-- ========== END HEADER ========== -->

    <!-- ========== MAIN CONTENT ========== -->
    <!-- Sidebar Toggle -->
    <div
        class="sticky top-0 inset-x-0 z-20 bg-white border-y px-4 sm:px-6 md:px-8 lg:hidden dark:bg-gray-800 dark:border-gray-700">
        <div class="flex items-center py-4">
            <!-- Navigation Toggle -->
            <button type="button" class="text-gray-500 hover:text-gray-600" data-hs-overlay="#application-sidebar"
                    aria-controls="application-sidebar" aria-label="Toggle navigation">
                <span class="sr-only">Toggle Navigation</span>
                <svg class="flex-shrink-0 size-4" xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                     viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                     stroke-linejoin="round">
                    <line x1="3" x2="21" y1="6" y2="6"/>
                    <line x1="3" x2="21" y1="12" y2="12"/>
                    <line x1="3" x2="21" y1="18" y2="18"/>
                </svg>
            </button>
            <!-- End Navigation Toggle -->

        </div>
    </div>
    <!-- End Sidebar Toggle -->

    <!-- Sidebar -->
    <div id="application-sidebar"
         class="hs-overlay hs-overlay-open:translate-x-0 -translate-x-full transition-all duration-300 transform hidden fixed top-0 start-0 bottom-0 z-[60] w-64 bg-white border-e border-gray-200 pt-7 pb-10 overflow-y-auto lg:block lg:translate-x-0 lg:end-auto lg:bottom-0 [&::-webkit-scrollbar]:w-2 [&::-webkit-scrollbar-thumb]:rounded-full [&::-webkit-scrollbar-track]:bg-gray-100 [&::-webkit-scrollbar-thumb]:bg-gray-300 dark:[&::-webkit-scrollbar-track]:bg-slate-700 dark:[&::-webkit-scrollbar-thumb]:bg-slate-500 dark:bg-gray-800 dark:border-gray-700">
        <div class="px-6">
            <Link :href="route('dashboard')">
                <ApplicationMark class="block h-9 w-auto"/>
            </Link>
        </div>

        <nav class="hs-accordion-group p-6 w-full flex flex-col flex-wrap" data-hs-accordion-always-open>
            <ul class="space-y-1.5">
                <NavLink draggable="false" :href="route('dashboard')" :active="route().current('dashboard')">
                    Сводка
                </NavLink>
                <NavLink draggable="false":href="route('lesson.index')" :active="route().current('lesson.index')">
                    Уроки
                </NavLink>
                <NavLink draggable="false" :href="route('pupil.index')" :active="route().current('pupil.index')">
                    Ученики
                </NavLink>
                <NavLink draggable="false" :href="route('calendar.index')" :active="route().current('calendar.index')">
                    Календарь
                </NavLink>
                <NavLink draggable="false" :href="route('test')" :active="route().current('test')">
                    Видеоконференция
                </NavLink>
            </ul>
        </nav>
    </div>
    <!-- End Sidebar -->

    <!-- Content -->
    <!-- Page Heading -->

    <!-- Page Content -->

    <header v-if="$slots.header" class="top-0 inset-x-0 flex flex-wrap sm:justify-start sm:flex-nowrap z-[48] w-full bg-white border-b text-sm py-2.5 sm:py-4 lg:ps-64 dark:bg-gray-800 dark:border-gray-700">
        <div class="max-w-7x px-4 sm:px-6 lg:px-8">
            <slot name="header"/>
        </div>
    </header>
    <div class="w-full pt-10 px-4 sm:px-6 md:px-8 lg:ps-72">
        <!-- Page Heading -->

        <main>
            <slot/>
        </main>
        <!-- End Page Heading -->
    </div>
    <!-- End Content -->
    <!-- ========== END MAIN CONTENT ========== -->
    </body>


    <div>
    </div>
</template>
