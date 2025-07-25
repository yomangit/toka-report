<div>
    <x-notification />
    <script src="https://cdn.ckeditor.com/ckeditor5/41.4.2/classic/ckeditor.js"></script>
    <style>
        .ck-editor__editable[role="textbox"] {
            /* Editing area */
            /* min-height: 200px; */
            padding-left: 40px;
        }

    </style>
    @section('bradcrumbs')
    {{ Breadcrumbs::render('hazardReportDetail', $data_id) }}
    @endsection
    <div class="font-mono text-sm font-semibold text-transparent divider divider-info bg-clip-text bg-gradient-to-r from-pink-500 to-violet-500">
        {{ $divider }}</div>
    <livewire:event-report.hazard-report.panal.index :id="$data_id">
        <form wire:submit.prevent='store'>
            @csrf
            @method('PATCH')
            <div wire:target="store" wire:loading.class="skeleton" class="left-0 p-2 border rounded-sm border-slate-300">

                <x-btn-save wire:target="store" wire:loading.class="btn-disabled" class="{{ $currentStep === 'Closed' || $currentStep === 'Cancelled' ? 'btn-disabled' : '' }}">
                    {{ __('Save') }}</x-btn-save>
                <x-btn-delete wire:target="store" wire:loading.class="btn-disabled" class="{{ $currentStep === 'Closed' || $currentStep === 'Cancelled' ? 'btn-disabled' : '' }}" wire:click='destroy' wire:confirm.prompt=" Are you sure delete {{ $reference }}?\n\nType DELETE to confirm|DELETE">{{ __('Delete') }}</x-btn-delete>
            </div>
            <div wire:target="store" wire:loading.class="skeleton" class="p-2 my-1 border rounded-sm border-slate-300">
                <div class="grid gap-1 sm:grid-cols-2 lg:grid-cols-3">
                    <div class="w-full max-w-md xl:max-w-xl form-control">
                        <x-label-req :value="__('event_type')" />
                        <x-select wire:model.live='event_type_id' class="{{ $currentStep === 'Closed' || $currentStep === 'Cancelled' ? 'btn-disabled bg-gray-300' : '' }}" :error="$errors->get('event_type_id')">
                            <option value="" selected>Select an option</option>
                            @foreach ($EventType as $event_type)
                            <option value="{{ $event_type->id }}" selected>
                                {{ $event_type->EventCategory->event_category_name }} -
                                {{ $event_type->type_eventreport_name }}</option>
                            @endforeach
                        </x-select>
                        <x-label-error :messages="$errors->get('event_type_id')" />
                    </div>
                    <div class="w-full max-w-md xl:max-w-xl form-control">
                        <x-label-req :value="__('sub_event_type')" />
                        <x-select wire:model.live='sub_event_type_id' class="{{ $currentStep === 'Closed' || $currentStep === 'Cancelled' ? 'btn-disabled bg-gray-300' : '' }}" :error="$errors->get('sub_event_type_id')">
                            <option value="" selected>Select an option</option>
                            @foreach ($EventSubType as $item)
                            <option value="{{ $item->id }}">{{ $item->event_sub_type_name }}</option>
                            @endforeach
                        </x-select>
                        <x-label-error :messages="$errors->get('sub_event_type_id')" />
                    </div>
                    <div class="w-full max-w-md xl:max-w-xl form-control">
                        <x-label-req :value="__('Perusahaan terkait')" />
                        <div class="dropdown dropdown-end">
                            <x-input wire:click='clickWorkgroup' wire:model.live='workgroup_name' wire:keydown.self="changeConditionDivision" :error="$errors->get('workgroup_name')" class="cursor-pointer {{ $currentStep === 'Closed' || $currentStep === 'Cancelled' ? 'btn-disabled bg-gray-300' : '' }}" tabindex="0" role="button" />
                            <div tabindex="0" class="z-10 w-full   overflow-y-auto shadow dropdown-content card card-compact bg-base-200 text-primary-content {{ $hiddenWorkgroup }}">
                                <ul class="h-full px-4 py-4 list-disc list-inside max-h-40 bg-base-200 rounded-box">
                                    @forelse ($Division as $item)
                                    <li wire:click="select_division({{ $item->id }})" class="text-[9px] text-wrap hover:bg-primary subpixel-antialiased text-left cursor-pointer">
                                        {{ $item->DeptByBU->BusinesUnit->Company->name_company }}-{{ $item->DeptByBU->Department->department_name }}
                                        @if (!empty($item->company_id))
                                        -{{ $item->Company->name_company }}
                                        @endif
                                        @if (!empty($item->section_id))
                                        -{{ $item->Section->name }}
                                        @endif
                                    </li>
                                    @empty
                                    <li class='font-semibold text-center text-rose-500'>Division not found!! </li>
                                    @endforelse
                                </ul>
                            </div>
                        </div>
                        <x-label-error :messages="$errors->get('workgroup_name')" />
                    </div>
                    <div class="w-full max-w-md xl:max-w-xl form-control">
                        <x-label-req :value="__('report_by')" />
                        <div class="dropdown dropdown-end">
                            <x-input wire:click='clickReportBy' wire:model.live='report_byName' :error="$errors->get('report_byName')" class="{{ $currentStep === 'Closed' || $currentStep === 'Cancelled' ? 'btn-disabled bg-gray-300' : '' }} cursor-pointer" tabindex="0" role="button" />
                            <div tabindex="0" class="dropdown-content card card-compact  bg-base-300 text-primary-content z-[1] w-full  p-2 shadow {{ $hiddenReportBy }}">
                                <div class="relative">
                                    <div class="h-full mb-2 overflow-auto max-h-40 scroll-smooth focus:scroll-auto" wire:target='report_byName' wire:loading.class='hidden'>
                                        @forelse ($Report_By as $report_by)
                                        <div wire:click="reportedBy({{ $report_by->id }})" class="flex flex-col border-b cursor-pointer border-base-200 ">
                                            <strong class="text-[10px] text-slate-800">{{ $report_by->lookup_name }}</strong>
                                        </div>
                                        @empty
                                        <strong class="text-xs text-transparent bg-clip-text bg-gradient-to-r from-rose-400 to-rose-800">Name
                                            Not Found!!!</strong>
                                        @endforelse
                                    </div>
                                    <div class="hidden pt-5 text-center" wire:target='report_byName' wire:loading.class.remove='hidden'>
                                        <x-loading-spinner />
                                    </div>
                                    <div class="pb-6">{{ $Report_By->links('pagination.minipaginate') }}</div>
                                    <div class="fixed bottom-0 left-0 right-0 px-2 mb-1 bg-base-300 opacity-95 ">
                                        <x-input-no-req wire:model.live='report_by_nolist' placeholder="{{ __('name_notList') }}" />
                                    </div>
                                </div>
                            </div>
                        </div>
                        <x-label-error :messages="$errors->get('report_byName')" />
                    </div>
                    <div class="w-full max-w-md xl:max-w-xl form-control">
                        <x-label-req :value="__('report_to')" />
                        <div class="dropdown dropdown-end">
                            <x-input wire:click='clickReportTo' wire:model.live='report_toName' :error="$errors->get('report_toName')" class="cursor-pointer {{ $currentStep === 'Closed' || $currentStep === 'Cancelled' ? 'btn-disabled bg-gray-300' : '' }}" tabindex="0" role="button" />
                            <div tabindex="0" class="dropdown-content card card-compact  bg-base-300 text-primary-content z-[1] w-full  p-2 shadow {{ $hiddenReportTo }}">
                                <div class="relative">

                                    <div class="h-40 mb-2 overflow-auto scroll-smooth focus:scroll-auto" wire:target='report_toName' wire:loading.class='hidden'>
                                        @forelse ($Report_To as $report_to)
                                        <div wire:click="reportedTo({{ $report_to->id }})" class="flex flex-col border-b cursor-pointer border-base-200 ">
                                            <strong class="text-[10px] text-slate-800">{{ $report_to->lookup_name }}</strong>
                                        </div>
                                        @empty
                                        <strong class="text-xs text-transparent bg-clip-text bg-gradient-to-r from-rose-400 to-rose-800">Name
                                            Not Found!!!</strong>
                                        @endforelse
                                    </div>
                                    <div class="hidden pt-5 text-center" wire:target='report_toName' wire:loading.class.remove='hidden'>
                                        <x-loading-spinner />
                                    </div>
                                    <div class="pb-6">{{ $Report_To->links('pagination.minipaginate') }}</div>
                                    <div class="fixed bottom-0 left-0 right-0 px-2 mb-1 bg-base-300 opacity-95 ">
                                        <x-input-no-req wire:model.live='report_to_nolist' placeholder="{{ __('name_notList') }}" />
                                    </div>
                                </div>
                            </div>
                        </div>
                        <x-label-error :messages="$errors->get('report_toName')" />
                    </div>
                    <div class="w-full max-w-md xl:max-w-xl form-control">
                        <x-label-req :value="__('date of event')" />
                        <x-input-date id="tanggal" wire:model.live='date' class="{{ $currentStep === 'Closed' || $currentStep === 'Cancelled' ? 'btn-disabled bg-gray-300' : '' }}" readonly :error="$errors->get('date')" />
                        <x-label-error :messages="$errors->get('date')" />
                    </div>
                    <div class="w-full max-w-md xl:max-w-xl form-control">
                        <x-label-req :value="__('eventLocation')" />
                        <x-select wire:model.live='location_id' :error="$errors->get('location_id')">
                            <option value="" selected>Select an option</option>
                            @forelse ($Location as $location)
                            <option value="{{ $location->id }}" selected>{{ $location->location_name }}</option>
                            @endforeach
                        </x-select>
                        <x-label-error :messages="$errors->get('location_id')" />
                    </div>
                    <div class="w-full max-w-md xl:max-w-xl form-control {{ $showLocation==true ? 'block' : 'hidden' }}">
                        <x-label-req :value="__('Lokasi Spesifik')" />
                        <x-input wire:model.blur='location_name' :error="$errors->get('location_name')" />
                        <x-label-error :messages="$errors->get('location_name')" />
                    </div>



                    <div class="w-full max-w-md xl:max-w-xl form-control">
                        <x-label-no-req :value="__('documentation')" />
                        <div class="relative">
                            <x-input-file wire:model='file_doc' class="{{ $currentStep === 'Closed' || $currentStep === 'Cancelled' ? 'btn-disabled bg-gray-300' : '' }}" :error="$errors->get('file_doc')" />
                            <div class="absolute inset-y-0 right-0 avatar" wire:target="file_doc" wire:loading.class="hidden">
                                <div class="w-6 rounded  @if ($nameFileDb == $documentation) cursor-pointer @endif " @if ($nameFileDb==$documentation) wire:click="download" @endif>
                                    @include('livewire.event-report.svg-file')
                                </div>
                            </div>
                            <span wire:target="file_doc" wire:loading.class="absolute inset-y-0 right-0 loading loading-spinner text-warning"></span>
                        </div>
                        <x-label-error :messages="$errors->get('documentation')" />
                    </div>
                </div>
                <div>
                    <div wire:ignore class="w-full form-control">
                        <x-label-req :value="__('Hazard Details')" />
                        <textarea id="description">{{ $description_temp }}</textarea>
                    </div>
                    <x-label-error :messages="$errors->get('description')" />
                </div>
                <div>
                    <x-label-req :value="__('immediate corrective action')" />
                    <div class="@error('immediate_corrective_action') border border-rose-500 rounded-sm @enderror">
                        <div wire:ignore wire:ignore class="w-full form-control">
                            <textarea id="immediate_corrective_action">{{ $immediate_corrective_action_temp }}</textarea>
                        </div>
                    </div>
                    <x-label-error :messages="$errors->get('immediate_corrective_action')" />
                </div>
                <div class="grid grid-cols-1 gap-6 mt-4 transition-all duration-300 ease-in-out border divide-y border-base-200 divide-base-200 rounded-xl md:grid-cols-3 md:divide-y-0 md:divide-x md:p-6">
                    <!-- KEYWORD (KTA / TTA) -->
                    <div class="px-4 py-2 space-y-3 md:px-0">
                        <fieldset x-data="{ status: @entangle('key_word') }" class="space-y-3">
                            <x-label-req :value="__('Key Word')" />

                            <div class="flex items-center gap-4 mt-2">
                                <label class="flex items-center space-x-1">
                                    <input x-model="status" value="kta" id="draft" type="radio" name="status" class="radio radio-sm radio-primary" />
                                    <span class="text-xs font-semibold">Kondisi Tidak Aman</span>
                                </label>

                                <label class="flex items-center space-x-1">
                                    <input x-model="status" value="tta" id="published" type="radio" name="status" class="radio radio-sm radio-accent" />
                                    <span class="text-xs font-semibold">Tindakan Tidak Aman</span>
                                </label>
                            </div>
                            <x-label-error :messages="$errors->get('key_word')" />

                            <!-- KTA Select -->
                            <div x-show="status === 'kta'" x-transition.opacity.duration.300ms class="mt-2">
                                <x-select wire:model.live='kondisitidakamen_id' :error="$errors->get('kondisitidakamen_id')">
                                    <option value="" selected>Pilih KTA...</option>
                                    @forelse ($KTA as $kta)
                                    <option value="{{ $kta->id }}">{{ $kta->name }}</option>
                                    @endforeach
                                </x-select>
                                <x-label-error :messages="$errors->get('kondisitidakamen_id')" />
                            </div>

                            <!-- TTA Select -->
                            <div x-show="status === 'tta'" x-transition.opacity.duration.300ms class="mt-2">
                                <x-select wire:model.live='tindakantidakamen_id' :error="$errors->get('tindakantidakamen_id')">
                                    <option value="" selected>Pilih TTA</option>
                                    @forelse ($TTA as $tta)
                                    <option value="{{ $tta->id }}">{{ $tta->name }}</option>
                                    @endforeach
                                </x-select>
                                <x-label-error :messages="$errors->get('tindakantidakamen_id')" />
                            </div>
                        </fieldset>

                    </div>

                    <!-- Divider untuk mobile -->
                    <div class="border-t md:hidden border-base-200"></div>

                    <!-- PERBAIKAN TINGKAT LANJUT -->
                    <div class="px-4 py-2 md:px-6 md:col-span-2">
                        <fieldset class="space-y-2">
                            <x-label-req :value="__('Perbaikan Tingkat Lanjut')" />
                            <div class="flex flex-wrap gap-4 mt-2">
                                <!-- YES -->
                                <label class="flex items-center space-x-1 transition-transform duration-200 ease-in-out transform hover:scale-105">
                                    <input wire:click="$dispatch('modalActionHazardNew')" wire:model.live="tindakkan_selanjutnya" value="1" name="tingkat_lanjut" id="yes_lanjut" class="radio radio-sm radio-error peer/yes_lanjut" type="radio" />
                                    <span class="text-xs font-semibold peer-checked/yes_lanjut:text-error">Yes</span>
                                </label>

                                <!-- NO -->
                                <label class="flex items-center space-x-1 transition-transform duration-200 ease-in-out transform hover:scale-105">
                                    <input wire:model.live="tindakkan_selanjutnya" value="0" name="tingkat_lanjut" id="no_lanjut" class="radio radio-sm radio-success peer/no_lanjut" type="radio" />
                                    <span class="text-xs font-semibold peer-checked/no_lanjut:text-success">No</span>
                                </label>
                            </div>
                            <x-label-error :messages="$errors->get('tindakkan_selanjutnya')" />
                        </fieldset>
                    </div>
                </div>

                <div class="flex flex-col-reverse items-center mt-2 border-2 rounded-sm md:flex-row md:divide-x-2 divide-late-400/25 border-slate-400/25">
                    <div class="flex-auto p-2 divide-y-2 divide-slate-400/25">
                        <div class="flex flex-col sm:items-center sm:flex-row">

                            <div class="w-full px-2">
                                <p class="font-mono text-sm font-semibold text-justify">
                                    {{ $risk_probability_doc }}
                                </p>
                            </div>
                        </div>
                        <div class="flex flex-col sm:items-center sm:flex-row">
                            <div class="flex-none px-2 w-52">
                                <div class="w-full max-w-md xl:max-w-xl form-control">
                                    <x-label-req :value="__('potential consequence')" />
                                    <x-select wire:model.live='risk_consequence_id' class="{{ $currentStep === 'Closed' || $currentStep === 'Cancelled' ? 'btn-disabled bg-gray-300' : '' }}" :error="$errors->get('risk_consequence_id')">
                                        <option value="">Select an option</option>
                                        @foreach ($RiskConsequence as $consequence)
                                        <option value="{{ $consequence->id }}">
                                            {{ $consequence->risk_consequence_name }}
                                        </option>
                                        @endforeach
                                    </x-select>
                                    <x-label-error :messages="$errors->get('risk_consequence_id')" />
                                </div>
                            </div>
                            <div class="w-full px-2">
                                <p class="font-mono text-sm font-semibold text-justify">
                                    {{ $risk_consequence_doc }}
                                </p>
                            </div>
                        </div>
                        <div class="flex flex-col sm:items-center sm:flex-row">
                            <div class="flex-none px-2 w-52">
                                <div class="w-full max-w-md xl:max-w-xl form-control">
                                    <x-label-req :value="__('Potential Likelihood')" />
                                    <x-select wire:model.live='risk_likelihood_id' class="{{ $currentStep === 'Closed' || $currentStep === 'Cancelled' ? 'btn-disabled bg-gray-300' : '' }}" :error="$errors->get('risk_likelihood_id')">
                                        <option value="">Select an option</option>
                                        @foreach ($RiskLikelihood as $likelihood)
                                        <option value="{{ $likelihood->id }}">
                                            {{ $likelihood->risk_likelihoods_name }}
                                        </option>
                                        @endforeach
                                    </x-select>
                                    <x-label-error :messages="$errors->get('risk_likelihood_id')" />
                                </div>
                            </div>
                            <div class="px-2 ">
                                <p class="font-mono text-sm font-semibold text-justify">{{ $risk_likelihood_notes }}
                                </p>
                            </div>
                        </div>
                    </div>
                    <div class="flex-none md:w-72 ">
                        <div class="m-1 overflow-x-auto ">
                            <table class="w-full text-xs border border-black table-auto bg-base-300 md:table-fixed">
                                <caption class="mb-2 text-sm font-bold caption-top">Table Initial Risk Assessment</caption>

                                <thead>
                                    <tr>
                                        <th colspan="2" class="p-1 text-center bg-gray-200 border border-black">Legend</th>
                                        @foreach ($RiskAssessments as $risk_assessment)
                                        <th class="p-1 text-xs text-center rotate_text border border-black {{ $risk_assessment->colour }}">
                                            {{ $risk_assessment->risk_assessments_name }}
                                        </th>
                                        @endforeach
                                    </tr>
                                    <tr>
                                        <th class="text-center bg-gray-100 border border-black">Likelihood</th>
                                        @foreach ($RiskConsequence as $risk_consequence)
                                        <th class="text-center bg-gray-100 border border-black rotate_text">
                                            {{ $risk_consequence->risk_consequence_name }}
                                        </th>
                                        @endforeach
                                    </tr>
                                </thead>

                                <tbody>
                                    @foreach ($RiskLikelihood as $risk_likelihood)
                                    <tr>
                                        <th class=" p-0 text-[10px] font-semibold border-2 border-black">
                                            {{ $risk_likelihood->risk_likelihoods_name }}
                                        </th>
                                        @foreach ($risk_likelihood->RiskConsequence()->get() as $risk_consequence)
                                        <th class=" p-0 text-xs font-semibold text-center border-2 border-black {{ $currentStep === 'Closed' || $currentStep === 'Cancelled' ? ' opacity-35 bg-gray-500' : '' }}">
                                            <label @if ($currentStep==='Closed' || $currentStep==='Cancelled' ) @else wire:click="riskId({{ $risk_likelihood->id }}, {{ $risk_consequence->id }},{{ $TableRisk->where('risk_likelihood_id', $risk_likelihood->id)->where('risk_consequence_id', $risk_consequence->id)->first()->risk_assessment_id }})" @endif class="btn p-0 mt-1 btn-block btn-xs {{ $currentStep === 'Closed' || $currentStep === 'Cancelled' ? 'cursor-not-allowed' : '' }}  @if (
                                                            $tablerisk_id ==
                                                                $TableRisk->where('risk_likelihood_id', $risk_likelihood->id)->where('risk_consequence_id', $risk_consequence->id)->first()->id) border-4 border-neutral @endif {{ $TableRisk->where('risk_likelihood_id', $risk_likelihood->id)->where('risk_consequence_id', $risk_consequence->id)->first()->RiskAssessment->colour }}">
                                            </label>
                                        </th>
                                        @endforeach
                                    </tr>
                                    @endforeach


                                </tbody>
                            </table>

                        </div>
                    </div>
                </div>
                <table class="table table-xs">
                    @foreach ($RiskAssessment as $item)
                    <tr>
                        <th class="w-40 text-xs border-2 border-slate-400">Potential Risk Rating</th>
                        <td class="pl-2 text-xs border-2 border-slate-400">
                            {{ $item->RiskAssessment->risk_assessments_name }}</td>
                    </tr>
                    <tr>
                        <th class="w-40 text-xs border-2 border-slate-400">Notify</th>
                        <td class="pl-2 text-xs border-2 border-slate-400">
                            {{ $item->RiskAssessment->reporting_obligation }}</td>
                    </tr>
                    <tr>
                        <th class="w-40 text-xs border-2 border-slate-400">Deadline</th>
                        <td class="pl-2 text-xs border-2 border-slate-400">{{ $item->RiskAssessment->notes }}</td>
                    </tr>
                    <tr>
                        <th class="w-40 text-xs border-2 border-slate-400">Coordinator</th>
                        <td class="pl-2 text-xs border-2 border-slate-400">
                            {{ $item->RiskAssessment->coordinator }}
                        </td>
                    </tr>
                    @endforeach

                </table>


                <div class="flex flex-col w-full mt-4 border-opacity-50">
                    <div role="tablist" class="mb-4 tabs tabs-lifted">
                        <input type="radio" name="my_tabs_1" class="font-semibold tab z-1 font-signika text-sky-500" aria-label="Final Documentation" checked="checked" />
                        <div role="tabpanel" class="p-6 tab-content bg-base-100 border-base-300 rounded-box">
                            <div class="mx-4 my-2">
                                <x-btn-add data-tip="Add" class="{{ $currentStep === 'Closed' || $currentStep === 'Cancelled' ? 'btn-disabled ' : '' }}" wire:click="$dispatch('openModal', { component: 'event-report.hazard-report.documentation.create', arguments: { doc: {{ $data_id }} }})" />
                                <livewire:event-report.hazard-report.documentation.index :id="$data_id">
                            </div>
                        </div>
                    </div>
                    <div role="tablist" class="mb-4 tabs tabs-lifted">
                        <input type="radio" name="my_tabs_2" class="font-semibold tab z-1 font-signika text-sky-500" aria-label="Additional Action" checked="checked" />
                        <div role="tabpanel" class="p-6 tab-content bg-base-100 border-base-300 rounded-box">
                            <div class="mx-4 my-2">
                                <livewire:event-report.hazard-report.action.index :id="$data_id">
                            </div>
                        </div>
                    </div>
                    <div role="tablist" class="mb-4 tabs tabs-lifted">
                        <input type="radio" name="my_tabs_3" class="font-semibold tab z-1 font-signika text-sky-500" aria-label="Event Keyword" checked="checked" />
                        <div role="tabpanel" class="p-6 tab-content bg-base-100 border-base-300 rounded-box">
                            <div class="mx-4 my-2">
                                <livewire:event-report.event-keyword.index :data="$data_id">
                            </div>
                        </div>
                    </div>
                    <div>
                        <div wire:ignore class="w-full form-control">
                            <x-label-no-req :value="__('moderator comment')" />
                            <textarea id="comment">{{ $comment_temp }}</textarea>
                        </div>
                        <x-label-error :messages="$errors->get('comment')" />
                    </div>
                </div>
            </div>
        </form>
        <livewire:event-report.hazard-report.action.create>
            <script nonce="{{ csp_nonce() }}" type="module">
                ClassicEditor
                    .create(document.querySelector('#immediate_corrective_action'), {
                        toolbar: ['undo', 'redo', 'bold', 'italic', 'numberedList', 'bulletedList', 'link'],
                        placeholder: 'Type the content here!'
                    })
                    .then(newEditor1 => {
                        newEditor1.editing.view.change((writer) => {
                            writer.setStyle(
                                "height",
                                "155px",
                                newEditor1.editing.view.document.getRoot()
                            );
                        });
                        setInterval(() => Livewire.dispatch('ubahData'), 1000);
                        document.addEventListener('livewire:init', () => {
                            Livewire.on('berhasilUpdate', (event) => {
                                const a = event[0];
                                if (a === "Closed" || a === "Cancelled") {
                                    newEditor1.enableReadOnlyMode('immediate_corrective_action');
                                } else {
                                    newEditor1.disableReadOnlyMode('immediate_corrective_action');
                                }
                            });
                        });
                        newEditor1.model.document.on('change:data', () => {
                            @this.set('immediate_corrective_action', newEditor1.getData())
                        });
                    })
                    .catch(error => {
                        console.error(error);
                    });
                ClassicEditor
                    .create(document.querySelector('#description'), {
                        toolbar: ['undo', 'redo', 'bold', 'italic', 'numberedList', 'bulletedList', 'link'],
                        placeholder: 'Type the content here!'
                    })
                    .then(newEditor2 => {
                        newEditor2.editing.view.change((writer) => {
                            writer.setStyle(
                                "height",
                                "155px",
                                newEditor2.editing.view.document.getRoot()
                            );
                        });
                        document.addEventListener('livewire:init', () => {
                            Livewire.on('berhasilUpdate', (event) => {
                                const a = event[0];
                                if (a === "Closed" || a === "Cancelled") {
                                    newEditor2.enableReadOnlyMode('description');
                                } else {
                                    newEditor2.disableReadOnlyMode('description');
                                }
                            });
                        });
                        newEditor2.model.document.on('change:data', () => {
                            @this.set('description', newEditor2.getData())
                        });
                    })
                    .catch(error => {
                        console.error(error);
                    });
                ClassicEditor
                    .create(document.querySelector('#suggested_corrective_action'), {
                        toolbar: ['undo', 'redo', 'bold', 'italic', 'numberedList', 'bulletedList', 'link'],
                        placeholder: 'Type the content here!'
                    })
                    .then(newEditor3 => {
                        newEditor3.editing.view.change((writer) => {
                            writer.setStyle(
                                "height",
                                "155px",
                                newEditor3.editing.view.document.getRoot()
                            );
                        });
                        document.addEventListener('livewire:init', () => {
                            Livewire.on('berhasilUpdate', (event) => {
                                const a = event[0];
                                if (a === "Closed" || a === "Cancelled") {
                                    newEditor3.enableReadOnlyMode('suggested_corrective_action');
                                } else {
                                    newEditor3.disableReadOnlyMode('suggested_corrective_action');
                                }
                            });
                        });
                        newEditor3.model.document.on('change:data', () => {
                            @this.set('suggested_corrective_action', newEditor3.getData())
                        });
                    })
                    .catch(error => {
                        console.error(error);
                    });
                ClassicEditor
                    .create(document.querySelector('#corrective_action_suggested'), {
                        toolbar: ['undo', 'redo', 'bold', 'italic', 'numberedList', 'bulletedList', 'link'],
                        placeholder: 'Type the content here!'
                    })
                    .then(newEditor4 => {
                        newEditor4.editing.view.change((writer) => {
                            writer.setStyle(
                                "height",
                                "155px",
                                newEditor4.editing.view.document.getRoot()
                            );
                        });
                        document.addEventListener('livewire:init', () => {
                            Livewire.on('berhasilUpdate', (event) => {
                                const a = event[0];
                                if (a === "Closed" || a === "Cancelled") {
                                    newEditor4.enableReadOnlyMode('corrective_action_suggested');
                                } else {
                                    newEditor4.disableReadOnlyMode('corrective_action_suggested');
                                }

                            });
                        });
                        newEditor4.model.document.on('change:data', () => {
                            @this.set('corrective_action_suggested', newEditor4.getData())
                        });
                    })
                    .catch(error => {
                        console.error(error);
                    });
                ClassicEditor
                    .create(document.querySelector('#comment'), {
                        toolbar: ['undo', 'redo', 'bold', 'italic', 'numberedList', 'bulletedList', 'link'],
                        placeholder: 'Type the content here!'
                    })
                    .then(newEditor5 => {
                        newEditor5.editing.view.change((writer) => {
                            writer.setStyle(
                                "height",
                                "155px",
                                newEditor5.editing.view.document.getRoot()
                            );
                        });
                        document.addEventListener('livewire:init', () => {
                            Livewire.on('berhasilUpdate', (event) => {
                                const a = event[0];
                                if (a === "Closed" || a === "Cancelled") {
                                    newEditor5.enableReadOnlyMode('comment');
                                } else {
                                    newEditor5.disableReadOnlyMode('comment');
                                }
                            });
                        });
                        newEditor5.model.document.on('change:data', () => {
                            @this.set('comment', newEditor5.getData())
                        });
                    })
                    .catch(error => {
                        console.error(error);
                    });
            </script>

</div>
