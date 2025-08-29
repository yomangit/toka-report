<div>
    <table class="table table-zebra table-xs">
        <!-- head -->
        <thead>
            <tr class="text-center">
                <th>Tanggal</th>
                <th>Reference</th>
                <th>Tipe Bahaya</th>
                <th>Jenis Bahaya</th>
                <th>Dilaporkan Oleh</th>
                <th>Divisi yang melapor</th>
                <th>Divisi Penanggung Jawab</th>
                <th>Penanggung Jawab Area</th>
                <th>Lokasi / Lokasi Spesifik</th>
                <th>Rincian Bahaya</th>
                <th>Tindakan Perbaikan Langsung</th>
                <th>KTA/TTA</th>
                <th>Perbaikkan Tingkat Lanjut</th>
                <th>Status</th>
                <th>Closed By</th>

            </tr>
        </thead>
        <tbody>
            <!-- row 1 -->
            @foreach ($HazardReport as $no => $hr)
            <tr class="text-center">

                <td>{{ DateTime::createFromFormat('Y-m-d : H:i', $hr->date)->format('d-m-Y') }}</td>
                <td>{{ $hr->reference }}</td>
                <td>{{$hr->eventType->type_eventreport_name}}</td>
                <td>{{ $hr->subEventType->event_sub_type_name }}</td>
                <td> {{ $hr->report_byName }}</td>
                <td> {{ $hr->reportBy->department_name }}</td>
                <td> {{ $hr->workgroup_name }}</td>
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
                       
                            Follow Up: {{ $action->followup_action ?? '-' }} <br>
                            Kondisi: {{ $action->action_condition ?? '-' }} <br>
                            Tanggung Jawab: {{ $action->users->lookup_name ?? '-' }} <br>
                            Due: {{ $action->due_date ?? '-' }} <br>
                            Completion: {{ $action->completion_date ?? '-' }}
                        
                        @endforeach
                    @else
                    -
                    @endif
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
