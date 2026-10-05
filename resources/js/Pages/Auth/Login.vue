<script setup>
import { ref } from 'vue'
import { Head, Link, useForm } from '@inertiajs/vue3'
import AuthLayout from '../../Layouts/AuthLayout.vue'
import Field from '../../Components/Field.vue'
defineOptions({ layout: AuthLayout })
const visible = ref(false)
const form = useForm({ email: '', password: '', remember: false })
function submit() {
    form.post('/login', { onFinish: () => form.reset('password') })
}
</script>

<template>
    <Head title="Masuk ke workspace" />
    <span class="eyebrow">Area admin</span>
    <h1>Selamat datang<br />kembali.</h1>
    <p class="auth-description">Masuk untuk mengelola perjalanan, booking, dan cerita OTEWE.</p>
    <form @submit.prevent="submit">
        <Field
            v-model="form.email"
            label="Email"
            type="email"
            name="email"
            autocomplete="username"
            required
            autofocus
            :error="form.errors.email"
        /><Field
            v-model="form.password"
            label="Kata sandi"
            :type="visible ? 'text' : 'password'"
            name="password"
            autocomplete="current-password"
            required
            :error="form.errors.password"
        />
        <div class="auth-options">
            <label class="checkbox-label"
                ><input v-model="form.remember" type="checkbox" name="remember" /> Ingat saya</label
            ><button
                type="button"
                class="text-button"
                :aria-pressed="visible"
                @click="visible = !visible"
            >
                {{ visible ? 'Sembunyikan' : 'Tampilkan' }} kata sandi
            </button>
        </div>
        <button class="button button-primary button-full" :disabled="form.processing">
            {{ form.processing ? 'Memeriksa akun…' : 'Masuk ke workspace' }}</button
        ><Link href="/password/reset" class="auth-forgot text-link">Lupa kata sandi?</Link>
    </form>
</template>
