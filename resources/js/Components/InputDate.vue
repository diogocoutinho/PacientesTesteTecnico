<script setup>
import {onMounted, ref, watch} from 'vue';

const props = defineProps({
    modelValue: String,
});

defineEmits(['update:modelValue']);

const input = ref(null);
</script>

<template>
    <div>
        <input
            type="date"
            class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"
            style="min-width: 220px;"
            :value="modelValue"
            @input="$emit('update:modelValue', $event.target.value)"
        >
    </div>
</template>

<style scoped>

</style>
