<div>
    <h2 class="mb-4 text-lg font-bold">Kelola Akses Divisi</h2>
    <div class="flex flex-col sm:flex-row sm:justify-between ">
        <div> </div>
        <div>
            <div class="flex flex-col sm:flex-row">
                <x-inputsearch name='search' wire:model.live='search_nama' placeholder="Search Department" />
            </div>
        </div>
    </div>
    <div class="overflow-x-auto">
        <table class="table w-full text-sm table-xs">
            <thead class="">
                <tr>
                    <th>Nama</th>
                    <th>Email</th>
                    <th>Divisi</th>
                    <th>Boleh Lihat Divisi</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($users as $user)
                <tr class="border-t">
                    <td>{{ $user->lookup_name }}</td>
                    <td>{{ $user->email }}</td>
                    <td>{{ optional($user->division)->formatWorkgroupName() }}</td>
                    <td class="p-2 text-center">
                        @if ($user->can_view_own_division)
                        ✅
                        @else
                        ❌
                        @endif
                    </td>
                    <td>
                        <button wire:click="edit({{ $user->id }})" class="px-2 py-1 text-white bg-blue-500 rounded">Edit</button>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        <div>{{ $users->links() }}</div>
    </div>

    <x-modal wire:model="showEditModal">
        <x-slot name="title">
            Edit Akses Divisi
        </x-slot>

        <div class="space-y-3">
            <div>
                <p><strong>Nama:</strong> {{ $editUserName }}</p>
                <p><strong>Email:</strong> {{ $editUserEmail }}</p>
            </div>

            <label class="inline-flex items-center space-x-2">
                <input type="checkbox" wire:model="editCanView" class="form-checkbox">
                <span>Boleh melihat divisinya sendiri</span>
            </label>
        </div>

        <x-slot name="footer">
            <button wire:click="updateAccess" class="px-3 py-1 text-white bg-green-600 rounded">Simpan</button>
            <button wire:click="$set('showEditModal', false)" class="px-3 py-1 bg-gray-300 rounded">Tutup</button>
        </x-slot>
    </x-modal>
</div>
