<div>
    <div>
        <x-notification />

        @section('bradcrumbs')
        {{ Breadcrumbs::render('hazardReportform') }}
        @endsection

        @if ($show)
        <x-btn-admin-template wire:click="$dispatch('openModal', { component: 'admin.chose-event-type.create'})">
            Chose Event Category
        </x-btn-admin-template>
        @endif

        <div class="text-sm font-extrabold text-transparent divider divider-info bg-clip-text bg-gradient-to-r from-pink-500 to-violet-500">
            Form Hazard Report
        </div>

        <form wire:target="store" wire:loading.class="skeleton" wire:submit.prevent='store' enctype="multipart/form-data" class="space-y-6">
            @csrf
            <div class="grid grid-cols-1 gap-2 sm:grid-cols-2 lg:grid-cols-3">
                <!-- contoh 1 kolom -->
                <div class="w-full max-w-md xl:max-w-xl form-control">
                    <x-label-req :value="__('tipe bahaya')" />
                    <x-select wire:model.live='event_type_id' :error="$errors->get('event_type_id')">
                        <option value="">Select an option</option>
                        @foreach ($EventType as $event_type)
                        <option value="{{ $event_type->id }}">
                            {{ $event_type->EventCategory->event_category_name }} - {{ $event_type->type_eventreport_name }}
                        </option>
                        @endforeach
                    </x-select>
                    <x-label-error :messages="$errors->get('event_type_id')" />
                </div>

                <div class="w-full max-w-md xl:max-w-xl form-control">
                    <x-label-req :value="__('jenis bahaya')" />
                    <x-select wire:model.live='sub_event_type_id' :error="$errors->get('sub_event_type_id')">
                        <option value="" selected>Select an option</option>
                        @foreach ($EventSubType as $item)
                        <option value="{{ $item->id }}">{{ $item->event_sub_type_name }}</option>
                        @endforeach
                    </x-select>
                    <x-label-error :messages="$errors->get('sub_event_type_id')" />
                </div>
                <div class="w-full max-w-md xl:max-w-xl form-control">
                    <fieldset class="fieldset ">
                        <x-label-req :value="__('Dilaporkan Oleh')" />
                        <div class="relative">
                            <!-- Input Search -->
                            <input name="searchPelapor" type="text" wire:model.live.debounce.300ms="searchPelapor" placeholder="Cari Nama Pelapor..." class="input input-bordered w-full max-w-sm focus:ring-1 focus:border-info focus:ring-info focus:outline-hidden input-xs {{ $errors->has('pelapor_id') ? 'ring-1 ring-rose-500 focus:ring-rose-500 focus:border-rose-500' : '' }}" x-ref="searchInput" />
                            <!-- Dropdown hasil search (teleport keluar collapse) -->
                            @if ($showPelaporDropdown)
                            <template wire:ignore x-teleport="body">
                                <ul x-data x-init="
                                    // Posisikan dropdown tepat di bawah input
                                    $el.style.position = 'absolute';
                                    const rect = $refs.searchInput.getBoundingClientRect();
                                    $el.style.top = rect.bottom + 'px';
                                    $el.style.left = rect.left + 'px';
                                    $el.style.width = rect.width + 'px';
                                    $el.style.zIndex = 9999;
                                " class="mt-1 overflow-auto border rounded-md shadow bg-base-100 max-h-60">
                                    <div wire:loading wire:target="selectPelapor" class="p-2 text-center">
                                        <span class="loading loading-spinner loading-sm text-secondary"></span>
                                    </div>

                                    @if (count($pelapors) > 0)
                                    @foreach ($pelapors as $pelapor)
                                    <li wire:click="selectPelapor({{ $pelapor->id }}, '{{ $pelapor->name }}')" class="px-3 py-2 cursor-pointer hover:bg-base-200">
                                        {{ $pelapor->name }}
                                    </li>
                                    @endforeach
                                    @else
                                    @if (!$manualPelaporMode)
                                    <li wire:click="enableManualPelapor" class="px-3 py-2 cursor-pointer text-warning hover:bg-base-200">
                                        Tidak ditemukan, tambah pelapor manual
                                    </li>
                                    @endif
                                    @endif

                                    @if ($manualPelaporMode)
                                    <li class="p-2">
                                        <div class="relative w-full">
                                            <input name="manualPelaporName" type="text" wire:model.live="manualPelaporName" placeholder="Masukkan nama pelapor..." class="input input-bordered w-full pr-20 focus:ring-1 focus:border-info focus:ring-info focus:outline-hidden input-xs {{ $errors->has('manualPelaporName') ? 'ring-1 ring-rose-500 focus:ring-rose-500 focus:border-rose-500' : '' }}" />
                                            <div class="!absolute top-1/2 -translate-y-1/2 right-0 z-20">
                                                <flux:button size="xs" wire:click="addPelaporManual" icon="plus" variant="primary">
                                                    Tambah
                                                </flux:button>
                                            </div>
                                        </div>
                                    </li>
                                    @endif
                                </ul>
                            </template>
                            @endif
                        </div>

                        <!-- Error Message -->
                        @if ($manualPelaporMode)
                        <x-label-error :messages="$errors->get('manualPelaporName')" />
                        @else
                        <x-label-error :messages="$errors->get('pelapor_id')" />
                        @endif
                    </fieldset>
                </div>
               
                <div class="w-full max-w-md xl:max-w-xl form-control">
                    <x-label-req :value="__('Perusahaan terkait')" />
                    <div class="dropdown dropdown-end">
                        <div class="relative">
                            <x-input wire:click='clickWorkgroup' wire:model.live='workgroup_name' wire:keydown.self="changeConditionDivision" placeholder='cari divisi...' :error="$errors->get('workgroup_name')" class="cursor-pointer" tabindex="0" role="button" />
                            <span wire:loading wire:loading.class.remove="hidden" wire:target="select_division" class="absolute right-0 hidden -translate-y-1/2 top-1/2 loading loading-spinner text-secondary">
                            </span>
                        </div>
                        <div tabindex="0" class="z-10 w-full   overflow-y-auto shadow dropdown-content card card-compact bg-base-200 text-primary-content {{ $hiddenWorkgroup }}">
                            <ul class="h-full px-4 py-4 list-disc list-inside max-h-40 bg-base-200 rounded-box">
                                @forelse ($Division as $item)
                                <li wire:click="select_division({{ $item->id }})" class="text-xs subpixel-antialiased text-left cursor-pointer text-wrap hover:bg-primary">
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
                    <x-label-no-req :value="__('report_to')" />
                    <div class="dropdown dropdown-end">
                        <div class="relative">
                            <x-input wire:click='clickReportTo' wire:model.live='report_toName' placeholder="{{ __('report_to') }}" :error="$errors->get('report_toName')" class="cursor-pointer" tabindex="0" role="button" />
                            <span wire:loading wire:loading.class.remove="hidden" wire:target="reportedTo" class="absolute right-0 hidden -translate-y-1/2 top-1/2 loading loading-spinner text-secondary">
                            </span>
                        </div>
                        <div tabindex="0" class="dropdown-content card card-compact  bg-base-300 text-primary-content z-[1] w-full  p-2 shadow {{ $hiddenReportTo }}">
                            <div class="relative">
                                <div class="h-full pb-6 mb-4 overflow-auto max-h-40 scroll-smooth focus:scroll-auto" wire:target='report_toName' wire:loading.class='hidden'>
                                    @forelse ($Report_To as $report_to)
                                    <div wire:click="reportedTo({{ $report_to->users->id }})" class="flex flex-col border-b cursor-pointer hover:bg-primary border-base-200 ">
                                        <strong class="text-xs text-slate-800">{{ $report_to->users->lookup_name }}</strong>
                                    </div>
                                    @empty
                                    <strong class="text-xs text-transparent bg-clip-text bg-gradient-to-r from-rose-400 to-rose-800">Name
                                        Not Found!!!</strong>
                                    @endforelse
                                </div>
                                <div class="hidden pt-5 text-center" wire:target='report_toName' wire:loading.class.remove='hidden'>
                                    <x-loading-spinner />
                                </div>

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
                    <x-input id="tanggal" wire:model.live='date' readonly :error="$errors->get('date')" />
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
            </div>

            <!-- Textarea description -->

            <div>
                <x-label-req :value="__('Hazard Details')" />
                <div class="@error('description') border border-rose-500 rounded-sm @enderror">
                    <div wire:ignore class="w-full form-control">
                        <textarea id="description" class="w-full"></textarea>
                    </div>
                </div>
                <x-label-error :messages="$errors->get('description')" />
            </div>

            <!-- Upload dokumen -->
            <div wire:ignore class="w-full max-w-md xl:max-w-xl form-control">
                <x-label-no-req :value="__('documentation')" />
                <div class="relative">
                    <x-input-file wire:model.live='documentation' :error="$errors->get('documentation')" />
                    <div class="absolute right-0 transform -translate-y-1/2 top-1/2" wire:target="documentation" wire:loading.class="hidden">
                        @include('livewire.event-report.svg-file')
                        {{ $documentation }}
                    </div>
                    <span wire:target="documentation" wire:loading.class="absolute right-0 transform -translate-y-1/2 top-1/2 loading loading-spinner text-secondary"></span>
                </div>
                <x-label-error :messages="$errors->get('documentation')" />
            </div>
            <div>
                <x-label-req :value="__('immediate corrective action')" />
                <div class="@error('immediate_corrective_action') border border-rose-500 rounded-sm @enderror">
                    <div wire:ignore wire:ignore class="w-full form-control">
                        <textarea id="immediate_corrective_action"></textarea>
                    </div>
                </div>
                <x-label-error :messages="$errors->get('immediate_corrective_action')" />
            </div>
            <div class="grid grid-cols-1 gap-6 mt-4 transition-all duration-300 ease-in-out border divide-y border-base-200 divide-base-200 rounded-xl md:grid-cols-3 md:divide-y-0 md:divide-x md:p-6">
                <!-- KEYWORD (KTA / TTA) -->
                <div class="px-4 py-2 space-y-3 md:px-0">
                    <fieldset class="space-y-3">
                        <x-label-req :value="__('Key Word')" />

                        <div class="flex items-center gap-4 mt-2">
                            <label class="flex items-center space-x-1">
                                <input wire:model.live="key_word" value="kta" type="radio" name="key_word" class="radio radio-sm radio-primary" />
                                <span class="text-xs font-semibold">Kondisi Tidak Aman</span>
                            </label>

                            <label class="flex items-center space-x-1">
                                <input wire:model.live="key_word" value="tta" type="radio" name="key_word" class="radio radio-sm radio-accent" />
                                <span class="text-xs font-semibold">Tindakan Tidak Aman</span>
                            </label>
                        </div>
                        <x-label-error :messages="$errors->get('key_word')" />

                        <!-- KTA Select -->
                        <div x-data x-show="$wire.key_word === 'kta'" x-transition.opacity.duration.300ms class="mt-2">
                            <x-select wire:model.live='kondisitidakamen_id' :error="$errors->get('kondisitidakamen_id')">
                                <option value="" selected>Pilih KTA...</option>
                                @foreach ($KTA as $kta)
                                <option value="{{ $kta->id }}">{{ $kta->name }}</option>
                                @endforeach
                            </x-select>
                            <x-label-error :messages="$errors->get('kondisitidakamen_id')" />
                        </div>

                        <!-- TTA Select -->
                        <div x-data x-show="$wire.key_word === 'tta'" x-transition.opacity.duration.300ms class="mt-2">
                            <x-select wire:model.live='tindakantidakamen_id' :error="$errors->get('tindakantidakamen_id')">
                                <option value="" selected>Pilih TTA</option>
                                @foreach ($TTA as $tta)
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

            <!-- Tombol Simpan -->
            <div class="flex justify-end">
                <x-btn-save-active wire:target="documentation,division_id" wire:loading.class="btn-disabled">
                    {{ __('Submit') }}
                </x-btn-save-active>
            </div>
        </form>

        <livewire:event-report.hazard-report-guest.action :token="$token" :tgl="$date" />

        <!-- Flatpickr dan CKEditor -->
        <script src="https://cdn.ckeditor.com/ckeditor5/41.4.2/classic/ckeditor.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
        <script>
            ClassicEditor.create(document.querySelector('#description'), {
                toolbar: ['undo', 'redo', 'bold', 'italic', 'numberedList', 'bulletedList', 'link']
            }).then(editor => {
                editor.model.document.on('change:data', () => {
                    @this.set('description', editor.getData());
                });
            });
            ClassicEditor.create(document.querySelector('#immediate_corrective_action'), {
                toolbar: ['undo', 'redo', 'bold', 'italic', 'numberedList', 'bulletedList', 'link']
            }).then(editor => {
                editor.model.document.on('change:data', () => {
                    @this.set('immediate_corrective_action', editor.getData());
                });
            });

        </script>
    </div>
</div>
