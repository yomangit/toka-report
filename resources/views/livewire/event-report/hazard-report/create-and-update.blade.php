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
                    <x-label-req :value="__('kategori bahaya')" />
                    <x-select wire:model.live='sub_event_type_id' :error="$errors->get('sub_event_type_id')">
                        <option value="" selected>Select an option</option>
                        @foreach ($EventSubType as $item)
                        <option value="{{ $item->id }}">{{ $item->event_sub_type_name }}</option>
                        @endforeach
                    </x-select>
                    <x-label-error :messages="$errors->get('sub_event_type_id')" />
                </div>
                <div class="w-full max-w-md xl:max-w-xl form-control">
                    <x-label-req :value="__('analisis penyebab')" />
                    <div class="flex items-center gap-4 p-1 pl-3 border rounded border-base-300">
                        <label class="flex items-center space-x-1">
                            <input wire:model.live="key_word" value="kta" type="radio" name="key_word" class="radio radio-xs radio-primary" />
                            <span class="text-xs font-semibold">Kondisi Tidak Aman</span>
                        </label>

                        <label class="flex items-center space-x-1">
                            <input wire:model.live="key_word" value="tta" type="radio" name="key_word" class="radio radio-xs radio-accent" />
                            <span class="text-xs font-semibold">Tindakan Tidak Aman</span>
                        </label>
                    </div>
                    <x-label-error :messages="$errors->get('key_word')" />
                </div>
                @if ($key_word)
                <div class="w-full max-w-md xl:max-w-xl form-control">
                    <div x-data x-show="$wire.key_word === 'kta'">
                        <x-label-req :value="__('kategori bahaya')" />
                        <x-select wire:model.live='kondisitidakamen_id' :error="$errors->get('kondisitidakamen_id')">
                            <option value="" selected>Pilih KTA...</option>
                            @foreach ($KTA as $kta)
                            <option value="{{ $kta->id }}">{{ $kta->name }}</option>
                            @endforeach
                        </x-select>
                        <x-label-error :messages="$errors->get('kondisitidakamen_id')" />
                    </div>

                    <!-- TTA Select -->
                    <div x-data x-show="$wire.key_word === 'tta'">
                        <x-label-req :value="__('kategori bahaya')" />
                        <x-select wire:model.live='tindakantidakamen_id' :error="$errors->get('tindakantidakamen_id')">
                            <option value="" selected>Pilih TTA</option>
                            @foreach ($TTA as $tta)
                            <option value="{{ $tta->id }}">{{ $tta->name }}</option>
                            @endforeach
                        </x-select>
                        <x-label-error :messages="$errors->get('tindakantidakamen_id')" />
                    </div>
                </div>
                @endif
                <div class="w-full max-w-md xl:max-w-xl form-control">
                    <fieldset class="fieldset ">
                        <x-label-req :value="__('Dilaporkan Oleh')" />
                        <div class="relative">
                            <!-- Input Search -->
                            <x-input wire:model.live='searchPelapor' wire:keydown.self="changeConditionDivision" placeholder='cari divisi...' :error="$errors->get('pelapor_id')" class="cursor-pointer" />
                            <!-- Dropdown hasil search (teleport keluar collapse) -->
                            @if ($showPelaporDropdown)
                            <ul class="absolute z-10 w-full mt-1 overflow-auto border rounded-md shadow bg-base-100 max-h-60">
                                <div class="text-center " >
                                    <span class="hidden loading loading-spinner loading-sm text-secondary" wire:loading.class.remove="hidden" wire:loading wire:target="selectPelapor"></span>
                                </div>
                                @if (count($pelapors) > 0)
                                @foreach ($pelapors as $pelapor)
                                <li wire:click="selectPelapor({{ $pelapor->id }}, '{{ $pelapor->lookup_name }}')" class="px-3 py-2 text-xs cursor-pointer hover:bg-base-200" wire:loading.class="hidden" wire:loading wire:target="selectPelapor">
                                    {{ $pelapor->lookup_name }}

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
                    <fieldset class="fieldset ">
                        <x-label-req :value="__('eventLocation')" />
                        <div class="relative">
                            <!-- Input Search -->
                            <x-input wire:model.live='searchLocation' placeholder='Pilih Lokasi...' :error="$errors->get('location_id')" class="cursor-pointer" />
                            <!-- Dropdown hasil search -->
                            @if($showLocationDropdown && count($locations) > 0)
                            <ul class="absolute z-10 w-full mt-1 overflow-auto border rounded-md shadow bg-base-100 max-h-60">
                                <!-- Spinner ketika klik -->
                                <div class="text-center" >
                                    <span class="hidden loading loading-spinner loading-sm text-secondary" wire:loading.class.remove="hidden" wire:loading wire:target="selectLocation"></span>
                                </div>
                                @foreach($locations as $loc)
                                <li wire:click="selectLocation({{ $loc->id }}, '{{ $loc->location_name }}')" class="px-3 py-2 cursor-pointer hover:bg-base-200" wire:loading.class="hidden" wire:loading wire:target="selectLocation">
                                    {{ $loc->location_name }}

                                </li>
                                @endforeach
                            </ul>
                            @endif
                        </div>
                        <x-label-error :messages="$errors->get('location_id')" />
                    </fieldset>
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
            <div class="mx-auto mb-4" x-data x-show="$wire.tindakkan_selanjutnya === '1'">
                <fieldset class="p-3 border border-gray-200 shadow-md fieldset card bg-base-100">
                    <legend class="text-sm font-semibold card-title "> Tindakan Lanjutan</legend>
                    <div class="card-body ">

                        <!-- Deskripsi Tindakan -->
                        <fieldset class="fieldset md:col-span-1">
                            <x-form.label label="Deskripsi Tindakan" required />

                            <div wire:ignore>
                                <textarea id="ckeditor-action_description" class="w-full h-20 textarea textarea-bordered"></textarea>
                            </div>
                            <input name="action_description" type="hidden" wire:model.live="action_description" id="action_description">
                            <x-label-error :messages="$errors->get('action_description')" />
                        </fieldset>
                        <div class="grid items-end grid-cols-1 gap-4 md:grid-cols-3">
                            <!-- Tanggal & Waktu -->
                            <fieldset class="fieldset md:col-span-1">
                                <x-form.label label="Batas Waktu Penyelesaian" required />
                                <div class="relative" wire:ignore x-data="{
                                    fp: null,
                                    initFlatpickr() {
                                        if (this.fp) this.fp.destroy();
                                        this.fp = flatpickr(this.$refs.tanggalInput2, {
                                            disableMobile: true,
                                            enableTime: false,
                                            dateFormat: 'd-m-Y',
                                            onChange: (dates, str) => $wire.set('action_due_date', str),
                                        });
                                    }
                                }" x-init="initFlatpickr();
                                Livewire.hook('message.processed', () => initFlatpickr());" x-ref="wrapper">
                                    <input name="action_due_date" type="text" x-ref="tanggalInput2" wire:model.live="action_due_date" placeholder="Pilih Tanggal" class="input input-bordered w-full focus:ring-1 focus:border-info focus:ring-info focus:outline-hidden input-xs {{ $errors->has('action_due_date') ? 'ring-1 ring-rose-500 focus:ring-rose-500 focus:border-rose-500' : '' }}" readonly />
                                </div>
                                <x-label-error :messages="$errors->get('action_due_date')" />
                            </fieldset>
                            <fieldset class="fieldset md:col-span-1">
                                <x-form.label label="Tanggal Penyelesaian Tindakan" required />
                                <div class="relative" wire:ignore x-data="{
                                    fp: null,
                                    initFlatpickr() {
                                        if (this.fp) this.fp.destroy();
                                        this.fp = flatpickr(this.$refs.tanggalInput3, {
                                            disableMobile: true,
                                            enableTime: false,
                                            dateFormat: 'd-m-Y',
                                            onChange: (dates, str) => $wire.set('actual_close_date', str),
                                        });
                                    }
                                }" x-init="initFlatpickr();
                                Livewire.hook('message.processed', () => initFlatpickr());" x-ref="wrapper">
                                    <input name="actual_close_date" type="text" x-ref="tanggalInput3" wire:model.live="actual_close_date" placeholder="Pilih Tanggal" class="input input-bordered w-full focus:ring-1 focus:border-info focus:ring-info focus:outline-hidden input-xs {{ $errors->has('actual_close_date') ? 'ring-1 ring-rose-500 focus:ring-rose-500 focus:border-rose-500' : '' }}" readonly />
                                </div>
                                <x-label-error :messages="$errors->get('actual_close_date')" />
                            </fieldset>
                            <!-- Dilaporkan Oleh -->
                            <fieldset class="relative fieldset md:col-span-1">
                                <x-form.label label="Penanggung Jawab Area" required />
                                <div class="relative">
                                    <input name="searchActResponsibility" type="text" wire:model.live.debounce.300ms="searchActResponsibility" placeholder="Cari Nama Pelapor..." class="input input-bordered w-full focus:ring-1 focus:border-info focus:ring-info focus:outline-hidden input-xs {{ $errors->has('pelapor_id') ? 'ring-1 ring-rose-500 focus:ring-rose-500 focus:border-rose-500' : '' }}" />

                                    <!-- Dropdown hasil search -->
                                    @if ($showActPelaporDropdown)
                                    <ul class="absolute z-10 w-full mt-1 overflow-auto border rounded-md shadow bg-base-100 max-h-60">
                                        <div wire:loading wire:target="selectPelapor" class="p-2 text-center">
                                            <span class="loading loading-spinner loading-sm text-secondary"></span>
                                        </div>
                                        @if (count($pelaporsAct) > 0)
                                        @foreach ($pelaporsAct as $pelapor)
                                        <li wire:click="selectActPelapor({{ $pelapor->id }}, '{{ $pelapor->lookup_name }}')" class="px-3 py-2 cursor-pointer hover:bg-base-200">
                                            {{ $pelapor->lookup_name }}
                                        </li>
                                        @endforeach
                                        @else
                                        @if (!$manualActPelaporMode)
                                        <li wire:click="enableManualActPelapor" class="px-3 py-2 cursor-pointer text-warning hover:bg-base-200">
                                            Tidak ditemukan, tambah pelapor manual
                                        </li>
                                        @endif
                                        @endif

                                        @if ($manualActPelaporMode)
                                        <li class="p-2">
                                            <div class="relative w-full">
                                                <input name="manualActPelaporName" type="text" wire:model.live="manualActPelaporName" placeholder="Masukkan nama..." class="input input-bordered w-full focus:ring-1 focus:border-info focus:ring-info focus:outline-hidden input-xs {{ $errors->has('manualPelaporName') ? 'ring-1 ring-rose-500 focus:ring-rose-500 focus:border-rose-500' : '' }}" />
                                                <div class="absolute right-0 -translate-y-1/2 top-1/2">
                                                    <flux:button size="xs" wire:click="addActPelaporManual" icon="plus" variant="primary">
                                                        Tambah
                                                    </flux:button>
                                                </div>
                                            </div>
                                        </li>
                                        @endif
                                    </ul>
                                    @endif
                                </div>
                                @if ($manualPelaporMode)
                                <x-label-error :messages="$errors->get('manualPelaporName')" />
                                @else
                                <x-label-error :messages="$errors->get('action_responsible_id')" />
                                @endif
                            </fieldset>
                        </div>

                        <!-- Tombol Tambah -->
                        <div class="flex justify-end ">
                            <label for="" class="btn btn-accent btn-xs" wire:click="addAction">Tambah</label>
                        </div>
                        <!-- List Actions -->
                        <div class="my-2 divider">Daftar Tindakan</div>
                        <ul class="space-y-2">
                            @forelse($actions as $index => $act)
                            <li class="flex flex-col justify-between p-3 border rounded md:flex-row md:items-center bg-base-50">
                                <div class="mb-2 md:mb-0">
                                    <p><strong>{!! $act['description'] !!}</strong></p>
                                    <p class="text-sm text-gray-500">
                                        Batas Waktu Penyelesaian: {{ $act['due_date'] }} |
                                        Tanggal Penyelesaian Tindakan: {{ $act['actual_close_date'] }} |
                                        PIC: {{ optional(\App\Models\User::find($act['responsible_id']))->name }}
                                    </p>
                                </div>
                                <label type="button" wire:click="removeAction({{ $index }})" class="self-start btn btn-error btn-xs md:self-center">Hapus</label>
                            </li>
                            @empty
                            <li class="text-sm text-gray-500">Belum ada tindakan lanjutan ditambahkan.</li>
                            @endforelse
                        </ul>
                    </div>
                </fieldset>
            </div>
            <!-- Tombol Simpan -->
            <div class="flex justify-end">
                <x-btn-save-active wire:target="documentation,division_id" wire:loading.class="btn-disabled">
                    {{ __('Submit') }}
                </x-btn-save-active>
            </div>
        </form>





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
        <script>
            let ckAction_description = null;
            document.addEventListener('livewire:navigated', () => {
                ClassicEditor
                    .create(document.querySelector('#ckeditor-action_description'), {
                        toolbar: [
                            , 'bold', 'italic', 'bulletedList', 'numberedList', '|'
                            , 'undo', 'redo'
                        ]
                        , removePlugins: ['ImageUpload', 'EasyImage', 'MediaEmbed'] // buang plugin gambar
                    })
                    .then(editor => {
                        ckAction_description = editor;
                        editor.model.document.on('change:data', () => {
                            const data = editor.getData();
                            document.querySelector('#ckeditor-action_description').value = data;
                            @this.set('action_description', data);
                            if (data.trim() !== '') {
                                editor.ui.view.editable.element.classList.remove('error');
                            }
                        });
                    })
                    .catch(error => {
                        console.error(error);
                    });
            });
            Livewire.on('validateCkEditorAddAction', event => {
                if (ckAction_description) {
                    const data = ckAction_description.getData().trim();
                    if (data === '') {
                        ckAction_description.ui.view.editable.element.classList.add('error');
                        return false; // cegah submit
                    }
                }
                return true;
            });

        </script>
    </div>
</div>
