<script setup>
import { Head, Link, usePage } from '@inertiajs/vue3'
import AdminLayout from '../../Layouts/AdminLayout.vue'
import AdminPageHeader from '../../Components/AdminPageHeader.vue'
import EmptyState from '../../Components/EmptyState.vue'
import Icon from '../../Components/Icon.vue'
import { dateLabel, statusLabels } from '../../lib/format'
defineOptions({ layout: AdminLayout })
defineProps({ stats: Object, recentBookings: Array, upcomingBookings: Array })
const page = usePage()
</script>

<template>
    <Head title="Ringkasan admin" />
    <AdminPageHeader
        :title="'Halo, ' + page.props.auth.user.name + '.'"
        description="Pantau permintaan perjalanan dan siapkan keberangkatan berikutnya."
        ><Link href="/admin/travel_packages/create" class="button button-primary"
            ><Icon name="plus" /> Tambah paket</Link
        ></AdminPageHeader
    >
    <section class="dashboard-overview">
        <div class="booking-priority">
            <div>
                <span class="eyebrow">Perlu ditindaklanjuti</span>
                <div class="priority-count">
                    {{ stats.pending }}<span>booking menunggu<br />konfirmasi</span>
                </div>
                <p>
                    {{
                        stats.pending
                            ? 'Hubungi calon peserta dan konfirmasikan detail perjalanan.'
                            : 'Semua permintaan sudah ditindaklanjuti, atau belum ada booking masuk.'
                    }}
                </p>
                <Link href="/admin/bookings?status=pending" class="button button-cream"
                    >Lihat permintaan</Link
                >
            </div>
            <Icon name="calendar" :size="100" class="priority-icon" />
        </div>
        <div class="overview-totals">
            <Link href="/admin/travel_packages"
                ><span>Paket wisata</span><strong>{{ stats.packages }}</strong
                ><Icon name="compass" /></Link
            ><Link href="/admin/bookings"
                ><span>Total booking</span><strong>{{ stats.bookings }}</strong
                ><Icon name="calendar" /></Link
            ><Link href="/admin/blogs"
                ><span>Artikel jurnal</span><strong>{{ stats.blogs }}</strong
                ><Icon name="book"
            /></Link>
        </div>
    </section>
    <section class="dashboard-detail">
        <div class="panel">
            <div class="panel-heading">
                <div>
                    <h2>Booking terbaru</h2>
                    <p>Permintaan yang baru masuk ke OTEWE.</p>
                </div>
                <Link href="/admin/bookings" class="text-link">Lihat semua</Link>
            </div>
            <div
                v-if="recentBookings.length"
                class="table-scroll"
                tabindex="0"
                role="region"
                aria-label="Booking terbaru"
            >
                <table>
                    <thead>
                        <tr>
                            <th>Peserta</th>
                            <th>Perjalanan</th>
                            <th>Keberangkatan</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="booking in recentBookings" :key="booking.id">
                            <td>
                                <Link
                                    :href="
                                        '/admin/bookings?search=' +
                                        encodeURIComponent(booking.email)
                                    "
                                    class="table-title"
                                    >{{ booking.name }}</Link
                                ><span class="table-subtitle">{{ booking.email }}</span>
                            </td>
                            <td>
                                {{ booking.travel_package?.location || 'Paket tidak tersedia' }}
                            </td>
                            <td>{{ dateLabel(booking.date) }}</td>
                            <td>
                                <span class="status-label" :class="'status-' + booking.status">{{
                                    statusLabels[booking.status]
                                }}</span>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <EmptyState
                v-else
                title="Belum ada booking masuk"
                description="Permintaan dari formulir booking akan muncul di sini."
            />
        </div>
        <aside class="panel departures-panel">
            <div class="panel-heading">
                <div>
                    <h2>Keberangkatan berikutnya</h2>
                    <p>Booking yang sudah dikonfirmasi.</p>
                </div>
            </div>
            <div v-if="upcomingBookings.length" class="departure-list">
                <Link
                    v-for="booking in upcomingBookings"
                    :key="booking.id"
                    :href="'/admin/bookings?search=' + encodeURIComponent(booking.email)"
                    class="departure-item"
                    ><Icon name="calendar" :size="24" />
                    <div>
                        <span>{{ dateLabel(booking.date) }}</span
                        ><strong>{{ booking.travel_package?.location }}</strong>
                        <p>{{ booking.name }}</p>
                    </div></Link
                >
            </div>
            <EmptyState
                v-else
                title="Belum ada keberangkatan"
                description="Konfirmasikan booking untuk melihat jadwal perjalanan di sini."
            /><Link href="/admin/bookings?status=confirmed" class="departure-link text-link"
                >Lihat booking terkonfirmasi</Link
            >
        </aside>
    </section>
    <section class="admin-quick-note">
        <Icon name="book" :size="26" />
        <div>
            <h2>Lengkapi cerita destinasi.</h2>
            <p>
                Kelola foto paket dan jurnal agar pengunjung mendapat gambaran perjalanan sebelum
                booking.
            </p>
        </div>
        <Link href="/admin/blogs/create" class="button button-secondary">Tulis artikel</Link>
    </section>
</template>
