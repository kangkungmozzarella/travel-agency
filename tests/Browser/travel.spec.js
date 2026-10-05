import { test, expect } from '@playwright/test'

async function login(page) {
    await page.goto('/login')
    await page.getByLabel('Email', { exact: true }).fill('browser@example.test')
    await page.getByLabel('Kata sandi', { exact: true }).fill('BrowserTest123!')
    await page.getByRole('button', { name: 'Masuk ke workspace' }).click()
    await expect(page).toHaveURL(/admin\/dashboard/)
}

test('public navigation, catalogue filters, gallery and booking work without console errors', async ({
    page,
}) => {
    const errors = []
    page.on('pageerror', (error) => errors.push(error.message))
    await page.goto('/')
    await expect(page.getByRole('heading', { name: /Pergi sebentar/ })).toBeVisible()
    await page.getByLabel('Cari destinasi', { exact: true }).fill('Bali')
    await page.getByRole('button', { name: 'Cari perjalanan' }).click()
    await expect(page).toHaveURL(/travel-packages\?search=Bali/)
    await expect(page.locator('.package-card')).toHaveCount(1)
    await page.getByRole('button', { name: 'Hapus filter' }).click()
    await expect(page.locator('.package-card')).toHaveCount(4)
    await page.getByLabel('Urutkan').selectOption('price_asc')
    await expect(page.locator('.package-card').first()).toContainText('Bromo')
    await page.getByLabel('Durasi perjalanan').selectOption('3 hari 2 malam')
    await expect(page.locator('.package-card')).toHaveCount(4)
    await page.getByRole('heading', { name: 'Bali (data uji)' }).click()
    await expect(page).toHaveURL('/travel-packages/bali-uji')
    await page.getByRole('button', { name: 'Foto berikutnya', exact: true }).click()
    await expect(page.locator('.gallery-toolbar')).toContainText('2 dari 2')
    await page.getByRole('button', { name: 'Foto sebelumnya', exact: true }).click()
    await page.getByRole('button', { name: 'Lihat foto Foto contoh 2' }).click()
    await page.getByRole('button', { name: /Perbesar foto/ }).click()
    await expect(page.getByRole('dialog')).toBeVisible()
    await page.getByRole('button', { name: 'Sebelumnya', exact: true }).click()
    await page.getByRole('button', { name: 'Berikutnya', exact: true }).click()
    await page.keyboard.press('Escape')
    await expect(page.getByRole('dialog')).not.toBeVisible()
    await page.getByLabel('Nama lengkap').fill('Peserta Browser')
    await page.getByLabel('Email', { exact: true }).fill('booking-browser@example.test')
    await page.getByLabel('Nomor WhatsApp / telepon').fill('abc')
    await page.getByLabel('Tanggal keberangkatan').fill('2027-01-10')
    await page.getByRole('button', { name: 'Kirim permintaan booking' }).click()
    await expect(page.getByRole('alert')).toContainText('Nomor telepon')
    await page.getByLabel('Nomor WhatsApp / telepon').fill('081234567890')
    await page.getByRole('button', { name: 'Kirim permintaan booking' }).click()
    await expect(
        page.getByRole('status').filter({ hasText: 'Permintaan booking diterima' }),
    ).toBeVisible()
    await page.getByRole('button', { name: 'Tutup pemberitahuan' }).click()
    await page.getByRole('link', { name: 'Jurnal perjalanan', exact: true }).first().click()
    await page.getByRole('link', { name: 'Panduan (data uji)', exact: true }).click()
    await expect(page).toHaveURL(/blogs\/category\/panduan-uji/)
    await page.getByLabel('Cari artikel').fill('Bali')
    await page.getByRole('button', { name: 'Cari', exact: true }).click()
    await expect(page.locator('.blog-card')).toHaveCount(1)
    await page.getByRole('heading', { name: 'Catatan Bali (data uji)' }).click()
    await expect(page.locator('.article-content')).toContainText('data uji')
    await page.getByRole('link', { name: 'Kontak', exact: true }).click()
    await expect(page.getByRole('link', { name: 'Chat lewat WhatsApp' })).toHaveAttribute(
        'href',
        /https:\/\/wa.me\/6281292015768/,
    )
    await expect(page.getByRole('link', { name: 'Telepon kami' })).toHaveAttribute(
        'href',
        'tel:+6281292015768',
    )
    expect(errors).toEqual([])
})

