import TomSelect from 'tom-select';
// fungsi reusable untuk inisialisasi
function initTomSelects() {
    document.querySelectorAll('select.tom-select').forEach((el) => {
        if (!el.tomSelect) {
            el.tomSelect = new TomSelect(el, {
                create: false,
                allowEmptyOption: true,
                sortField: {
                    field: "text",
                    direction: "asc"
                }
            });
        }
    });
}

// Auto init setelah semua update Livewire
Livewire.hook('message.processed', (message, component) => {
    initTomSelects();
});

// Manual trigger pakai dispatch('initTomSelect')
Livewire.on('initTomSelect', () => {
    initTomSelects();
});
