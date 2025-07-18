<div class="p-2 sm:p-4">
    <x-notification />

    {{-- Tombol & Pencarian Responsif --}}
    <div class="flex flex-col gap-2 mb-4 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <x-btn-add 
                class="w-full sm:w-auto"
                data-tip="Add data" 
                wire:click="$dispatch('openModal', { component: 'admin.event-category.create-and-update' })" 
            />
        </div>
        <div class="w-full sm:w-64">
            <x-inputsearch 
                name='search' 
                wire:model.live='search'
                class="w-full"
            />
        </div>
    </div>

    {{-- Table --}}
    <div class="overflow-x-auto bg-white rounded shadow">
        <table class="table w-full text-sm table-zebra table-xs">
            <thead>
                <tr class="text-center text-gray-700 bg-gray-100">
                    <th class="px-2 py-3">#</th>
                    <th class="px-2 py-3">Name</th>
                    <th class="px-2 py-3">Action</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($EventCategory as $no => $cc)
                <tr class="text-center">
                    <th class="px-2 py-2">{{ $EventCategory->firstItem() + $no }}</th>
                    <td class="px-2 py-2">{{ $cc->event_category_name }}</td>
                    <td class="px-2 py-2">
                        <div class="flex flex-wrap justify-center gap-1">
                            <x-icon-btn-edit 
                                data-tip="Edit" 
                                wire:click="$dispatch('openModal', { component: 'admin.event-category.create-and-update', arguments: { event_category: {{ $cc->id }} }})" 
                            />
                            <x-icon-btn-delete 
                                wire:click="delete({{ $cc->id }})" 
                                wire:confirm.prompt="Are you sure delete  ?\n\nType DELETE to confirm|DELETE" 
                                data-tip="Delete" 
                            />
                        </div>
                    </td>
                </tr>
                @empty
                <tr class="text-center">
                    <td colspan="3" class="py-4 text-error">Data not found!!!</td>
                </tr>
                @endforelse
            </tbody>
        </table>

        {{-- Pagination --}}
        <div class="mt-4">
            {{ $EventCategory->links() }}
        </div>
    </div>
</div>
