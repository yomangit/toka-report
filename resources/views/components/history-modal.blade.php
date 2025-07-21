@props([
    'show' => false,
    'logs' => [],
    'title' => '📜 Riwayat Perubahan',
    'maxWidth' => '2xl',
])

<x-modal wire:model="$show" :maxWidth="$maxWidth">
    <x-slot name="title">
        {{ $title }}
    </x-slot>

    <x-slot name="content">
        <div class="max-h-[70vh] overflow-y-scroll scroll-smooth pr-2">
            @forelse ($logs as $log)
                <div class="p-4 mb-3 transition duration-200 border rounded-md shadow-sm bg-base-100 hover:bg-base-200">
                    <div class="flex flex-col sm:flex-row sm:justify-between">
                        <div class="text-sm text-gray-700">
                            <strong>{{ $log->user->name }}</strong> melakukan
                            <span class="italic text-blue-600">{{ $log->action }}</span>
                        </div>
                        <div class="mt-1 text-xs text-gray-500 sm:mt-0">
                            {{ $log->created_at->format('d M Y H:i') }}
                        </div>
                    </div>

                    @if ($log->old_values || $log->new_values)
                        <div class="mt-2 text-sm">
                            <div class="text-red-500">
                                <strong>Dari:</strong> {{ $log->old_values['status'] ?? '-' }}
                            </div>
                            <div class="mt-1 text-green-600">
                                <strong>Menjadi:</strong> {{ $log->new_values['status'] ?? '-' }}
                            </div>
                        </div>
                    @endif
                </div>
            @empty
                <div class="text-sm italic text-gray-500">Belum ada perubahan yang tercatat.</div>
            @endforelse
        </div>
    </x-slot>

    <x-slot name="footer">
        <x-btn-close wire:click="$set('$show', false)">Tutup</x-btn-close>
    </x-slot>
</x-modal>
