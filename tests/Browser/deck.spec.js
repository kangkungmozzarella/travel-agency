import { test, expect } from '@playwright/test'

test('destination cards scrub with scroll, reverse, and remain reachable by keyboard', async ({
    page,
}) => {
    await page.emulateMedia({ reducedMotion: 'no-preference' })
    await page.goto('/')
    const deck = page.locator('.destination-deck')
    await expect(deck).toHaveClass(/deck-enhanced/)
    const panels = page.locator('.destination-panel')
    async function scrub(fraction) {
        await deck.evaluate((el, value) => {
            const stage = el.querySelector('.destination-stage')
            const top = parseFloat(getComputedStyle(stage).top)
            window.scrollTo({
                top:
                    scrollY +
                    el.getBoundingClientRect().top -
                    top +
                    (el.offsetHeight - stage.offsetHeight) * value,
                behavior: 'instant',
            })
        }, fraction)
    }
    await scrub(0)
    await expect(panels.nth(0)).toHaveCSS('pointer-events', 'auto')
    const initial = await panels.nth(1).evaluate((el) => el.style.transform)
    await scrub(0.2)
    await expect.poll(() => panels.nth(1).evaluate((el) => el.style.transform)).not.toBe(initial)
    await page.screenshot({ path: 'test-results/deck-transition.png' })
    await scrub(1 / 3)
    await expect(panels.nth(1)).toHaveCSS('pointer-events', 'auto')
    await expect(page.locator('.deck-counter')).toHaveText('02 / 04')
    await scrub(0)
    await expect(panels.nth(0)).toHaveCSS('pointer-events', 'auto')
    await panels.nth(3).getByRole('link').focus()
    await expect(panels.nth(3)).toHaveCSS('pointer-events', 'auto')
    await expect(panels.nth(3).getByRole('link')).toBeInViewport()
    await page.keyboard.press('Enter')
    await expect(page).toHaveURL(/travel-packages\/bromo-uji/)
    await page.setViewportSize({ width: 390, height: 844 })
    await page.goto('/')
    await scrub(0.5)
    await expect
        .poll(() => page.evaluate(() => document.documentElement.scrollWidth <= innerWidth))
        .toBe(true)
    await scrub(1 / 3)
    await expect(panels.nth(1)).toHaveCSS('pointer-events', 'auto')
    await expect(panels.nth(1).getByRole('heading')).toBeInViewport({ ratio: 0.99 })
    await expect(panels.nth(1).locator('.package-bottom')).toBeInViewport({ ratio: 0.99 })
    await page.screenshot({ path: 'test-results/deck-mobile.png' })
    await page.emulateMedia({ reducedMotion: 'reduce' })
    await expect(deck).not.toHaveClass(/deck-enhanced/)
    await expect(panels.nth(3)).toHaveCSS('position', 'static')
    await expect(panels.nth(3)).toHaveCSS('transform', 'none')
})
