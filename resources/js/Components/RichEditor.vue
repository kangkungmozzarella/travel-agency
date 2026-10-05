<script setup>
import { ref, onMounted, watch, useId } from 'vue'
import DOMPurify from 'dompurify'
const props = defineProps({
    modelValue: String,
    label: { type: String, default: 'Deskripsi perjalanan' },
    error: String,
})
const emit = defineEmits(['update:modelValue'])
const editor = ref(null)
const id = useId()
const sanitize = (value) => DOMPurify.sanitize(value, { USE_PROFILES: { html: true } })
onMounted(() => {
    editor.value.innerHTML = sanitize(props.modelValue || '')
})
watch(
    () => props.modelValue,
    (value) => {
        if (document.activeElement !== editor.value) editor.value.innerHTML = sanitize(value || '')
    },
)
function update() {
    emit(
        'update:modelValue',
        editor.value.textContent.trim() ? sanitize(editor.value.innerHTML) : '',
    )
}
function format(command, value = null) {
    editor.value.focus()
    document.execCommand(command, false, value)
    update()
}
</script>

<template>
    <div class="field">
        <label :id="id + '-label'">{{ label }}</label>
        <div class="rich-editor" :class="{ 'editor-error': error }">
            <div class="editor-toolbar" role="group" aria-label="Format teks">
                <button type="button" @mousedown.prevent @click="format('bold')">
                    <strong>Tebal</strong>
                </button>
                <button type="button" @mousedown.prevent @click="format('italic')">
                    <em>Miring</em>
                </button>
                <button type="button" @mousedown.prevent @click="format('formatBlock', 'h2')">
                    Subjudul
                </button>
                <button type="button" @mousedown.prevent @click="format('insertUnorderedList')">
                    Daftar
                </button>
                <button type="button" @mousedown.prevent @click="format('formatBlock', 'p')">
                    Paragraf
                </button>
            </div>
            <div
                ref="editor"
                class="editor-content prose"
                contenteditable="true"
                role="textbox"
                aria-multiline="true"
                :aria-labelledby="id + '-label'"
                :aria-invalid="!!error"
                :aria-describedby="error ? id + '-error' : undefined"
                @input="update"
            />
        </div>
        <p v-if="error" :id="id + '-error'" class="field-message" role="alert">{{ error }}</p>
    </div>
</template>
