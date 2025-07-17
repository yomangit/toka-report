
import TomSelect from 'tom-select';

// Inisialisasi semua <select data-tom>
function initTomSelects() {
    document.querySelectorAll('select[data-tom]').forEach((el) => {
        if (!el.tomselect) {
            new TomSelect(el, {
                create: false,
                allowEmptyOption: true,
                placeholder: el.getAttribute('placeholder') || "Pilih opsi...",
            });
        }
    });
}

// Pertama kali halaman dimuat
document.addEventListener('livewire:load', () => {
    initTomSelects();
});

// Saat Livewire update DOM (misal: buka modal, submit, dsb)
Livewire.hook('message.processed', () => {
    initTomSelects();
});
