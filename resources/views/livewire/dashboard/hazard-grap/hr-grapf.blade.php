<div>
    @if (auth()->user()->role_user_permit_id == 1 || auth()->user()->can_view_own_division)
    <div
    id="divisionChart"
    data-labels='@json($divisionLabels)'
    data-counts='@json($divisionCounts)'
    data-colors='@json($divisionColors)'
    class="w-full h-64"
></div>
    @endif
</div>
