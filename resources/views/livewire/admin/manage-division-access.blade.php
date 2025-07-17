<div>
    <h2 class="mb-4 text-lg font-bold">Kelola Akses Divisi</h2>

   <div>
     <table class="w-full text-sm border">
        <thead class="bg-gray-200">
            <tr>
                <th class="p-2">Nama</th>
                <th class="p-2">Email</th>
                <th class="p-2">Divisi</th>
                <th class="p-2">Boleh Lihat Divisi</th>
                <th class="p-2">Aksi</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($users as $user)
                <tr class="border-t">
                    <td class="p-2">{{ $user->name }}</td>
                    <td class="p-2">{{ $user->email }}</td>
                    <td class="p-2">{{ optional($user->division)->formatWorkgroupName() }}</td>
                    <td class="p-2 text-center">
                        @if ($user->can_view_own_division)
                            ✅
                        @else
                            ❌
                        @endif
                    </td>
                    <td class="p-2">
                        <button wire:click="edit({{ $user->id }})" class="px-2 py-1 text-white bg-blue-500 rounded">Edit</button>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
    <div>{{ $users->links() }}</div>
   </div>

    @if ($editUserId)
        <div class="p-4 mt-6 bg-gray-100 rounded">
            <h3 class="mb-2 font-semibold">Edit Akses</h3>

            <label class="inline-flex items-center space-x-2">
                <input type="checkbox" wire:model="editCanView" class="form-checkbox">
                <span>Boleh melihat divisinya sendiri</span>
            </label>

            <div class="mt-3">
                <button wire:click="updateAccess" class="px-3 py-1 text-white bg-green-600 rounded">Simpan</button>
            </div>
        </div>
    @endif
</div>
