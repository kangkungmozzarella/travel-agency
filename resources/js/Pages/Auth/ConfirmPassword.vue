<script setup>
import { Head, useForm } from '@inertiajs/vue3'
import AuthLayout from '../../Layouts/AuthLayout.vue'
import Field from '../../Components/Field.vue'
defineOptions({ layout: AuthLayout })
const form = useForm({ password: '' })
</script>

<template>
    <Head title="Konfirmasi kata sandi" /><span class="eyebrow">Akses akun</span>
    <h1>Konfirmasi akunmu.</h1>
    <p class="auth-description">Masukkan kata sandi sebelum melanjutkan.</p>
    <form @submit.prevent="form.post('/password/confirm')">
        <Field
            v-model="form.password"
            label="Kata sandi"
            type="password"
            name="password"
            autocomplete="current-password"
            required
            :error="form.errors.password"
        /><button class="button button-primary button-full" :disabled="form.processing">
            {{ form.processing ? 'Memeriksa…' : 'Konfirmasi' }}
        </button>
    </form>
</template>
