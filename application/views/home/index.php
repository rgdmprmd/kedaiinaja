<section>
    <div class="hero">
        <div class="tagline">
            <h1>
                KASI TAU <span>KOKI</span> TERBAIK KAMI <br />
                KAMU MAU <span>MAKAN</span> APA
            </h1>
            <div class="tagline-enak">
                <p>Kayanya enak nih makan&nbsp</p>
                <div id="vertSlider" class="owl-carousel">
                    <div class="vert-item">Indomie</div>
                    <div class="vert-item">Ayam Geprek</div>
                    <div class="vert-item">Roti Bakar</div>
                    <div class="vert-item">Otak-otak</div>
                    <div class="vert-item">Kentang Goreng</div>
                </div>
            </div>
        </div>
        <img src="<?= base_url(); ?>assets/img/koki.svg" alt="koki" />
    </div>
</section>

<section>
    <div class="menu_category">
        <div class="swiper_nav">
            <h2>Menu Kami</h2>
            <div class="btn">
                <i class="fas fa-arrow-left"></i>
                <i class="fas fa-arrow-right"></i>
            </div>
        </div>
        <div class="swiper-container">
            <div id="lightSlider" class="owl-carousel">
                <?php foreach($category as $c): ?>
                <div class="item" data-item="<?= $c['makananjenis_nama']; ?>">
                    <?php if($c['makananjenis_icon']): ?>
                    <img src="<?= base_url(); ?>assets/img/icon/<?= $c['makananjenis_icon']; ?>" alt="" />
                    <?php else:?>
                    <img src="<?= base_url(); ?>assets/img/icon/046-food tray.png" alt="" />
                    <?php endif; ?>
                    <p><?= $c['makananjenis_nama']; ?></p>
                </div>
                <?php endforeach;?>
            </div>
        </div>
    </div>
</section>

<script>
    $(function() {
        $(".dark_mode").click(function() {
            console.log("daaaa");
            $("body").toggleClass("dark");
            $(".menu-toggle span").toggleClass("darkest");
        });

        $(document).on("click", ".item", function() {
            console.log($(this).data('item'));
        });
    })
</script>