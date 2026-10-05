<script setup>
import { Link } from '@inertiajs/vue3'
import Brand from '../Components/Brand.vue'
import Feedback from '../Components/Feedback.vue'
</script>

<template>
    <main class="auth-layout">
        <aside class="auth-photo">
            <img src="/frontend/assets/img/hero.jpg" alt="Pemandangan laut dan pulau dari atas" />
            <div class="auth-photo-copy">
                <Brand light />
                <h2>Perjalanan yang baik<br />dimulai dari sini.</h2>
                <p>
                    Ruang kerja tim OTEWE untuk mengelola destinasi dan menemani perjalanan
                    berikutnya.
                </p>
            </div>
        </aside>
        <div class="auth-panel">
            <div class="auth-mobile-brand"><Brand /></div>
            <div class="auth-card page-motion">
                <Feedback /><slot /><Link href="/" class="auth-back">Kembali ke website</Link>
            </div>
            <p class="auth-footer">OTEWE · Ruang untuk perjalanan</p>
        </div>
    </main>
</template>
