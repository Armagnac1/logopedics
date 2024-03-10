<script setup>

import { ref } from 'vue';

const props = defineProps({
    modelValue: Array,
    relatedModelName: String,
    relatedModelId: Number
})
const emit = defineEmits(['uploaded'])
const submit = (event) => {
    let formData = new FormData();
    for (let i = 0; i < event.target.files.length; i++) {
        formData.append('files[]', event.target.files[i]);
    }
    axios.post(route('media.store'), formData)
        .then(response => {
            emit('uploaded', response.data)
        })
        .catch((error) => {
            console.error('Error:', error);
        });
}

</script>

<template>
    <label class="block">
        <span class="sr-only">Загрузить файлы</span>
        <input type="file"
               @input="submit"
               multiple
               class="block w-full text-sm text-gray-500
      file:me-4 file:py-2 file:px-4
      file:rounded-lg file:border-0
      file:text-sm file:font-semibold
      file:bg-blue-600 file:text-white
      hover:file:bg-blue-700
      file:disabled:opacity-50 file:disabled:pointer-events-none
      dark:file:bg-blue-500
      dark:hover:file:bg-blue-400
    ">
    </label>
</template>
