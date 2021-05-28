<div class="container">
    <div class="row mt-5">
        <div class="col-md-12 d-flex">
            <h4>Menu Kami</h4>
            <div class="ml-auto">
                <a href="<?= base_url("cart"); ?>?type=<?= $type; ?>&meja=<?= $meja; ?>" class="btn btn-success"><i class="fas fa-fw fa-shopping-cart"></i> <span class="cart_total">30000</span></a>
                <select name="category" id="category" class="custom-select">
                    <option value="all">All Category</option>
                    <?php foreach($category as $c): ?>
                    <option value="<?= $c['makananjenis_id']; ?>"><?= $c['makananjenis_nama']; ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
        </div>
    </div>
</div>