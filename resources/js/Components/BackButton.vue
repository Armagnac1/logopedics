<script setup>
import { Link } from '@inertiajs/vue3';
</script>

<template>
    <Link draggable="false" href="#" onclick="history.back()">
        <font-awesome-icon icon="fa-solid fa-arrow-left"/>
    </Link>
</template>
