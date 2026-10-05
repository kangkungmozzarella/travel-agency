<script setup>
import { ref, computed } from 'vue'
import Icon from './Icon.vue'
import { imageFallback } from '../lib/format'
const props = defineProps({ images: Array, title: String })
const selected = ref(0)
const lightbox = ref(null)
const photo = computed(() => props.images[selected.value])
function move(direction) {
    selected.value = (selected.value + direction + props.images.length) % props.images.length
}
</script>

<template>
    <div class="photo-gallery">
        <template v-if="images.length">
            <button
                class="gallery-main"
                type="button"
                :aria-label="'Perbesar foto ' + photo.name"
                @click="lightbox.showModal()"
            >
                <Transition name="photo" mode="out-in"
                    ><img
                        :key="photo.id"
                        :src="photo.image_url"
                        :alt="photo.name"
                        @error="imageFallback" /></Transition
                ><span class="gallery-enlarge">Perbesar foto</span>
            </button>
            <div class="gallery-toolbar">
                <p aria-live="polite">
                    {{ photo.name }}
                    <span class="muted">/ {{ selected + 1 }} dari {{ images.length }}</span>
                </p>
                <div v-if="images.length > 1" class="button-row">
                    <button class="icon-button" aria-label="Foto sebelumnya" @click="move(-1)">
                        <Icon name="chevron" class="rotate" /></button
                    ><button class="icon-button" aria-label="Foto berikutnya" @click="move(1)">
                        <Icon name="chevron" />
                    </button>
                </div>
            </div>
            <div v-if="images.length > 1" class="gallery-thumbs">
                <button
                    v-for="(image, index) in images"
                    :key="image.id"
                    :class="{ selected: index === selected }"
                    :aria-label="'Lihat foto ' + image.name"
                    :aria-pressed="index === selected"
                    @click="selected = index"
                >
                    <img
                        :src="image.image_url"
                        :alt="image.name"
                        loading="lazy"
                        @error="imageFallback"
                    />
                </button>
            </div>
            <dialog
                ref="lightbox"
                class="lightbox"
                :aria-label="'Foto ' + title"
                @keydown.left="move(-1)"
                @keydown.right="move(1)"
            >
                <button class="button lightbox-close" autofocus @click="lightbox.close()">
                    <Icon name="close" /> Tutup</button
                ><img :src="photo.image_url" :alt="photo.name" @error="imageFallback" />
                <div class="lightbox-controls">
                    <button v-if="images.length > 1" class="button button-cream" @click="move(-1)">
                        Sebelumnya</button
                    ><span>{{ photo.name }}</span
                    ><button v-if="images.length > 1" class="button button-cream" @click="move(1)">
                        Berikutnya
                    </button>
                </div>
            </dialog>
        </template>
        <img
            v-else
            src="/images/placeholder.svg"
            alt="Foto destinasi belum tersedia"
            class="gallery-placeholder"
        />
    </div>
</template>
