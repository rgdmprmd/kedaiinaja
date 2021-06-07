<style>
    .hide {
        display: none;
    }
</style>
<div class="container">
    <div class="row mt-5">
        <div class="col-md-3">
            <h4>Orderku</h4>
        </div>
        <div class="col-md-9">
            <div class="d-none d-md-block">
                <form class="form-inline d-flex justify-content-end">
                    <div class="form-group">
                        <select name="category" id="category" class="custom-select">
                            <option value="pending">Pending</option>
                            <option value="settlement">Settlement</option>
                            <option value="failure">Failure</option>
                            <option value="all">All Status</option>
                        </select>
                    </div>
                </form>
            </div>
            <div class="d-sm-block d-md-none">
                <form class="text-center">
                    <div class="form-group">
                        <select name="category" id="category" class="custom-select">
                            <option value="all">All Status</option>
                            <option value="pending">Pending</option>
                            <option value="settlement">Settlement</option>
                        </select>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="row mt-3 order_container">
        <!-- ajax -->
    </div>
</div>

<script>
    $(function() {
        const base_url = '<?= base_url(); ?>';

        function get_data(url) {
            if (!url) {
                url = base_url + 'order/ajaxGetData';
            }

            $.ajax({
                url: url,
                method: 'POST',
                dataType: 'JSON',
                data: {
                    status: $("#category").val(),
                },
                success: function(resp) {
                    console.log(resp);
                    // $('.cart_total').html(hasil.cart.total_pesanan);
                    $('.order_container').html(resp.hasil);
                }
            });
        }

        get_data();

        $(document).on('click', ".copy_toclipboard", function(e) {
            e.preventDefault();

            let value = $('.copy_toclipboard strong');
            var $temp = $("<input>");
            $("body").append($temp);

            $temp.val($(value).text()).select();
            document.execCommand("copy");
            $temp.remove();

            $('.copied').html('<i class="small">copied</i>');
        });
    });
</script>