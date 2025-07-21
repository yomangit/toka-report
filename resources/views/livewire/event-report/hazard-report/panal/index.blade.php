<div class="p-2 md:p-4">
    <form wire:submit.prevent='store'>
        <div wire:target="store" wire:loading.class="skeleton" class="p-3 bg-white border rounded-md shadow-sm">
            <div class="grid gap-4 md:grid-cols-2">
                {{-- Info Kiri --}}
                <div class="space-y-2">
                    <div>
                        <div class="font-mono text-xs font-semibold text-gray-600">Current Step</div>
                        <div class="font-mono text-sm italic font-semibold text-gray-800">
                            {{ $current_step }}
                        </div>
                    </div>
                    <div>
                        <div class="font-mono text-xs font-semibold text-gray-600">Status</div>
                        <div class="font-mono text-sm italic font-semibold">
                            <span class="bg-clip-text text-transparent {{ $bg_status }}">
                                {{ $status }}
                            </span>
                        </div>
                    </div>
                </div>

                {{-- Procced dan Assignment --}}
                <div class="space-y-4">
                    @if ($muncul)
                    <div>
                        <x-label-no-req value="Procced To" class="font-mono text-sm font-semibold" />
                        <x-select wire:model.live='procced_to' :error="$errors->get('procced_to')" class="w-full">
                            <option value="">Select an option</option>
                            @forelse ($Workflow as $value)
                            <option value="{{ $value->destination_1 }}">{{ $value->destination_1_label }}</option>
                            @if ($value->destination_2)
                            <option value="{{ $value->destination_2 }}">{{ $value->destination_2_label }}</option>
                            @elseif($value->is_cancel_step === 'Cancel')
                            <option value="{{ $value->is_cancel_step }}">{{ $value->is_cancel_step }}</option>
                            @endif
                            @empty
                            @endforelse
                        </x-select>
                        <x-label-error :messages="$errors->get('procced_to')" />
                    </div>

                    @if ($show)
                    <div class="grid gap-4 md:grid-cols-2">
                        <div>
                            <x-label-no-req value="Assign To" class="font-mono text-sm font-semibold" />
                            <x-select wire:model.live='assign_to' :error="$errors->get('assign_to')" class="w-full">
                                <option value="">Select an option</option>
                                @foreach ($EventUserSecurity as $user)
                                <option value="{{ $user->user_id }}">{{ $user->User->lookup_name }}</option>
                                @endforeach
                            </x-select>
                            <x-label-error :messages="$errors->get('assign_to')" />
                        </div>

                        <div>
                            <x-label-no-req value="Also Assign To" class="font-mono text-sm font-semibold" />
                            <x-select wire:model.live='also_assign_to' :error="$errors->get('also_assign_to')" class="w-full">
                                <option value="">Select an option</option>
                                @foreach ($EventUserSecurity as $user)
                                <option value="{{ $user->user_id }}">{{ $user->User->lookup_name }}</option>
                                @endforeach
                            </x-select>
                            <x-label-error :messages="$errors->get('also_assign_to')" />
                        </div>
                    </div>
                    @endif
                    @endif

                    @if ($muncul)
                    <x-btn-panel data-tip="Submit" />
                    @endif
                </div>
            </div>

            {{-- Riwayat Perubahan --}}
            <div class="mt-4">
                <x-btn-show wire:click="$set('showHistoryModal', true)">
                    📜 Lihat Riwayat Perubahan
                </x-btn-show>
            </div>
        </div>

        {{-- Modal --}}
        <x-history-modal :show="$showHistoryModal" :logs="$hazardReport->logs" />

    </form>
</div>
