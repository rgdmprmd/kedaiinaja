<section>
    <div class="menu_category">
        <div class="swiper_nav">
            <h3>Menu Kami</h3>
            <div class="filter">
                <a href="#" class="btn-cart"><i class="fas fa-shopping-cart"></i> <span class="text number">0</span></a>
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
                    $('.menus-container').html(hasil.hasil);
                }
            });
        }

        get_data('');

        $(".dark_mode").click(function() {
            console.log("daaaa");
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

            if (!user) {
                document.location.href = base_url + 'client_auth';
                return false;
            }
            console.log(id, user);
        })
    });
</script>