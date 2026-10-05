<script setup>
import { Head, Link, useForm } from '@inertiajs/vue3'
import PublicLayout from '../../Layouts/PublicLayout.vue'
import PhotoGallery from '../../Components/PhotoGallery.vue'
import Field from '../../Components/Field.vue'
import PackageCard from '../../Components/PackageCard.vue'
import SafeContent from '../../Components/SafeContent.vue'
import Icon from '../../Components/Icon.vue'
import { money, today } from '../../lib/format'
defineOptions({ layout: PublicLayout })
const props = defineProps({ package: Object, related: Object })
const form = useForm({
    name: '',
    email: '',
    number_phone: '',
    date: '',
    travel_package_id: props.package.id,
})
function submit() {
    form.post('/booking', {
        preserveScroll: true,
        onSuccess: () => form.reset('name', 'email', 'number_phone', 'date'),
    })
}
</script>

<template>
    <Head :title="package.location"
        ><meta
            name="description"
            :content="
                'Lihat detail paket wisata ' +
                package.location +
                ' dan kirim permintaan booking melalui OTEWE.'
            "
    /></Head>
    <div class="container">
        <nav class="breadcrumbs" aria-label="Breadcrumb">
            <Link href="/travel-packages">Paket wisata</Link
            ><Icon name="chevron" :size="14" /><span>{{ package.location }}</span>
        </nav>
        <div class="detail-heading">
            <div>
                <span class="eyebrow">{{ package.type }}</span>
                <h1>{{ package.location }}</h1>
            </div>
            <a href="#booking" class="button button-primary"
                >Ajukan booking <Icon name="calendar" :size="18"
            /></a>
        </div>
        <PhotoGallery :images="package.galleries" :title="package.location" />
        <section class="detail-grid" v-reveal>
            <div class="trip-description">
                <span class="eyebrow">Tentang perjalanan</span>
                <h2>Sebelum kamu berangkat.</h2>
                <SafeContent :html="package.description" />
                <div class="trip-facts">
                    <div>
                        <span>Durasi</span><strong>{{ package.type }}</strong>
                    </div>
                    <div>
                        <span>Destinasi</span><strong>{{ package.location }}</strong>
                    </div>
                    <div>
                        <span>Harga paket</span><strong>{{ money(package.price) }}</strong>
                    </div>
                </div>
            </div>
            <aside id="booking" class="booking-panel">
                <span class="eyebrow">Rencanakan keberangkatan</span>
                <h2>Mulai perjalananmu.</h2>
                <p class="booking-price">{{ money(package.price) }} <span>/ paket</span></p>
                <p class="muted small">
                    Kirim permintaan booking. Tim kami akan menghubungi kamu untuk memastikan
                    tanggal dan detail perjalanan.
                </p>
                <form @submit.prevent="submit">
                    <Field
                        v-model="form.name"
                        label="Nama lengkap"
                        name="name"
                        autocomplete="name"
                        required
                        maxlength="255"
                        :error="form.errors.name"
                    /><Field
                        v-model="form.email"
                        label="Email"
                        name="email"
                        type="email"
                        autocomplete="email"
                        required
                        maxlength="255"
                        :error="form.errors.email"
                    /><Field
                        v-model="form.number_phone"
                        label="Nomor WhatsApp / telepon"
                        name="number_phone"
                        type="tel"
                        autocomplete="tel"
                        placeholder="Contoh: 081234567890"
                        required
                        :error="form.errors.number_phone"
                    /><Field
                        v-model="form.date"
                        label="Tanggal keberangkatan"
                        name="date"
                        type="date"
                        :min="today()"
                        required
                        :error="form.errors.date"
                    />
                    <p v-if="form.errors.travel_package_id" class="field-message" role="alert">
                        {{ form.errors.travel_package_id }}
                    </p>
                    <button class="button button-primary button-full" :disabled="form.processing">
                        {{ form.processing ? 'Mengirim permintaan…' : 'Kirim permintaan booking' }}
                    </button>
                    <p v-if="form.recentlySuccessful" class="inline-success" role="status">
                        Permintaan diterima. Kami akan menghubungimu untuk konfirmasi.
                    </p>
                    <p class="form-footnote">
                        Formulir ini mengirim permintaan booking. Pembayaran belum dilakukan pada
                        tahap ini.
                    </p>
                </form>
            </aside>
        </section>
        <section v-if="related.data.length" class="section-space" v-reveal>
            <div class="section-heading">
                <div>
                    <span class="eyebrow">Pilihan lainnya</span>
                    <h2>Masih ingin melihat-lihat?</h2>
                </div>
                <Link href="/travel-packages" class="text-link">Kembali ke katalog</Link>
            </div>
            <div class="package-grid">
                <PackageCard v-for="item in related.data" :key="item.id" :package="item" />
            </div>
        </section>
    </div>
</template>
