<script setup>
import { ref, useId, onMounted, onUnmounted } from 'vue'

const clipId = `coast-${useId()}`
const scene = ref(null)
const coast = ref(null)
const shadow = ref(null)
const outer = ref(null)
const inner = ref(null)
const photo = ref(null)
const traveller = ref(null)
let frame = 0
let elapsed = 0
let previous = 0
let visible = true
let observer
let preference
const target = { x: 0, y: 0 }
const pointer = { x: 0, y: 0 }
const count = 16

function pointsAt(time) {
    return Array.from({ length: count }, (_, index) => {
        const angle = (index / count) * Math.PI * 2
        const wave =
            1 +
            0.105 * Math.sin(3 * angle + time * 0.85) +
            0.055 * Math.cos(5 * angle - time * 0.6) +
            0.035 * Math.sin(2 * angle - time)
        return { x: 250 + Math.cos(angle) * 188 * wave, y: 280 + Math.sin(angle) * 214 * wave }
    })
}
function contour(points) {
    let path = `M ${points[0].x} ${points[0].y}`
    for (let index = 0; index < count; index++) {
        const a = points[(index + count - 1) % count]
        const b = points[index]
        const c = points[(index + 1) % count]
        const d = points[(index + 2) % count]
        path += ` C ${b.x + (c.x - a.x) / 6} ${b.y + (c.y - a.y) / 6} ${c.x - (d.x - b.x) / 6} ${c.y - (d.y - b.y) / 6} ${c.x} ${c.y}`
    }
    return `${path} Z`
}
function draw() {
    const time = elapsed / 1000
    const path = contour(pointsAt(time))
    for (const element of [coast.value, shadow.value, outer.value, inner.value]) {
        element?.setAttribute('d', path)
    }
    pointer.x += (target.x - pointer.x) * 0.07
    pointer.y += (target.y - pointer.y) * 0.07
    photo.value?.setAttribute(
        'transform',
        `translate(${pointer.x * -12} ${pointer.y * -12}) translate(250 280) scale(1.08) translate(-250 -280)`,
    )
    if (coast.value && traveller.value) {
        const length = coast.value.getTotalLength()
        const distance = ((0.68 + time / 22) % 1) * length
        const point = coast.value.getPointAtLength(distance)
        const next = coast.value.getPointAtLength((distance + 2) % length)
        const angle = (Math.atan2(next.y - point.y, next.x - point.x) * 180) / Math.PI
        traveller.value.setAttribute(
            'transform',
            `translate(${point.x} ${point.y}) rotate(${angle + 90})`,
        )
    }
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
    if (visible && !document.hidden && !preference.matches) frame = requestAnimationFrame(tick)
    else if (preference.matches) {
        elapsed = 0
        target.x = target.y = pointer.x = pointer.y = 0
        draw()
    }
}
function move(event) {
    if (event.pointerType !== 'mouse' || preference?.matches) return
    const bounds = scene.value.getBoundingClientRect()
    target.x = ((event.clientX - bounds.left) / bounds.width) * 2 - 1
    target.y = ((event.clientY - bounds.top) / bounds.height) * 2 - 1
}
function reset() {
    target.x = target.y = 0
}
onMounted(() => {
    preference = window.matchMedia('(prefers-reduced-motion: reduce)')
    preference.addEventListener('change', sync)
    document.addEventListener('visibilitychange', sync)
    observer = new IntersectionObserver(([entry]) => {
        visible = entry.isIntersecting
        sync()
    })
    observer.observe(scene.value)
    draw()
    sync()
})
onUnmounted(() => {
    cancelAnimationFrame(frame)
    observer?.disconnect()
    preference?.removeEventListener('change', sync)
    document.removeEventListener('visibilitychange', sync)
})
</script>

<template>
    <div ref="scene" class="coastline-scene" @pointermove="move" @pointerleave="reset">
        <svg
            viewBox="0 0 500 560"
            role="img"
            aria-label="Foto laut dan pulau dalam kontur garis pantai yang berubah bentuk"
        >
            <defs>
                <clipPath :id="clipId"><path ref="coast" /></clipPath>
            </defs>
            <path ref="shadow" fill="#173e32" transform="translate(8 10)" />
            <path
                ref="outer"
                class="coast-outer"
                transform="translate(250 280) scale(1.055) translate(-250 -280)"
            />
            <g :clip-path="`url(#${clipId})`">
                <image
                    ref="photo"
                    href="/frontend/assets/img/hero.jpg"
                    x="0"
                    y="0"
                    width="500"
                    height="560"
                    preserveAspectRatio="xMidYMid slice"
                />
                <path d="M80 350C130 110 390 430 420 165" class="coast-route" />
            </g>
            <path ref="inner" class="coast-outline" />
            <g ref="traveller" class="coast-traveller" aria-hidden="true">
                <circle r="22" fill="#f8f5ed" stroke="#173e32" stroke-width="2" />
                <path
                    d="M0 -13L8 10L0 6L-8 10Z"
                    fill="#b34522"
                    stroke="#173e32"
                    stroke-width="1.5"
                />
            </g>
        </svg>
        <span class="coast-label" aria-hidden="true">IKUTI RASA INGIN PERGI ↗</span>
    </div>
</template>
