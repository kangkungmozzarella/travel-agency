<script setup>
import { ref } from 'vue'
import { Head, Link, router } from '@inertiajs/vue3'
import PublicLayout from '../../Layouts/PublicLayout.vue'
import BlogCard from '../../Components/BlogCard.vue'
import EmptyState from '../../Components/EmptyState.vue'
import Pagination from '../../Components/Pagination.vue'
defineOptions({ layout: PublicLayout })
const props = defineProps({ blogs: Object, categories: Array, category: Object, filters: Object })
const search = ref(props.filters.search || '')
const loading = ref(false)
function submit() {
    router.get(
        props.category ? '/blogs/category/' + props.category.slug : '/blogs',
        { search: search.value },
        {
            preserveState: true,
            onStart: () => (loading.value = true),
            onFinish: () => (loading.value = false),
        },
    )
}
</script>

<template>
    <Head :title="category ? category.name + ' · Jurnal' : 'Jurnal perjalanan'" />
    <section class="container page-intro" v-reveal>
        <div>
            <span class="eyebrow">Jurnal OTEWE</span>
            <h1>Cerita yang membuat<br /><em>kita ingin pergi.</em></h1>
        </div>
        <p>Catatan destinasi dan panduan<br />untuk perjalanan berikutnya.</p>
    </section>
    <section class="container catalogue-section">
        <div class="journal-filters">
            <nav class="category-tabs" aria-label="Kategori artikel">
                <Link href="/blogs" :class="{ active: !category }">Semua cerita</Link
                ><Link
                    v-for="item in categories"
                    :key="item.id"
                    :href="'/blogs/category/' + item.slug"
                    :class="{ active: category?.id === item.id }"
                    >{{ item.name }}</Link
                >
            </nav>
            <form class="journal-search" role="search" @submit.prevent="submit">
                <label for="journal-search" class="sr-only">Cari artikel</label
                ><input
                    id="journal-search"
                    v-model="search"
                    maxlength="100"
                    placeholder="Cari cerita perjalanan"
                /><button class="button button-secondary" :disabled="loading">
                    {{ loading ? 'Mencari…' : 'Cari' }}
                </button>
            </form>
        </div>
        <div class="results-heading">
            <span
                >{{ blogs.meta.total }} artikel
                {{ category ? 'di kategori ' + category.name : 'tersedia' }}</span
            >
        </div>
        <div v-if="blogs.data.length" class="journal-grid">
            <BlogCard
                v-for="(blog, index) in blogs.data"
                :key="blog.id"
                :blog="blog"
                :reveal-delay="(index % 3) * 100"
            />
        </div>
        <EmptyState
            v-else
            title="Belum ada cerita di sini"
            description="Coba kategori lain atau ubah kata pencarianmu."
            href="/blogs"
            action="Lihat semua cerita"
        /><Pagination :links="blogs.meta.links" />
    </section>
</template>
