<div>
    <x-notification />
    <x-btn-add data-tip="Add data" wire:click="$dispatch('openModal', { component: 'admin.dept-by-b-u.create-and-update' })" />
    <div class="overflow-x-auto">
        <table class="table table-zebra table-xs">
            <!-- head -->
            <thead>
                <tr class="text-center">
                    <th>#</th>
                    <th class="font-extrabold">Busines Unit</th>
                    <th class="font-extrabold">Department</th>
                </tr>
            </thead>
            <tbody>
                <!-- row 1 -->
                @forelse ($BusinesUnit as $no => $bu)
                <tr>
                    <th class="text-center">{{ $BusinesUnit->firstItem() + $no }}</th>
                    <td class="font-bold text-center">{{ $bu->Company->name_company }}</td>
                    <td class="w-96 ">

                        @forelse ($bu->Department()->get() as $dept)
                        <div class="grid items-center grid-cols-2 hover:bg-slate-300">

                            <div class="m-1 font-semibold ">
                                {{ $dept->department_name }}
                            </div>
                            <div class="m-1">
                                <x-icon-btn-edit data-tip="Edit" wire:click="$dispatch('openModal', { component: 'admin.dept-by-b-u.create-and-update', arguments: { bu: {{ $bu->id }} , dept:{{ $dept->id }} }})" />
                                <x-icon-btn-delete wire:click="delete({{ $bu->id }}, {{ $dept->id }})" wire:confirm.prompt="Are you sure delete  {{ $dept->department_name }}?\n\nType DELETE to confirm|DELETE" data-tip="Delete" />
                            </div>
                        </div>
                        @empty
                        <div class="font-semibold text-center text-transparent bg-clip-text bg-gradient-to-r from-pink-500 to-violet-500">
                            No Subcontractors</div>
                        @endforelse
                    </td>

                </tr>
                @empty
                <tr class="text-center">
                    <th colspan="4" class="text-error">data not found!!! </th>
                </tr>
                @endforelse
            </tbody>
        </table>
        <div>{{ $BusinesUnit->links() }}</div>
    </div>
</div>
