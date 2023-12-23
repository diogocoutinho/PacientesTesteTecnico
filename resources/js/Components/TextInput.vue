<script setup>
import {onMounted, ref, watch} from 'vue';

const props = defineProps({
    modelValue: String,
    maskInput: {
        type: String,
        default: null,
    },
    id: String,
    name: String,
    disabled: {
        type: Boolean,
        default: false,
    },
});

const emit = defineEmits(['update:modelValue']);

const input = ref(null);
const maskedValue = ref(props.modelValue);

onMounted(() => {
    if (input.value.hasAttribute('autofocus')) {
        input.value.focus();
    }
});

defineExpose({focus: () => input.value.focus()});

const formatMask = () => {
    if (maskedValue.value === "" || props.maskInput === null) {
        return;
    }

    const numericInput = maskedValue.value.replace(/\D/g, "");
    const numericMask = props.maskInput.replace(/\D/g, "");
    let formattedNumber = "";
    let currentPosition = 0;

    for (const char of props.maskInput) {
        if (char === "#") {
            if (currentPosition < numericInput.length) {
                formattedNumber += numericInput[currentPosition];
                currentPosition++;
            } else {
                break;
            }
        } else if (/\d/.test(char)) {
            formattedNumber += char;
            if (currentPosition < numericMask.length && numericMask[currentPosition] === char) {
                currentPosition++;
            }
        } else {
            formattedNumber += char;
        }
    }

    maskedValue.value = formattedNumber;
    emit('update:modelValue', maskedValue.value);
};

const handleKeyDown = (event) => {
    const specialCharacters = ["-", " ", ".", ")", "(", "0", "1", "2", "3", "4", "5", "6", "7", "8", "9"];

    if (event.key === "Backspace") {
        if (specialCharacters.includes(maskedValue.value[maskedValue.value.length - 1])) {
            maskedValue.value = maskedValue.value.slice(0, -1);
            event.preventDefault();
        }
    }
};

const handleInput = (event) => {
    maskedValue.value = event.target.value;
    formatMask();
    emit('update:modelValue', maskedValue.value);
};

watch(() => props.modelValue, (newValue) => {
    maskedValue.value = newValue;
    formatMask();
});
</script>

<template>
    <input
        ref="input"
        class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"
        :value="maskedValue"
        @input="handleInput"
        :id="id"
        :name="name"
        @keydown="handleKeyDown"
        :disabled="disabled"
    >
</template>
