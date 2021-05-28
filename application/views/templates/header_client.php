<!doctype html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="<?= base_url(); ?>assets/vendor/fontawesome-free/css/all.min.css">
    <link rel="stylesheet" href="<?= base_url(); ?>assets/css/main.css">
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/js/bootstrap.bundle.min.js"></script>
    <title><?= $title; ?></title>
</head>

<body>
    <nav class="navbar navbar-expand-lg navbar-light">
        <div class="container mt-2">
            <a class="navbar-brand" href="<?= base_url("home"); ?>?type=<?= $type; ?>&meja=<?= $meja; ?>"><strong>KEDAI IN AJA</strong></a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNavAltMarkup" aria-controls="navbarNavAltMarkup" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNavAltMarkup">
                <div class="navbar-nav ml-auto">
                    <a class="nav-link align-self-center" href="<?= base_url("home"); ?>?type=<?= $type; ?>&meja=<?= $meja; ?>">Home</a>
                    <a class="nav-link align-self-center" href="<?= base_url("menu"); ?>?type=<?= $type; ?>&meja=<?= $meja; ?>">Menu</a>
                    <?php if($user): ?>
                    <a class="nav-link align-self-center" href="<?= base_url("client_auth/logout"); ?>?type=<?= $type; ?>&meja=<?= $meja; ?>">Logout</a>
                    <a class="nav-link align-self-center" href="<?= base_url("profile"); ?>?type=<?= $type; ?>&meja=<?= $meja; ?>"><img src="<?= base_url('assets/img/profile/'. $user['user_image']); ?>" alt="profile" class="img-thumbnail rounded-circle" width="40"></a>
                    <?php else: ?>
                    <a class="nav-link align-self-center" href="<?= base_url("client_auth"); ?>?type=<?= $type; ?>&meja=<?= $meja; ?>">Login</a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </nav>