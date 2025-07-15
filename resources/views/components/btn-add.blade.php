<label
    {{ $attributes->merge([
        'class' => 'btn btn-xs btn-success btn-square btn-outline tooltip tooltip-right tooltip-success',
        'data-tip' => 'Tambah',
    ]) }}>
    
    <!-- Ikon SVG -->
    <svg xmlns="http://www.w3.org/2000/svg"
        viewBox="0 0 32 32"
        class="w-5 h-5 mx-auto my-auto">
        <style>
            .pictogram_zes { fill: #0C6667; }
            .pictogram_vijf { fill: #01A59C; }
            .pictogram_twee { fill: #F8AD89; }
            .pictogram_een { fill: #F4D6B0; }
        </style>
        <g>
            <circle class="pictogram_een" cx="16" cy="16" r="16" />
            <path class="pictogram_twee" d="M4,16c0,6.627,5.373,12,12,12V4C9.373,4,4,9.373,4,16z" />
            <path class="pictogram_vijf" d="M16,0v4c6.627,0,12,5.373,12,12s-5.373,12-12,12v4c8.837,0,16-7.163,16-16S24.837,0,16,0z" />
            <path class="pictogram_zes" d="M16,28v4C7.163,32,0,24.837,0,16S7.163,0,16,0v4C9.373,4,4,9.373,4,16c0,6.627,5.373,12,12,12z
                M22,14h-4.5V9.5C17.5,8.672,16.828,8,16,8s-1.5,0.672-1.5,1.5V14H10c-0.828,0-1.5,0.672-1.5,1.5S9.172,17,10,17h4.5v4.5
                c0,0.828,0.672,1.5,1.5,1.5s1.5-0.672,1.5-1.5V17H22c0.828,0,1.5-0.672,1.5-1.5S22.828,14,22,14z" />
        </g>
    </svg>
</label>
