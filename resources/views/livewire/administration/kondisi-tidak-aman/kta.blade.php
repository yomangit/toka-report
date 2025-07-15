<div>
    <div class="flex flex-col sm:flex-row sm:justify-between ">
        <div>
            <x-btn-add wire:click='openModal'/>
        </div>
        <div>
            <x-inputsearch name='search' wire:model.live='search' />
        </div>
    </div>

    <dialog class="{{ $modal }}">
        <div class="modal-box">
            <div
                class="py-4 font-extrabold text-transparent divider divider-info bg-clip-text bg-gradient-to-r from-pink-500 to-violet-500">
                {{ $divider }}</div>
            <form wire:submit.prevent='store'>
                @csrf
                @method('PATCH')
                <div class="w-full max-w-xs sm:max-w-sm xl:max-w-xl form-control">
                    <x-label-req :value="__('Name')" />
                    <x-input wire:model.blur='name' :error="$errors->get('name')" />
                    <x-label-error :messages="$errors->get('name')" />
                </div>
                <div class="modal-action">
                    <button type="submit" class="btn btn-xs btn-success btn-outline">Save</button>
                    <label wire:click='closeModal' class="btn btn-xs btn-error btn-outline">Close</label>
                </div>
            </form>
        </div>
    </dialog>
</div>
