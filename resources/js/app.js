import { createApp, h } from 'vue'
import { createInertiaApp } from '@inertiajs/vue3'
import '../css/app.css'

createInertiaApp({
    title: (title) => `${title} · OTEWE`,
    resolve: (name) => {
        const pages = import.meta.glob('./Pages/**/*.vue')
        return pages[`./Pages/${name}.vue`]()
    },
    setup({ el, App, props, plugin }) {
        createApp({ render: () => h(App, props) })
            .use(plugin)
            .directive('reveal', {
                mounted(el, binding) {
                    if (binding.value === false) return
                    if (
                        window.matchMedia('(prefers-reduced-motion: reduce)').matches ||
                        !('IntersectionObserver' in window)
                    )
                        return
                    el.style.setProperty('--reveal-delay', `${Math.min(binding.value || 0, 320)}ms`)
                    if (binding.modifiers.left) el.classList.add('reveal-left')
                    if (binding.modifiers.right) el.classList.add('reveal-right')
                    const show = () => {
                        el.classList.add('revealed')
                        el._revealObserver?.disconnect()
                    }
                    el._revealFocus = show
                    el.addEventListener('focusin', show)
                    el._revealObserver = new IntersectionObserver(
                        (entries) => {
                            if (entries.some((entry) => entry.isIntersecting)) show()
                        },
                        { threshold: 0, rootMargin: '0px 0px -32px 0px' },
                    )
                    el.classList.add('reveal')
                    el._revealObserver.observe(el)
                },
                unmounted(el) {
                    el._revealObserver?.disconnect()
                    el.removeEventListener('focusin', el._revealFocus)
                },
            })
            .directive('tilt', {
                mounted(el, binding) {
                    if (binding.value === false) return
                    el._tiltMove = (event) => {
                        if (
                            event.pointerType !== 'mouse' ||
                            window.matchMedia('(prefers-reduced-motion: reduce)').matches
                        )
                            return
                        const bounds = el.getBoundingClientRect()
                        const x = (event.clientX - bounds.left) / bounds.width - 0.5
                        const y = (event.clientY - bounds.top) / bounds.height - 0.5
                        el.style.setProperty('--tilt-x', `${(y * -9).toFixed(2)}deg`)
                        el.style.setProperty('--tilt-y', `${(x * 12).toFixed(2)}deg`)
                    }
                    el._tiltLeave = () => {
                        el.style.removeProperty('--tilt-x')
                        el.style.removeProperty('--tilt-y')
                    }
                    el.addEventListener('pointermove', el._tiltMove)
                    el.addEventListener('pointerleave', el._tiltLeave)
                },
                unmounted(el) {
                    el.removeEventListener('pointermove', el._tiltMove)
                    el.removeEventListener('pointerleave', el._tiltLeave)
                },
            })
            .mount(el)
    },
    progress: { color: '#b34522', showSpinner: false },
})
