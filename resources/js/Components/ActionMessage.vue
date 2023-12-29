<script setup>
import { ref, watchEffect, onMounted, onUnmounted } from 'vue';

const props = defineProps({
    on: Boolean,
    duration: {
        type: Number,
        default: 5000,
    },
});

const show = ref(false);
const progress = ref(0);
let intervalId = null;

watchEffect(() => {
    if (props.on) {
        show.value = true;
        progress.value = 0;
        intervalId = setInterval(() => {
            progress.value += 100 / (props.duration / 100);
            if (progress.value >= 100) {
                clearInterval(intervalId);
                show.value = false;
                emit('close');
            }
        }, 100);
    } else {
        clearInterval(intervalId);
        progress.value = 0;
    }
});

const emit = defineEmits(['close']);
</script>

<template>
    <div v-if="show" class="fixed top-0 right-0 m-4 p-4 rounded bg-blue-500 shadow-lg max-w-sm min-w-64 relative">
        <div class="absolute bottom-0 left-0 w-full">
            <div class="overflow-hidden h-2 text-xs flex bg-blue-200">
                <div :style="{width: progress + '%'}"
                     class="shadow-none flex flex-col text-center whitespace-nowrap text-white justify-center bg-blue-800"></div>
            </div>
        </div>
        <div class="flex flex-col justify-between h-full">
            <div>
                <slot/>
            </div>
        </div>
    </div>
</template>
