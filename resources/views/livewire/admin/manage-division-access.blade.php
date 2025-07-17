<div>
    <h2 class="text-xl font-bold">Kelola Akses Divisi</h2>
    <x-notification />
    <x-btn-add data-tip="Tambah Data" wire:click="openCreateModal" />
    <div class="p-4">
        <div class="relative w-full" wire:click.away="$set('showUserDropdown', false)">
            <input type="text" wire:model.live="searchUserQuery" class="w-full input input-bordered input-sm" placeholder="Cari nama user...">

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

            @if ($selectedUserId)
            <p class="mt-1 text-xs text-gray-500">User terpilih: {{ \App\Models\User::find($selectedUserId)?->name }}</p>
            @endif
        </div>
    </div>
    {{-- Tabel User & Akses --}}
    <div class="overflow-x-auto">
        <table class="table table-zebra table-xs">
            <thead>
                <tr>
                    <th>User</th>
                    <th>Divisi</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($users as $user)
                <tr class="border-t">
                    <td>{{ $user->lookup_name }}</td>
                    <td>
                        @foreach($user->divisions as $div)
                        <span class="inline-block px-2 py-1 mr-1 text-sm text-blue-800 bg-blue-100 rounded">
                            {{ $div->formatWorkgroupName() }}
                        </span>
                        @endforeach
                    </td>
                    <td class="p-2 space-x-2">
                        <button wire:click="openEditModal({{ $user->id }})" class="text-blue-600">Edit</button>
                        <button wire:click="delete({{ $user->id }})" class="text-red-600" onclick="return confirm('Hapus semua akses divisi user ini?')">Hapus</button>
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
                <x-select wire:model="selectedUserId" id="select-user" :error="$errors->get('selectedUserId')">
                    <option value="">-- Pilih User --</option>
                    @foreach ($users as $user)
                    <option value="{{ $user->id }}">{{ $user->lookup_name }}</option>
                    @endforeach
                </x-select>

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
            <button wire:click="resetForm" class="px-4 py-2 bg-gray-300 rounded">Batal</button>
            <button wire:click="store" class="px-4 py-2 text-white bg-blue-600 rounded">Simpan</button>
        </x-slot>
    </x-modal>
</div>
