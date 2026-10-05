<script setup>
import { ref, onMounted, onUnmounted } from 'vue'
import { Head, Link, router } from '@inertiajs/vue3'
import PublicLayout from '../Layouts/PublicLayout.vue'
import PackageCard from '../Components/PackageCard.vue'
import BlogCard from '../Components/BlogCard.vue'
import EmptyState from '../Components/EmptyState.vue'
import Icon from '../Components/Icon.vue'
import CoastlineScene from '../Components/CoastlineScene.vue'
defineOptions({ layout: PublicLayout })
defineProps({ packages: Object, blogs: Object, packageCount: Number })
const search = ref('')
const heroVisual = ref(null)
const heroVisible = ref(true)
let heroObserver
onMounted(() => {
    heroObserver = new IntersectionObserver(([entry]) => {
        heroVisible.value = entry.isIntersecting
    })
    heroObserver.observe(heroVisual.value)
})
onUnmounted(() => heroObserver?.disconnect())
const submit = () =>
    router.get('/travel-packages', search.value.trim() ? { search: search.value.trim() } : {})
</script>

<template>
    <Head title="Perjalanan berikutnya"
        ><meta
            name="description"
            content="Temukan paket wisata OTEWE, baca jurnal perjalanan, dan kirim permintaan booking untuk liburan berikutnya."
    /></Head>
    <section class="container home-hero">
        <div class="hero-copy" v-reveal.left>
            <span class="eyebrow">Saatnya mengambil jeda</span>
            <h1>Pergi sebentar.<br />Pulang dengan<br /><em>cerita baru.</em></h1>
            <p>Ada tempat yang belum kamu lihat.<br />Temukan perjalanan yang ingin kamu jalani.</p>
            <form class="hero-search" role="search" @submit.prevent="submit">
                <div>
                    <Icon name="pin" /><label for="destination-search" class="sr-only"
                        >Cari destinasi</label
                    ><input
                        id="destination-search"
                        v-model="search"
                        maxlength="100"
                        placeholder="Mau pergi ke mana?"
                    />
                </div>
                <button class="button button-primary" type="submit">
                    Cari perjalanan <Icon name="search" :size="18" />
                </button>
            </form>
            <div class="hero-note">
                <span class="route-mark" aria-hidden="true">⌁</span
                ><span>Pilih destinasi. Tentukan tanggal.<br />Kami bantu langkah berikutnya.</span>
            </div>
        </div>
        <div ref="heroVisual" class="hero-visual" :class="{ 'motion-paused': !heroVisible }">
            <CoastlineScene />
            <div class="hero-caption">
                <Icon name="compass" :size="28" />
                <div>
                    <strong>Jeda yang kamu cari</strong
                    ><span>Mulai dari satu rencana perjalanan.</span>
                </div>
            </div>
            <span class="hero-vertical" aria-hidden="true">Bawa pulang cerita.</span>
        </div>
    </section>
    <section id="destinasi" class="container section-space">
        <div class="section-heading" v-reveal>
            <div>
                <span class="eyebrow">Pilihan perjalanan</span>
                <h2>Ke mana langkah<br />berikutnya?</h2>
            </div>
            <div class="section-heading-aside">
                <p v-if="packageCount">
                    {{ packageCount }} paket wisata tersedia.<br />Pilih yang sesuai rencana
                    liburanmu.
                </p>
                <p v-else>Paket perjalanan akan hadir di sini.</p>
                <Link href="/travel-packages" class="text-link"
                    >Semua paket <Icon name="arrow" :size="18"
                /></Link>
            </div>
        </div>
        <div v-if="packages.data.length" class="package-grid home-package-grid">
            <PackageCard
                v-for="(item, index) in packages.data"
                :key="item.id"
                :package="item"
                :reveal-delay="(index % 4) * 100"
            />
        </div>
        <EmptyState
            v-else
            title="Perjalanan sedang disiapkan"
            description="Belum ada paket wisata yang tersedia. Hubungi kami untuk membicarakan rencana perjalananmu."
            href="/contact"
            action="Hubungi tim OTEWE"
        />
    </section>
    <section class="travel-note" v-reveal>
        <div class="container travel-note-inner">
            <div class="travel-note-number" aria-hidden="true">
                <Icon name="mountain" :size="100" />
            </div>
            <div>
                <span class="eyebrow">Liburan dengan rencanamu sendiri</span>
                <h2>Mulai dari destinasi.<br />Sisanya, kita bicarakan.</h2>
            </div>
            <div>
                <p>
                    Lihat detail paket, kirim tanggal yang kamu inginkan, lalu tim kami akan
                    menghubungimu untuk konfirmasi perjalanan.
                </p>
                <Link href="/contact" class="button button-secondary"
                    >Bicarakan rencana liburan</Link
                >
            </div>
        </div>
    </section>
    <section class="container section-space journal-section">
        <div class="section-heading" v-reveal>
            <div>
                <span class="eyebrow">Jurnal perjalanan</span>
                <h2>Catatan sebelum berangkat.</h2>
            </div>
            <Link href="/blogs" class="text-link"
                >Semua cerita <Icon name="arrow" :size="18"
            /></Link>
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
            title="Belum ada catatan perjalanan"
            description="Cerita dan panduan perjalanan yang diterbitkan akan tampil di sini."
        />
    </section>
</template>
