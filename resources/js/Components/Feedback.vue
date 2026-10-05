<script setup>
import { ref, computed, watch, onMounted, onBeforeUnmount } from 'vue'
import { router, usePage } from '@inertiajs/vue3'
import Icon from './Icon.vue'
const page = usePage()
const dismissed = ref(false)
const networkError = ref('')
const loading = ref(false)
const removers = []
const message = computed(() => page.props.flash?.message || page.props.flash?.status)
watch(
    () => [page.props.flash, page.props.errors],
    () => {
        dismissed.value = false
    },
    { deep: true },
)
onMounted(() => {
    removers.push(
        router.on('start', () => {
            loading.value = true
            networkError.value = ''
        }),
    )
    removers.push(
        router.on('finish', () => {
            loading.value = false
        }),
    )
    removers.push(
        router.on('exception', (event) => {
            networkError.value = 'Halaman gagal dimuat. Periksa koneksi lalu coba kembali.'
            loading.value = false
            event.preventDefault()
        }),
    )
})
onBeforeUnmount(() => removers.forEach((remove) => remove()))
</script>

<template>
    <div v-if="loading" class="navigation-status" role="status">Memuat halaman…</div>
    <div v-if="networkError" class="notice notice-error" role="alert">
        <p>{{ networkError }}</p>
        <button class="text-button" @click="router.reload()">Coba kembali</button>
    </div>
    <Transition name="notice">
        <div v-if="message && !dismissed" class="notice notice-success" role="status">
            <Icon name="check" />
            <p>{{ message }}</p>
            <button class="icon-button" aria-label="Tutup pemberitahuan" @click="dismissed = true">
                <Icon name="close" />
            </button>
        </div>
    </Transition>
    <div v-if="page.props.errors?.delete" class="notice notice-error" role="alert">
        {{ page.props.errors.delete }}
    </div>
</template>
