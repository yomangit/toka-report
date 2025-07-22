<div>
    <x-notification />
    <div class="flex justify-between">
        <x-btn-add data-tip="Tambah Data" wire:click="openCreateModal" />
         <x-inputsearch name='search' wire:model.live='search_nama' />
    </div>
    {{-- Tabel User & Akses --}}
    <div class="overflow-x-auto">
        <table class="table table-zebra table-xs">
            <thead>
              <tr class="text-center">
                    <th>User</th>
                    <th>Divisi</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($users as $user)
                <tr class="text-center">
                    <td>{{ $user->lookup_name }}</td>
                    <td>
                        @foreach($user->divisions as $div)
                        <span class="inline-block px-2 mr-1 text-sm text-blue-800 bg-blue-100 rounded">
                            {{ $div->formatWorkgroupName() }}
                        </span>
                        @endforeach
                    </td>
                    <td class="p-2 space-x-2">
                        <x-icon-btn-edit data-tip="Edit" wire:click="openEditModal({{ $user->id }})" class="text-blue-600"/>
                       <x-icon-btn-delete data-tip="Hapus" wire:click="delete({{ $user->id }})" class="text-red-600" onclick="return confirm('Hapus semua akses divisi user ini?')"/>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <div class="mt-2">{{ $users->links() }}</div>

    <x-modal wire:model="showModal">
        <x-slot name="title">
            {{ $this->modalTitle }}
        </x-slot>
        <div class="space-y-4">
            <div>
                <label for="user" class="block font-semibold">Pilih User:</label>
                <div class="relative w-full" wire:click.away="$set('showUserDropdown', false)">
                    <x-input wire:model.live="searchUserQuery" placeholder="Cari nama user..." :error="$errors->get('searchUserQuery')" />

                    @if ($showUserDropdown && strlen($searchUserQuery) > 1)
                    <ul class="absolute z-10 w-full mt-1 overflow-auto text-sm bg-white border border-gray-300 rounded shadow max-h-60">
                        @forelse ($searchResults as $user)
                        <li wire:click="selectUserFromDropdown({{ $user->id }})" class="px-3 py-2 cursor-pointer hover:bg-sky-100">
                            {{ $user->lookup_name }}
                        </li>
                        @empty
                        <li class="px-3 py-2 text-gray-400">Tidak ditemukan</li>
                        @endforelse
                    </ul>
                    @endif
                </div>

            </div>
            <div>
                <label class="block font-semibold">Pilih Divisi:</label>
                <div class="p-2 overflow-y-auto border rounded max-h-48">
                    @foreach($divisions as $division)
                    <label class="block">
                        <input type="checkbox" wire:model="selectedDivisionIds" class="checkbox checkbox-xs" value="{{ $division->id }}">
                        {{ $division->formatWorkgroupName() }}
                    </label>
                    @endforeach
                </div>
            </div>
        </div>
        <x-slot name="footer">
            <x-btn-save wire:click="store" >Simpan</x-btn-save>
            <x-btn-close wire:click="resetForm" >Batal</x-btn-close>
        </x-slot>
    </x-modal>
</div>
