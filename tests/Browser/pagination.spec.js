import { test, expect } from '@playwright/test'

test('catalogue and admin pagination preserve filters and change the displayed records', async ({
    page,
}) => {
    await page.goto('/login')
    await page.getByLabel('Email', { exact: true }).fill('browser@example.test')
    await page.getByLabel('Kata sandi', { exact: true }).fill('BrowserTest123!')
    await page.getByRole('button', { name: 'Masuk ke workspace' }).click()
    await expect(page).toHaveURL('/admin/dashboard')

    const csrf = decodeURIComponent(
        (await page.context().cookies()).find((cookie) => cookie.name === 'XSRF-TOKEN').value,
    )
    const headers = { 'X-XSRF-TOKEN': csrf }

    const packageIds = []
    for (let index = 0; index < 11; index++) {
        const result = await page.request.post('/admin/travel_packages', {
            headers,
            form: {
                location: `Pagination ${index} (data uji)`,
                type: 'Durasi pagination uji',
                price: 1000000 + index,
                description: 'Konten pengujian pagination.',
            },
        })
        expect(result.ok()).toBeTruthy()
        packageIds.push(Number(result.url().match(/travel_packages\/(\d+)\/edit/)[1]))
    }
    await page.goto('/travel-packages?type=Durasi%20pagination%20uji&sort=price_asc')
    await expect(page.locator('.package-card')).toHaveCount(9)
    await page
        .getByRole('navigation', { name: 'Halaman daftar' })
        .getByRole('link', { name: 'Berikutnya', exact: true })
        .click()
    await expect(page.locator('.package-card')).toHaveCount(2)
    await expect(page).toHaveURL(/page=2/)
    await expect(page.getByLabel('Durasi perjalanan')).toHaveValue('Durasi pagination uji')
    await expect(page.getByLabel('Urutkan')).toHaveValue('price_asc')
    await page
        .getByRole('navigation', { name: 'Halaman daftar' })
        .getByRole('link', { name: 'Sebelumnya', exact: true })
        .click()
    await expect(page.locator('.package-card')).toHaveCount(9)

    await page.goto('/admin/travel_packages?search=Pagination')
    await expect(page.locator('tbody tr')).toHaveCount(10)
    await page
        .getByRole('navigation', { name: 'Halaman daftar' })
        .getByRole('link', { name: 'Berikutnya', exact: true })
        .click()
    await expect(page.locator('tbody tr')).toHaveCount(1)
    await expect(page.getByRole('textbox', { name: 'Cari Paket wisata' })).toHaveValue('Pagination')
    for (const id of packageIds) {
        const result = await page.request.post('/admin/travel_packages/' + id, {
            headers,
            form: { _method: 'delete' },
        })
        expect(result.ok()).toBeTruthy()
    }
})
