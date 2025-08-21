<div>
    <li class="hover:bg-base-200" wire:click="clickLogout">
       <span class="w-full">Logout</span>
    </li>
</div>
<script>
    document.addEventListener('livewire:initialized', () => {
        Livewire.on('logged-out', () => {
            window.location.href = "{{ route('login') }}";
        });
    });
</script>