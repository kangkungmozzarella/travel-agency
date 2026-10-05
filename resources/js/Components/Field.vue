<script setup>
import { useId } from 'vue'
defineOptions({ inheritAttrs: false })
defineProps({
    modelValue: [String, Number],
    label: String,
    error: String,
    hint: String,
    type: { type: String, default: 'text' },
})
defineEmits(['update:modelValue'])
const id = useId()
</script>

<template>
    <div class="field" :class="{ 'field-error': error }">
        <label :for="id">{{ label }}</label>
        <textarea
            v-if="type === 'textarea'"
            :id="id"
            v-bind="$attrs"
            :value="modelValue"
            :aria-invalid="!!error"
            :aria-describedby="error ? id + '-error' : hint ? id + '-hint' : undefined"
            @input="$emit('update:modelValue', $event.target.value)"
        />
        <input
            v-else
            :id="id"
            v-bind="$attrs"
            :type="type"
            :value="modelValue"
            :aria-invalid="!!error"
            :aria-describedby="error ? id + '-error' : hint ? id + '-hint' : undefined"
            @input="$emit('update:modelValue', $event.target.value)"
        />
        <p v-if="error" :id="id + '-error'" class="field-message" role="alert">{{ error }}</p>
        <p v-else-if="hint" :id="id + '-hint'" class="field-hint">{{ hint }}</p>
    </div>
</template>
