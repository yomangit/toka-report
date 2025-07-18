<div class="p-4">
    <x-notification />

    {{-- Header Action --}}
    <div class="flex flex-col gap-2 mb-4 sm:flex-row sm:items-center sm:justify-between">
        <x-btn-add 
            class="w-full sm:w-auto" 
            data-tip="Add data" 
            wire:click="$dispatch('openModal', { component: 'admin.event-category.create-and-update' })" 
        />

        <x-inputsearch 
            name="search" 
            wire:model.live="search" 
            class="w-full sm:w-64"
        />
    </div>

    {{-- Grid Card Style --}}
    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-3">
        @forelse ($EventCategory as $no => $cc)
            <div class="p-4 bg-white rounded-lg shadow">
                <div class="flex items-center justify-between mb-2">
                    <span class="font-bold text-gray-700">#{{ $EventCategory->firstItem() + $no }}</span>
                    <div class="flex gap-1">
                        <x-icon-btn-edit 
                            data-tip="Edit" 
                            wire:click="$dispatch('openModal', { component: 'admin.event-category.create-and-update', arguments: { event_category: {{ $cc->id }} }})" 
                        />
                        <x-icon-btn-delete 
                            wire:click="delete({{ $cc->id }})" 
                            wire:confirm.prompt="Are you sure delete?\n\nType DELETE to confirm|DELETE" 
                            data-tip="Delete" 
                        />
                    </div>
                </div>
                <div class="text-gray-800">
                    <p class="font-semibold">{{ $cc->event_category_name }}</p>
                </div>
            </div>
        @empty
            <div class="text-center col-span-full text-error">
                Data not found!!!
            </div>
        @endforelse
    </div>

    {{-- Pagination --}}
    <div class="mt-6">
        {{ $EventCategory->links() }}
    </div>
</div>
