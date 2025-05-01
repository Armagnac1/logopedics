<script setup>
import { nextTick, ref } from 'vue';
import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome';
import { useForm } from '@inertiajs/vue3';
import { dayjs } from '../translation.config.ts';
import DateTimePicker from '@/Components/DateTime/DateTimePicker.vue';

const props = defineProps({
    modelValue: String,
    field: String,
    route_url: String
});

const input = ref(null);

const form = useForm({
    [props.field]: props.modelValue
});

const submit = () => {
    console.log(form[props.field]);
    form.submit('put', props.route_url, {
        preserveScroll: true,
        onSuccess: () => {}
    });
};

</script>

<template>
    <DateTimePicker
        ref="input"
        position="left"
        v-model="form[props.field]"
        @update:model-value="submit">
        <template #trigger>
            <div class="flex group items-center cursor-pointer">
                <span>{{ modelValue ? dayjs(modelValue).format('D MMM, LT') : $t('common.notScheduled') }}</span>
                <FontAwesomeIcon class="ml-1.5 size-3 group-hover:visible invisible"
                                 icon="fa-regular fa-pen-to-square"/>
            </div>
        </template>
    </DateTimePicker>
</template>
