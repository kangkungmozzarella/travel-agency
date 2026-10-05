<script setup>
defineProps({ name: String, size: { type: Number, default: 20 } })
const paths = {
    mountain: 'M2 19 10 5l6 10 3-5 5 9H2Z M7 10l3 3 3-3',
    compass: 'M12 3a9 9 0 1 0 0 18 9 9 0 0 0 0-18Z m4 5-3 5-5 3 3-5 5-3Z',
    grid: 'M3 3h7v7H3V3Z M14 3h7v7h-7V3Z M3 14h7v7H3v-7Z M14 14h7v7h-7v-7Z',
    calendar: 'M4 5h16v16H4V5Z M8 2v6 M16 2v6 M4 11h16 M8 15h2 M14 15h2',
    book: 'M4 3h16v18H4V3Z M8 3v18 M12 8h4 M12 12h4',
    folder: 'M3 6h7l2 3h9v11H3V6Z',
    users: 'M9 4a3 3 0 1 0 0 6 3 3 0 0 0 0-6Z M2 21v-4a5 5 0 0 1 10 0v4 M16 4a3 3 0 0 1 0 6 M16 12a5 5 0 0 1 5 5v4',
    settings:
        'M12 3v3 M12 18v3 M3 12h3 M18 12h3 M6 6l2 2 M16 16l2 2 M6 18l2-2 M16 8l2-2 M12 8a4 4 0 1 0 0 8 4 4 0 0 0 0-8Z',
    search: 'M10 3a7 7 0 1 0 0 14 7 7 0 0 0 0-14Z M15 15l6 6',
    menu: 'M3 6h18 M3 12h18 M3 18h18',
    close: 'm5 5 14 14 M19 5 5 19',
    arrow: 'M4 12h16 M13 5l7 7-7 7',
    chevron: 'm9 5 7 7-7 7',
    plus: 'M12 4v16 M4 12h16',
    pin: 'M12 2a7 7 0 0 0-7 7c0 5 7 13 7 13s7-8 7-13a7 7 0 0 0-7-7Z M12 6a3 3 0 1 0 0 6 3 3 0 0 0 0-6Z',
    check: 'm4 12 5 5L20 6',
    logout: 'M10 3H3v18h7 M9 12h12 M16 7l5 5-5 5',
    phone: 'm5 3 4 4-2 3a17 17 0 0 0 7 7l3-2 4 4-3 3C9 23 1 15 2 6l3-3Z',
}
</script>

<template>
    <svg
        :width="size"
        :height="size"
        viewBox="0 0 24 24"
        fill="none"
        stroke="currentColor"
        stroke-width="1.8"
        stroke-linecap="square"
        stroke-linejoin="round"
        aria-hidden="true"
    >
        <path :d="paths[name] || paths.compass" />
    </svg>
</template>
