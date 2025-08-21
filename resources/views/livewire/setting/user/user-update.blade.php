<div class="max-w-md p-6 mx-auto bg-white rounded shadow">
    <form wire:submit.prevent="update" class="space-y-4">
        <!-- Nama -->
        <div>
            <label class="block text-sm font-medium text-gray-700">Name</label>
            <input type="text" wire:model.live="name"
                   class="block w-full mt-1 border-gray-300 rounded-md shadow-sm">
            @error('name') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
        </div>

        <!-- Departemen -->
        <div>
            <label class="block text-sm font-medium text-gray-700">Department</label>
            <select wire:model.live="department_id"
                    class="block w-full mt-1 border-gray-300 rounded-md shadow-sm">
                <option value="">-- Select Department --</option>
                @foreach ($departments as $dept)
                    <option value="{{ $dept->id }}">{{ $dept->department_name }}</option>
                @endforeach
            </select>
            @error('department_id') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
        </div>

        <!-- Password -->
        <div>
            <label class="block text-sm font-medium text-gray-700">Password (optional)</label>
            <input type="password" wire:model.live="password"
                   class="block w-full mt-1 border-gray-300 rounded-md shadow-sm">
            @error('password') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
        </div>

        <div>
            <button type="submit"
                class="px-4 py-2 text-white bg-blue-600 rounded shadow hover:bg-blue-700">
                Update User
            </button>
        </div>
    </form>
</div>
