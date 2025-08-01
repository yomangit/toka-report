<script nonce="{{ csp_nonce() }}">
    document.addEventListener('livewire:init', () => {
        Livewire.on('alert', (event) => {
            const data = event
            Toastify({
                text: data[0]['text']
                , duration: data[0]['duration']
                , destination: data[0]['destination']
                , newWindow: data[0]['newWindow']
                , close: data[0]['close']
                , gravity: "top", // `top` or `bottom`
                position: 'right', // `true` or `false`
                style: {
                    background: data[0]['backgroundColor']
                }

            }).showToast();
        });
    });

</script>
