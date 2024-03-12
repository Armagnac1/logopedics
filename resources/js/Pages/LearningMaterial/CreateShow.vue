<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import BackButton from '@/Components/BackButton.vue';
import Textarea from '@/Components/Textarea.vue';
import Card from '@/Components/Card.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import { router, useForm } from '@inertiajs/vue3';
import InputLabel from '@/Components/InputLabel.vue';
import TextInput from '@/Components/TextInput.vue';
import InputError from '@/Components/InputError.vue';
import AdvancedSelect from '@/Components/AdvancedSelect.vue';
import AttachedFiles from '@/Components/File/AttachedFiles.vue';

const props = defineProps({
    learning_material: Object,
    usedForPupils: Array,
    tags: Array
})
const form = useForm({
    title: '',
    text: '',
    tags: [],
    media: []
})
const tagMap = (i) => {
    return { name: i.name.ru, id: i.id }
}

const tagsOptions = props.tags.map(tagMap)

if (props.learning_material) {
    form.title = props.learning_material.title
    form.text = props.learning_material.text
    form.tags = props.learning_material.tags.map(tagMap)
    form.media = props.learning_material.media
}


const submit = () => {
    form.transform(({ media, ...data }) => ({
        ...data,
        mediaIds: media.map(i => i.id)
    })).submit(props.learning_material ? 'put' : 'post',
        props.learning_material ? route('learning_material.update', props.learning_material.id) : route('learning_material.store'), {
            onSuccess: () => {
                router.reload()
            }
        })
}

</script>

<template>
    <AppLayout :title="props.learning_material ? 'Редактирование учебного материала' : 'Создание учебного материала'">
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                <BackButton/>
                {{ props.learning_material ? 'Редактирование учебного материала' : 'Создание учебного материала' }}
            </h2>
        </template>

        <div class="grid gap-3">
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
                        v-model="form.text"
                    />
                    <div>
                        <InputLabel for="tags" value="Категория"/>
                        <AdvancedSelect id="tags" track-by="id" :multiple="true" label="name" :options="tagsOptions"
                                        v-model="form.tags"/>
                    </div>
                    <div>
                        <InputLabel for="tags" value="Файлы"/>
                        <AttachedFiles v-model="form.media"/>
                    </div>
                    <div class="flex justify-end items-center gap-x-2 pt-4 sm:px-7 border-t dark:border-gray-700">
                        <PrimaryButton class="ms-4" :class="{ 'opacity-25': form.processing }"
                                       :disabled="form.processing">
                            Сохранить
                        </PrimaryButton>
                    </div>
                </form>
            </Card>
            <!--            <Card class="space-y-2">
                            <h1 class="text-2xl font-bold">Запланировано/пройдено у учеников</h1>
                            <Link v-for="pupil in usedForPupils" :key="pupil.id" :href="route('pupil.show', pupil.id)">
                                <Card class="grid row-span-1 col-span-1 place-items-center mb-2">
                                    <h1 class="text-2xl font-bold">{{ pupil.full_name }}</h1>
                                </Card>
                            </Link>
                        </Card>
                        <Card class="space-y-2">
                            <h1 class="text-2xl font-bold">Используется в уроках</h1>
                            <SmallLessonCard v-for="lesson in learning_material.lessons" :key="lesson.id" :lesson="lesson"/>
                        </Card>-->
        </div>
    </AppLayout>
</template>
<style src="vue-multiselect/dist/vue-multiselect.css"></style>
