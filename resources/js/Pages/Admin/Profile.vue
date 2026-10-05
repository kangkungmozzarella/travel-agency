<script setup>
import { Head, useForm, usePage } from '@inertiajs/vue3'
import AdminLayout from '../../Layouts/AdminLayout.vue'
import AdminPageHeader from '../../Components/AdminPageHeader.vue'
import Field from '../../Components/Field.vue'
defineOptions({ layout: AdminLayout })
const page = usePage()
const form = useForm({
    name: page.props.auth.user.name,
    email: page.props.auth.user.email,
    password: '',
    password_confirmation: '',
})
function submit() {
    form.put('/admin/profile', {
        preserveScroll: true,
        onSuccess: () => form.reset('password', 'password_confirmation'),
    })
}
</script>

<template>
    <Head title="Profil saya" />
    <AdminPageHeader
        title="Profil saya"
        description="Perbarui informasi akun dan kata sandi untuk masuk ke workspace."
    />
    <form class="panel narrow-panel form-panel" @submit.prevent="submit">
        <div class="form-panel-body">
            <div class="profile-intro">
                <span class="avatar avatar-large">{{
                    page.props.auth.user.name.slice(0, 1).toUpperCase()
                }}</span>
                <div>
                    <h2>{{ page.props.auth.user.name }}</h2>
                    <p class="muted">Administrator OTEWE</p>
                </div>
            </div>
            <Field
                v-model="form.name"
                label="Nama lengkap"
                name="name"
                autocomplete="name"
                required
                :error="form.errors.name"
            /><Field
                v-model="form.email"
                label="Email"
                type="email"
                name="email"
                autocomplete="email"
                required
                :error="form.errors.email"
            />
            <div class="form-divider">
                <h3>Ubah kata sandi</h3>
                <p>Biarkan kosong untuk mempertahankan kata sandi saat ini.</p>
            </div>
            <Field
                v-model="form.password"
                label="Kata sandi baru"
                type="password"
                name="password"
                autocomplete="new-password"
                minlength="8"
                hint="Gunakan minimal 8 karakter."
                :error="form.errors.password"
            /><Field
                v-model="form.password_confirmation"
                label="Konfirmasi kata sandi baru"
                type="password"
                name="password_confirmation"
                autocomplete="new-password"
                :error="form.errors.password_confirmation"
            /><button class="button button-primary" :disabled="form.processing">
                {{ form.processing ? 'Menyimpan…' : 'Simpan profil' }}
            </button>
        </div>
    </form>
</template>
