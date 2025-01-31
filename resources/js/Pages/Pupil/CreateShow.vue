<script setup>
import BackButton from '@/Components/BackButton.vue';
import Card from '@/Components/Card.vue';
import { Link, useForm } from '@inertiajs/vue3';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import InputLabel from '@/Components/InputLabel.vue';
import InputError from '@/Components/InputError.vue';
import TextInput from '@/Components/TextInput.vue';
import CitySelector from '@/Components/CitySelector.vue';
import TopBarLayout from '@/Layouts/TopBarLayout.vue';
import SmallLessonCard from '@/Components/Lesson/SmallLessonCard.vue';
import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome';
import Textarea from '@/Components/Textarea.vue';
import AdvancedSelect from '@/Components/AdvancedSelect.vue';
import DeleteEntityButton from '@/Components/DeleteEntityButton.vue';


const props = defineProps({
    pupil: Object,
    tags: Array
})

const form = useForm({
    full_name: '',
    age: '',
    parent_name: '',
    time_zone: '',
    email: '',
    lesson_duration: '30',
    tutor_comments: '',
    city_id: null,
    tags: []
})

const tagMap = (i) => {
    return { name: i.name.ru, id: i.id }
}
const tagsOptions = props.tags.map(tagMap)

if (props.pupil) {
    form.full_name = props.pupil.full_name
    form.age = props.pupil.age
    form.parent_name = props.pupil.parent_name
    form.time_zone = props.pupil.time_zone
    form.email = props.pupil.email
    form.lesson_duration = props.pupil.lesson_duration
    form.tutor_comments = props.pupil.tutor_comments
    form.city_id = props.pupil.city?.id
    form.tags = props.pupil.tags.map(tagMap)
}
const submit = () => {
    form.transform(({ media, ...data }) => ({
        ...data
    })).submit(props.pupil ? 'put' : 'post',
        props.pupil ? route('pupil.update', props.pupil.id) : route('pupil.store'), {
            onSuccess: () => {
            }
        })
}

</script>

<template>
    <TopBarLayout title="Calendar">
        <template #header>
            <h2 class="font-semibold text-xl L dark:text-gray-200 leading-tight">
                <BackButton/>
                {{ props.pupil ? props.pupil.full_name : 'Новый ученик' }}
            </h2>
        </template>
        <div>
            <Card class="mb-5">
                <form @submit.prevent="submit">
                    <div class="grid grid-cols-1 md:grid-cols-3 border-b">
                        <div><h2 class="text-base font-semibold leading-7 text-gray-900">Персональная информация</h2>
                            <p class="mt-1 text-sm leading-6 text-gray-600">Заполните персональную информацию об
                                ученике</p></div>

                        <div class="md:col-span-2 mt-10 pb-7 grid grid-cols-1 gap-x-6 gap-y-8 sm:grid-cols-6">
                            <div class="sm:col-span-4">
                                <InputLabel for="full_name" value="Имя*"/>
                                <TextInput
                                    v-model="form.full_name"
                                    class="mt-1 block w-full"
                                    required
                                    type="text"
                                />
                                <InputError :message="form.errors.full_name" class="mt-2"/>
                            </div>
                            <div class="sm:col-span-4">
                                <InputLabel for="age" value="Возраст"/>
                                <TextInput
                                    v-model="form.age"
                                    class="mt-1 block w-full"
                                    type="text"
                                />
                                <InputError :message="form.errors.age" class="mt-2"/>
                            </div>
                            <div class="sm:col-span-4">
                                <InputLabel for="parent_name" value="Имя родителя"/>
                                <TextInput
                                    v-model="form.parent_name"
                                    class="mt-1 block w-full"
                                    type="text"
                                />
                                <InputError :message="form.errors.parent_name" class="mt-2"/>
                            </div>
                            <div class="sm:col-span-4">
                                <InputLabel for="time_zone" value="Временная зона"/>
                                <TextInput
                                    v-model="form.time_zone"
                                    class="mt-1 block w-full"
                                    type="text"
                                />
                                <InputError :message="form.errors.time_zone" class="mt-2"/>
                            </div>

                            <div class="sm:col-span-4">
                                <InputLabel for="email" value="Email"/>
                                <TextInput
                                    v-model="form.email"
                                    class="mt-1 block w-full"
                                    type="text"
                                />
                                <InputError :message="form.errors.email" class="mt-2"/>
                            </div>
                            <div class="sm:col-span-4">
                                <InputLabel for="tags" value="Группа"/>
                                <AdvancedSelect id="tags" v-model="form.tags" :multiple="true" :options="tagsOptions"
                                                label="name"
                                                track-by="id"/>
                            </div>
                            <div class="sm:col-span-4">
                                <CitySelector v-model="form.city_id"/>
                                <InputError :message="form.errors.city_id" class="mt-2"/>
                            </div>
                        </div>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-3 border-b">
                        <div><h2 class="text-base font-semibold leading-7 text-gray-900">Обучение</h2>
                            <p class="mt-1 text-sm leading-6 text-gray-600">Информация об обучении</p></div>

                        <div class="md:col-span-2 mt-10 pb-7 grid grid-cols-1 gap-x-6 gap-y-8 sm:grid-cols-6">
                            <div class="sm:col-span-4">
                                <InputLabel for="duration" value="Длительность урока (в минутах)"/>
                                <TextInput
                                    v-model="form.lesson_duration"
                                    class="mt-1 block w-full"
                                    type="number"
                                />
                                <InputError :message="form.errors.lesson_duration" class="mt-2"/>
                            </div>
                            <div class="sm:col-span-4">
                                <Textarea v-model="form.tutor_comments" :label="'Комментарии логопеда'"
                                          :rows="9"
                                          class="mt-1 block w-full"/>
                                <InputError :message="form.errors.email" class="mt-2"/>
                            </div>
                        </div>
                    </div>
                    <div class="flex justify-end items-center gap-x-2 mt-6 sm:px-7">
                        <PrimaryButton :class="{ 'opacity-25': form.processing }" :disabled="form.processing"
                                       class="ms-4">
                            Сохранить
                        </PrimaryButton>
                    </div>
                </form>
            </Card>
            <Card v-if="pupil" class="row-span-2 col-span-1 mb-5">
                <div class="py-1.5 pb-5 px-1 border-b border-gray-100 justify-between align-middle flex items-center">
                    <h3 class="text-xl font-semibold text-gray-800 dark:text-gray-200">Уроки</h3>
                    <Link :href="route('lesson.create', pupil.id)" draggable="false">
                        <PrimaryButton class="py-2 px-3 inline-flex items-center gap-x-2">
                            <font-awesome-icon icon="fa-solid fa-plus"/>
                            Создать
                        </PrimaryButton>
                    </Link>
                </div>
                <div class="divide-y divide-gray-100 gap-2">
                    <SmallLessonCard v-for="lesson in pupil.lessons" :key="lesson.id" :lesson="lesson" class="mb-2"/>
                </div>

            </Card>
            <Card class="row-span-2 col-span-1 flex justify-end items-center gap-x-2">
                <DeleteEntityButton  :entityName="'ученика'" :url="route('pupil.destroy', pupil.id)"></DeleteEntityButton>
            </Card>
        </div>
    </TopBarLayout>
</template>

