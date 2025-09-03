<div>
    <div class="p-2" wire:target='files,store' wire:loading.class="skeleton">
        <div
            class="font-semibold text-transparent divider divider-info bg-clip-text bg-gradient-to-r from-pink-500 to-violet-500">
            <span class="text-transparent bg-clip-text bg-gradient-to-r from-pink-500 to-violet-500">
                Upload File
            </span>
        </div>

        <form wire:submit.prevent='store'>
            @csrf
            <div class="w-full max-w-md xl:max-w-xl form-control">
                <x-label-req :value="__('import_file')" />
                <x-input-file wire:model.live='files' :error="$errors->get('files')" />
                <x-label-error :messages="$errors->get('files')" />
            </div>

            {{-- ✅ tampilkan error dari import --}}
            @if (session()->has('failures'))
                <div class="p-3 mt-3 text-red-700 bg-red-100 rounded">
                    <p class="mb-1 font-semibold">Terjadi error pada data berikut:</p>
                    <ul class="space-y-1 text-sm list-disc list-inside">
                        @foreach (session('failures') as $failure)
                            <li>
                                Baris {{ $failure->row() }} -
                                Kolom: {{ $failure->attribute() }} -
                                Pesan: {{ implode(', ', $failure->errors()) }}
                            </li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="modal-action">
                <x-btn-save wire:target="store,files" wire:loading.class="btn-disabled">
                    {{ __('Save') }}
                </x-btn-save>
                <x-btn-close wire:target="store,files" wire:loading.class="btn-disabled"
                    wire:click="$dispatch('closeModal')">
                    {{ __('Close') }}
                </x-btn-close>
            </div>
        </form>
    </div>
</div>
