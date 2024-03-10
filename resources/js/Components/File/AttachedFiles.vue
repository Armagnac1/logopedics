<script setup>
import FileUpload from '@/Components/File/FileUpload.vue';
import { ref } from 'vue';
import { Link, router } from '@inertiajs/vue3';
import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome';

const props = defineProps({
    modelValue: Array,
    relatedModelName: String,
    relatedModelId: Number
})
const emit = defineEmits(['update:modelValue', 'uploaded'])
const files = ref(props.modelValue)

const uploaded = (event) => {
    files.value = files.value.concat(event.files)
    emit('update:modelValue', files.value)
    emit('uploaded', event.files)
}

</script>

<template>
    <div v-for="file in files" :key="file.id">
        <a :href="file.url" target="_blank">{{ file.name }}</a>
        <Link :href="route('media.destroy', file.id)" method="delete">
            <FontAwesomeIcon icon="trash"/>
        </Link>
    </div>
    <FileUpload @uploaded="uploaded"
                class="mt-1 block w-full"
    />
</template>
