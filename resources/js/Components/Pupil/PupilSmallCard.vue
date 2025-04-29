<script setup>

import { Link } from '@inertiajs/vue3';
import SecondaryButton from '@/Components/SecondaryButton.vue';

const props = defineProps({
    pupil: Object
})
</script>

<template>

    <Link :href="route('pupil.show', pupil.id)">
        <div class="flex items-center gap-x-3 group">
            <div class="grow">
            <span class="block text-2xl font-bold text-gray-800 dark:text-gray-200">
                    {{ pupil.full_name }}
            </span>

            </div>
            <div class="flex-grow-0 invisible group-hover:visible">
                <SecondaryButton>
                    {{ $t('common.profile')}}
                </SecondaryButton>
            </div>
        </div>
    </Link>
</template>
