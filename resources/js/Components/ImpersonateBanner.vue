<script setup>
import { ref, watchEffect } from 'vue';
import { usePage, Link } from '@inertiajs/vue3';
import PrimaryButton from '@/Components/PrimaryButton.vue';

const page = usePage();
const show = ref(page.props.impersonate);
const style = ref('success');
const message = ref('');

watchEffect(async () => {
    show.value = page.props.impersonate;
});
</script>

<template>
    <div>
        <div v-if="show" class="bg-indigo-500">
            <div class="max-w-screen-xl mx-auto py-2 px-3 sm:px-6 lg:px-8">
                <div class="flex items-center justify-between flex-wrap">
                    <div class="w-0 flex-1 flex items-center min-w-0">
                        <p class="ms-3 font-medium text-sm text-white truncate">
                            Сейчас страница просматривается от другого пользователя
                        </p>
                    </div>

                    <div class="shrink-0 sm:ms-3">
                        <Link :href="route('admin.impersonate-back')">
                            <PrimaryButton>
                                Вернуться к себе
                            </PrimaryButton>
                        </Link>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