test('admin CRUD, uploads, status changes, rich editor, profile and logout work', async ({
    page,
}) => {
    const errors = []
    page.on('pageerror', (error) => errors.push(error.message))
    await login(page)
    await expect(page.getByRole('heading', { name: 'Halo, Admin Uji.' })).toBeVisible()
    await page.getByRole('link', { name: 'Lihat permintaan', exact: true }).click()
    await expect(page).toHaveURL(/bookings\?status=pending/)
    await page.getByRole('button', { name: 'Hapus filter' }).click()
    await page.getByLabel('Status booking Peserta Uji 1').selectOption('confirmed')
    await expect(
        page.getByRole('status').filter({ hasText: 'Status booking diperbarui' }),
    ).toBeVisible()
    await page.getByRole('link', { name: 'Paket wisata', exact: true }).click()
    await page.getByRole('link', { name: 'Tambah paket', exact: true }).click()
    await page.getByLabel('Nama destinasi').fill('Destinasi Browser (data uji)')
    await page.getByLabel('Durasi perjalanan').fill('2 hari 1 malam')
    await page.getByLabel('Harga paket (Rp)').fill('1250000')
    await page
        .getByRole('textbox', { name: 'Deskripsi perjalanan' })
        .fill('Deskripsi contoh browser.')
    await page.getByRole('button', { name: 'Subjudul', exact: true }).click()
    await page.getByRole('button', { name: 'Paragraf', exact: true }).click()
    await page.getByRole('button', { name: 'Buat paket wisata' }).click()
    await expect(
        page.getByRole('heading', { name: 'Edit paket wisata', exact: true }),
    ).toBeVisible()
    await page.getByLabel('Nama / keterangan foto').fill('Foto Browser')
    await page
        .getByLabel('Foto', { exact: true })
        .setInputFiles('public/frontend/assets/img/bali.jpg')
    await expect(page.getByAltText('Pratinjau foto')).toBeVisible()
    await page.getByRole('button', { name: 'Tambahkan ke galeri' }).click()
    await expect(page.locator('.admin-gallery-grid')).toContainText('Foto Browser')
    await page.getByRole('link', { name: 'Edit foto', exact: true }).click()
    await expect(page.getByRole('heading', { name: 'Edit foto galeri', exact: true })).toBeVisible()
    await page.getByLabel('Nama / keterangan foto').fill('Foto diperbarui')
    await page.getByRole('button', { name: 'Simpan foto', exact: true }).click()
    await expect(page.locator('.admin-gallery-grid')).toContainText('Foto diperbarui')
    await page.getByRole('button', { name: 'Hapus foto Foto diperbarui' }).click()
    await expect(page.getByRole('dialog')).toBeVisible()
    await page.keyboard.press('Escape')
    await expect(page.getByRole('dialog')).not.toBeVisible()
    await page.getByRole('button', { name: 'Hapus foto Foto diperbarui' }).click()
    await page.getByRole('button', { name: 'Ya, hapus', exact: true }).click()
    await expect(page.getByRole('heading', { name: 'Galeri masih kosong' })).toBeVisible()
    await page.getByLabel('Harga paket (Rp)').fill('1300000')
    await page.getByRole('button', { name: 'Simpan perubahan', exact: true }).click()
    await expect(
        page.getByRole('status').filter({ hasText: 'Perubahan paket disimpan' }),
    ).toBeVisible()
    await page.getByRole('link', { name: 'Kategori', exact: true }).click()
    await page.getByRole('link', { name: 'Tambah kategori', exact: true }).click()
    await page.getByLabel('Nama kategori').fill('Kategori Browser (data uji)')
    await page.getByRole('button', { name: 'Simpan kategori' }).click()
    await expect(
        page.getByRole('link', { name: 'Kategori Browser (data uji)', exact: true }),
    ).toBeVisible()
    await page.getByRole('link', { name: 'Jurnal', exact: true }).click()
    await page.getByRole('link', { name: 'Tulis artikel', exact: true }).click()
    await page.getByLabel('Judul artikel').fill('Artikel Browser (data uji)')
    await page.getByLabel('Ringkasan').fill('Ringkasan artikel contoh.')
    await page.getByRole('textbox', { name: 'Isi cerita' }).fill('Isi cerita contoh untuk browser.')
    await page
        .getByLabel('Kategori', { exact: true })
        .selectOption({ label: 'Kategori Browser (data uji)' })
    await page.getByLabel('Foto sampul').setInputFiles('public/frontend/assets/img/bali.jpg')
    await page.getByRole('button', { name: 'Terbitkan artikel' }).click()
    await expect(
        page.getByRole('link', { name: 'Artikel Browser (data uji)', exact: true }),
    ).toBeVisible()
    await page.getByRole('link', { name: 'Artikel Browser (data uji)', exact: true }).click()
    await page.getByLabel('Judul artikel').fill('Artikel Browser revisi (data uji)')
    await page.getByRole('button', { name: 'Simpan perubahan' }).click()
    const articleRow = page
        .getByRole('row')
        .filter({ hasText: 'Artikel Browser revisi (data uji)' })
    await articleRow.getByRole('button', { name: /Hapus/ }).click()
    await page.getByRole('button', { name: 'Ya, hapus', exact: true }).click()
    await expect(articleRow).toHaveCount(0)
    await page.getByRole('link', { name: 'Kategori', exact: true }).click()
    const categoryRow = page.getByRole('row').filter({ hasText: 'Kategori Browser (data uji)' })
    await categoryRow.getByRole('link', { name: 'Edit', exact: true }).click()
    await page.getByLabel('Nama kategori').fill('Kategori Browser revisi (data uji)')
    await page.getByRole('button', { name: 'Simpan kategori' }).click()
    await page.getByRole('button', { name: 'Hapus Kategori Browser revisi (data uji)' }).click()
    await page.getByRole('button', { name: 'Ya, hapus', exact: true }).click()
    await expect(
        page.getByRole('link', { name: 'Kategori Browser revisi (data uji)', exact: true }),
    ).toHaveCount(0)
    await page.getByRole('link', { name: 'Paket wisata', exact: true }).click()
    await page.getByRole('button', { name: 'Hapus Destinasi Browser (data uji)' }).click()
    await page.getByRole('button', { name: 'Ya, hapus', exact: true }).click()
    await expect(
        page.getByRole('link', { name: 'Destinasi Browser (data uji)', exact: true }),
    ).toHaveCount(0)
    await page.getByRole('link', { name: 'Pengguna', exact: true }).click()
    await expect(page.getByRole('table')).toContainText('browser@example.test')
    const csrf = decodeURIComponent(
        (await page.context().cookies()).find((cookie) => cookie.name === 'XSRF-TOKEN').value,
    )
    const fixtureBooking = await page.request.post('/booking', {
        headers: { 'X-XSRF-TOKEN': csrf },
        form: {
            name: 'Peserta Admin Browser',
            email: 'admin-booking-browser@example.test',
            number_phone: '081234567890',
            date: '2027-01-10',
            travel_package_id: 1,
        },
    })
    expect(fixtureBooking.ok()).toBeTruthy()
    await page.getByRole('link', { name: 'Booking', exact: true }).click()
    await page
        .getByRole('textbox', { name: 'Cari Booking perjalanan' })
        .fill('admin-booking-browser')
    await page.getByRole('button', { name: 'Cari', exact: true }).click()
    await expect(page.locator('tbody tr')).toHaveCount(1)
    await page.getByRole('button', { name: 'Hapus Peserta Admin Browser', exact: true }).click()
    await page.getByRole('button', { name: 'Ya, hapus', exact: true }).click()
    await expect(page.getByRole('heading', { name: 'Belum ada booking yang cocok' })).toBeVisible()
    await page.getByRole('link', { name: 'Profil saya', exact: true }).click()
    await page.getByLabel('Nama lengkap').fill('Admin Uji')
    await page.getByRole('button', { name: 'Simpan profil' }).click()
    await expect(
        page.getByRole('status').filter({ hasText: 'Profil berhasil diperbarui.' }),
    ).toBeVisible()
    await page.getByRole('button', { name: 'Keluar', exact: true }).click()
    await expect(page).toHaveURL('/')
    expect(errors).toEqual([])
})

