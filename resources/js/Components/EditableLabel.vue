<script setup>
import { nextTick, ref } from 'vue';
import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome';
import { useForm } from '@inertiajs/vue3';

const props = defineProps({
    modelValue: String,
    field: String,
    route_url: String
});


const input = ref(null);
const edited = ref(false)

const form = useForm({
    [props.field]: props.modelValue
})
const editStart = () => {
    edited.value = true
    nextTick().then(() => {
        input.value.focus()
    })
}

const editEnd = () => {
    setTimeout(() => {
        edited.value = false
    }, 5);
}

const submit = () => {
    form.submit('put', props.route_url, {
        preserveScroll: true,
        onSuccess: () => {
            editEnd()
        }
    })
}

</script>

<template>
    <div @keydown.enter="submit" @keydown.esc="editEnd">
        <div @click="editStart"  class="flex group items-center cursor-pointer" v-show="!edited">
            <span v-if="modelValue">{{ modelValue }}</span>
            <span v-else class="text-gray-400 font-normal italic">Без названия</span>
            <FontAwesomeIcon class="ml-1.5 size-3 group-hover:visible invisible" icon="fa-regular fa-pen-to-square"/>
        </div>
        <div class="flex" v-show="edited">
            <input
                @blur="editEnd"
                ref="input"
                v-model="form[props.field]"
                class="py-0 px-0 w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm"
            >
            <FontAwesomeIcon class="ml-1.5 size-5 text-green-600 cursor-pointer" icon="fa-check" @mousedown="submit"/>
            <FontAwesomeIcon class="ml-1.5 size-5 text-red-600 cursor-pointer" icon="fa-xmark" @click="editEnd"/>
        </div>
    </div>
</template>
