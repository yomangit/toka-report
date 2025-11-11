<div>
    <x-notification />
    <form wire:submit.prevent='store'>
        @csrf

        <!-- Name -->
        <div class="w-full max-w-xs sm:max-w-sm xl:max-w-xl form-control">
            <x-input-label for="name" :value="__('Name')" />
            <div class="dropdown dropdown-end">
                <x-input-search-with-error placeholder="search name" wire:model.live='name' :error="$errors->get('name')" class="cursor-pointer read-only:bg-gray-200 " tabindex="0" role="button" />
                <div tabindex="0" class="dropdown-content card card-compact  bg-base-300 text-primary-content z-[1] w-full  p-2 shadow">
                    <div class="relative">
                        <ul class="pt-2 mb-2 overflow-auto scroll-smooth focus:scroll-auto h-28" wire:target='name' wire:loading.class='hidden'>
                            @forelse ($User as $spv_area)
                            <div wire:click="name_Click({{ $spv_area->id }})" class="flex flex-col border-b cursor-pointer border-base-200 active:bg-gray-400">
                                <strong class="text-[10px] text-slate-800">{{ $spv_area->lookup_name }}</strong>
                            </div>
                            @empty
                            <strong class="text-xs text-transparent bg-clip-text bg-gradient-to-r from-rose-400 to-rose-800">Name
                                Not Found!!!</strong>
                            @endforelse
                        </ul>
                        <div class="hidden pt-5 text-center" wire:target='name' wire:loading.class.remove='hidden'>
                            <x-loading-spinner />
                        </div>
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

            <div class="relative">
                <input id="password" class="block w-full mt-1 pr-10 input input-bordered @error('password') input-error @enderror" type="{{ $showPassword ? 'text' : 'password' }}" wire:model.live="password" required autocomplete="new-password" />

                <!-- eye icon -->
                <button type="button" wire:click="togglePasswordVisibility" class="absolute inset-y-0 right-0 flex items-center pr-3 text-gray-500 hover:text-gray-800">
                    @if($showPassword)
                    <!-- eye-off icon -->
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-5.523 0-10-4.477-10-10a9.958 9.958 0 012.1-6.125M6.222 6.222L3 3m18 18l-3.222-3.222M9.88 9.88a3 3 0 104.24 4.24" />
                    </svg>
                    @else
                    <!-- eye icon -->
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.522 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.478 0-8.268-2.943-9.542-7z" />
                    </svg>
                    @endif
                </button>
            </div>
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Confirm Password -->
        <div class="w-full max-w-xs sm:max-w-sm xl:max-w-xl form-control">
            <x-input-label for="password_confirmation" :value="__('Confirm Password')" />

            <div class="relative">
                <input id="password_confirmation" class="block w-full mt-1 pr-10 input input-bordered @error('password_confirmation') input-error @enderror" type="{{ $showPasswordConfirmation ? 'text' : 'password' }}" wire:model.live="password_confirmation" required autocomplete="new-password" />

                <!-- eye icon -->
                <button type="button" wire:click="togglePasswordConfirmationVisibility" class="absolute inset-y-0 right-0 flex items-center pr-3 text-gray-500 hover:text-gray-800">
                    @if($showPasswordConfirmation)
                    <!-- eye-off icon -->
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-5.523 0-10-4.477-10-10a9.958 9.958 0 012.1-6.125M6.222 6.222L3 3m18 18l-3.222-3.222M9.88 9.88a3 3 0 104.24 4.24" />
                    </svg>
                    @else
                    <!-- eye icon -->
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.522 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.478 0-8.268-2.943-9.542-7z" />
                    </svg>
                    @endif
                </button>
            </div>

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
