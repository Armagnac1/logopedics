<script setup>
import Paginator from '@/Components/Paginator.vue';
import Card from '@/Components/Card.vue';
import { Link } from '@inertiajs/vue3';
import TopBarLayout from '@/Layouts/TopBarLayout.vue';

defineProps({
    users: Object
})
</script>

<template>
    <TopBarLayout title="Calendar">
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ $t('common.administration') }}
            </h2>
        </template>

        <Card>
            {{ $t('common.loginAs') }} :
            <Link v-for="user in users.data" :key="user.id" :href="route('admin.login-as', user.id)">
                <Card class="mb-4">
                    <div>{{ user.email }}</div>
                    <div>{{ user.name }}</div>
                    <div>{{ user.roles[0].name }}</div>
                </Card>
            </Link>
            <Paginator :links="users.links"></Paginator>
        </Card>
    </TopBarLayout>
</template>
