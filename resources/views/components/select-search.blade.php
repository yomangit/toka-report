{{-- @props(['value', 'name']) --}}
{{-- <select
    {{ $attributes->class([
        'select select-bordered select-xs w-full max-w-xs  px-3 bg-transparant border shadow-sm border-slate-300 placeholder-slate-400
                                    focus:outline-none focus:border-sky-500 focus:ring-sky-500 block w-full  text-xs font-semibold focus:ring-1  ',
    ]) }}>
    {{ $slot }}

</select> --}}
@props([
    'id' => Str::uuid(), // kalau tidak dikasih id, auto generate
])

<div>
    <select {{ $attributes->merge(['class' => 'form-select w-full']) }} id="{{ $id }}">
        {{ $slot }}
    </select>
</div>

@push('scripts')
<script nonce="{{ csp_nonce() }}">
    document.addEventListener('DOMContentLoaded', function () {
        if (!document.getElementById(@json($id)).tomselect) {
            new TomSelect('#{{ $id }}', {
                create: false,
                placeholder: 'Cari...',
                sortField: {
                    field: "text",
                    direction: "asc"
                }
            });
        }
    });

    Livewire.hook('message.processed', (message, component) => {
        const select = document.getElementById(@json($id));
        if (select && !select.tomselect) {
            new TomSelect(select, {
                create: false,
                placeholder: 'Cari...',
                sortField: {
                    field: "text",
                    direction: "asc"
                }
            });
        }
    });
</script>
@endpush
