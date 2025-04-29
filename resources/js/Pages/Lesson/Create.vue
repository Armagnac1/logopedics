<script setup>
import BackButton from '@/Components/BackButton.vue';
import Card from '@/Components/Card.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import { useForm } from '@inertiajs/vue3';
import InputLabel from '@/Components/InputLabel.vue';
import TextInput from '@/Components/TextInput.vue';
import InputError from '@/Components/InputError.vue';
import TopBarLayout from '@/Layouts/TopBarLayout.vue';
import TimePicker from '@/Components/DateTime/TimePicker.vue';
import DatePicker from '@/Components/DateTime/DatePicker.vue';

const props = defineProps({
    pupil: Object,
    name_suggestions: Array
})
const form = useForm({
    pupil_id: props.pupil.id,
    title: '',
    start_time: '',
    start_dates: [],
    duration: props.pupil.lesson_duration
})

const submit = () => {
    form.submit('post', route('lesson.store'), {
        onSuccess: () => {
        }
    })
}

const setTitleFormSuggestion = (value) => {
    form.title = value
}

</script>

<template>
    <TopBarLayout :title="$t('lesson.create_for_pupil', { name: pupil.full_name })">
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                <BackButton/>
                {{ $t('lesson.create_for_pupil', { name: pupil.full_name }) }}
            </h2>
        </template>

        <div class="grid gap-3">
            <Card class="">
                <form class="space-y-5 " @submit.prevent="submit">
                    <div class="grid grid-cols-1 md:grid-cols-3 border-b">
                        <div>
                            <h2 class="text-base font-semibold leading-7 text-gray-900">{{ $t('lesson.main_information') }}</h2>
                        </div>

                        <div class="md:col-span-2 mt-10 pb-7 grid grid-cols-1 gap-x-6 gap-y-8 sm:grid-cols-6">
                            <div class="sm:col-span-4">
                                <InputLabel for="title" :value="$t('common.title')"/>
                                <TextInput
                                    id="title"
                                    v-model="form.title"
                                    class="mt-1 block w-full"
                                    type="text"
                                />
                                <ul class="text-sm text-gray-500">
                                    <li v-for="suggestion in name_suggestions"
                                        class="inline-block cursor-pointer hover:underline relative pe-8 text-sm last:pe-0 last-of-type:before:hidden before:absolute before:top-1/2 before:end-3 before:-translate-y-1/2 before:size-1 before:bg-gray-300 before:rounded-full dark:text-neutral-400 dark:before:bg-neutral-600"
                                        @click="setTitleFormSuggestion(suggestion)"
                                    >
                                        {{ suggestion }}
                                    </li>
                                </ul>
                                <InputError :message="form.errors.title" class="mt-2"/>
                            </div>
                        </div>
                        <div>
                            <h2 class="text-base font-semibold leading-7 text-gray-900">{{ $t('common.schedule') }}</h2>
                        </div>
                        <div class="md:col-span-2 mt-10 pb-7 grid grid-cols-1 gap-x-6 gap-y-8 sm:grid-cols-6">
                            <div class="sm:col-span-4">
                                <InputLabel for="start_time" :value="$t('common.time')"/>
                                <TimePicker v-model="form.start_time"/>
                                <InputError :message="form.errors.start_time" class="mt-2"/>
                            </div>
                            <div class="sm:col-span-4">
                                <InputLabel for="start_dates" :value="$t('common.date')"/>
                                <DatePicker v-model="form.start_dates" multiple/>
                                <InputError :message="form.errors.start_dates" class="mt-2"/>
                            </div>
                            <div class="sm:col-span-4">
                                <InputLabel for="duration" :value="$t('lesson.duration')"/>
                                <TextInput
                                    v-model="form.duration"
                                    class="mt-1 block w-full"
                                    required
                                    type="number"
                                    step="5"
                                />
                                <InputError :message="form.errors.duration" class="mt-2"/>
                            </div>
                        </div>
                    </div>

                    <div class="flex justify-end items-center gap-x-2 mt-6 sm:px-7">
                        <PrimaryButton :class="{ 'opacity-25': form.processing }" :disabled="form.processing"
                                       class="ms-4">
                            {{ $t('common.save') }}
                        </PrimaryButton>
                    </div>
                </form>
            </Card>
        </div>
    </TopBarLayout>
</template>
