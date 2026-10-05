<script setup>
import { computed, reactive, ref, watch } from 'vue'
import { Head, Link, router } from '@inertiajs/vue3'
import AdminLayout from '../../Layouts/AdminLayout.vue'
import AdminPageHeader from '../../Components/AdminPageHeader.vue'
import Pagination from '../../Components/Pagination.vue'
import EmptyState from '../../Components/EmptyState.vue'
import ConfirmDialog from '../../Components/ConfirmDialog.vue'
import Icon from '../../Components/Icon.vue'
import { money, dateLabel, statusLabels, imageFallback } from '../../lib/format'
defineOptions({ layout: AdminLayout })
const props = defineProps({ kind: String, items: Object, filters: Object })
const definitions = {
    travel_packages: {
        title: 'Paket wisata',
        description: 'Kelola destinasi, harga, deskripsi, dan galeri perjalanan.',
        create: 'Tambah paket',
        empty: 'Belum ada paket wisata',
    },
    blogs: {
        title: 'Jurnal perjalanan',
        description: 'Terbitkan cerita dan panduan untuk calon peserta perjalanan.',
        create: 'Tulis artikel',
        empty: 'Belum ada artikel',
    },
    categories: {
        title: 'Kategori jurnal',
        description: 'Susun artikel berdasarkan topik perjalanan.',
        create: 'Tambah kategori',
        empty: 'Belum ada kategori',
    },
    bookings: {
        title: 'Booking perjalanan',
        description: 'Tindak lanjuti permintaan, hubungi peserta, dan perbarui status perjalanan.',
        empty: 'Belum ada booking yang cocok',
    },
    users: {
        title: 'Pengguna',
        description: 'Daftar akun yang terdaftar di aplikasi.',
        empty: 'Belum ada pengguna yang cocok',
    },
}
const config = computed(() => definitions[props.kind])
const base = computed(() => '/admin/' + props.kind)
const filter = reactive({ search: props.filters.search || '', status: props.filters.status || '' })
const loading = ref(false)
const deleting = ref(null)
const processing = ref(false)
watch(
    () => [props.kind, props.filters],
    () =>
        Object.assign(filter, {
            search: props.filters.search || '',
            status: props.filters.status || '',
        }),
)
const pagination = computed(() => props.items.meta?.links || props.items.links)
const total = computed(() => props.items.meta?.total ?? props.items.total)
const itemName = (item) => item.location || item.title || item.name
function search() {
    router.get(base.value, filter, {
        preserveState: true,
        preserveScroll: true,
        onStart: () => (loading.value = true),
        onFinish: () => (loading.value = false),
    })
}
function reset() {
    Object.assign(filter, { search: '', status: '' })
    search()
}
function destroy() {
    router.delete(base.value + '/' + deleting.value.id, {
        preserveScroll: true,
        onStart: () => (processing.value = true),
        onFinish: () => {
            processing.value = false
            deleting.value = null
        },
    })
}
function updateStatus(booking, status) {
    router.patch(
        '/admin/bookings/' + booking.id,
        { status },
        {
            preserveScroll: true,
            onStart: () => (loading.value = true),
            onFinish: () => (loading.value = false),
        },
    )
}
</script>

