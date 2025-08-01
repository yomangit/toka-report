<div
    class="fixed inset-0 z-50 flex items-center justify-center h-screen bg-black bg-opacity-40"
    x-data="{
        open: @entangle($attributes->wire('model')),
        init() {
            document.addEventListener('keydown', (e) => {
                // Nonaktifkan tombol Escape
                if (e.key === 'Escape') e.preventDefault();
            });
        }
    }"
    x-init="init"
    x-show="open"
    x-transition:enter="transition ease-out duration-200"
    x-transition:enter-start="opacity-0 scale-95"
    x-transition:enter-end="opacity-100 scale-100"
    x-transition:leave="transition ease-in duration-150"
    x-transition:leave-start="opacity-100 scale-100"
    x-transition:leave-end="opacity-0 scale-95"
    style="display: none;"
>
    <div class="w-full max-w-lg p-6 rounded shadow-lg bg-base-300">
        <div class="text-lg font-semibold">
            {{ $title }}
        </div>

        <div class="mt-4">
            {{ $slot }}
        </div>

        <div class="flex justify-end mt-6 space-x-2">
            {{ $footer }}
        </div>
    </div>
</div>
