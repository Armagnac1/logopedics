<script setup>
import FullCalendar from '@fullcalendar/vue3'
import ruLocale from '@fullcalendar/core/locales/ru'
import dayGridPlugin from '@fullcalendar/daygrid'
import timeGridPlugin from '@fullcalendar/timegrid'
import interactionPlugin from '@fullcalendar/interaction'
import { router } from '@inertiajs/vue3';
import { dayjs } from '../translation.config.ts';

const props = defineProps({
    events: Array
})

const calendarOptions = {
    plugins: [dayGridPlugin, interactionPlugin, timeGridPlugin],
    initialView: 'timeGridWeek',
    locale: ruLocale,
    allDaySlot: false,
    eventShortHeight: 60,
    slotEventOverlap: false,
    nowIndicator: true,
    slotMinTime: '00:00:00',
    slotMaxTime: '24:00:00',
    expandRows: true,
    events: props.events,
    headerToolbar: {
        start: 'title',
        center: '',
        right: 'prev,today,next'
    },
    titleFormat: { year: 'numeric', month: 'long' },
    eventClick: function (info) {
        info.jsEvent.preventDefault(); // don't let the browser navigate
        router.visit(info.event.url);
    },
    displayEventEnd: false,
}

</script>

<template>
    <FullCalendar :options="calendarOptions">
        <template #dayHeaderContent="arg">
            <div class="fc-scrollgrid-sync-inner">
                <a class="fc-col-header-cell-cushion">
                    <span class="fc-day-of-week">{{ dayjs(arg.date).format('dd') }} </span>
                    <span class="fc-day-number">{{ dayjs(arg.date).format('D') }}</span>
                </a>
            </div>
        </template>
    </FullCalendar>
</template>


<style scoped>
:deep(.fc-button) {
    @apply bg-white border-0 border-t-2 border-b-2 border-gray-200 rounded-lg shadow-sm text-gray-700 py-1;
}

:deep(.fc-button:hover) {
    @apply text-black bg-gray-50 border-gray-200;
}

:deep(.fc-button:focus) {
    @apply bg-gray-50 ring-0 border-0 border-gray-200 border-t-2 border-b-2;
}


:deep(.fc-button:disabled) {
    @apply bg-white text-black border-t-2 border-b-2 pointer-events-none border-gray-200 opacity-100;
}

:deep(.fc-button:not(:disabled):active) {
    @apply bg-gray-50 text-black border-0 border-t-2 border-b-2 border-gray-200 shadow-none;
    box-shadow: none !important;
}

:deep(.fc-prev-button) {
    @apply border-l-2 border-gray-200;
}

:deep(.fc-prev-button:focus) {
    @apply border-l-2;
}

:deep(.fc-prev-button:not(:disabled):active) {
    @apply border-l-2 border-t-2 border-b-2 border-gray-200;
}

:deep(.fc-today-button) {
    @apply border-gray-200;
}

:deep(.fc-next-button) {
    @apply border-r-2 border-gray-200;
}

:deep(.fc-next-button:focus) {
    @apply border-r-2;
}

:deep(.fc-next-button:not(:disabled):active) {
    @apply border-r-2 border-t-2 border-b-2 border-gray-200;
}


:deep(.fc-event) {
    @apply bg-blue-50;
}

:deep(.fc-event:hover) {
    @apply bg-blue-100;
}

:deep(.fc-event:active) {
    @apply bg-blue-100;
}

:deep(.fc-event .fc-event-main-frame) {
    @apply text-black font-medium;
}

:deep(.fc-toolbar-title) {
    @apply font-sans font-extrabold text-xl text-gray-800 dark:text-gray-200;
}

:deep(.fc-col-header-cell-cushion) {
    @apply flex;
}

:deep(.fc-scrollgrid-sync-inner) {
    @apply flex items-center justify-center;
}

:deep(.fc-day-today .fc-col-header-cell-cushion .fc-day-number) {
    @apply rounded-full bg-blue-400 text-white;
}

:deep(.fc-col-header-cell-cushion .fc-day-number) {
    @apply w-6 h-6 ml-1;
}

:deep(.fc-col-header-cell-cushion .fc-day-of-week) {
    @apply font-light
}

:deep(.fc-timegrid-col.fc-day-today) {
    @apply bg-amber-50/50;
}

:deep(.fc-event-time) {
    @apply hidden sm:block;
}

:deep(.fc-timegrid-event-short .fc-event-time::after) {
    @apply content-none sm:content-["_-_"];
}

</style>

