<script setup>
import { ref, watch, onBeforeUnmount, useId } from 'vue'
import { imageFallback } from '../lib/format'
const props = defineProps({
    modelValue: [File, Object],
    label: { type: String, default: 'Foto' },
    error: String,
    existing: String,
})
defineEmits(['update:modelValue'])
const id = useId()
const preview = ref(props.existing)
const input = ref(null)
let objectUrl
watch(
    () => props.modelValue,
    (value) => {
        if (objectUrl) URL.revokeObjectURL(objectUrl)
        objectUrl = value ? URL.createObjectURL(value) : null
        preview.value = objectUrl || props.existing
        if (!value && input.value) input.value.value = ''
    },
)
onBeforeUnmount(() => {
    if (objectUrl) URL.revokeObjectURL(objectUrl)
})
</script>

<template>
    <div class="field upload-field">
        <label :for="id">{{ label }}</label>
        <img
            v-if="preview"
            :src="preview"
            alt="Pratinjau foto"
            class="upload-preview"
            @error="imageFallback"
        />
        <input
            ref="input"
            :id="id"
            type="file"
            accept="image/png,image/jpeg,image/webp"
            :aria-invalid="!!error"
            :aria-describedby="id + '-hint'"
            @change="$emit('update:modelValue', $event.target.files[0] || null)"
        />
        <p :id="id + '-hint'" class="field-hint">JPG, PNG, atau WebP. Maksimal 5 MB.</p>
        <p v-if="error" class="field-message" role="alert">{{ error }}</p>
    </div>
</template>
