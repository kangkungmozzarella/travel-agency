<script setup>
import { Link } from '@inertiajs/vue3'
import Icon from './Icon.vue'
import { money, imageFallback } from '../lib/format'
defineProps({ package: Object, revealDelay: { type: Number, default: 0 } })
</script>

<template>
    <article class="package-card" v-reveal="revealDelay">
        <Link :href="'/travel-packages/' + package.slug" class="package-link">
            <div class="package-image">
                <img
                    :src="package.image_url"
                    :alt="package.location"
                    loading="lazy"
                    @error="imageFallback"
                /><span class="duration">{{ package.type }}</span>
            </div>
            <div class="package-body">
                <span class="package-location"
                    ><Icon name="pin" :size="16" /> Destinasi perjalanan</span
                >
                <h3>{{ package.location }}</h3>
                <div class="package-bottom">
                    <div>
                        <span class="small muted">Harga paket</span
                        ><strong>{{ money(package.price) }}</strong>
                    </div>
                    <span class="card-arrow"><Icon name="arrow" /></span>
                </div>
            </div>
        </Link>
    </article>
</template>
