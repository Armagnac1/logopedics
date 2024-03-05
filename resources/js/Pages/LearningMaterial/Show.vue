<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import BackButton from '@/Components/BackButton.vue';
import Textarea from '@/Components/Textarea.vue';
import Card from '@/Components/Card.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import { useForm } from '@inertiajs/vue3';
import InputLabel from '@/Components/InputLabel.vue';
import TextInput from '@/Components/TextInput.vue';
import InputError from '@/Components/InputError.vue';
import VueMultiselect from 'vue-multiselect';
import AdvancedSelect from '@/Components/AdvancedSelect.vue';

const props = defineProps({
    learning_material: Object,
    tags: Array
})

const tagMap = (i) => {
    return { name: i.name.ru, id: i.id }
}
const tagsOptions = props.tags.map(tagMap)
const form = useForm({
    title: props.learning_material.title,
    text: props.learning_material.text,
    tags: props.learning_material.tags.map(tagMap)
})

const submit = () => {
    form.put(route('learning_material.update', props.learning_material.id),{
        onSuccess: () => {
            history.back()
        }
    })
}

</script>

<template>
    <AppLayout :title="learning_material.title">
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                <BackButton/>
                {{ learning_material.title }}
            </h2>
        </template>

        <div class="grid">
            <Card class="">
                <form class="space-y-5 " @submit.prevent="submit">
                    <InputLabel for="title" value="Название"/>
                    <TextInput
                        id="title"
                        v-model="form.title"
                        type="text"
                        class="mt-1 block w-full"
                        required
                        autocomplete="title"
                    />
                    <InputError :message="form.errors.title" class="mt-2"/>
                    <Textarea
                        :label="'Текст'"
                        :rows="9"
                        v-model="learning_material.text"
                    />
                    <div>
                        <InputLabel for="tags" value="Категория"/>
                        <AdvancedSelect id="tags" track-by="id" :multiple="true" label="name" :options="tagsOptions"
                                        v-model="form.tags"/>
                    </div>
                    <div class="flex justify-end items-center gap-x-2 pt-4 sm:px-7 border-t dark:border-gray-700">
                        <PrimaryButton class="ms-4" :class="{ 'opacity-25': form.processing }"
                                       :disabled="form.processing">
                            Сохранить
                        </PrimaryButton>
                    </div>
                </form>
            </Card>
        </div>
    </AppLayout>
</template>
<style src="vue-multiselect/dist/vue-multiselect.css"></style>
