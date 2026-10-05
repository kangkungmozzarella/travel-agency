<script setup>
import { Head, useForm } from '@inertiajs/vue3'
import AuthLayout from '../../Layouts/AuthLayout.vue'
import Field from '../../Components/Field.vue'
defineOptions({ layout: AuthLayout })
const props = defineProps({ token: String, email: String })
const form = useForm({
    token: props.token,
    email: props.email || '',
    password: '',
    password_confirmation: '',
})
</script>

<template>
    <Head title="Kata sandi baru" /><span class="eyebrow">Akses akun</span>
    <h1>Buat kata sandi baru.</h1>
    <p class="auth-description">Gunakan minimal 8 karakter untuk kata sandi akunmu.</p>
    <form @submit.prevent="form.post('/password/reset')">
        <Field
            v-model="form.email"
            label="Email akun"
            name="email"
            type="email"
            autocomplete="username"
            required
            :error="form.errors.email"
        /><Field
            v-model="form.password"
            label="Kata sandi baru"
            name="password"
            type="password"
            autocomplete="new-password"
            minlength="8"
            required
            :error="form.errors.password"
        /><Field
            v-model="form.password_confirmation"
            label="Konfirmasi kata sandi"
            name="password_confirmation"
            type="password"
            autocomplete="new-password"
            required
            :error="form.errors.password_confirmation"
        />
        <p v-if="form.errors.token" class="field-message" role="alert">{{ form.errors.token }}</p>
        <button class="button button-primary button-full" :disabled="form.processing">
            {{ form.processing ? 'Menyimpan…' : 'Simpan kata sandi baru' }}
        </button>
    </form>
</template>
