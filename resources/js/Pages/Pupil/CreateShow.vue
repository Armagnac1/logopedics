<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import BackButton from '@/Components/BackButton.vue';
import Card from '@/Components/Card.vue';
import { router, useForm } from '@inertiajs/vue3';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import InputLabel from '@/Components/InputLabel.vue';
import InputError from '@/Components/InputError.vue';
import TextInput from '@/Components/TextInput.vue';


const props = defineProps({
    pupil: Object
})

const form = useForm({
    first_name: '',
    last_name: '',
    email: '',
})

if (props.pupil) {
    form.first_name = props.pupil.first_name
    form.last_name = props.pupil.last_name
    form.email = props.pupil.email
}
const submit = () => {
    form.transform(({ media, ...data }) => ({
        ...data
    })).submit(props.pupil ? 'put' : 'post',
        props.pupil ? route('pupil.update', props.pupil.id) : route('pupil.store'), {
            onSuccess: () => {
                router.reload()
            }
        })
}

</script>

<template>
    <AppLayout title="Calendar">
        <template #header>
            <h2 class="font-semibold text-xl L dark:text-gray-200 leading-tight">
                <BackButton/>
                {{ props.pupil ? props.pupil.full_name : 'Новый ученик' }}
            </h2>
        </template>
        <div >
            <Card class="">
                <form class="space-y-5 " @submit.prevent="submit">
                    <InputLabel for="title" value="Имя"/>
                    <TextInput
                        id="title"
                        v-model="form.first_name"
                        type="text"
                        class="mt-1 block w-full"
                        required
                        autocomplete="title"
                    />
                    <InputError :message="form.errors.first_name" class="mt-2"/>
                    <InputLabel for="title" value="Фамилия"/>
                    <TextInput
                        id="title"
                        v-model="form.last_name"
                        type="text"
                        class="mt-1 block w-full"
                        required
                        autocomplete="title"
                    />
                    <InputError :message="form.errors.last_name" class="mt-2"/>
                    <InputLabel for="title" value="Email"/>
                    <TextInput
                        id="title"
                        v-model="form.email"
                        type="text"
                        class="mt-1 block w-full"
                        autocomplete="title"
                    />
                    <InputError :message="form.errors.email" class="mt-2"/>
                    <div class="flex justify-end items-center gap-x-2 pt-4 sm:px-7 border-t dark:border-gray-700">
                        <PrimaryButton class="ms-4" :class="{ 'opacity-25': form.processing }"
                                       :disabled="form.processing">
                            Сохранить
                        </PrimaryButton>
                    </div>
                </form>
            </Card>
<!--            <Card class="row-span-2 col-span-1">
                <h1 class="text-2xl font-bold">Использованные материалы</h1>
                <div v-for="material in pupil.lessons.map(i=>i.learning_materials).flat()" :key="material.id">
                    <LearningMaterialItem :material="material"/>
                </div>
            </Card>-->
        </div>
    </AppLayout>
</template>
