<script setup>
import { Link } from '@inertiajs/vue3'
import Icon from './Icon.vue'
defineProps({
    title: { type: String, default: 'Belum ada data' },
    description: String,
    href: String,
    action: String,
})
</script>

<template>
    <div class="empty-state">
        <Icon name="compass" :size="36" />
        <h3>{{ title }}</h3>
        <p>{{ description }}</p>
        <Link v-if="href" :href="href" class="button button-secondary">{{ action }}</Link>
    </div>
</template>
