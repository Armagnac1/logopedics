<script setup>
import { Disclosure, DisclosureButton, DisclosurePanel, Menu, MenuButton, MenuItem, MenuItems } from '@headlessui/vue'
import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome';
import { Head, usePage } from '@inertiajs/vue3';
import ImpersonateBanner from '@/Components/ImpersonateBanner.vue';
import Banner from '@/Components/Banner.vue';
import SearchInput from '@/Layouts/SearchInput.vue';
import { ref } from 'vue';
import SearchModal from '@/Components/SearchModal.vue';
import Modal from '@/Components/Modal.vue';

defineProps({
    title: String
});

const page = usePage()
const showSearch = ref(false)
const searchModal = ref(null)
const openSearchModal = () => {
    showSearch.value = true
    searchModal.value.focus()
}

const user = {
    name: page.props.auth.user.name,
    email: page.props.auth.user.email,
    imageUrl: page.props.auth.user.profile_photo_path
}
const navigation = [
    {
        name: 'Календарь',
        route: 'calendar.index',
        icon: 'fa-regular fa-calendar-check',
        availableForRoles: ['superadmin', 'admin', 'tutor', 'tutor-seller']
    },
    {
        name: 'Ученики',
        route: 'pupil.index',
        icon: 'fa-regular fa-user',
        availableForRoles: ['superadmin', 'admin', 'tutor', 'tutor-seller']
    },
    {
        name: 'Видеоконференция',
        route: 'test',
        icon: 'fa-regular fa-eye',
        availableForRoles: ['superadmin', 'admin', 'tutor', 'tutor-seller']
    },
    {
        name: 'Администрирование',
        route: 'admin.index',
        icon: 'fa-regular fa-chess-king',
        availableForRoles: ['superadmin', 'admin']
    }

]
const userNavigation = [
    { name: 'Профиль', route: 'profile.show' },
    { name: 'Создание учебного материала', route: 'learning_material.create' },
    { name: 'Выйти', route: 'logout' }
]
</script>

<template>
    <Head :title="title"/>

    <ImpersonateBanner/>
    <div class="min-h-full">
        <Disclosure as="nav" class="bg-indigo-600" v-slot="{ open }">
            <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                <div class="flex h-16 items-center justify-between">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <img class="h-8 w-8" src="/favicon.ico"
                                 alt="Logo"/>
                        </div>
                        <div class="hidden lg:block">
                            <div class="ml-10 flex items-baseline space-x-4">
                                <a draggable="false" v-for="item in navigation"
                                   :key="item.name"
                                   :href="route(item.route)"
                                   :class="[route().current(item.route) ? 'bg-indigo-700 text-white' : 'text-gray-200 hover:bg-indigo-500 hover:text-white', 'rounded-md px-3 py-2 text-sm font-medium']"
                                   :aria-current="route().current(item.route) ? 'page' : undefined"
                                >
                                    <!--                                    <font-awesome-icon :icon="item.icon"/>-->
                                    {{ item.name }}
                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="block lg:hidden">
                        <SearchInput @click="openSearchModal"/>
                    </div>
                    <div class="hidden lg:block">
                        <div class="ml-4 flex items-center lg:ml-6">
                            <SearchInput @click="openSearchModal"/>
                            <!-- Profile dropdown -->
                            <Menu as="div" class="relative ml-3">
                                <div>
                                    <MenuButton
                                        class="relative flex max-w-xs items-center rounded-full bg-indigo-700 text-sm focus:outline-none focus:ring-2 focus:ring-white focus:ring-offset-2 focus:ring-offset-indigo-700">
                                        <span class="absolute -inset-1.5"/>
                                        <span class="sr-only">Открыть меню пользователя</span>
                                        <img class="h-8 w-8 rounded-full" :src="user.imageUrl" alt=""/>
                                    </MenuButton>
                                </div>
                                <transition enter-active-class="transition ease-out duration-100"
                                            enter-from-class="transform opacity-0 scale-95"
                                            enter-to-class="transform opacity-100 scale-100"
                                            leave-active-class="transition ease-in duration-75"
                                            leave-from-class="transform opacity-100 scale-100"
                                            leave-to-class="transform opacity-0 scale-95">
                                    <MenuItems
                                        class="absolute right-0 z-10 mt-2 w-48 origin-top-right rounded-md bg-white py-1 shadow-lg ring-1 ring-black ring-opacity-5 focus:outline-none">
                                        <MenuItem v-for="item in userNavigation" :key="item.name" v-slot="{ active }">
                                            <a
                                                :href="route(item.route)"
                                                :class="[route().current(item.route) ? 'bg-gray-100' : '', 'block px-4 py-2 text-sm']">
                                                {{ item.name }}
                                            </a>
                                        </MenuItem>
                                    </MenuItems>
                                </transition>
                            </Menu>
                        </div>
                    </div>
                    <div class="-mr-2 flex lg:hidden">
                        <!-- Mobile menu button -->
                        <DisclosureButton
                            class="relative inline-flex items-center justify-center rounded-md bg-indigo-600 p-2 text-indigo-400 hover:bg-indigo-600 hover:text-white focus:outline-none focus:ring-2 focus:ring-white focus:ring-offset-2 focus:ring-offset-gray-800">
                            <span class="absolute -inset-0.5"/>
                            <span class="sr-only">Open main menu</span>
                            <font-awesome-icon :icon="open ? 'fa-solid fa-xmark' : 'fa-solid fa-bars'"/>
                        </DisclosureButton>
                    </div>
                </div>
            </div>

            <DisclosurePanel class="lg:hidden">
                <div class="space-y-1 px-2 pb-3 pt-2 sm:px-3">
                    <DisclosureButton v-for="item in navigation" :key="item.name" as="a" :href="route(item.route)"
                                      :class="[route().current(item.route) ? 'bg-indigo-700' : 'hover:bg-indigo-500', ' text-white block rounded-md px-3 py-2 text-base font-medium']"
                                      :aria-current="item.current ? 'page' : undefined">
                        {{ item.name }}

                    </DisclosureButton>
                </div>
                <div class="border-t border-gray-700 pb-3 pt-4">
                    <div class="flex items-center px-5">
                        <div class="flex-shrink-0">
                            <img class="h-10 w-10 rounded-full" :src="user.imageUrl" alt=""/>
                        </div>
                        <div class="ml-3">
                            <div class="text-base font-medium leading-none text-white">{{ user.name }}</div>
                            <div class="text-sm font-medium leading-none text-indigo-200">{{ user.email }}</div>
                        </div>
                    </div>
                    <div class="mt-3 space-y-1 px-2">
                        <DisclosureButton v-for="item in userNavigation" :key="item.name" as="a"
                                          :href="route(item.route)"
                                          :class="[route().current(item.route) ? 'bg-indigo-700' : 'hover:bg-indigo-500', ' text-white block rounded-md px-3 py-2 text-base font-medium']"
                        >
                            {{ item.name }}
                        </DisclosureButton>
                    </div>
                </div>
            </DisclosurePanel>
        </Disclosure>

        <Banner/>
        <header v-if="$slots.header" class="bg-white shadow">
            <div class="mx-auto max-w-7xl px-4 py-6 sm:px-6 lg:px-8">
                <slot name="header"/>
            </div>
        </header>
        <main>
            <div class="mx-auto max-w-7xl py-6 sm:px-6 lg:px-8">
                <slot/>
            </div>
        </main>
        <Modal @close="showSearch = false" :show="showSearch">
            <SearchModal ref="searchModal"/>
        </Modal>
    </div>
</template>

