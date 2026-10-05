<script setup>
import { Head, Link } from '@inertiajs/vue3'
import PublicLayout from '../../Layouts/PublicLayout.vue'
import SafeContent from '../../Components/SafeContent.vue'
import BlogCard from '../../Components/BlogCard.vue'
import { dateLabel, imageFallback } from '../../lib/format'
defineOptions({ layout: PublicLayout })
defineProps({ blog: Object, related: Object })
</script>

<template>
    <Head :title="blog.title"><meta name="description" :content="blog.excerpt" /></Head>
    <article class="container article-detail">
        <Link href="/blogs" class="text-link article-back">Kembali ke jurnal</Link>
        <div class="article-title">
            <Link
                v-if="blog.category"
                :href="'/blogs/category/' + blog.category.slug"
                class="eyebrow"
                >{{ blog.category.name }}</Link
            >
            <h1>{{ blog.title }}</h1>
            <p class="article-lead">{{ blog.excerpt }}</p>
            <div class="article-meta">
                <span>{{ dateLabel(blog.created_at) }}</span
                ><span>{{ blog.reads }} kali dibaca</span>
            </div>
        </div>
        <img class="article-cover" :src="blog.image_url" :alt="blog.title" @error="imageFallback" />
        <div class="article-content" v-reveal>
            <SafeContent :html="blog.description" />
            <div class="article-end">
                <span>Catatan perjalanan OTEWE</span
                ><Link href="/travel-packages" class="text-link">Cari perjalanan berikutnya</Link>
            </div>
        </div>
    </article>
    <section v-if="related.data.length" class="container section-space" v-reveal>
        <div class="section-heading">
            <h2>Cerita lainnya.</h2>
            <Link href="/blogs" class="text-link">Semua artikel</Link>
        </div>
        <div class="journal-grid">
            <BlogCard v-for="item in related.data" :key="item.id" :blog="item" />
        </div>
    </section>
</template>
