@props([
    'label',
    'required' => false,
])

<label {{ $attributes->merge(['class' => 'text-xs capitalize']) }}>
    {{ $label }}
    @if ($required)
        <span class="font-bold text-red-500">*</span>
    @endif
</label>
