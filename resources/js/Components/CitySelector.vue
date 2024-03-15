<script setup>
import InputLabel from '@/Components/InputLabel.vue';
import VueMultiselect from 'vue-multiselect';
import { ref } from 'vue';

const props = defineProps({
    modelValue: Number
})
const options = ref([])
const loadOptions = () => {
    axios.get(route('city.index'))
        .then(response => {
            options.value = response.data
        })
}

loadOptions()
</script>
<template>
    <InputLabel for="city" value="Город"/>
    <VueMultiselect selectLabel="Нажмите Enter чтобы выбрать"
                    selectedLabel="Выбрано"
                    deselectLabel="Нажмите Enter чтобы убрать"
                    placeholder="Выберите.."
                    track-by="id" label="name"
                    @update:model-value="$emit('update:modelValue', $event.id)"
                    :options="options"
                    :model-value="options.find(i=>i.id === modelValue)">
        <template #noResult>
            Город не найден. Попробуйте изменить поисковый запрос.
        </template>
    </VueMultiselect>
</template>

