<script setup>
import { Link } from '@inertiajs/vue3'
defineProps({ title: String, description: String, back: String })
</script>

<template>
    <div class="admin-page-header">
        <div>
            <Link v-if="back" :href="back" class="text-link admin-back">Kembali ke daftar</Link
            ><span v-else class="eyebrow">Workspace OTEWE</span>
            <h1>{{ title }}</h1>
            <p v-if="description">{{ description }}</p>
        </div>
        <div class="header-actions"><slot /></div>
    </div>
</template>
