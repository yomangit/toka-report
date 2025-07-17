@props(['value', 'name','placeholder'])

<input {{ $attributes->class([
            'input input-bordered input-xs font-sans font-semibold placeholder:italic placeholder:text-slate-400   w-full border border-slate-300 pl-6 pr-3 shadow-sm focus:outline-none focus:border-sky-500 focus:ring-sky-500 focus:ring-1 sm:text-xs',
            
        ]) }} type="text" name="search" @isset($name) name="{{ $name }}" @endif @isset($placeholder) placeholder="{{ $placeholder }}" @endif type="text" @isset($value) value="{{ $value }}" @endif {{ $attributes }} autocomplete="off" />
