<div class="max-w-md p-6 mx-auto bg-white rounded shadow">
    <form wire:submit.prevent="update" class="space-y-4">
        <!-- Nama -->
        <div>
            <label class="block text-sm font-medium text-gray-700">Name</label>
            <input type="text" wire:model.live="name" class="w-full input input-sm">
            @error('name') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
        </div>

        <!-- Departemen -->
        <div>
            <label class="block text-sm font-medium text-gray-700">Department</label>
            <select wire:model.live="department_id" class="block w-full mt-1 border-gray-300 rounded-md shadow-sm select select-sm">
                <option value="">-- Select Department --</option>
                @foreach ($departments as $dept)
                <option value="{{ $dept->id }}">{{ $dept->department_name }}</option>
                @endforeach
            </select>
            @error('department_id') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
        </div>

        <!-- Password -->
        <div x-data="{ show: false }" class="relative">
            <label class="block text-sm font-medium text-gray-700">Password (optional)</label>

            <input :type="show ? 'text' : 'password'" wire:model.live="password" class="block w-full input input-sm">

            <!-- Tombol toggle -->
            <button type="button" @click="show = !show" class="absolute inset-y-0 right-0 flex items-center pr-3 text-sm leading-5">
                <svg x-show="!show" xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.477 0 8.268 2.943 9.542 7-1.274 4.057-5.065 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                </svg>
                <svg x-show="show" xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.477 0-8.268-2.943-9.542-7a9.956 9.956 0 012.815-4.427M9.88 9.88a3 3 0 104.24 4.24" />
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3l18 18" />
                </svg>
            </button>

            @error('password')
            <span class="text-xs text-red-500">{{ $message }}</span>
            @enderror
        </div>


        <div>
            <button type="submit" class="px-4 py-2 text-white bg-blue-600 rounded shadow hover:bg-blue-700">
                Update User
            </button>
        </div>
    </form>
</div>
