<div>
    <form wire:submit.prevent='store'>
        @csrf
        @method('PATCH')
        <div wire:target="store" wire:loading.class="skeleton" class="flex flex-col w-full text-sm border lg:flex-row">
            {{-- Kolom Kiri --}}
            <div class="flex-1 p-4 space-y-1 border-b lg:border-b-0 lg:border-r">
                {{-- Current Step --}}
                <div class="flex items-start gap-x-2">
                    <div class="w-32 font-semibold">Current Step</div>
                    <div>:</div>
                    <div>{{ $current_step }}</div>
                </div>
                {{-- Status --}}
                <div class="flex items-start gap-x-2">
                    <div class="w-32 font-semibold md:w-auto">Status</div>
                    <div>:</div>
                    <div>{{ $status }}</div>
                </div>
                {{-- Proceed + Assign To --}}
                <div class="flex flex-col pt-2 gap-y-2 lg:flex-row lg:items-center lg:gap-x-4">
                    {{-- Proceed To --}}
                    <div class="flex items-center gap-x-2">
                        <div class="w-32 font-semibold md:w-auto">Proceed To</div>
                        <div>:</div>
                        <div>
                            @if ($muncul)
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
                            @endif
                        </div>
                    </div>

                    {{-- Assign To --}}
                    @if ($show)
                    <div class="flex items-center gap-x-2">
                        <div class="font-semibold">Assign To</div>
                        <div>:</div>
                        <div>
                            <x-select wire:model.live='assign_to' :error="$errors->get('assign_to')" class="w-full">
                                <option value="">Select an option</option>
                                @foreach ($EventUserSecurity as $user)
                                <option value="{{ $user->user_id }}">{{ $user->User->lookup_name }}</option>
                                @endforeach
                            </x-select>
                            <x-label-error :messages="$errors->get('assign_to')" />
                        </div>
                    </div>

                    {{-- Also Assign To --}}
                    <div class="flex items-center gap-x-2">
                        <div class="font-semibold">Also Assign To</div>
                        <div>:</div>
                        <div>
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
                </div>
            </div>
            {{-- Kolom Kanan --}}
            <div class="flex items-center justify-center p-4 text-center lg:w-64">
                <x-btn-show wire:click="$set('showHistoryModal', true)">
                    📜 View History
                </x-btn-show>
            </div>
        </div>
    </form>
    <x-modal wire:model="showHistoryModal" maxWidth="2xl">
        <x-slot name="title">
            📜 Riwayat Perubahan
        </x-slot>
        <div class="max-h-[70vh] overflow-y-scroll scroll-smooth pr-2">
            @forelse ($hazardReport->logs as $log)
            <div class="p-4 mb-3 transition duration-200 border rounded-md shadow-sm bg-base-100 hover:bg-base-200">
                <div class="flex flex-col sm:flex-row sm:justify-between">
                    <div class="text-sm text-gray-700">
                        <strong>{{ $log->user->name }}</strong> melakukan
                        <span class="italic text-blue-600">{{ $log->action }}</span>
                    </div>
                    <div class="mt-1 text-xs text-gray-500 sm:mt-0">
                        {{ $log->created_at->format('d M Y H:i') }}
                    </div>
                </div>
                @if ($log->old_values || $log->new_values)
                <div class="mt-2 text-sm">
                    <div class="text-red-500">
                        <strong>Dari:</strong> {{ $log->old_values['status'] ?? '-' }}
                    </div>
                    <div class="mt-1 text-green-600">
                        <strong>Menjadi:</strong> {{ $log->new_values['status'] ?? '-' }}
                    </div>
                </div>
                @endif
            </div>
            @empty
            <div class="text-sm italic text-gray-500">Belum ada perubahan yang tercatat.</div>
            @endforelse
        </div>
        <x-slot name="footer">
            <x-btn-close wire:click="$set('showHistoryModal', false)">
                Tutup
            </x-btn-close>
        </x-slot>
    </x-modal>
</div>
