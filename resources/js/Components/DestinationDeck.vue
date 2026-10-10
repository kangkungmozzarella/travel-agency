<script setup>
import { ref, onMounted, onUnmounted } from 'vue'
import PackageCard from './PackageCard.vue'

const props = defineProps({ packages: Array })
const root = ref(null)
const stage = ref(null)
const panels = ref([])
const enhanced = ref(false)
const current = ref(1)
const progress = ref(0)
let frame = 0
let observer
let resizeObserver
let preference
let nearby = true
const clamp = (value, min, max) => Math.min(max, Math.max(min, value))

function update() {
    frame = 0
    if (!enhanced.value || !root.value || !stage.value) return
    const bounds = root.value.getBoundingClientRect()
    const height = stage.value.offsetHeight
    const top = parseFloat(getComputedStyle(stage.value).top)
    const range = bounds.height - height
    const position =
        clamp((top - bounds.top) / Math.max(range, 1), 0, 1) * (props.packages.length - 1)
    progress.value = position / (props.packages.length - 1)
    current.value = Math.round(position) + 1
    panels.value.forEach((panel, index) => {
        if (!panel) return
        const distance = index - position
        const incoming = clamp(distance, 0, 1.2)
        const outgoing = clamp(-distance, 0, 1)
        // Incoming panels swing up from below like a page; earlier panels sink back into depth.
        const side = index % 2 ? 1 : -1
        panel.style.transform = `translate3d(0, ${incoming * (height + 30) - outgoing * 36}px, ${outgoing * -220}px) rotateX(${incoming * -32 + outgoing * 9}deg) rotateY(${incoming * side * 10}deg) rotateZ(${incoming * side * 2}deg)`
        panel.style.opacity = distance > 1.15 ? '0' : String(1 - outgoing * 0.35)
        panel.style.pointerEvents = Math.abs(distance) < 0.55 ? 'auto' : 'none'
        const image = panel.querySelector('.package-image img')
        if (image)
            image.style.transform = `scale(${1.04 + incoming * 0.1}) translateY(${incoming * -4}%)`
    })
}
function schedule() {
    if (nearby && !frame) frame = requestAnimationFrame(update)
}
function configure() {
    enhanced.value = !preference.matches && props.packages.length > 1 && innerHeight >= 600
    if (!enhanced.value) {
        for (const panel of panels.value) {
            panel?.removeAttribute('style')
            panel?.querySelector('img')?.removeAttribute('style')
        }
    }
    schedule()
}
function focusPanel(index) {
    if (!enhanced.value) return
    const bounds = root.value.getBoundingClientRect()
    const height = stage.value.offsetHeight
    const top = parseFloat(getComputedStyle(stage.value).top)
    const position =
        scrollY +
        bounds.top -
        top +
        (index / (props.packages.length - 1)) * (bounds.height - height)
    window.scrollTo({ top: position, behavior: 'instant' })
    schedule()
}
onMounted(() => {
    preference = matchMedia('(prefers-reduced-motion: reduce)')
    preference.addEventListener('change', configure)
    window.addEventListener('scroll', schedule, { passive: true })
    window.addEventListener('resize', configure)
    observer = new IntersectionObserver(
        ([entry]) => {
            nearby = entry.isIntersecting
            schedule()
        },
        { rootMargin: '200px' },
    )
    observer.observe(root.value)
    resizeObserver = new ResizeObserver(schedule)
    resizeObserver.observe(stage.value)
    configure()
})
onUnmounted(() => {
    cancelAnimationFrame(frame)
    observer?.disconnect()
    resizeObserver?.disconnect()
    preference?.removeEventListener('change', configure)
    window.removeEventListener('scroll', schedule)
    window.removeEventListener('resize', configure)
})
</script>

<template>
    <div
        ref="root"
        class="destination-deck"
        :class="{ 'deck-enhanced': enhanced }"
        :style="{ '--deck-count': packages.length }"
    >
        <div ref="stage" class="destination-stage">
            <div v-if="enhanced" class="deck-caption">
                <span>Scroll untuk menjelajahi destinasi</span>
                <span class="deck-counter"
                    >{{ String(current).padStart(2, '0') }} /
                    {{ String(packages.length).padStart(2, '0') }}</span
                >
                <div class="deck-progress" aria-hidden="true">
                    <span :style="{ transform: `scaleX(${progress})` }" />
                </div>
            </div>
            <div class="destination-panels">
                <div
                    v-for="(item, index) in packages"
                    :key="item.id"
                    :ref="(el) => (panels[index] = el)"
                    class="destination-panel"
                    :style="{ zIndex: index + 1 }"
                    @focusin="focusPanel(index)"
                >
                    <PackageCard :package="item" featured />
                </div>
            </div>
        </div>
    </div>
</template>
