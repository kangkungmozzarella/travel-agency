export const money = (value) =>
    new Intl.NumberFormat('id-ID', {
        style: 'currency',
        currency: 'IDR',
        maximumFractionDigits: 0,
    }).format(value ?? 0)
export const dateLabel = (value) =>
    value
        ? new Intl.DateTimeFormat('id-ID', {
              day: 'numeric',
              month: 'short',
              year: 'numeric',
          }).format(new Date(value.slice(0, 10) + 'T00:00:00'))
        : 'Belum ditentukan'
export const statusLabels = {
    pending: 'Menunggu konfirmasi',
    confirmed: 'Dikonfirmasi',
    completed: 'Selesai',
    cancelled: 'Dibatalkan',
}
export const today = () =>
    new Intl.DateTimeFormat('en-CA', {
        timeZone: 'Asia/Jakarta',
        year: 'numeric',
        month: '2-digit',
        day: '2-digit',
    }).format(new Date())
export const imageFallback = (event) => {
    if (!event.target.src.endsWith('/images/placeholder.svg'))
        event.target.src = '/images/placeholder.svg'
}
