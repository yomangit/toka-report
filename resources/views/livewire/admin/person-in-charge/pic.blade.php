<div>
    <h2 class="text-lg font-bold">Divisi - Akses Khusus</h2>
    <div class="flex justify-end mb-4">
        <button wire:click="resetForm" class="px-4 py-2 text-white bg-green-600 rounded">
            + Tambah Akses Khusus
        </button>
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
                <td class="px-4 py-2">{{ $division->name }}</td>
                <td class="px-4 py-2">
                    {{ $division->users_pic->pluck('name')->join(', ') }}
                </td>
                <td class="px-4 py-2 text-center">
                    <button wire:click="edit({{ $division->id }})" class="text-blue-600">Edit</button>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <!-- Modal -->
    @if ($divisionId === 0)
    <div class="fixed inset-0 z-50 flex items-center justify-center bg-black/50">
        <div class="w-full max-w-md p-6 bg-white rounded-lg">
            <h3 class="mb-4 text-xl font-semibold">Tambah Akses Divisi</h3>

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

            <div class="mb-4">
                <label class="block mb-1">User</label>
                <select wire:model="selectedUsers" multiple class="w-full h-40 px-3 py-2 border rounded">
                    @foreach ($users as $user)
                    <option value="{{ $user->id }}">{{ $user->lookup_name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="flex justify-end gap-2">
                <button wire:click="$set('divisionId', null)" class="text-gray-500">Batal</button>
                <button wire:click="save" class="px-4 py-2 text-white bg-blue-600 rounded">
                    Simpan
                </button>
            </div>
        </div>
    </div>
    @endif

    @if ($divisionId !== null && $divisionId !== 0)
    <div class="fixed inset-0 z-50 flex items-center justify-center bg-black/50">
        <div class="w-full max-w-md p-6 bg-white rounded-lg">
            <h3 class="mb-4 text-xl font-semibold">
                {{ $editMode ? 'Edit' : 'Tambah' }} Akses Divisi
            </h3>

            <div class="mb-4">
                <label class="block mb-1">Divisi</label>
                <select wire:model="divisionId" class="w-full px-3 py-2 border rounded" @if($editMode) disabled @endif>
                    <option value="">Pilih Divisi</option>
                    @foreach ($divisions as $division)
                    <option value="{{ $division->id }}">{{ $division->formatWorkgroupName() }}</option>
                    @endforeach
                </select>
            </div>

            <div class="mb-4">
                <label class="block mb-1">User</label>
                <select wire:model="selectedUsers" multiple class="w-full h-40 px-3 py-2 border rounded">
                    @foreach ($users as $user)
                    <option value="{{ $user->id }}">{{ $user->lookup_name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="flex justify-end gap-2">
                <button wire:click="resetForm" class="text-gray-500">Batal</button>
                <button wire:click="save" class="px-4 py-2 text-white bg-blue-600 rounded">
                    Simpan
                </button>
            </div>
        </div>
    </div>
    @endif
</div>
