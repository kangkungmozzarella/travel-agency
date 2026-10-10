<script setup>
import { Link } from '@inertiajs/vue3'
import { dateLabel, imageFallback } from '../lib/format'
defineProps({ blog: Object, revealDelay: { type: Number, default: 0 } })
</script>

<template>
    <article class="blog-card" v-reveal="revealDelay" v-tilt>
        <Link :href="'/blogs/' + blog.slug"
            ><div class="blog-image">
                <img
                    :src="blog.image_url"
                    :alt="blog.title"
                    loading="lazy"
                    @error="imageFallback"
                />
            </div>
            <div class="blog-body">
                <div class="article-meta">
                    <span>{{ blog.category?.name || 'Jurnal' }}</span
                    ><span>{{ dateLabel(blog.created_at) }}</span>
                </div>
                <h3>{{ blog.title }}</h3>
                <p>{{ blog.excerpt }}</p>
                <span class="article-link">Baca cerita</span>
            </div></Link
        >
    </article>
</template>
