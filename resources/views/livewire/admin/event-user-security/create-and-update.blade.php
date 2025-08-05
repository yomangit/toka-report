<div class="p-2 space-y-6">
    <div wire:target="store" wire:loading.class="skeleton">
        <div class="py-4 text-lg font-extrabold text-center text-transparent divider divider-info bg-clip-text bg-gradient-to-r from-pink-500 to-violet-500">
            {{ $divider }}
        </div>

        <form wire:submit.prevent='store' class="space-y-4">
            @csrf

            {{-- WORKGROUP --}}
            <div class="w-full">
                <x-label-req :value="__('Workgroup')" />
                <div class="relative dropdown dropdown-end">
                    <x-input wire:model.live='workgroupName' :error="$errors->get('workgroupName')" class="cursor-pointer" tabindex="0" role="button" />

                    <div tabindex="0" class="absolute z-20 w-full mt-1 dropdown-content card card-compact bg-primary text-primary-content">
                        <div class="flex flex-col w-full gap-1">
                            <div class="grid h-40 overflow-y-auto card bg-base-300 rounded-box">
                                <ul class="text-xs list-inside">
                                    <li class="px-2 font-semibold">Company</li>
                                    @foreach ($ParentCompany as $item)
                                        <li wire:click="parentCompany({{ $item->id }})" class="px-4 py-1 cursor-pointer hover:bg-base-200">
                                            {{ $item->name_category_company }}
                                        </li>
                                    @endforeach
                                    <li class="px-2 font-semibold">Business Unit</li>
                                    @foreach ($BusinessUnit as $item)
                                        <li wire:click="businessUnit({{ $item->id }})" class="px-4 py-1 cursor-pointer hover:bg-base-200">
                                            {{ $item->Company->name_company }}
                                        </li>
                                    @endforeach
                                    <li class="px-2 font-semibold">Department</li>
                                    @foreach ($Department as $item)
                                        <li wire:click="department({{ $item->id }})" class="px-4 py-1 cursor-pointer hover:bg-base-200">
                                            {{ $item->BusinesUnit->Company->name_company }} - {{ $item->Department->department_name }}
                                        </li>
                                    @endforeach
                                    <li class="px-2 font-semibold">Contractor</li>
                                    @foreach ($Division as $item)
                                        <li wire:click="division({{ $item->id }})" class="px-4 py-1 cursor-pointer hover:bg-base-200">
                                            {{ $item->formatWorkgroupName() }}
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
                <x-label-error :messages="$errors->get('workgroupName')" />
            </div>

            {{-- PEOPLE --}}
            <div class="w-full">
                <x-label-req :value="__('People')" />
                <ul class="menu menu-xs rounded-box p-2 shadow @error('user_id') border border-rose-500 @enderror">
                    <li class="menu-title">
                        <div class="mb-2">
                            <x-inputsearch name='search' wire:model.live='search_people' placeholder="{{ __('search_people') }}" />
                        </div>
                    </li>
                    <li class="menu-item">
                        <ul wire:loading.class="hidden" wire:target="search_people" class="overflow-y-auto list-inside h-28">
                            @forelse ($User as $users)
                                <li class="text-xs cursor-pointer hover:bg-slate-200">
                                    <label class="flex items-start gap-2 cursor-pointer">
                                        @if ($event_user_security_id)
                                            <input type="radio" wire:model.live="user_id_update" checked value="{{ $users->id }}" class="radio radio-primary radio-xs" />
                                        @else
                                            <input type="checkbox" wire:model.live="user_id" value="{{ $users->id }}" class="checkbox checkbox-primary checkbox-xs" />
                                        @endif
                                        <span class="label-text">{{ $users->lookup_name }}</span>
                                    </label>
                                </li>
                            @empty
                                <li class="text-xs italic text-rose-500">{{ __('dataNotFound') }}</li>
                            @endforelse
                        </ul>
                        <div class="hidden text-center" wire:target='search_people' wire:loading.class.remove='hidden'>
                            <x-loading-spinner />
                        </div>
                        <div class="m-2">
                            {{ $User->links('pagination.minipaginate') }}
                        </div>
                    </li>
                </ul>
                <x-label-error :messages="$errors->get('user_id')" />
            </div>

            {{-- RESPONSIBLE ROLE --}}
            <div class="w-full">
                <x-label-req :value="__('Workflow Role')" />
                <x-select wire:model='responsible_role_id' :error="$errors->get('responsible_role_id')">
                    <option value="">{{ __('Select an option') }}</option>
                    @foreach ($ResponsibleRole as $rr)
                        <option value="{{ $rr->id }}">{{ $rr->responsible_role_name }}</option>
                    @endforeach
                </x-select>
                <x-label-error :messages="$errors->get('responsible_role_id')" />
            </div>

            {{-- EVENT TYPE --}}
            <div class="w-full">
                <x-label-req :value="__('Event Type')" />
                <x-select wire:model='type_event_report_id' :error="$errors->get('type_event_report_id')">
                    <option value="">{{ __('Select an option') }}</option>
                    @foreach ($TypeEventReport as $item)
                        <option value="{{ $item->id }}">{{ $item->type_eventreport_name }}</option>
                    @endforeach
                </x-select>
                <x-label-error :messages="$errors->get('type_event_report_id')" />
            </div>

            {{-- BUTTONS --}}
            <div class="flex justify-end gap-2 pt-4">
                <x-btn-save wire:target="store" wire:loading.class="btn-disabled">{{ __('Save') }}</x-btn-save>
                <x-btn-close wire:target="store" wire:loading.class="btn-disabled" wire:click="$dispatch('closeModal')">{{ __('Close') }}</x-btn-close>
            </div>
        </form>
    </div>
</div>
