<div class="container">
    <div class="row mt-5">
        <div class="col-md-3">
            <h4>Checkout</h4>
        </div>
        <div class="col-md-9" id="form-group">
            <div class="d-none d-md-block">
                <form class="form-inline d-flex justify-content-end">
                    <div class="form-group ml-1">
                        <select class="form-control" name="meja_id" id="meja_id" style="width:100%;" data-placeholder="Pilih meja" data-theme="bootstrap4" data-dropdown-parent="#form-group"></select>
                    </div>
                </form>
            </div>

            <div class="d-sm-block d-md-none">
                <form class="text-center">
                </form>
            </div>
        </div>
    </div>
</div>

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
                    if (resp.cart.meja_id) {
                        let option = new Option(resp.cart.meja_nomer, resp.cart.meja_id, true, true);
                        $('#meja_id').append(option).trigger('change');
                    }

                    // $('.checkout_item').html(resp.hasil);
                    // $('.amount').html('Rp. ' + resp.pesanan_total)
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