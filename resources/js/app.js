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
            .mount(el)
    },
    progress: { color: '#b34522', showSpinner: false },
})
