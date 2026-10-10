<script setup>
import { ref, computed, onMounted, onUnmounted } from 'vue'

const props = defineProps({ destinations: { type: Array, default: () => [] } })
const root = ref(null)
const canvas = ref(null)
const labels = ref([])
const status = ref('loading')
const pins = computed(() => props.destinations.slice(0, 4))
let world
let frame = 0
let elapsed = 0
let previous = 0
let visible = true
let observer
let resizer
let preference
let active = true
const target = { x: 0, y: 0 }
const pointer = { x: 0, y: 0 }

function draw() {
    if (!world) return
    pointer.x += (target.x - pointer.x) * 0.06
    pointer.y += (target.y - pointer.y) * 0.06
    world.render(elapsed / 1000, pointer)
    world.anchors().forEach(({ x, y }, index) => {
        const label = labels.value[index]
        if (!label) return
        label.style.left = `${x * 100}%`
        label.style.top = `${y * 100}%`
    })
    root.value.dataset.time = Math.round(elapsed)
    root.value.dataset.pointer = pointer.x.toFixed(3)
}
function tick(now) {
    if (previous) elapsed += Math.min(now - previous, 50)
    previous = now
    draw()
    frame = requestAnimationFrame(tick)
}
function sync() {
    cancelAnimationFrame(frame)
    frame = 0
    previous = 0
    if (!world) return
    if (visible && !document.hidden && !preference.matches) frame = requestAnimationFrame(tick)
    else if (preference.matches) {
        elapsed = 0
        target.x = target.y = pointer.x = pointer.y = 0
        draw()
    }
}
function resize() {
    if (!world) return
    const { width, height } = root.value.getBoundingClientRect()
    if (!width || !height) return
    world.resize(width, height)
    if (!frame) draw()
}
function move(event) {
    if (event.pointerType !== 'mouse' || preference?.matches) return
    const bounds = root.value.getBoundingClientRect()
    target.x = ((event.clientX - bounds.left) / bounds.width) * 2 - 1
    target.y = ((event.clientY - bounds.top) / bounds.height) * 2 - 1
}
function reset() {
    target.x = target.y = 0
}
onMounted(async () => {
    preference = window.matchMedia('(prefers-reduced-motion: reduce)')
    preference.addEventListener('change', sync)
    document.addEventListener('visibilitychange', sync)
    observer = new IntersectionObserver(([entry]) => {
        visible = entry.isIntersecting
        sync()
    })
    observer.observe(root.value)
    try {
        const { createIsland } = await import('../lib/island.js')
        if (!active) return
        world = createIsland(canvas.value, {
            destinations: pins.value,
            image: '/frontend/assets/img/hero.jpg',
            onChange: () => !frame && draw(),
        })
    } catch {
        status.value = 'fallback'
        return
    }
    resizer = new ResizeObserver(resize)
    resizer.observe(root.value)
    resize()
    status.value = 'ready'
    sync()
})
onUnmounted(() => {
    active = false
    cancelAnimationFrame(frame)
    observer?.disconnect()
    resizer?.disconnect()
    preference?.removeEventListener('change', sync)
    document.removeEventListener('visibilitychange', sync)
    world?.dispose()
})
</script>

<template>
    <div
        ref="root"
        class="island-scene"
        :class="`island-${status}`"
        role="img"
        aria-label="Diorama 3D pulau tropis dengan rute perjalanan menuju destinasi OTEWE"
        @pointermove="move"
        @pointerleave="reset"
    >
        <img v-if="status === 'fallback'" class="island-poster" src="/frontend/assets/img/hero.jpg" alt="" />
        <canvas ref="canvas" aria-hidden="true" />
        <span
            v-for="(name, index) in pins"
            :key="`${index}-${name}`"
            :ref="(el) => (labels[index] = el)"
            class="island-pin"
            aria-hidden="true"
            >{{ name }}</span
        >
        <span class="island-label" aria-hidden="true">IKUTI RASA INGIN PERGI ↗</span>
    </div>
</template>
