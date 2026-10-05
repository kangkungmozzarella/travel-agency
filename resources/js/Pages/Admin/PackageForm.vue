<script setup>
import { ref } from 'vue'
import { Head, Link, useForm, router } from '@inertiajs/vue3'
import AdminLayout from '../../Layouts/AdminLayout.vue'
import AdminPageHeader from '../../Components/AdminPageHeader.vue'
import Field from '../../Components/Field.vue'
import RichEditor from '../../Components/RichEditor.vue'
import UploadField from '../../Components/UploadField.vue'
import EmptyState from '../../Components/EmptyState.vue'
import ConfirmDialog from '../../Components/ConfirmDialog.vue'
import { imageFallback } from '../../lib/format'
defineOptions({ layout: AdminLayout })
const props = defineProps({ package: Object })
const form = useForm({
    location: props.package?.location || '',
    type: props.package?.type || '',
    price: props.package?.price ?? '',
    description: props.package?.description || '',
})
const gallery = useForm({ name: '', images: null })
const deleting = ref(null)
const processing = ref(false)
function submit() {
    props.package
        ? form.put('/admin/travel_packages/' + props.package.id, { preserveScroll: true })
        : form.post('/admin/travel_packages')
}
function upload() {
    gallery.post('/admin/travel_packages/' + props.package.id + '/galleries', {
        preserveScroll: true,
        onSuccess: () => gallery.reset(),
    })
}
function destroy() {
    router.delete(
        '/admin/travel_packages/' + props.package.id + '/galleries/' + deleting.value.id,
        {
            preserveScroll: true,
            onStart: () => (processing.value = true),
            onFinish: () => {
                deleting.value = null
                processing.value = false
            },
        },
    )
}
</script>

<template>
    <Head :title="package ? 'Edit ' + package.location : 'Tambah paket wisata'" />
    <AdminPageHeader
        :title="package ? 'Edit paket wisata' : 'Perjalanan baru'"
        description="Tuliskan informasi yang membantu pengunjung merencanakan perjalanan."
        back="/admin/travel_packages"
        ><Link
            v-if="package"
            :href="'/travel-packages/' + package.slug"
            class="button button-secondary"
            >Lihat halaman paket</Link
        ></AdminPageHeader
    >
    <form class="admin-form-layout" @submit.prevent="submit">
        <section class="panel form-panel">
            <div class="panel-heading">
                <div>
                    <h2>Detail perjalanan</h2>
                    <p>Destinasi dan deskripsi tampil di halaman publik.</p>
                </div>
            </div>
            <div class="form-panel-body">
                <Field
                    v-model="form.location"
                    label="Nama destinasi"
                    placeholder="Contoh: Bali, Indonesia"
                    name="location"
                    maxlength="255"
                    required
                    :error="form.errors.location"
                /><RichEditor v-model="form.description" :error="form.errors.description" />
                <p v-if="form.recentlySuccessful" class="inline-success" role="status">
                    Perubahan tersimpan.
                </p>
            </div>
        </section>
        <aside class="panel form-panel form-summary">
            <div class="panel-heading"><h2>Informasi paket</h2></div>
            <div class="form-panel-body">
                <Field
                    v-model="form.type"
                    label="Durasi perjalanan"
                    placeholder="Contoh: 4 hari 3 malam"
                    name="type"
                    maxlength="255"
                    required
                    :error="form.errors.type"
                /><Field
                    v-model="form.price"
                    type="number"
                    label="Harga paket (Rp)"
                    name="price"
                    min="0"
                    max="2147483647"
                    step="1"
                    placeholder="Contoh: 2500000"
                    required
                    :error="form.errors.price"
                />
                <p class="form-footnote">
                    Paket akan tampil di katalog setelah disimpan. Foto dapat ditambahkan pada
                    bagian galeri.
                </p>
                <button class="button button-primary button-full" :disabled="form.processing">
                    {{
                        form.processing
                            ? 'Menyimpan…'
                            : package
                              ? 'Simpan perubahan'
                              : 'Buat paket wisata'
                    }}</button
                ><Link href="/admin/travel_packages" class="button button-secondary button-full"
                    >Batal</Link
                >
            </div>
        </aside>
    </form>
    <section v-if="package" class="panel gallery-management">
        <div class="panel-heading">
            <div>
                <h2>Galeri destinasi</h2>
                <p>Foto pertama menjadi sampul paket. Gunakan foto yang sesuai dengan destinasi.</p>
            </div>
            <span class="muted">{{ package.galleries.length }} foto</span>
        </div>
        <div class="gallery-manager-body">
            <div>
                <div v-if="package.galleries.length" class="admin-gallery-grid">
                    <article v-for="photo in package.galleries" :key="photo.id">
                        <img :src="photo.image_url" :alt="photo.name" @error="imageFallback" />
                        <div>
                            <strong>{{ photo.name }}</strong>
                            <div class="table-actions">
                                <Link
                                    :href="
                                        '/admin/travel_packages/' +
                                        package.id +
                                        '/galleries/' +
                                        photo.id +
                                        '/edit'
                                    "
                                    class="table-action"
                                    >Edit foto</Link
                                ><button
                                    class="table-action danger-text"
                                    :aria-label="'Hapus foto ' + photo.name"
                                    @click="deleting = photo"
                                >
                                    Hapus
                                </button>
                            </div>
                        </div>
                    </article>
                </div>
                <EmptyState
                    v-else
                    title="Galeri masih kosong"
                    description="Tambahkan foto pertama agar paket memiliki sampul."
                />
            </div>
            <form class="gallery-upload" @submit.prevent="upload">
                <h3>Tambahkan foto</h3>
                <Field
                    v-model="gallery.name"
                    label="Nama / keterangan foto"
                    name="name"
                    required
                    maxlength="255"
                    :error="gallery.errors.name"
                /><UploadField v-model="gallery.images" :error="gallery.errors.images" /><progress
                    v-if="gallery.progress"
                    :value="gallery.progress.percentage"
                    max="100"
                >
                    {{ gallery.progress.percentage }}%</progress
                ><button class="button button-secondary button-full" :disabled="gallery.processing">
                    {{ gallery.processing ? 'Mengunggah…' : 'Tambahkan ke galeri' }}
                </button>
            </form>
        </div>
    </section>
    <ConfirmDialog
        :open="!!deleting"
        :title="'Hapus foto ' + (deleting?.name || '') + '?'"
        description="Foto akan dihapus dari galeri dan penyimpanan."
        :processing="processing"
        @close="deleting = null"
        @confirm="destroy"
    />
</template>
