<script setup>
import FullCalendar from '@fullcalendar/vue3'
import ruLocale from '@fullcalendar/core/locales/ru'
import dayGridPlugin from '@fullcalendar/daygrid'
import interactionPlugin from '@fullcalendar/interaction'
import { router } from '@inertiajs/vue3';

const props = defineProps({
    events: Array
})

const calendarOptions = {
    plugins: [dayGridPlugin, interactionPlugin],
    initialView: 'dayGridMonth',
    locale: ruLocale,

    events: props.events,
    eventClick: function(info) {
        info.jsEvent.preventDefault(); // don't let the browser navigate
        router.visit(info.event.url);
    }
}

</script>

<template>
    <FullCalendar :options="calendarOptions"/>
</template>
