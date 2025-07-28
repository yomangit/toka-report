<div class="p-4 space-y-4">
    <h2 class="text-lg font-bold">Divisi - Akses Khusus</h2>

    <div class="flex justify-end">
        <x-btn-add data-tip="Tambah Data" wire:click="openCreateModal" />
    </div>

    <div class="overflow-x-auto">
        <table class="min-w-full text-sm table-auto">
            <thead>
                <tr class="text-left bg-gray-100">
                    <th class="px-4 py-2">Divisi</th>
                    <th class="px-4 py-2">User</th>
                    <th class="px-4 py-2 text-center">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($specialAccessList as $division)
                    <tr class="border-b">
                        <td class="px-4 py-2">{{ $division->formatWorkgroupName() }}</td>
                        <td class="px-4 py-2">
                            {{ $division->users_pic->pluck('lookup_name')->join(', ') }}
                        </td>
                        <td class="px-4 py-2 space-x-2 text-center">
                            <x-icon-btn-edit data-tip="Edit" wire:click="openEditModal({{ $division->id }})" class="text-blue-600" />
                            <x-icon-btn-delete data-tip="Hapus" wire:click="delete({{ $division->id }})" class="text-red-600" onclick="return confirm('Hapus semua akses divisi user ini?')" />
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="mt-2">{{ $specialAccessList->links() }}</div>

    <!-- Modal -->
    <x-modal wire:model="showModal">
        <x-slot name="title">
            {{ $this->modalTitle }}
        </x-slot>

        <div class="space-y-4">
            <!-- Divisi Dropdown -->
            <div>
                <label for="division" class="block font-semibold">Pilih Divisi:</label>
                <div class="relative w-full" wire:click.away="$set('showDivisionDropdown', false)">
                    <x-input wire:model.live="searchDivisionQuery" placeholder="Cari nama divisi..." :error="$errors->get('searchDivisionQuery')" />
                    @if ($showDivisionDropdown && strlen($searchDivisionQuery) > 1)
                        <ul class="absolute z-10 w-full mt-1 overflow-auto text-sm bg-white border border-gray-300 rounded shadow max-h-60">
                            @forelse ($divisionSearchResults as $division)
                                <li wire:click="selectDivisionFromDropdown({{ $division->id }})" class="px-3 py-2 cursor-pointer hover:bg-sky-100">
                                    {{ $division->formatWorkgroupName() }}
                                </li>
                            @empty
                                <li class="px-3 py-2 text-gray-400">Tidak ditemukan</li>
                            @endforelse
                        </ul>
                    @endif
                </div>
            </div>

            <!-- User Checkbox -->
            <div>
                <label class="block font-semibold">Pilih User:</label>
                <x-inputsearch name='search' wire:model.live='search_nama' class="w-full" />

                <div class="p-2 mt-2 space-y-2 overflow-y-auto border rounded max-h-48">
                    @foreach ($users as $user)
                        <label class="flex items-center space-x-2">
                            <input type="checkbox" wire:model="selectedUsers" value="{{ (string) $user->id }}" class="checkbox checkbox-xs" />
                            <span>{{ $user->lookup_name }}</span>
                        </label>
                    @endforeach
                </div>
                <div class="pt-2">{{ $users->links('pagination.minipaginate') }}</div>
            </div>
        </div>

        <x-slot name="footer">
            <x-btn-save wire:click="save">Simpan</x-btn-save>
            <x-btn-close wire:click="resetForm">Batal</x-btn-close>
        </x-slot>
    </x-modal>
    <script>
         function updateTooltipPosition() {
            const isMobile = window.innerWidth < 640;
            document.querySelectorAll('.tooltip').forEach((el) => {
                el.classList.remove('tooltip-top', 'tooltip-right', 'tooltip-left', 'tooltip-bottom');
                el.classList.add(isMobile ? 'tooltip-top' : 'tooltip-left');
            });
        }
        window.addEventListener('DOMContentLoaded', updateTooltipPosition);
        window.addEventListener('resize', updateTooltipPosition);
    </script>
   
</div>
