<div>
    @if (auth()->user()->role_user_permit_id == 1 || auth()->user()->can_view_own_division)
    <div id="divisionChart" data-labels='@json($divisionLabels)' data-counts='@json($divisionCounts)' data-colors='@json($divisionColors)'></div>
    @endif

     <livewire:dashboard.causes-analysis.grapf>
</div>
