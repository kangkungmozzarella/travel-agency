<script setup>
import { Head, Link, useForm } from '@inertiajs/vue3'
import AdminLayout from '../../Layouts/AdminLayout.vue'
import AdminPageHeader from '../../Components/AdminPageHeader.vue'
import Field from '../../Components/Field.vue'
defineOptions({ layout: AdminLayout })
const props = defineProps({ category: Object })
const form = useForm({ name: props.category?.name || '' })
function submit() {
    props.category
        ? form.put('/admin/categories/' + props.category.id)
        : form.post('/admin/categories')
}
</script>

<template>
    <Head :title="category ? 'Edit kategori' : 'Tambah kategori'" />
    <AdminPageHeader
        :title="category ? 'Edit kategori' : 'Kategori baru'"
        description="Kelompokkan jurnal berdasarkan topik yang membantu pembaca."
        back="/admin/categories"
    />
    <form class="panel narrow-panel form-panel" @submit.prevent="submit">
        <div class="form-panel-body">
            <Field
                v-model="form.name"
                label="Nama kategori"
                name="name"
                maxlength="255"
                placeholder="Contoh: Panduan perjalanan"
                required
                :error="form.errors.name"
            />
            <p class="form-footnote">
                Kategori dapat dipilih saat membuat atau mengedit artikel jurnal.
            </p>
            <div class="button-row">
                <button class="button button-primary" :disabled="form.processing">
                    {{ form.processing ? 'Menyimpan…' : 'Simpan kategori' }}</button
                ><Link href="/admin/categories" class="button button-secondary">Batal</Link>
            </div>
        </div>
    </form>
</template>
