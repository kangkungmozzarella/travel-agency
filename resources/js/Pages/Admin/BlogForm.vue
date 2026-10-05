<script setup>
import { Head, Link, useForm } from '@inertiajs/vue3'
import AdminLayout from '../../Layouts/AdminLayout.vue'
import AdminPageHeader from '../../Components/AdminPageHeader.vue'
import Field from '../../Components/Field.vue'
import RichEditor from '../../Components/RichEditor.vue'
import UploadField from '../../Components/UploadField.vue'
defineOptions({ layout: AdminLayout })
const props = defineProps({ blog: Object, categories: Array })
const form = useForm({
    title: props.blog?.title || '',
    excerpt: props.blog?.excerpt || '',
    description: props.blog?.description || '',
    category_id: props.blog?.category_id || '',
    image: null,
})
function submit() {
    if (props.blog)
        form.transform((data) => ({ ...data, _method: 'put' })).post(
            '/admin/blogs/' + props.blog.id,
            { forceFormData: true },
        )
    else form.post('/admin/blogs', { forceFormData: true })
}
</script>

<template>
    <Head :title="blog ? 'Edit artikel' : 'Tulis artikel'" />
    <AdminPageHeader
        :title="blog ? 'Edit catatan perjalanan' : 'Cerita perjalanan baru'"
        description="Berikan informasi dan inspirasi yang berguna bagi pembaca."
        back="/admin/blogs"
        ><Link v-if="blog" :href="'/blogs/' + blog.slug" class="button button-secondary"
            >Lihat artikel</Link
        ></AdminPageHeader
    >
    <form class="admin-form-layout" @submit.prevent="submit">
        <section class="panel form-panel">
            <div class="panel-heading"><h2>Isi artikel</h2></div>
            <div class="form-panel-body">
                <Field
                    v-model="form.title"
                    label="Judul artikel"
                    name="title"
                    required
                    maxlength="255"
                    :error="form.errors.title"
                /><Field
                    v-model="form.excerpt"
                    type="textarea"
                    label="Ringkasan"
                    name="excerpt"
                    rows="3"
                    required
                    maxlength="2000"
                    hint="Ringkasan singkat yang tampil pada daftar jurnal."
                    :error="form.errors.excerpt"
                /><RichEditor
                    v-model="form.description"
                    label="Isi cerita"
                    :error="form.errors.description"
                />
            </div>
        </section>
        <aside class="panel form-panel">
            <div class="panel-heading"><h2>Publikasi</h2></div>
            <div class="form-panel-body">
                <div class="field">
                    <label for="article-category">Kategori</label
                    ><select
                        id="article-category"
                        v-model="form.category_id"
                        required
                        :aria-invalid="!!form.errors.category_id"
                    >
                        <option value="" disabled>Pilih kategori</option>
                        <option
                            v-for="category in categories"
                            :key="category.id"
                            :value="category.id"
                        >
                            {{ category.name }}
                        </option>
                    </select>
                    <p v-if="form.errors.category_id" class="field-message" role="alert">
                        {{ form.errors.category_id }}
                    </p>
                    <Link
                        v-if="!categories.length"
                        href="/admin/categories/create"
                        class="text-link"
                        >Buat kategori terlebih dahulu</Link
                    >
                </div>
                <UploadField
                    v-model="form.image"
                    label="Foto sampul"
                    :existing="blog?.image_url"
                    :error="form.errors.image"
                /><progress v-if="form.progress" :value="form.progress.percentage" max="100">
                    {{ form.progress.percentage }}%
                </progress>
                <p class="form-footnote">
                    Artikel akan langsung tampil di jurnal setelah disimpan.
                </p>
                <button
                    class="button button-primary button-full"
                    :disabled="form.processing || !categories.length"
                >
                    {{
                        form.processing
                            ? 'Menyimpan…'
                            : blog
                              ? 'Simpan perubahan'
                              : 'Terbitkan artikel'
                    }}</button
                ><Link href="/admin/blogs" class="button button-secondary button-full">Batal</Link>
            </div>
        </aside>
    </form>
</template>
