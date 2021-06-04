<!doctype html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="<?= base_url(); ?>assets/css/select2.min.css" />
    <link href="<?= base_url(); ?>assets/css/select2-bootstrap4.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/css/bootstrap.min.css">
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="<?= base_url(); ?>assets/vendor/fontawesome-free/css/all.min.css">
    <link rel="stylesheet" href="<?= base_url(); ?>assets/css/main.css">

    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="<?= base_url(); ?>assets/js/select2.min.js"></script>
    <script src="<?= base_url(); ?>assets/js/sweetalert2/sweetalert2.all.min.js"></script>


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

                    <?php if($order): ?>
                    <a class="nav-link align-self-center" href="<?= base_url("order"); ?>?type=<?= $type; ?>&meja=<?= $meja; ?>">Order <small class="badge badge-sm badge-success small"><?= count($order); ?></small></a>
                    <?php else: ?>
                    <a class="nav-link align-self-center" href="<?= base_url("order"); ?>?type=<?= $type; ?>&meja=<?= $meja; ?>">Order </a>
                    <?php endif; ?>

                    <div class="dropdown align-self-center">
                        <a class="nav-link align-self-center dropdown-toggle" id="navbarDropdownMenuLink" href="<?= base_url("profile"); ?>?type=<?= $type; ?>&meja=<?= $meja; ?>" data-toggle="dropdown">
                            Hi, <?= explode(' ', $user['user_nama'])[0]; ?>
                        </a>
                        <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdownMenuLink">
                            <a class="dropdown-item" href="#">Another action</a>
                            <a class="dropdown-item" href="<?= base_url("profile"); ?>?type=<?= $type; ?>&meja=<?= $meja; ?>">Profile</a>
                            <a class="dropdown-item" href="<?= base_url("client_auth/logout"); ?>?type=<?= $type; ?>&meja=<?= $meja; ?>">Logout</a>
                        </div>
                    </div>
                    <?php else: ?>
                    <a class="nav-link align-self-center" href="<?= base_url("client_auth"); ?>?type=<?= $type; ?>&meja=<?= $meja; ?>">Login</a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </nav>