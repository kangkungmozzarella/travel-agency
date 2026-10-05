<script setup>
import { ref, watch } from 'vue'
import { Link, usePage } from '@inertiajs/vue3'
import Brand from '../Components/Brand.vue'
import Icon from '../Components/Icon.vue'
import Feedback from '../Components/Feedback.vue'
const page = usePage()
const menu = ref(false)
const navigation = [
    { label: 'Beranda', href: '/' },
    { label: 'Paket wisata', href: '/travel-packages' },
    { label: 'Jurnal perjalanan', href: '/blogs' },
    { label: 'Kontak', href: '/contact' },
]
const active = (href) => (href === '/' ? page.url === '/' : page.url.startsWith(href))
watch(
    () => page.url,
    () => {
        menu.value = false
    },
)
</script>

<template>
    <div class="public-site">
        <a class="skip-link" href="#main">Lewati ke konten</a>
        <header class="site-header">
            <div class="container header-inner">
                <Brand />
                <nav class="desktop-nav" aria-label="Navigasi utama">
                    <Link
                        v-for="item in navigation"
                        :key="item.href"
                        :href="item.href"
                        :class="{ active: active(item.href) }"
                        :aria-current="active(item.href) ? 'page' : undefined"
                        >{{ item.label }}</Link
                    >
                </nav>
                <Link href="/travel-packages" class="button button-primary header-cta"
                    >Rencanakan liburan</Link
                ><button
                    class="button button-secondary mobile-menu-button"
                    aria-controls="mobile-navigation"
                    :aria-expanded="menu"
                    @click="menu = !menu"
                >
                    <Icon :name="menu ? 'close' : 'menu'" /> Menu
                </button>
            </div>
            <Transition name="menu"
                ><nav
                    v-if="menu"
                    id="mobile-navigation"
                    class="mobile-nav"
                    aria-label="Navigasi mobile"
                    @keydown.esc="menu = false"
                >
                    <Link
                        v-for="item in navigation"
                        :key="item.href"
                        :href="item.href"
                        :class="{ active: active(item.href) }"
                        >{{ item.label }}</Link
                    >
                </nav></Transition
            >
        </header>
        <main id="main">
            <div class="container"><Feedback /></div>
            <div :key="page.component" class="page-motion"><slot /></div>
        </main>
        <footer class="site-footer">
            <div class="container footer-main">
                <div>
                    <Brand light />
                    <p>
                        Temukan destinasi, baca cerita perjalanan,<br />dan rencanakan liburan
                        berikutnya.
                    </p>
                </div>
                <div class="footer-invitation">
                    <h2>Mau pergi ke mana?</h2>
                    <Link href="/travel-packages" class="button button-cream"
                        >Lihat paket wisata <Icon name="arrow"
                    /></Link>
                </div>
            </div>
            <div class="container footer-bottom">
                <span>© {{ new Date().getFullYear() }} OTEWE</span>
                <div>
                    <Link href="/contact">Hubungi kami</Link>
                </div>
            </div>
        </footer>
    </div>
</template>
