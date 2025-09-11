<div>
     <x-notification />
    <form wire:submit.prevent='store'>
        @csrf

        <!-- Name -->
        <div class="w-full max-w-xs sm:max-w-sm xl:max-w-xl form-control">
            <x-input-label for="name" :value="__('Name')" />
            <div class="dropdown dropdown-end">
                <x-input-search-with-error placeholder="search name" wire:model.live='name'
                    :error="$errors->get('name')" class="cursor-pointer read-only:bg-gray-200 " tabindex="0"
                    role="button" />
                <div tabindex="0"
                    class="dropdown-content card card-compact  bg-base-300 text-primary-content z-[1] w-full  p-2 shadow">
                    <div class="relative">
                        <ul class="pt-2 mb-2 overflow-auto scroll-smooth focus:scroll-auto h-28"
                            wire:target='name' wire:loading.class='hidden'>
                            @forelse ($User as $spv_area)
                                <div wire:click="name_Click({{ $spv_area->id }})"
                                    class="flex flex-col border-b cursor-pointer border-base-200 active:bg-gray-400">
                                    <strong
                                        class="text-[10px] text-slate-800">{{ $spv_area->lookup_name }}</strong>
                                </div>
                            @empty
                                <strong
                                    class="text-xs text-transparent bg-clip-text bg-gradient-to-r from-rose-400 to-rose-800">Name
                                    Not Found!!!</strong>
                            @endforelse
                            </ul>
                        <div class="hidden pt-5 text-center" wire:target='name'
                            wire:loading.class.remove='hidden'> <x-loading-spinner /></div>
                    </div>
                </div>
            </div>
            <x-label-error :messages="$errors->get('name')" />
        </div>
       

        <!-- Email Address -->
       <div class="w-full max-w-xs sm:max-w-sm xl:max-w-xl form-control">
            <x-input-label for="username" :value="__('Username')" />
            <x-text-input id="username" class="block w-full mt-1" type="text" wire:model.live="username" :error="$errors->get('username')" required autocomplete="username" />
            <x-input-error :messages="$errors->get('username')" class="mt-2" />
        </div>
        <!-- Email Address -->
       <div class="w-full max-w-xs sm:max-w-sm xl:max-w-xl form-control">
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" class="block w-full mt-1" type="email" wire:model.live="email" :error="$errors->get('email')" required autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>
       
        <!-- Password -->
       <div class="w-full max-w-xs sm:max-w-sm xl:max-w-xl form-control">
            <x-input-label for="password" :value="__('Password')" />

            <x-text-input  class="block w-full mt-1"
                            type="password"
                            :error="$errors->get('password')" 
                            wire:model.live="password"
                            required autocomplete="new-password" />

            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Confirm Password -->
       <div class="w-full max-w-xs sm:max-w-sm xl:max-w-xl form-control">
            <x-input-label for="password_confirmation" :value="__('Confirm Password')" />

            <x-text-input id="password_confirmation" class="block w-full mt-1"
                            type="password"
                            wire:model.live="password_confirmation"
                            :error="$errors->get('password_confirmation')" 
                            required autocomplete="new-password" />

            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
        </div>

        <div class="flex items-center justify-end mt-4">
            <a class="text-sm text-gray-600 underline rounded-md dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-gray-800" href="{{ route('login') }}">
                {{ __('Already registered?') }}
            </a>
            <x-btn-save class="ms-4" wire:target="store" wire:loading.class="btn-disabled">{{ __('Register') }}</x-btn-save>
           
        </div>
    </form>
</div>
