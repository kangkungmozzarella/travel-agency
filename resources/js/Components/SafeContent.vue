<script setup>
import { computed } from 'vue'
import DOMPurify from 'dompurify'
const props = defineProps({ html: String })
const content = computed(() =>
    DOMPurify.sanitize(props.html || '', { USE_PROFILES: { html: true } }),
)
</script>

<template><div class="prose" v-html="content" /></template>
