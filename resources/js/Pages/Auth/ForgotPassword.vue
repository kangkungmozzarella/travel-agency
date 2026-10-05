<script setup>
import { Head, Link, useForm } from '@inertiajs/vue3'
import AuthLayout from '../../Layouts/AuthLayout.vue'
import Field from '../../Components/Field.vue'
defineOptions({ layout: AuthLayout })
const form = useForm({ email: '' })
</script>

<template>
    <Head title="Lupa kata sandi" /><span class="eyebrow">Akses akun</span>
    <h1>Lupa kata sandi?</h1>
    <p class="auth-description">
        Masukkan email akunmu. Kami akan mengirim tautan untuk membuat kata sandi baru.
    </p>
    <form @submit.prevent="form.post('/password/email')">
        <Field
            v-model="form.email"
            label="Email akun"
            type="email"
            name="email"
            autocomplete="email"
            required
            :error="form.errors.email"
        /><button class="button button-primary button-full" :disabled="form.processing">
            {{ form.processing ? 'Mengirim…' : 'Kirim tautan pemulihan' }}</button
        ><Link href="/login" class="auth-forgot text-link">Kembali ke halaman masuk</Link>
    </form>
</template>