<template>
    <Head :title="config.title" />
    <AdminPageHeader :title="config.title" :description="config.description"
        ><Link v-if="config.create" :href="base + '/create'" class="button button-primary"
            ><Icon name="plus" />{{ config.create }}</Link
        ></AdminPageHeader
    >
    <section class="panel">
        <form class="admin-filter" role="search" @submit.prevent="search">
            <div class="input-icon">
                <Icon name="search" /><label for="admin-search" class="sr-only"
                    >Cari {{ config.title }}</label
                ><input
                    id="admin-search"
                    v-model="filter.search"
                    maxlength="100"
                    :placeholder="
                        kind === 'bookings' || kind === 'users'
                            ? 'Cari nama atau email'
                            : 'Cari ' + config.title.toLowerCase()
                    "
                />
            </div>
            <select
                v-if="kind === 'bookings'"
                v-model="filter.status"
                aria-label="Filter status booking"
                @change="search"
            >
                <option value="">Semua status</option>
                <option v-for="(label, value) in statusLabels" :key="value" :value="value">
                    {{ label }}
                </option></select
            ><button class="button button-secondary" :disabled="loading">
                {{ loading ? 'Mencari…' : 'Cari' }}</button
            ><button
                v-if="filter.search || filter.status"
                type="button"
                class="text-button"
                @click="reset"
            >
                Hapus filter</button
            ><span class="result-count">{{ total }} data</span>
        </form>
        <div
            v-if="items.data.length"
            class="table-scroll"
            tabindex="0"
            role="region"
            :aria-label="'Daftar ' + config.title"
            :aria-busy="loading"
        >
            <table>
                <thead>
                    <tr v-if="kind === 'travel_packages'">
                        <th>Destinasi</th>
                        <th>Durasi</th>
                        <th>Harga paket</th>
                        <th>Galeri</th>
                        <th>Aksi</th>
                    </tr>
                    <tr v-else-if="kind === 'blogs'">
                        <th>Artikel</th>
                        <th>Kategori</th>
                        <th>Dibaca</th>
                        <th>Diterbitkan</th>
                        <th>Aksi</th>
                    </tr>
                    <tr v-else-if="kind === 'categories'">
                        <th>Nama kategori</th>
                        <th>Artikel</th>
                        <th>Aksi</th>
                    </tr>
                    <tr v-else-if="kind === 'bookings'">
                        <th>Peserta</th>
                        <th>Kontak</th>
                        <th>Perjalanan</th>
                        <th>Keberangkatan</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                    <tr v-else>
                        <th>Nama</th>
                        <th>Email</th>
                        <th>Peran</th>
                        <th>Terdaftar</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="item in items.data" :key="item.id">
                        <template v-if="kind === 'travel_packages'"
                            ><td>
                                <div class="table-media">
                                    <img
                                        :src="item.image_url"
                                        :alt="item.location"
                                        @error="imageFallback"
                                    />
                                    <div>
                                        <Link
                                            :href="base + '/' + item.id + '/edit'"
                                            class="table-title"
                                            >{{ item.location }}</Link
                                        ><span class="table-subtitle">{{ item.slug }}</span>
                                    </div>
                                </div>
                            </td>
                            <td>{{ item.type }}</td>
                            <td class="nowrap">{{ money(item.price) }}</td>
                            <td>{{ item.galleries.length }} foto</td></template
                        >
                        <template v-else-if="kind === 'blogs'"
                            ><td>
                                <div class="table-media">
                                    <img
                                        :src="item.image_url"
                                        :alt="item.title"
                                        @error="imageFallback"
                                    />
                                    <div>
                                        <Link
                                            :href="base + '/' + item.id + '/edit'"
                                            class="table-title"
                                            >{{ item.title }}</Link
                                        ><span class="table-subtitle">{{ item.slug }}</span>
                                    </div>
                                </div>
                            </td>
                            <td>{{ item.category?.name }}</td>
                            <td>{{ item.reads }}</td>
                            <td>{{ dateLabel(item.created_at) }}</td></template
                        >
                        <template v-else-if="kind === 'categories'"
                            ><td>
                                <Link :href="base + '/' + item.id + '/edit'" class="table-title">{{
                                    item.name
                                }}</Link
                                ><span class="table-subtitle">{{ item.slug }}</span>
                            </td>
                            <td>{{ item.blogs_count }} artikel</td></template
                        >
                        <template v-else-if="kind === 'bookings'"
                            ><td>
                                <strong>{{ item.name }}</strong
                                ><span class="table-subtitle">#{{ item.id }}</span>
                            </td>
                            <td>
                                <a :href="'mailto:' + item.email" class="table-contact">{{
                                    item.email
                                }}</a
                                ><a :href="'tel:' + item.number_phone" class="table-contact">{{
                                    item.number_phone
                                }}</a>
                            </td>
                            <td>{{ item.travel_package?.location || 'Paket tidak tersedia' }}</td>
                            <td class="nowrap">{{ dateLabel(item.date) }}</td>
                            <td>
                                <label :for="'status-' + item.id" class="sr-only"
                                    >Status booking {{ item.name }}</label
                                ><select
                                    :id="'status-' + item.id"
                                    :value="item.status"
                                    class="status-select"
                                    :disabled="loading"
                                    @change="updateStatus(item, $event.target.value)"
                                >
                                    <option
                                        v-for="(label, value) in statusLabels"
                                        :key="value"
                                        :value="value"
                                    >
                                        {{ label }}
                                    </option>
                                </select>
                            </td></template
                        >
                        <template v-else
                            ><td>
                                <div class="table-media">
                                    <span class="avatar">{{
                                        item.name.slice(0, 1).toUpperCase()
                                    }}</span
                                    ><strong>{{ item.name }}</strong>
                                </div>
                            </td>
                            <td>{{ item.email }}</td>
                            <td>{{ item.is_admin ? 'Administrator' : 'Pengguna' }}</td>
                            <td>{{ dateLabel(item.created_at) }}</td></template
                        >
                        <td v-if="kind !== 'users'">
                            <div class="table-actions">
                                <Link
                                    v-if="kind !== 'bookings'"
                                    :href="base + '/' + item.id + '/edit'"
                                    class="table-action"
                                    >Edit</Link
                                ><Link
                                    v-if="kind === 'travel_packages' || kind === 'blogs'"
                                    :href="
                                        (kind === 'blogs' ? '/blogs/' : '/travel-packages/') +
                                        item.slug
                                    "
                                    class="table-action"
                                    >Lihat</Link
                                ><button
                                    class="table-action danger-text"
                                    :aria-label="'Hapus ' + itemName(item)"
                                    @click="deleting = item"
                                >
                                    Hapus
                                </button>
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
        <EmptyState
            v-else
            :title="config.empty"
            description="Data yang sesuai akan muncul di sini. Ubah pencarian atau tambahkan data baru."
            :href="config.create ? base + '/create' : undefined"
            :action="config.create"
        />
        <div class="panel-footer">
            <span class="muted small">{{ total }} data tersimpan sesuai filter</span
            ><Pagination :links="pagination" />
        </div>
    </section>
    <ConfirmDialog
        :open="!!deleting"
        :title="deleting ? 'Hapus ' + itemName(deleting) + '?' : ''"
        :description="
            kind === 'travel_packages'
                ? 'Paket dan foto galeri akan dihapus. Paket yang memiliki booking harus dipertahankan untuk menjaga riwayat.'
                : kind === 'categories'
                  ? 'Kategori hanya dapat dihapus jika sudah tidak digunakan oleh artikel.'
                  : 'Data yang dihapus tidak dapat dikembalikan. Pastikan data ini sudah tidak diperlukan.'
        "
        :processing="processing"
        @close="deleting = null"
        @confirm="destroy"
    />
</template>
