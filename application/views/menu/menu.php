<div class="container">
    <div class="row mt-5">
        <div class="col-md-3">
            <h4>Menu Kami</h4>
        </div>
        <div class="col-md-9">
            <div class="d-none d-md-block">
                <form class="form-inline d-flex justify-content-end">
                    <div class="form-group">
                        <a href="<?= base_url("cart"); ?>?type=<?= $type; ?>&meja=<?= $meja; ?>" class="btn btn-outline-success"><i class="fas fa-fw fa-shopping-cart"></i> <span class="cart_total">30000</span></a>
                    </div>
                    <div class="form-group ml-1">
                        <select name="category" id="category" class="custom-select">
                            <option value="all">All Category</option>
                            <?php foreach($category as $c): ?>
                            <option value="<?= $c['makananjenis_id']; ?>"><?= $c['makananjenis_nama']; ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="form-group ml-1">
                        <input type="text" id="keyword_search" class="form-control" placeholder="Search">
                    </div>
                </form>
            </div>

            <div class="d-sm-block d-md-none">
                <form class="text-center">
                    <div class="form-group">
                        <a href="<?= base_url("cart"); ?>?type=<?= $type; ?>&meja=<?= $meja; ?>" class="btn btn-outline-success btn-block"><i class="fas fa-fw fa-shopping-cart"></i> <span class="cart_total">30000</span></a>
                    </div>
                    <div class="form-group">
                        <select name="category" id="category" class="custom-select">
                            <option value="all">All Category</option>
                            <?php foreach($category as $c): ?>
                            <option value="<?= $c['makananjenis_id']; ?>"><?= $c['makananjenis_nama']; ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <input type="text" id="keyword_search" class="form-control" placeholder="Search">
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="row mt-3 menu-container">
        <!-- ajax -->
    </div>
</div>

<script>
    $(function() {
        const base_url = '<?= base_url(); ?>';

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
                    console.log(hasil);
                    $('.cart_total').html(hasil.cart.total_pesanan);
                    $('.menu-container').html(hasil.hasil);
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

        $(document).on('click', '.add-to-cart', function(e) {
            let id = $(this).data('id');
            let user = $(this).data('user');
            let meja = $('.btn-cart').data('meja');
            let type = $('.btn-cart').data('type');

            if (!user) {
                document.location.href = base_url + `client_auth?type=${type}&meja=${meja}`;
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