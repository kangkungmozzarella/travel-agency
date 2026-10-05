import { test, expect } from '@playwright/test'

test('desktop and phone previews keep content within the viewport and support keyboard navigation', async ({
    page,
}) => {
    const errors = []
    page.on('pageerror', (error) => errors.push(error.message))
    await page.emulateMedia({ reducedMotion: 'reduce' })
    await page.setViewportSize({ width: 1440, height: 1000 })
    await page.goto('/')
    await page.locator('.coastline-scene svg').waitFor()
    await page.evaluate(async () => {
        const hero = new Image()
        hero.src = document.querySelector('.coastline-scene image').getAttribute('href')
        await hero.decode()
        await Promise.all(Array.from(document.images).map((img) => img.decode().catch(() => {})))
        document.querySelectorAll('.reveal').forEach((el) => el.classList.add('revealed'))
    })
    await page.screenshot({
        path: 'test-results/home-desktop.png',
        fullPage: true,
        animations: 'disabled',
    })
    await page.goto('/login')
    await page.getByLabel('Email', { exact: true }).fill('browser@example.test')
    await page.getByLabel('Kata sandi', { exact: true }).fill('BrowserTest123!')
    await page.getByRole('button', { name: 'Masuk ke workspace' }).click()
    await expect(page).toHaveURL('/admin/dashboard')
    await page.screenshot({
        path: 'test-results/admin-desktop.png',
        fullPage: true,
        animations: 'disabled',
    })
    for (const width of [320, 768, 1024]) {
        await page.setViewportSize({ width, height: 900 })
        for (const path of [
            '/',
            '/travel-packages',
            '/travel-packages/bali-uji',
            '/blogs',
            '/contact',
            '/admin/dashboard',
            '/admin/travel_packages',
            '/admin/travel_packages/create',
            '/admin/bookings',
            '/admin/blogs/create',
            '/admin/profile',
        ]) {
            await page.goto(path)
            expect(
                await page.evaluate(
                    () => document.documentElement.scrollWidth <= window.innerWidth,
                ),
                `${path} at ${width}px`,
            ).toBeTruthy()
        }
    }
    await page.setViewportSize({ width: 390, height: 844 })
    await page.goto('/')
    await page.evaluate(() =>
        document.querySelectorAll('.reveal').forEach((el) => el.classList.add('revealed')),
    )
    await page.screenshot({
        path: 'test-results/home-mobile.png',
        fullPage: true,
        animations: 'disabled',
    })
    await page.getByRole('button', { name: 'Menu', exact: true }).focus()
    await page.keyboard.press('Enter')
    await expect(page.getByRole('navigation', { name: 'Navigasi mobile' })).toBeVisible()
    await page.keyboard.press('Tab')
    await expect(
        page
            .getByRole('navigation', { name: 'Navigasi mobile' })
            .getByRole('link', { name: 'Beranda', exact: true }),
    ).toBeFocused()
    await page.keyboard.press('Escape')
    await expect(page.getByRole('navigation', { name: 'Navigasi mobile' })).not.toBeVisible()
    expect(errors).toEqual([])
})
