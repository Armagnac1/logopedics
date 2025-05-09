<script setup>
import BackButton from '@/Components/BackButton.vue';
import Textarea from '@/Components/Textarea.vue';
import Card from '@/Components/Card.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import { Link, router, useForm } from '@inertiajs/vue3';
import InputLabel from '@/Components/InputLabel.vue';
import TextInput from '@/Components/TextInput.vue';
import InputError from '@/Components/InputError.vue';
import AdvancedSelect from '@/Components/AdvancedSelect.vue';
import AttachedFiles from '@/Components/File/AttachedFiles.vue';
import SmallLessonCard from '@/Components/Lesson/SmallLessonCard.vue';
import TopBarLayout from '@/Layouts/TopBarLayout.vue';

const props = defineProps({
    learning_material: Object,
    usedForPupils: Array,
    tags: Array
})

const tagMap = (i) => {
    return { name: i.name.ru, id: i.id }
}
const tagsOptions = props.tags.map(tagMap)

const form = useForm({
    title: props.learning_material.title,
    text: props.learning_material.text,
    tags: props.learning_material.tags.map(tagMap),
    media: props.learning_material.media
})

const submit = () => {
    form.transform(({ media, ...data }) => ({
        ...data,
        mediaIds: media.map(i => i.id)
    })).put(route('learning_material.update', props.learning_material.id), {
        onSuccess: () => {
            router.reload()
        }
    })
}

</script>

<template>
    <TopBarLayout :title="learning_material.title">
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                <BackButton/>
                {{ learning_material.title }}
            </h2>
        </template>

        <div class="grid gap-3">
            <Card class="">
                <form class="space-y-5 " @submit.prevent="submit">
                    <InputLabel for="title" :value="$t('common.title')"/>
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
                        :label="$t('common.text')"
                        :rows="9"
                        v-model="form.text"
                    />
                    <div>
                        <InputLabel for="tags" :value="$t('common.category')"/>
                        <AdvancedSelect id="tags" track-by="id" :multiple="true" label="name" :options="tagsOptions"
                                        v-model="form.tags"/>
                    </div>
                    <div>
                        <InputLabel for="tags" :value="$t('common.files')"/>
                        <AttachedFiles :related-model-name="'App\\Models\\LearningMaterial'"
                                       :related-model-id="learning_material.id"
                                       v-model="form.media"/>
                    </div>
                    <div class="flex justify-end items-center gap-x-2 pt-4 sm:px-7 border-t dark:border-gray-700">
                        <PrimaryButton class="ms-4" :class="{ 'opacity-25': form.processing }"
                                       :disabled="form.processing">
                            {{ $t('common.save') }}
                        </PrimaryButton>
                    </div>
                </form>
            </Card>
            <Card class="space-y-2">
                <h1 class="text-2xl font-bold">{{ $t('common.plannedOrCompleted') }}</h1>
                <Link v-for="pupil in usedForPupils" :key="pupil.id" :href="route('pupil.show', pupil.id)">
                    <Card class="grid row-span-1 col-span-1 place-items-center mb-2">
                        <h1 class="text-2xl font-bold">{{ pupil.full_name }}</h1>
                    </Card>
                </Link>
            </Card>
            <Card class="space-y-2">
                <h1 class="text-2xl font-bold">{{ $t('common.usedInLessons') }}</h1>
                <SmallLessonCard v-for="lesson in learning_material.lessons" :key="lesson.id" :lesson="lesson"/>
            </Card>
        </div>
    </TopBarLayout>
</template>
