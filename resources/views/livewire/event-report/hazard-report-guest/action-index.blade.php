<div>
    <div class="flex justify-start gap-2 mb-2 md:justify-between">
        <x-btn-add data-tip="Add" wire:click="$dispatch('modalActionHazardNew')" />
        <div class="w-full max-w-xs">
            {{ $token }}
            <x-inputsearch wire:model.live='search' />
        </div>
    </div>
    @forelse ($DocHazPelapor as $no => $index)
    @php $data = json_decode($index->new_data, true); @endphp
     <div class="flex flex-col items-center justify-between p-2 mb-2 border-b-2 sm:flex-row border-base-300">
            <div class="w-full basis-1/5">
                <div class="space-x-4 text-gray-500">
                    <span class="font-mono text-[10px] font-semibold">Action No.</span>
                    <span class="text-[10px] font-semibold">{{ $DocHazPelapor->firstItem() + $no }} </span>
                </div>
            </div>
            <div class="w-full p-4 basis-3/4">
                <div class="flex flex-col items-stretch divide-y divide-gray-400 ">

                    
                    <div class="grid flex-row grid-cols-4 gap-1 text-gray-500 ">
                        <span class="font-mono text-[10px] font-semibold ">Followup Action</span>
                        <span
                            class="font-mono text-[10px] text-justify font-semibold col-span-3">{{ $data['followup_action'] }}</span>
                    </div>
                    <div class="grid flex-row grid-cols-4 gap-1 text-gray-500 ">
                        <span class="font-mono text-[10px] font-semibold ">Actionee Comments</span>
                        <span class="font-mono text-[10px] text-justify font-semibold col-span-3">
                            {{  $data['actionee_comment'] }}</span>
                    </div>
                    <div class="grid flex-row grid-cols-4 gap-1 text-gray-500 ">
                        <span class="font-mono text-[10px] font-semibold ">Action Conditions</span>
                        <span class="font-mono text-[10px] font-semibold col-span-3">
                            {{  $data['action_condition'] }}</span>
                    </div>
                </div>
            </div>
            <div class="w-full basis-2/3">
                <div class="flex flex-col items-stretch divide-y divide-gray-400 ">
                    <div class="flex-row">
                        <div class="grid grid-cols-3 text-gray-500">
                            <span class="font-mono text-[10px] font-semibold ">{{ __('Orginal Due Date') }}</span>
                            <span class="font-mono text-[10px] font-semibold "> {{ $tgl }}</span>
                        </div>
                    </div>
                    <div class="flex-row">
                        <div class="grid grid-cols-3 text-gray-500">
                            <span class="font-mono text-[10px] font-semibold ">{{ __('Responsibility') }}</span>
                            <span class="font-mono text-[10px] col-span-2 font-semibold ">
                                {{ App\Models\User::whereId($data['responsibility'])->first()->lookup_name }}</span>
                        </div>
                    </div>
                    <div class="flex-row">
                        <div class="grid grid-cols-3 text-gray-500">
                            <span class="font-mono text-[10px] font-semibold ">Due Date</span>
                            <span class="font-mono text-[10px] col-span-2 font-semibold ">
                                {{  $data['due_date'] }}</span>
                        </div>
                    </div>
                    <div class="flex-row">
                        <div class="grid grid-cols-3 text-gray-500">
                            <span class="font-mono text-[10px] font-semibold ">{{ __('Completion Date') }}</span>
                            <span class="font-mono text-[10px] col-span-2 font-semibold ">
                                {{  $data['completion_date'] }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @empty
    <div class="text-xs font-semibold text-center text-rose-500">there is no action</div>
    @endforelse

</div>
