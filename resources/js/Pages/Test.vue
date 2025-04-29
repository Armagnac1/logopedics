<script setup>
import { JitsiMeeting } from "@jitsi/vue-sdk";
import Card from '@/Components/Card.vue';
import TopBarLayout from '@/Layouts/TopBarLayout.vue';

const props = defineProps({
    lessons: Array
})

</script>

<template>
    <TopBarLayout title="Calendar">
        <Card class="grid place-items-center grow">
            <div class="grow">
                <JitsiMeeting class="w-9"
                              :domain="'meet.logopedics.ru'"
                              :room-name="'Встреча'"
                              lang="ru"
                />
            </div>

        </Card>

    </TopBarLayout>
</template>
