<script setup>
import { ref, watch, nextTick } from 'vue'
const props = defineProps({
    open: Boolean,
    title: String,
    description: String,
    processing: Boolean,
})
const emit = defineEmits(['close', 'confirm'])
const dialog = ref(null)
watch(
    () => props.open,
    async (open) => {
        await nextTick()
        if (open && !dialog.value.open) dialog.value.showModal()
        else if (!open && dialog.value.open) dialog.value.close()
    },
)
</script>

<template>
    <dialog
        ref="dialog"
        class="confirm-dialog"
        aria-labelledby="confirm-title"
        @cancel.prevent="!processing && emit('close')"
        @click="
            (event) => {
                if (event.target === dialog && !processing) emit('close')
            }
        "
    >
        <span class="eyebrow">Konfirmasi penghapusan</span>
        <h2 id="confirm-title">{{ title }}</h2>
        <p>{{ description }}</p>
        <div class="button-row">
            <button
                type="button"
                class="button button-secondary"
                :disabled="processing"
                autofocus
                @click="emit('close')"
            >
                Batal</button
            ><button
                type="button"
                class="button button-danger"
                :disabled="processing"
                @click="emit('confirm')"
            >
                {{ processing ? 'Menghapus…' : 'Ya, hapus' }}
            </button>
        </div>
    </dialog>
</template>
