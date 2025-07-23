// Import dasar
import "./bootstrap";
// Toastify
import "toastify-js/src/toastify.css";
import Toastify from "toastify-js";
window.Toastify = Toastify;

// Flatpickr
import flatpickr from "flatpickr";
import "flatpickr/dist/flatpickr.min.css";
import monthSelectPlugin from "flatpickr/dist/plugins/monthSelect";
import "flatpickr/dist/themes/dark.css";
import "flatpickr/dist/plugins/monthSelect/style.css";
// Charts
import {
    renderDivisionChart
} from './charts/divisionChart';
import {
    renderPerbandinganChart
} from './charts/perbandinganChart';

document.addEventListener('DOMContentLoaded', () => {
    renderPerbandinganChart();
});
import {
    renderKondisiBarChart
} from './charts/kondisiChart';

document.addEventListener('DOMContentLoaded', () => {
    renderKondisiBarChart();
});
document.addEventListener('livewire:load', () => {
    renderKondisiBarChart(); // render pertama

    // ⏱ Emit event Livewire setiap 10 detik
    setInterval(() => {
        if (window.livewire) {
            window.livewire.dispatch('refreshKondisiChart');
        } else {
            console.warn('Livewire belum siap');
        }
    }, 3000);
});
// Inisialisasi semua flatpickr jika elemen ada
const initDatePickers = () => {
    const datePickers = [{
            selector: "#tanggal",
            options: {
                enableTime: true,
                dateFormat: "d-m-Y : H:i"
            }
        },
        {
            selector: "#due_date",
            options: {
                dateFormat: "d-m-Y"
            }
        },
        {
            selector: "#completion_date",
            options: {
                dateFormat: "d-m-Y"
            }
        },
        {
            selector: "#due_date_guest",
            options: {
                dateFormat: "d-m-Y"
            }
        },
        {
            selector: "#completion_date_guest",
            options: {
                dateFormat: "d-m-Y"
            }
        },
        {
            selector: "#tanggal_komplite",
            options: {
                dateFormat: "d-m-Y"
            }
        },
        {
            selector: "#date_birth",
            options: {
                dateFormat: "d-m-Y"
            }
        },
        {
            selector: "#date_commenced",
            options: {
                dateFormat: "d-m-Y"
            }
        },
        {
            selector: "#end_date",
            options: {
                dateFormat: "d-m-Y"
            }
        },
        {
            selector: "#month",
            options: {
                plugins: [new monthSelectPlugin({
                    shorthand: true,
                    dateFormat: "M-Y",
                    altFormat: "F Y",
                    theme: "dark",
                })]
            }
        }
    ];

    datePickers.forEach(({
        selector,
        options
    }) => {
        const el = document.querySelector(selector);
        if (el) {
            flatpickr(el, {
                disableMobile: "true",
                ...options,
            });
        }
    });
};

// Inisialisasi sidebar toggle aman
const initSidebarToggle = () => {
    const sidebarToggle = document.querySelector(".sidebar-toggle");
    const sidebarOverlay = document.querySelector(".sidebar-overlay");
    const sidebarMenu = document.querySelector(".sidebar-menu");
    const main = document.querySelector(".main");

    if (!sidebarToggle || !sidebarOverlay || !sidebarMenu || !main) return;

    if (window.innerWidth < 768) {
        main.classList.toggle("active");
        sidebarOverlay.classList.toggle("hidden");
        sidebarMenu.classList.toggle("-translate-x-full");
    }

    sidebarToggle.addEventListener("click", function (e) {
        e.preventDefault();
        main.classList.toggle("active");
        sidebarOverlay.classList.toggle("hidden");
        sidebarMenu.classList.toggle("-translate-x-full");
    });

    sidebarOverlay.addEventListener("click", function (e) {
        e.preventDefault();
        main.classList.add("active");
        sidebarOverlay.classList.add("hidden");
        sidebarMenu.classList.add("-translate-x-full");
    });

    document.querySelectorAll(".sidebar-dropdown-toggle").forEach(function (item) {
        item.addEventListener("click", function (e) {
            e.preventDefault();
            const parent = item.closest(".group");
            if (parent.classList.contains("selected")) {
                parent.classList.remove("selected");
            } else {
                document.querySelectorAll(".sidebar-dropdown-toggle").forEach(function (i) {
                    i.closest(".group") ?.classList.remove("selected");
                });
                parent.classList.add("selected");
            }
        });
    });
};

// Inisialisasi setelah DOM siap
document.addEventListener('DOMContentLoaded', () => {
    initDatePickers();
    initSidebarToggle();
    renderDivisionChart();
});

// Service Worker
if ('serviceWorker' in navigator) {
    navigator.serviceWorker.register('sw.js', {
            scope: '/'
        })
        .then(() => {})
        .catch(() => {});
}
