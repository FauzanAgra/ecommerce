<script>
    $(document).ready(function() {
        var url = '<?= base_url('checkout') ?>';

        //Load Checkout
        loadCheckout();

        $(document).on('click', '.remove-cart', function() {
            var data = {
                'checkout-id': $(this).attr("id"),
                'action': 'delete'
            };

            $.ajax({
                url: url,
                data: data,
                type: 'post',
                dataType: 'json',
                cache: 'false',
                beforeSend: function() {

                },
                success: function(data) {
                    if (parseInt(data.stat) === 1) {
                        Swal.fire({
                            position: 'center',
                            icon: 'error',
                            title: data.mesg,
                            showConfirmButton: false,
                            timer: 1000
                        });
                        loadCheckout();
                    }
                },
                error: function(xhr, status, err) {

                }
            });
        });

        $(document).on('click', '.btnPayment', function(e) {
            e.preventDefault();
            var next = false;
            var paidBank = $('#pembayaran').val();
            var courier = $('#pengiriman').val();
            if (paidBank) {
                var data = {
                    'address': $('#nama').val() + '-' + $('#nomor').val() + '-' + $('#alamat').val(),
                    'paid-type': 1, //Status 1 : Bank
                    'paid-bank': parseInt(paidBank),
                    'courier': parseInt(courier),
                    'action': 'insert-trans'
                };
                next = true;
            }

            if (next) {
                $.ajax({
                    url: '<?= base_url('checkout') ?>',
                    data: data,
                    type: 'post',
                    dataType: 'json',
                    cache: 'false',
                    beforeSend: function() {

                    },
                    success: function(data) {
                        if (parseInt(data.stat) === 1) {
                            Swal.fire({
                                position: 'center',
                                icon: 'success',
                                title: data.mesg,
                                showConfirmButton: false,
                                timer: 1000
                            });
                            loadCheckout();
                        }
                    },
                    error: function(xhr, status, err) {

                    }
                });
            }

        });

        function loadCheckout() {
            $('#checkout').html(' ');
            var data = {
                'action': 'load'
            };
            $.ajax({
                url: url,
                data: data,
                type: 'post',
                dataType: 'json',
                cache: 'false',
                beforeSend: function() {

                },
                success: function(data) {
                    if (parseInt(data.stat) === 1) {
                        $('#checkout').append(data.data);
                    }
                },
                error: function(xhr, status, err) {

                }
            });
        };


    });
</script>