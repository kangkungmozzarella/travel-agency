<script setup>
import { Head, Link, usePage } from '@inertiajs/vue3'
import PublicLayout from '../Layouts/PublicLayout.vue'
import Icon from '../Components/Icon.vue'
defineOptions({ layout: PublicLayout })
const page = usePage()
</script>

<template>
    <Head title="Hubungi OTEWE" />
    <section class="container contact-page">
        <div class="contact-copy" v-reveal.left>
            <span class="eyebrow">Bicarakan perjalananmu</span>
            <h1>Rencana liburan<br />dimulai dari<br /><em>obrolan kecil.</em></h1>
            <p>
                Butuh bantuan memilih destinasi atau ingin menanyakan detail paket? Hubungi tim
                OTEWE melalui WhatsApp atau telepon.
            </p>
            <div class="contact-actions">
                <a
                    :href="
                        'https://wa.me/' +
                        page.props.contact.whatsapp +
                        '?text=' +
                        encodeURIComponent(
                            'Halo OTEWE, saya ingin bertanya tentang rencana perjalanan.',
                        )
                    "
                    target="_blank"
                    rel="noopener noreferrer"
                    class="button button-primary"
                    ><Icon name="phone" /> Chat lewat WhatsApp</a
                ><a :href="'tel:' + page.props.contact.phone" class="button button-secondary"
                    >Telepon kami</a
                >
            </div>
            <div class="contact-number">
                <span>Nomor kontak</span><strong>{{ page.props.contact.phone }}</strong>
            </div>
            <Link href="/travel-packages" class="text-link"
                >Lihat paket wisata terlebih dahulu</Link
            >
        </div>
        <div class="contact-visual" v-reveal.right="120">
            <img
                src="/frontend/assets/img/kontakp.jpg"
                alt="Pemandangan untuk inspirasi perjalanan"
                class="contact-photo"
            />
            <div class="contact-visual-note">
                <Icon name="compass" :size="32" /><span
                    >Satu tujuan.<br />Banyak cerita menunggu.</span
                >
            </div>
        </div>
    </section>
</template>
