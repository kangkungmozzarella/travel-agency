<script setup>
import { ref, watch } from 'vue'
import { Link, usePage, router } from '@inertiajs/vue3'
import Brand from '../Components/Brand.vue'
import Icon from '../Components/Icon.vue'
import Feedback from '../Components/Feedback.vue'
const page = usePage()
const menu = ref(false)
const links = [
    { label: 'Ringkasan', href: '/admin/dashboard', icon: 'grid' },
    { label: 'Booking', href: '/admin/bookings', icon: 'calendar' },
    { label: 'Paket wisata', href: '/admin/travel_packages', icon: 'compass' },
    { label: 'Jurnal', href: '/admin/blogs', icon: 'book' },
    { label: 'Kategori', href: '/admin/categories', icon: 'folder' },
    { label: 'Pengguna', href: '/admin/users', icon: 'users' },
    { label: 'Profil saya', href: '/admin/profile', icon: 'settings' },
]
const active = (href) => page.url.startsWith(href)
watch(
    () => page.url,
    () => {
        menu.value = false
    },
)
</script>

<template>
    <div class="admin-shell">
        <a class="skip-link" href="#main">Lewati ke konten</a>
        <button
            v-if="menu"
            class="sidebar-backdrop"
            aria-label="Tutup navigasi admin"
            @click="menu = false"
        />
        <aside class="admin-sidebar" :class="{ 'sidebar-open': menu }" @keydown.esc="menu = false">
            <div class="sidebar-brand">
                <Brand href="/admin/dashboard" light /><span class="workspace-label"
                    >Workspace admin</span
                >
            </div>
            <nav aria-label="Navigasi admin">
                <Link
                    v-for="item in links"
                    :key="item.href"
                    :href="item.href"
                    :class="{ active: active(item.href) }"
                    :aria-current="active(item.href) ? 'page' : undefined"
                    ><Icon :name="item.icon" /><span>{{ item.label }}</span></Link
                >
            </nav>
            <div class="sidebar-bottom">
                <Link href="/" class="sidebar-site-link"><Icon name="arrow" /> Lihat website</Link
                ><button class="sidebar-site-link" @click="router.post('/logout')">
                    <Icon name="logout" /> Keluar
                </button>
                <div class="sidebar-user">
                    <span class="avatar">{{
                        page.props.auth.user.name.slice(0, 1).toUpperCase()
                    }}</span>
                    <div>
                        <strong>{{ page.props.auth.user.name }}</strong
                        ><span>Administrator</span>
                    </div>
                </div>
            </div>
        </aside>
        <div class="admin-workspace">
            <header class="admin-topbar">
                <div>
                    <button
                        class="button button-secondary admin-menu-button"
                        :aria-expanded="menu"
                        @click="menu = !menu"
                    >
                        <Icon name="menu" /> Menu</button
                    ><span class="topbar-label">OTEWE / Administrasi</span>
                </div>
                <Link href="/admin/profile" class="profile-link"
                    ><span class="avatar avatar-small">{{
                        page.props.auth.user.name.slice(0, 1).toUpperCase()
                    }}</span
                    ><span>{{ page.props.auth.user.name }}</span></Link
                >
            </header>
            <main id="main" class="admin-main">
                <Feedback />
                <div :key="page.component" class="page-motion"><slot /></div>
            </main>
        </div>
    </div>
</template>
