<script setup>
import { Link } from '@inertiajs/vue3'
defineProps({ links: Array, meta: Object })
const label = (link) =>
    link.label.includes('Previous') || link.label.includes('pagination.previous')
        ? 'Sebelumnya'
        : link.label.includes('Next') || link.label.includes('pagination.next')
          ? 'Berikutnya'
          : link.label.replace(/&[^;]+;/g, '').trim()
</script>

<template>
    <nav v-if="links?.length > 3" class="pagination" aria-label="Halaman daftar">
        <template v-for="(link, index) in links" :key="index">
            <Link
                v-if="link.url"
                :href="link.url"
                class="pagination-link"
                :class="{ active: link.active }"
                :aria-current="link.active ? 'page' : undefined"
                preserve-scroll
                >{{ label(link) }}</Link
            >
            <span v-else class="pagination-link disabled">{{ label(link) }}</span>
        </template>
    </nav>
</template>
