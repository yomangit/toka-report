<div>
    <dialog id="modal_action_hazard" class="modal">
        <div class="modal-box">
            <h3 class="mb-4 text-lg font-bold">Tambah Tindakan Bahaya</h3>

            <!-- Isi form tindakan bahaya -->
            <input type="text" class="w-full mb-4 input input-bordered" placeholder="Deskripsi tindakan...">

            <div class="modal-action">
                <form method="dialog">
                    <button class="btn">Tutup</button>
                </form>
            </div>
        </div>
    </dialog>
   
    <script>
        window.addEventListener('show-modal-action-hazard', () => {
            const modal = document.getElementById('modal_action_hazard');
            if (modal) modal.showModal();
        });
    </script>
</div>
