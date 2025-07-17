import TomSelect from 'tom-select';

function initTomSelect() {
    const selectEl = document.getElementById('user-select');
    if (selectEl && !selectEl.tomselect) {
        new TomSelect(selectEl, {
            create: false,
            allowEmptyOption: true,
            placeholder: "Cari user...",
        });
    }
}

// Inisialisasi saat pertama kali load
document.addEventListener('livewire:load', () => {
    initTomSelect();
});

// Re-init setelah Livewire DOM update (misalnya habis submit, buka modal, dsb)
Livewire.hook('message.processed', () => {
    initTomSelect();
});
