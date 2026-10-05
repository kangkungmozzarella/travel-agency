<script setup>
import { Head, Link, useForm } from '@inertiajs/vue3'
import AdminLayout from '../../Layouts/AdminLayout.vue'
import AdminPageHeader from '../../Components/AdminPageHeader.vue'
import Field from '../../Components/Field.vue'
import UploadField from '../../Components/UploadField.vue'
defineOptions({ layout: AdminLayout })
const props = defineProps({ package: Object, gallery: Object })
const form = useForm({ name: props.gallery.name, images: null, _method: 'put' })
function submit() {
    form.post('/admin/travel_packages/' + props.package.id + '/galleries/' + props.gallery.id, {
        forceFormData: true,
    })
}
</script>

<template>
    <Head title="Edit foto galeri" />
    <AdminPageHeader
        title="Edit foto galeri"
        :description="package.location"
        :back="'/admin/travel_packages/' + package.id + '/edit'"
    />
    <form class="panel narrow-panel form-panel" @submit.prevent="submit">
        <div class="form-panel-body">
            <Field
                v-model="form.name"
                label="Nama / keterangan foto"
                name="name"
                required
                maxlength="255"
                :error="form.errors.name"
            /><UploadField
                v-model="form.images"
                :existing="gallery.image_url"
                :error="form.errors.images"
            />
            <p class="form-footnote">
                Biarkan pilihan foto kosong jika hanya ingin mengubah keterangannya.
            </p>
            <progress v-if="form.progress" :value="form.progress.percentage" max="100">
                {{ form.progress.percentage }}%
            </progress>
            <div class="button-row">
                <button class="button button-primary" :disabled="form.processing">
                    {{ form.processing ? 'Menyimpan…' : 'Simpan foto' }}</button
                ><Link
                    :href="'/admin/travel_packages/' + package.id + '/edit'"
                    class="button button-secondary"
                    >Batal</Link
                >
            </div>
        </div>
    </form>
</template>