test('mobile layouts, menus, reduced motion, error pages and empty search are usable', async ({
    page,
}) => {
    const errors = []
    page.on('pageerror', (error) => errors.push(error.message))
    await page.setViewportSize({ width: 390, height: 844 })
    await page.emulateMedia({ reducedMotion: 'reduce' })
    await page.goto('/')
    await page.getByRole('button', { name: 'Menu', exact: true }).click()
    await page
        .getByRole('navigation', { name: 'Navigasi mobile' })
        .getByRole('link', { name: 'Paket wisata', exact: true })
        .click()
    await page.getByLabel('Destinasi', { exact: true }).fill('tidak-ada-destinasi')
    await page.getByRole('button', { name: 'Cari paket', exact: true }).click()
    await expect(
        page.getByRole('heading', { name: 'Belum ada perjalanan yang cocok' }),
    ).toBeVisible()
    await page.getByRole('button', { name: 'Hapus filter' }).click()
    await expect(page.locator('.package-card')).toHaveCount(4)
    for (const path of [
        '/',
        '/travel-packages',
        '/travel-packages/bali-uji',
        '/blogs',
        '/blogs/catatan-bali-uji',
        '/contact',
        '/login',
    ]) {
        await page.goto(path)
        expect(
            await page.evaluate(() => document.documentElement.scrollWidth <= window.innerWidth),
        ).toBeTruthy()
    }
    await page.goto('/halaman-tidak-ada')
    await expect(page.getByRole('heading', { name: 'Sepertinya kita salah jalan.' })).toBeVisible()
    await login(page)
    await page.getByRole('button', { name: 'Menu', exact: true }).click()
    await page.getByRole('link', { name: 'Paket wisata', exact: true }).click()
    await expect(page.locator('.admin-sidebar')).not.toBeVisible()
    for (const path of [
        '/admin/dashboard',
        '/admin/travel_packages',
        '/admin/travel_packages/create',
        '/admin/bookings',
        '/admin/blogs/create',
        '/admin/categories/create',
        '/admin/users',
        '/admin/profile',
    ]) {
        await page.goto(path)
        expect(
            await page.evaluate(() => document.documentElement.scrollWidth <= window.innerWidth),
        ).toBeTruthy()
    }
    await page.screenshot({ path: 'test-results/mobile-profile.png', fullPage: true })
    expect(errors).toEqual([])
})

