<div>
    <table class="table table-zebra table-xs">
        <!-- head -->
        <thead>
            <tr class="text-center">
                <th>Reference</th>
                <th>Tanggal</th>
                <th>Dilaporkan Oleh</th>
                <th>Tipe Bahaya</th>
                <th>Jenis Bahaya</th>
                <th>Divisi yang melapor</th>
                <th>Workgroup</th>
                <th>Divisi Penanggung Jawab</th>
                <th>Penanggung Jawab Area</th>
                <th>Lokasi / Lokasi Spesifik</th>
                <th>Rincian Bahaya</th>
                <th>Tindakan Perbaikan Langsung</th>
                <th>KTA/TTA</th>
                <th>Perbaikkan Tingkat Lanjut</th>
                <th>Action [Total/Open] </th>
                <th>Status</th>
                <th>Closed By</th>

            </tr>
        </thead>
        <tbody>
            <!-- row 1 -->
            @foreach ($HazardReport as $no => $hr)
            <tr class="text-center">

                <td>{{ $hr->reference }}</td>
                <td>{{ DateTime::createFromFormat('Y-m-d : H:i', $hr->date)->format('Y-m-d : H:i') }}</td>
                <td> {{ $hr->report_byName }}</td>
                <td>{{$hr->eventType->type_eventreport_name}}</td>
                <td>{{ $hr->subEventType->event_sub_type_name }}</td>
                <td> {{ $hr->reportBy?->department_name ?? '-' }}</td>
                <td> {{ $hr->workgroup_name }}</td>
                <td> {{ $hr->division?->Company?->name_company ?? $hr->division?->DeptByBU?->Department?->department_name ?? '' }}</td>
                <td> {{ $hr->report_toName }}</td>
                <td> {{ $hr->eventLocation->location_name }}/ {{ $hr->location_name }}</td>
                <td>{!! $hr->description? "$hr->description":'-' !!}</td>
                <td>{!! $hr->immediate_corrective_action? "$hr->immediate_corrective_action":'-' !!}</td>
                <td>
                    @if($hr->kondisiTidakAman)
                    KTA = {{ $hr->kondisiTidakAman->name }}
                    @elseif($hr->tindakanTidakAman)
                    TTA = {{ $hr->tindakanTidakAman->name }}
                    @else
                    -
                    @endif
                </td>
                {{-- isi dari table action --}}
                <td>
                    @if($hr->actions->count())
                    @foreach($hr->actions as $action)
                    Follow Up: {{ $action->followup_action ?? '-' }}&#10;
                    Kondisi: {{ $action->action_condition ?? '-' }}&#10;
                    Tanggung Jawab: {{ $action->users->lookup_name ?? '-' }}&#10;
                    Due: {{ $action->due_date ?? '-' }}&#10;
                    Completion: {{ $action->completion_date ?? '-' }}&#10;&#10;
                    @endforeach
                    @else
                    -
                    @endif

                </td>
                <td>
                    {{ $ActionHazard->where('hazard_id', $hr->id)->count('due_date') }} / 
                        {{ $ActionHazard->where('hazard_id', $hr->id)->whereNull('completion_date')->count('completion_date') }}
                </td>
                <td>
                    @if ($hr->WorkflowDetails->Status->status_name ==='Closed')
                    Closed
                    @elseif($hr->WorkflowDetails->Status->status_name ==='Cancelled')
                    Cancelled
                    @else
                    Open
                    @endif
                </td>
                <td>{{ $hr->closed_by? "$hr->closed_by":'-' }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
