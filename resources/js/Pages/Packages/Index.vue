<script setup>
import { reactive, ref } from 'vue'
import { Head, router, Link } from '@inertiajs/vue3'
import PublicLayout from '../../Layouts/PublicLayout.vue'
import PackageCard from '../../Components/PackageCard.vue'
import Pagination from '../../Components/Pagination.vue'
import EmptyState from '../../Components/EmptyState.vue'
import Icon from '../../Components/Icon.vue'
defineOptions({ layout: PublicLayout })
const props = defineProps({ packages: Object, types: Array, filters: Object })
const filter = reactive({
    search: props.filters.search || '',
    type: props.filters.type || '',
    sort: props.filters.sort || 'newest',
})
const loading = ref(false)
function apply() {
    router.get('/travel-packages', filter, {
        preserveState: true,
        preserveScroll: true,
        onStart: () => (loading.value = true),
        onFinish: () => (loading.value = false),
    })
}
function reset() {
    Object.assign(filter, { search: '', type: '', sort: 'newest' })
    apply()
}
</script>

<template>
    <Head title="Paket wisata" />
    <section class="container page-intro" v-reveal>
        <div>
            <span class="eyebrow">Katalog perjalanan</span>
            <h1>Tempat baru.<br /><em>Kenangan berikutnya.</em></h1>
        </div>
        <p>Cari destinasi, bandingkan harga,<br />dan pilih perjalanan yang cocok untukmu.</p>
    </section>
    <section class="container catalogue-section">
        <form class="filter-bar" role="search" @submit.prevent="apply">
            <div class="field filter-search">
                <label for="package-search">Destinasi</label>
                <div class="input-icon">
                    <Icon name="search" /><input
                        id="package-search"
                        v-model="filter.search"
                        maxlength="100"
                        placeholder="Cari nama destinasi"
                    />
                </div>
            </div>
            <div class="field">
                <label for="package-type">Durasi perjalanan</label
                ><select id="package-type" v-model="filter.type" @change="apply">
                    <option value="">Semua durasi</option>
                    <option v-for="type in types" :key="type" :value="type">{{ type }}</option>
                </select>
            </div>
            <div class="field">
                <label for="package-sort">Urutkan</label
                ><select id="package-sort" v-model="filter.sort" @change="apply">
                    <option value="newest">Terbaru</option>
                    <option value="price_asc">Harga terendah</option>
                    <option value="price_desc">Harga tertinggi</option>
                </select>
            </div>
            <button class="button button-primary" :disabled="loading">
                {{ loading ? 'Mencari…' : 'Cari paket' }}
            </button>
        </form>
        <div class="results-heading">
            <span aria-live="polite">{{ packages.meta.total }} perjalanan ditemukan</span
            ><button
                v-if="filter.search || filter.type || filter.sort !== 'newest'"
                class="text-button"
                @click="reset"
            >
                Hapus filter
            </button>
        </div>
        <div v-if="packages.data.length" class="package-grid" :aria-busy="loading">
            <PackageCard
                v-for="(item, index) in packages.data"
                :key="item.id"
                :package="item"
                :reveal-delay="(index % 3) * 100"
            />
        </div>
        <EmptyState
            v-else
            title="Belum ada perjalanan yang cocok"
            description="Coba nama destinasi lain atau hapus filter pencarian."
        /><Pagination :links="packages.meta.links" />
        <div class="catalogue-help">
            <p>Belum menemukan perjalanan yang kamu cari?</p>
            <Link href="/contact" class="text-link">Bicarakan dengan tim kami</Link>
        </div>
    </section>
</template>
