<div>
    <form wire:submit.prevent='store'>
        @csrf
        @method('PATCH')
        <div wire:target="store" wire:loading.class="skeleton" class="flex justify-between p-2 my-1 text-xs border rounded-sm border-slate-30">
            <div>
                <div class=" w-72">
                    <div class="flex justify-between gap-x-1">
                        <div class="flex-none font-mono font-semibold w-28 ">
                            {{ __('Current Step') }}
                        </div>
                        <div class="font-mono italic font-semibold grow">
                            {{ $current_step }}
                        </div>
                    </div>
                    <div class="flex justify-between gap-x-1">
                        <div class="flex-none font-mono font-semibold w-28 ">
                            Status
                        </div>
                        <div class="grow">
                            <span class="bg-clip-text font-semibold italic text-transparent {{ $bg_status }}">{{ $status }}</span>
                        </div>
                    </div>
                </div>
                <div class="flex flex-col gap-2 md:flex-row">
                    @if ($muncul)
                    <div class="flex items-center gap-x-8">
                        <div class="flex-none font-mono font-semibold">
                            Procced To
                        </div>
                        <div class="grow gap-x-2">
                            <div class="w-auto">
                                <x-select wire:model.live='procced_to' :error="$errors->get('procced_to')">
                                    <option value="">select an option</option>
                                    @forelse ($Workflow as $value)
                                    <option value="{{ $value->destination_1 }}">{{ $value->destination_1_label }}
                                    </option>
                                    @if ($value->destination_2)
                                    <option value="{{ $value->destination_2 }}">
                                        {{ $value->destination_2_label }}
                                    </option>
                                    @elseif($value->is_cancel_step === 'Cancel')
                                    <option value="{{ $value->is_cancel_step }}">{{ $value->is_cancel_step }}
                                    </option>
                                    @endif
                                    @empty
                                    @endforelse
                                </x-select>
                                <x-label-error :messages="$errors->get('procced_to')" />
                            </div>
                        </div>
                    </div>

                    @if ($show)
                    <div class="flex items-center gap-x-4">
                        <div class="flex-none font-mono font-semibold">
                            Assign To
                        </div>
                        <div class="grow gap-x-2">
                            <div class="w-full max-w-xs">
                                <x-select wire:model.live='assign_to' :error="$errors->get('assign_to')">
                                    <option value="" selected>Select an option</option>
                                    @foreach ($EventUserSecurity as $user)
                                    <option value="{{ $user->user_id }}">{{ $user->User->lookup_name }}
                                    </option>
                                    @endforeach

                                </x-select>
                                <x-label-error :messages="$errors->get('assign_to')" />
                            </div>
                        </div>
                    </div>
                    <div class="flex items-center gap-x-4">
                        <div class="flex-none font-mono font-semibold">
                            Also Assign To
                        </div>
                        <div class="grow gap-x-2">
                            <div class="w-full max-w-xs">
                                <x-select wire:model.live='also_assign_to' :error="$errors->get('also_assign_to')">
                                    <option value="" selected>Select an option</option>
                                    @foreach ($EventUserSecurity as $user)
                                    <option value="{{ $user->user_id }}">{{ $user->User->lookup_name }}
                                    </option>
                                    @endforeach
                                </x-select>
                                <x-label-error :messages="$errors->get('also_assign_to')" />
                            </div>
                        </div>
                    </div>
                    @endif
                    @endif
                    <div class="flex items-center ">
                        @if ($muncul)
                        <x-btn-panel data-tip="submit" />
                        @endif
                    </div>
                </div>
            </div>
            <div>
                <div class="mt-8">
                    <h3 class="mb-4 text-lg font-bold text-gray-800">📜 Riwayat Perubahan</h3>

                    @forelse  ($hazardReport->logs as $log)
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
                                <strong>Dari:</strong> {!! nl2br(e($log->old_values ?? '-')) !!}
                                
                            </div>
                            <div class="mt-1 text-green-600">
                                <strong>Menjadi:</strong> {!! nl2br(e($log->new_values ?? '-')) !!}
                            </div>
                        </div>
                        @endif
                    </div>
                    @empty
                    <div class="text-sm italic text-gray-500">Belum ada perubahan yang tercatat.</div>
                    @endforelse
                </div>

            </div>
        </div>
    </form>
</div>
