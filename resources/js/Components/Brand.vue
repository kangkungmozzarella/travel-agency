<script setup>
import { Link } from '@inertiajs/vue3'
defineProps({ href: { type: String, default: '/' }, light: Boolean })
</script>

<template>
    <Link :href="href" class="brand" :class="{ 'brand-light': light }" aria-label="OTEWE, beranda">
        <span class="brand-word">otewe<span class="brand-dot">.</span></span>
        <span class="brand-caption">Ruang untuk perjalanan</span>
    </Link>
</template>
