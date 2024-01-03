<!-- Bootstrap core JavaScript-->
<script src="<?= base_url('assets/');?>vendor/jquery/jquery.min.js"></script>
    <script src="<?= base_url('assets/');?>vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

    <!-- Core plugin JavaScript-->
    <script src="<?= base_url('assets/');?>vendor/jquery-easing/jquery.easing.min.js"></script>

    <!-- Custom scripts for all pages-->
    <script src="<?= base_url('assets/');?>js/sb-admin-2.min.js"></script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>   

    <script>
    function updateStatus() {
        $.ajax({
            type: 'GET',
            url: '<?php echo base_url('user/getStatus'); ?>',
            dataType: 'json',
            success: function(response){
                // Ubah status pada elemen HTML tanpa reload halaman
                $('#statusCell').text(response.status);

                // Ubah warna dan properti lain jika perlu
                $('#statusCell').removeClass().addClass('status-cell ' + response.class);

                // Lakukan polling kembali setelah beberapa detik
                setTimeout(updateStatus, 5000); // misalnya setiap 5 detik
            },
            error: function(xhr, status, error){
                console.error(xhr.responseText);
            }
        });
    }

    $(document).ready(function(){
        // Mulai polling saat halaman dimuat
        updateStatus();
    });
</script>

</body>

</html>