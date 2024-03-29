<script>
    $(document).ready(function() {
        var url = '<?= base_url('dashboard_user') ?>';
        var id_trans = 0;
        var trans_number = '';

        $(".custom-file-input").on("change", function() {
            var fileName = $(this).val().split("\\").pop();
            $(this).siblings(".custom-file-label").addClass("selected").html(fileName);
        });

        loadTrans();

        $('.btnProfile').on('click', function() {
            var formData = new FormData();
            formData.append('id-user', $('#id-user').val());
            formData.append('name-full-user', $('#full-name-user').val());
            formData.append('name-user', $('#name-user').val());
            formData.append('phone-user', $('#phone-user').val());
            formData.append('address-user', $('#address-user').val());
            if ($('#image-user')[0].files[0]) {
                formData.append('image-user', $('#image-user')[0].files[0]);
            } else {
                var image = $('#image-profile')[0].src;
                var result = image.split('/');
                var dataImg = result[8];
                formData.append('image-user', dataImg);
            }
            formData.append('action', 'update-user');

            $.ajax({
                url: url,
                data: formData,
                type: 'post',
                dataType: 'json',
                cache: 'false',
                contentType: false,
                processData: false,
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
                        window.location.href = '<?= base_url('dashboard_user') ?>';
                    }
                },
                error: function(xhr, status, err) {

                }
            });
        });

        $('.btnChange').on('click', function(e) {
            e.preventDefault();

            var data = {
                'id-user': $('#id-user').val(),
                'current-password': $('#current-password').val(),
                'new-password': $('#new-password').val(),
                'new-password-repeat': $('#new-password-repeat').val(),
                'action': 'change-password'
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
                            icon: 'success',
                            title: data.mesg,
                            showConfirmButton: false,
                            timer: 1000
                        });
                        $('#form-password').trigger('reset');
                    } else {
                        Swal.fire({
                            position: 'center',
                            icon: 'warning',
                            title: data.mesg,
                            showConfirmButton: false,
                            timer: 1000
                        });
                    }
                },
                error: function(xhr, status, err) {

                }
            });
        });

        $(document).on('click', '#btnUpload', function(e) {
            e.preventDefault();
            $('#modalUpload').modal('show');
            id_trans = $(this).data('id');
            trans_number = $(this).data('trans');
        });

        $(document).on('click', '#btnSend', function(e) {
            e.preventDefault();
            var next = false;
            var image = $('#customFile')[0].files[0];

            if (image) {
                var formUpload = new FormData();
                formUpload.append('id_trans', id_trans);
                formUpload.append('trans_number', trans_number);
                formUpload.append('upload', $('#customFile')[0].files[0]);
                formUpload.append('trans_stat', 2);
                formUpload.append('action', 'upload');
                next = true;
            }


            if (next) {
                $.ajax({
                    url: url,
                    data: formUpload,
                    type: 'post',
                    dataType: 'json',
                    cache: 'false',
                    contentType: false,
                    processData: false,
                    beforeSend: function() {

                    },
                    success: function(data) {
                        if (parseInt(data.stat) === 1) {
                            $('.custom-file-input').siblings(".custom-file-label").addClass("selected").html('Choose file');
                            $('#modalUpload').modal('hide');
                            Swal.fire({
                                position: 'center',
                                icon: 'success',
                                title: data.mesg,
                                showConfirmButton: false,
                                timer: 1000
                            });
                            loadTrans();
                        }
                    },
                    error: function(xhr, status, err) {

                    }
                });
            } else {
                alert('Data tidak ada');
            }
        });

        $(document).on('click', '#btnAccept', function(e) {
            e.preventDefault();
            var id = $(this).data('id');
            var next = false;
            if (id) {
                var data = {
                    'trans-id': id,
                    'trans-stat': 5,
                    'action': 'received'
                };
                next = true;
            }

            if (next) {
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
                                icon: 'success',
                                title: data.mesg,
                                showConfirmButton: false,
                                timer: 1000
                            });
                            clearInterval(setInterval(loadTrans(), 1000));
                        }
                    },
                    error: function(xhr, status, err) {

                    }
                });
            }
        });

        $(document).on('click', '#btnDetail', function(e) {
            e.preventDefault();
            $('#modalDetailTrans').modal('show');
        });

        $(document).on('click', '#btnFind', function(e) {
            e.preventDefault();
            $('#modalCekResi').modal('show');

            var next = false;
            var id = $(this).data('id');

            if (id) {
                var data = {
                    'id': $(this).data('id'),
                    'action': 'cek-resi'
                }
                next = true;
            }

            if (next) {
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
                            var output = '';
                            $.each(data.data.history, function(index, data) {
                                output += `
                                    <li>
                                        <p class="font-weight-normal mb-0">` + data.desc + `</p>
                                        <small class="font-weight-bold">` + data.date + `</small>
                                    </li>
                                    `;
                            });

                            $('#resi-awb').text(data.data.awb);
                            $('#resi-penerima').text('Satrio Suryo');
                            $('#resi-pengirim').text('DMTekno');
                            $('#resi-ekspedisi').text(data.data.courier);
                            $('#resi-stat').text(data.data.status);
                            $('#resi-history').append(' ');
                            $('#resi-history').append(output);
                        }
                    },
                    error: function(xhr, status, err) {

                    }
                });
            }
        });

        function loadTrans() {
            $('.list-trans').html(' ');
            // Load Transaksi User
            var data = {
                'id-user': $('#id-user').val(),
                'action': 'load-trans'
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
                        $('.list-trans').append(' ');
                        $('.list-trans').append(data.data);
                    }
                },
                error: function(xhr, status, err) {

                }
            });
        }

    });
</script>