<div class="container">
    <form class="form">
        <div class="row mt-5">
            <div class="col-md-12 mb-3">
                <h4>Checkout</h4>
            </div>
            <div class="col-md-4" id="form-group">
                <div class="form-group">
                    <label for="jumlah_tamu">Jumlah Tamu</label>
                    <input type="number" class="form-control" name="jumlah_tamu" id="jumlah_tamu" min="1" max="8">
                </div>
            </div>
            <div class="col-md-4" id="form-group">
                <div class="form-group">
                    <label for="meja_id">Meja</label>
                    <select name="meja_id" id="meja_id" style="width:100%;" data-placeholder="Pilih meja" data-theme="bootstrap4" data-dropdown-parent="#form-group"></select>
                </div>
            </div>
            <div class="col-md-4" id="form-group">
                <div class="form-group">
                    <label for="type_id">Type</label>
                    <select class="form-control" name="type_id" id="type_id" style="width:100%;" data-placeholder="Pilih meja" data-theme="bootstrap4" data-dropdown-parent="#form-group">
                        <option selected disabled>Type</option>
                        <option value="dinein">Dine in</option>
                        <option value="takeaway">Take away</option>
                    </select>
                </div>
            </div>
        </div>

        <div class="row checkout_item">
            <!-- ajax -->
        </div>

        <div class="row my-3">
            <div class="col-2 align-self-baseline">
                <a href="<?= base_url('menu'); ?>?type=<?= $type; ?>&meja=<?= $meja; ?>" class="btn btn-sm btn-outline-success"><i class="fas fa-fw fa-plus"></i></a>
            </div>
            <div class="col-10 align-self-baseline text-right">
                <p class="mr-3">Total <span class="amount ml-5">Rp. 0</span></p>
            </div>
        </div>

        <div class="row">
            <div class="col-12 text-right">
                <button type="button" id="checkout_btn" class="btn btn-success">Checkout</button>
            </div>
        </div>
    </form>
</div>

<script type="text/javascript" src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="SB-Mid-client-HYBocWzI84ET2NEh"></script>
<script>
    $(function() {
        const base_url = '<?= base_url(); ?>';

        function get_data(url) {
            if (!url) {
                url = base_url + 'cart/ajaxGetData';
            }

            $.ajax({
                url: url,
                method: 'POST',
                dataType: 'JSON',
                success: function(resp) {
                    console.log(resp)
                    if (resp.cart.meja_id) {
                        let option = new Option(resp.cart.meja_nomer, resp.cart.meja_id, true, true);
                        $('#meja_id').append(option).trigger('change');
                        $('#meja_id').attr('disabled', true);

                        $('#jumlah_tamu').val(resp.cart.jumlah_tamu);
                        $('#jumlah_tamu').attr('max', resp.cart.kursi_tersedia);
                    } else {
                        $('#meja_id').val(null).trigger('change');

                    }

                    if (resp.cart.type) {
                        $('#type_id').val(resp.cart.type);
                        $('#type_id').attr('disabled', true);
                    }

                    $('.checkout_item').html(resp.hasil);
                    $('.amount').html('Rp. ' + resp.pesanan_total)
                }
            });
        }

        get_data('');

        $('#meja_id').select2({
            ajax: {
                url: base_url + 'cart/ajaxGetMeja',
                dataType: 'JSON',
                delay: 250,
                data: function(params) {
                    return {
                        search: params.term,
                        page: params.page,
                        limit: 20
                    }
                },
                processResults: function(data, params) {
                    params.page = params.page || 1;

                    return {
                        results: data.results,
                        pagination: {
                            more: (params.page * 30) < data.total_count
                        }
                    }
                },
                cache: true
            },
            templateResult: function(result) {
                return result.text;
            },
            templateSelection: function(result) {
                return result.text;
            }
        });

        $(document).on('change keyup', '#jumlah_tamu', function(e) {
            e.preventDefault();
            let key = e.keyCode ? e.keyCode : e.which;

            let val = $(this).val();
            let min = $(this).attr('min');
            let max = $(this).attr('max');

            // kalo dia nekennya cuman nomer
            if (key >= 48 && key <= 57) {
                if (parseInt(val) < parseInt(min)) {
                    $(this).val(min)
                } else if (parseInt(val) > parseInt(max)) {
                    $(this).val(max)
                }

                // kalo karakter paling depannya 0. contoh 012 jadi 12
                if ($(this).val().charAt(0) == 0) {
                    $(this).val(val);
                }
            }

            // kalo dia neken minus (-)
            if (key == 189) {
                $(this).val(min)
            }
        });

        $(document).on('click', '.btn-qty', function(e) {
            e.preventDefault();

            let id = $(this).data('id');
            let pesanan = $(this).data('pesananid');
            let type = $(this).data('type');

            $.ajax({
                url: base_url + 'cart/ajaxUpdateQty',
                method: 'POST',
                dataType: 'JSON',
                data: {
                    id,
                    pesanan,
                    type
                },
                success: function(resp) {
                    console.log(resp)
                    get_data('');
                }
            });
        });

        $(document).on('click', '.checkout_delete', function(e) {
            e.preventDefault();

            let id = $(this).data('id');
            let pesanan = $(this).data('pesananid');

            $.ajax({
                url: base_url + 'cart/ajaxDeleteItem',
                method: 'POST',
                dataType: 'JSON',
                data: {
                    id,
                    pesanan
                },
                success: function(resp) {
                    get_data('');
                }
            });
        });
    });
</script>