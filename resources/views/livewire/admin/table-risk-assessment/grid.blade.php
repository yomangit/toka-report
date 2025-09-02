<div>
    <div class="overflow-x-auto w-80">
        <table class="table table-xs">
            <thead>
                <tr class="text-center text-[9px]">
                    <th class="border-1">Likelihooc ↓ / Consequence →</th>
                    @foreach ($consequences as $c)
                    <th class="rotate_text border-1">{{ $c->risk_consequence_name }}</th>
                    @endforeach
                </tr>
            </thead>
            <tbody>
                @foreach ($likelihoods as $l)
                <tr class="text-xs text-center">

                    <td class="w-1 font-bold border-1">{{ $l->risk_likelihoods_name }}</td>
                    @foreach ($consequences as $c)
                    @php
                    $cell = App\Models\RiskMatrixCell::where('likelihood_id', $l->id)->where('risk_consequence_id', $c->id)->first() ?? null;
                    $score = $l->level * $c->level;
                    $severity = $cell?->severity ?? '';
                    $color = match($severity) {
                    'Low' => 'bg-emerald-500',
                    'Moderate' => 'bg-sky-500',
                    'High' => 'bg-orange-300',
                    'Extreme' => 'bg-rose-500',
                    default => 'bg-gray-100',
                    };
                    @endphp
                    <td wire:click="edit({{ $l->id }}, {{ $c->id }})" class="border w-1 cursor-pointer {{ $color }}">
                        <div class="text-[6px] ">{{ strtoupper( $severity[0] ?? '') }}
                        </div>
                    </td>
                    @endforeach
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <div x-data="{ open: @entangle('showModal') }">
        <div x-show="open" class="fixed inset-0 z-50 flex items-center justify-center bg-black/50">
            <div class="p-4 bg-white rounded-lg shadow-lg w-xs sm:w-sm">
                <form wire:submit.prevent="updateMatrix">
                    @csrf
                    <fieldset class="p-4 border fieldset bg-base-200 border-base-300 rounded-box">
                        {{-- Severity --}}
                        <x-label-req>{{ __('Severity') }} </x-label-req>
                        <select wire:model="severity" class="w-full select select-bordered select-xs">
                            <option value="">Choose Status...</option>
                            <option value="Low">Low</option>
                            <option value="Moderate">Moderate</option>
                            <option value="High">High</option>
                            <option value="Extreme">Extreme</option>
                        </select>
                        <x-label-error :messages="$errors->get('severity')" />

                        {{-- Description --}}
                        <x-label-req>{{ __('Description') }} </x-label-req>
                        <x-text-area wire:model="description" type="text" :error="$errors->get('description')" placeholder="Description" />

                        {{-- Action --}}
                        <x-label-req>{{ __('Action') }} </x-label-req>
                        <x-text-area wire:model="action" :error="$errors->get('action')" type="text" placeholder="Action" />
                    </fieldset>

                    <div class="flex justify-end gap-2 mt-4">
                        <button type="submit" class="btn btn-xs btn-primary">Save</button>
                        <button type="button" @click="open = false" class="btn btn-xs btn-error">Close</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

</div>
