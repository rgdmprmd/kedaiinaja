<div class="container">
    <!-- Desktop -->
    <div class="d-none d-md-block">
        <div class="row mt-5">
            <div class="col-md-6">
                <div class="d-flex h-100">
                    <div class="justify-content-center align-self-center">
                        <h2 class="hero-text"><strong>Your Favourite Foods,</strong><br>in Your Gadget</h2>
                        <p>Pesan langsung dari gadget kamu.</p>
                        <a href="<?= base_url(); ?>menu?type=<?= $type; ?>&meja=<?= $meja; ?>" class="btn px-4 btn-info">Foods xx <i class="fas fa-hamburger ml-2"></i></a>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <img src="<?= base_url(); ?>assets/img/koki.svg" alt="koki" width="90%" />
            </div>
        </div>
    </div>

    <!-- Mobile -->
    <div class="d-sm-block d-md-none">
        <div class="row mt-5">
            <div class="col-md-6 mb-5 text-center">
                <img src="<?= base_url(); ?>assets/img/koki.svg" alt="koki" width="90%" />
            </div>
            <div class="col-md-6">
                <div class="text-center">
                    <h3 class="hero-text"><strong>Your Favourite Foods,</strong><br>in Your Gadget</h3>
                    <p>Pesan langsung dari gadget kamu.</p>
                    <a href="<?= base_url(); ?>menu?type=<?= $type; ?>&meja=<?= $meja; ?>" class="btn px-4 btn-info">Foods xx <i class="fas fa-hamburger ml-2"></i></a>
                </div>
            </div>
        </div>
    </div>
</div>