<script setup>
import { Link, router, usePage } from '@inertiajs/vue3';
import { computed } from 'vue';
const page = usePage()

const urlPrev = computed(() => page.props.urlPrev)

</script>

<template>
    <Link draggable="false" :href="urlPrev">
        <font-awesome-icon icon="fa-solid fa-arrow-left"/>
    </Link>
</template>
