<div>
    <h2 class="text-lg font-bold">Divisi - Akses Khusus</h2>
    <div class="flex justify-end mb-4">
        <x-btn-add data-tip="Tambah Data" wire:click="openCreateModal" />
    </div>
    <table class="w-full mt-4 table-auto">
        <thead>
            <tr class="bg-gray-100">
                <th class="px-4 py-2 text-left">Divisi</th>
                <th class="px-4 py-2 text-left">User</th>
                <th class="px-4 py-2">Aksi</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($specialAccessList as $division)
            <tr>
                <td class="px-4 py-2">{{ $division->formatWorkgroupName() }}</td>
                <td class="px-4 py-2">
                    {{ $division->users_pic->pluck('lookup_name')->join(', ') }}
                </td>
                <td class="px-4 py-2 text-center">
                    <x-icon-btn-edit data-tip="Edit" wire:click="openEditModal({{ $division->id }})" class="text-blue-600" />
                    <x-icon-btn-delete data-tip="Hapus" wire:click="delete({{ $division->id }})" class="text-red-600" onclick="return confirm('Hapus semua akses divisi user ini?')" />
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
    <!-- Modal -->
        <x-modal wire:model="showModal">
            <x-slot name="title">
                {{ $this->modalTitle }}
            </x-slot>
            <div class="mb-4">
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

            <div>
                <label class="block font-semibold">Pilih User:</label>
                <div class="p-2 overflow-y-auto border rounded max-h-48">
                    @foreach($users as $user)
                    <label class="block">
                        <input type="checkbox" wire:model="selectedUserIds" class="checkbox checkbox-xs" value="{{ $user->id }}">
                        {{ $user->lookup_name }}
                    </label>
                    @endforeach
                </div>
            </div>
            <x-slot name="footer">
                <x-btn-save wire:click="save">Simpan</x-btn-save>
                <x-btn-close wire:click="resetForm">Batal</x-btn-close>
            </x-slot>
        </x-modal>
</div>
