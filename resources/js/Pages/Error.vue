<script setup>
import { computed } from 'vue'
import { Head, Link } from '@inertiajs/vue3'
import PublicLayout from '../Layouts/PublicLayout.vue'
defineOptions({ layout: PublicLayout })
const props = defineProps({ status: Number })
const messages = {
    403: ['Akses dibatasi.', 'Akunmu belum memiliki akses ke halaman ini.'],
    404: [
        'Sepertinya kita salah jalan.',
        'Halaman yang kamu cari tidak ditemukan. Coba kembali ke beranda.',
    ],
    419: ['Sesi sudah berakhir.', 'Muat ulang halaman lalu kirim formulir kembali.'],
    429: ['Tunggu sebentar, ya.', 'Terlalu banyak permintaan. Coba kembali dalam satu menit.'],
    500: [
        'Ada kendala di perjalanan.',
        'Kami belum bisa memuat halaman ini. Silakan coba kembali.',
    ],
    503: [
        'Kami sedang berbenah.',
        'Website sedang dalam pemeliharaan. Silakan kembali beberapa saat lagi.',
    ],
}
const message = computed(() => messages[props.status] || messages[500])
</script>

<template>
    <Head :title="message[0]" />
    <section class="container error-page">
        <span class="error-code">{{ status }}</span>
        <h1>{{ message[0] }}</h1>
        <p>{{ message[1] }}</p>
        <div class="button-row">
            <Link href="/" class="button button-primary">Kembali ke beranda</Link
            ><Link href="/contact" class="button button-secondary">Hubungi OTEWE</Link>
        </div>
    </section>
</template>
