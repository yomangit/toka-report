<div class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-40" x-data="{ open: @entangle($attributes->wire('model')) }" x-show="open">
    <div @click.away="open = false" class="w-full max-w-lg p-6 rounded shadow-lg">
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