test('login feedback, password visibility and recovery form are functional', async ({ page }) => {
    await page.goto('/login')
    await page.getByLabel('Email', { exact: true }).fill('wrong@example.test')
    await page.getByLabel('Kata sandi', { exact: true }).fill('wrong-password')
    await page.getByRole('button', { name: 'Tampilkan kata sandi' }).click()
    await expect(page.getByLabel('Kata sandi', { exact: true })).toHaveAttribute('type', 'text')
    await page.getByRole('button', { name: 'Sembunyikan kata sandi' }).click()
    await page.getByLabel('Ingat saya').check()
    await page.getByRole('button', { name: 'Masuk ke workspace' }).click()
    await expect(page.getByRole('alert')).toContainText('Email atau kata sandi tidak sesuai')
    await page.getByRole('link', { name: 'Lupa kata sandi?' }).click()
    await page.getByLabel('Email akun').fill('browser@example.test')
    await page.getByRole('button', { name: 'Kirim tautan pemulihan' }).click()
    await expect(
        page
            .getByRole('status')
            .filter({ hasText: 'Tautan pengaturan ulang kata sandi telah dikirim' }),
    ).toBeVisible()
    await page.getByRole('link', { name: 'Kembali ke halaman masuk' }).click()
    await expect(page).toHaveURL('/login')
})
