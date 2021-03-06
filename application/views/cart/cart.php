<section>
    <div class="menu_category">
        <div class="swiper_nav">
            <h3>Checkout</h3>
            <select name="meja_id" id="category">
                <?php if($meja): ?>
                <option value="<?= $meja_list['detail']['meja_id']; ?>" disabled selected>Meja <?= $meja_list['detail']['meja_nomer']; ?></option>
                <?php else: ?>
                <option value="all" disabled selected>Nomer meja</option>
                <?php foreach($meja_list['all'] as $m): ?>
                <option value="<?= $m['meja_id']; ?>">Meja <?= $m['meja_nomer']; ?></option>
                <?php endforeach; ?>
                <?php endif; ?>
            </select>
        </div>
        <div class="checkout_item">
            <!-- Generate by ajax -->
        </div>
        <div class="checkout_total">
            <div class="tambah_item">
                <a href="<?= base_url('menu'); ?>?type=<?= $type; ?>&meja=<?= $meja; ?>"><i class="fas fa-fw fa-plus"></i> Add Item</a>
            </div>
            <div class="total_prefix">
                <p>Total</p>
            </div>
            <div class="total_bayar">
                <p class="amount">Rp. 0</p>
            </div>
        </div>
    </div>
</section>

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
                    $('.checkout_item').html(resp.hasil);
                    $('.amount').html('Rp. ' + resp.pesanan_total)
                }
            });
        }

        get_data('');

        $(".dark_mode").click(function() {
            $("body").toggleClass("dark");
            $(".menu-toggle span").toggleClass("darkest");
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