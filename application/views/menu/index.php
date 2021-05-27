<section>
    <div class="menu_category">
        <div class="swiper_nav">
            <h3>Menu Kami</h3>
            <div class="filter">
                <a href="<?= base_url("cart"); ?>" class="btn-cart" data-meja="<?= $meja; ?>" data-type="<?= $type; ?>"><i class="fas fa-fw fa-shopping-cart"></i> <span class="cart_total"></span></a>
                <select name="category" id="category">
                    <option value="all">All Category</option>
                    <?php foreach($category as $c): ?>
                    <option value="<?= $c['makananjenis_id']; ?>"><?= $c['makananjenis_nama']; ?></option>
                    <?php endforeach; ?>
                </select>
                <input type="text" id="keyword_search" placeholder="Search">
            </div>
        </div>
        <div class="menus-container">
            <!-- ajax -->
        </div>
    </div>
</section>

<script>
    $(function() {
        let base_url = '<?= base_url(); ?>';

        function get_data(url) {
            if (!url) {
                url = base_url + 'menu/ajaxGetData';
            }

            $.ajax({
                url: url,
                method: 'POST',
                dataType: 'JSON',
                data: {
                    status: $("#category").val(),
                    search: $("#keyword_search").val()
                },
                success: function(hasil) {
                    console.log(hasil)
                    $('.cart_total').html(hasil.cart.total_pesanan);
                    $('.menus-container').html(hasil.hasil);
                }
            });
        }

        get_data('');

        $(".dark_mode").click(function() {
            $("body").toggleClass("dark");
            $(".menu-toggle span").toggleClass("darkest");
        });

        $(document).on('change', '#category', function(e) {
            get_data('');
        });

        $(document).on('change keyup', '#keyword_search', function(e) {
            get_data('');
        });

        $(document).on('click', '#add-to-cart', function(e) {
            let id = $(this).data('id');
            let user = $(this).data('user');
            let meja = $('.btn-cart').data('meja');

            if (!user) {
                document.location.href = base_url + 'client_auth';
                return false;
            }

            $.ajax({
                url: base_url + 'menu/ajaxAddToCart',
                method: 'POST',
                dataType: 'JSON',
                data: {
                    uid: user,
                    makanan: id,
                    meja: meja
                },
                beforeSend: function(hasil) {
                    $(this).attr('disabled', true);
                },
                success: function(hasil) {
                    get_data('');
                }
            });
        });
    });
</script>