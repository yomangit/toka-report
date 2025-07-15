@props(['value', 'name', 'error', 'disable', 'step'])

<select 
    @isset($step) disabled @endif
    {{ $attributes->merge([
        'class' => 
            'select select-bordered select-xs w-full pr-8 px-3 bg-transparent border shadow-sm border-slate-300
             placeholder-slate-400 focus:outline-none focus:border-sky-500 focus:ring-sky-500 block sm:text-xs font-semibold focus:ring-1'
            . ($error ? ' border-rose-500 ring-1 ring-rose-500 outline-none' : '')
    ]) }}
    data-tom-select
>
    {{ $slot }}
</select>
