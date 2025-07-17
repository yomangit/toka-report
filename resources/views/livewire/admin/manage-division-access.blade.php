<div class="space-y-4">
    <h2 class="text-xl font-bold">Kelola Akses Divisi</h2>

    @if (session()->has('success'))
    <div class="text-green-600">{{ session('success') }}</div>
    @endif

    <button wire:click="openCreateModal" class="px-4 py-2 text-white bg-green-600 rounded">
        Tambah Akses Divisi
    </button>

    {{-- Tabel User & Akses --}}
    <table class="w-full mt-4 border">
        <thead class="bg-gray-100">
            <tr>
                <th class="p-2">User</th>
                <th class="p-2">Divisi</th>
                <th class="p-2">Aksi</th>
            </tr>
        </thead>
        <tbody>
            @foreach($users as $user)
            <tr class="border-t">
                <td class="p-2">{{ $user->name }}</td>
                <td class="p-2">
                    @foreach($user->divisions as $div)
                    <span class="inline-block px-2 py-1 mr-1 text-sm text-blue-800 bg-blue-100 rounded">
                        {{ $div->name }}
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


    <x-modal wire:model="showModal">
        <x-slot name="title">
            {{ $this->modalTitle }}
        </x-slot>

        <div class="space-y-4">
            <div>
                <label for="user" class="block font-semibold">Pilih User:</label>
                <select wire:model="selectedUserId" class="w-full border rounded">
                    <option value="">-- Pilih User --</option>
                    @foreach($users as $user)
                    <option value="{{ $user->id }}">{{ $user->name }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block font-semibold">Pilih Divisi:</label>
                <div class="p-2 overflow-y-auto border rounded max-h-48">
                    @foreach($divisions as $division)
                    <label class="block">
                        <input type="checkbox" wire:model="selectedDivisionIds" value="{{ $division->id }}">
                        {{ $division->name }}
                    </label>
                    @endforeach
                </div>
            </div>
        </div>

        <x-slot name="footer">
            <button wire:click="resetForm" class="px-4 py-2 bg-gray-300 rounded">Batal</button>
            <button wire:click="save" class="px-4 py-2 text-white bg-blue-600 rounded">Simpan</button>
        </x-slot>
    </x-modal>
</div>
