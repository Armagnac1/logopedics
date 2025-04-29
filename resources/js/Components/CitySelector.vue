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
    <InputLabel for="city" :value="$t('citySelector.cityLabel')"/>
    <VueMultiselect :selectLabel="$t('citySelector.selectLabel')"
                    :selectedLabel="$t('citySelector.selectedLabel')"
                    :deselectLabel="$t('citySelector.deselectLabel')"
                    :placeholder="$t('citySelector.placeholder')"
                    track-by="id" label="name"
                    @update:model-value="$emit('update:modelValue', $event.id)"
                    :options="options"
                    :model-value="options.find(i=>i.id === modelValue)">
        <template #noResult>
            {{ $t('citySelector.noResult') }}
        </template>
    </VueMultiselect>
</template>
