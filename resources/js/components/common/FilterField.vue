<template>
    <template v-if="type === 'text'">
        <InputText :value="value" @input="updated" class="w-full" />
    </template>
    <template v-if="type === 'select'">
        <Dropdown :modelValue="value" :options="options" optionLabel="label" optionValue="value" filter :show-clear="true" @change="updated"/>
    </template>
</template>
<script setup>
import { ref, onMounted, computed } from 'vue';


const emit = defineEmits(['update:modelValue']);

const props = defineProps({
    modelValue: {
        type: [String, Number, null],
        default: null,
        required: true
    },
    filter: {
        type: Object,
        required: true
    }
});

const value = ref(props.modelValue);

const type = computed(() => {
    if (props.filter.type) {
        return props.filter.type;
    }
    else if (props.filter.options) {
        return 'select';
    }
    return 'text';
});

const options = computed(() => {
    if (!props.filter.options) {
        return [];
    }
    return props.filter.options.map(option => {
        return {
            label: option.label,
            value: option.value
        };
    });
});


const updated = (e) => {
    if(e.target){
        value.value = e.target.value;
    }else{
        value.value = e.value;
    }
    emit('update:modelValue', value.value);
    emit('change', value.value);
};


</script>