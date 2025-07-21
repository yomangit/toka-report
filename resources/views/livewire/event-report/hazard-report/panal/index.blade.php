<div>
    <form wire:submit.prevent='store'>
        @csrf
        @method('PATCH')
        <div wire:target="store" wire:loading.class="skeleton" class="flex justify-between p-2 my-1 text-xs border rounded-sm border-slate-30">
            <div class="w-full">
                {{-- Bagian Current Step & Status --}}
                <div class="w-full md:max-w-md">
                    <div class="grid gap-2">
                        <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center">
                            <div class="font-mono text-sm font-semibold text-gray-600 sm:w-32">
                                {{ __('Current Step') }}
                            </div>
                            <div class="font-mono text-sm italic font-semibold text-gray-800">
                                {{ $current_step }}
                            </div>
                        </div>
                        <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center">
                            <div class="font-mono text-sm font-semibold text-gray-600 sm:w-32">
                                Status
                            </div>
                            <div class="font-mono text-sm italic font-semibold">
                                <span class="bg-clip-text text-transparent {{ $bg_status }}">
                                    {{ $status }}
                                </span>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Bagian Form Procced & Assign --}}
                <div class="grid grid-cols-1 gap-4 mt-4 md:grid-cols-2">
                    @if ($muncul)
                    {{-- Procced To --}}
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
                    {{-- Assign To --}}
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

                    {{-- Also Assign To --}}
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
                    @endif
                    @endif
                </div>

                {{-- Tombol Submit --}}
                @if ($muncul)
                <div class="mt-4">
                    <x-btn-panel data-tip="submit" />
                </div>
                @endif
            </div>

            <div>
                <x-btn-show wire:click="$set('showHistoryModal', true)">
                    📜 Lihat Riwayat Perubahan
                </x-btn-show>
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
        </div>
    </form>
</div>
