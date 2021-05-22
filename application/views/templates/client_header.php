<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title><?= $title; ?></title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" />
    <script src="<?= base_url(); ?>assets/vendor/jquery/jquery.min.js"></script>
    <link rel="stylesheet" href="<?= base_url(); ?>assets/css/owl.carousel.min.css" />
    <link rel="stylesheet" href="<?= base_url(); ?>assets/css/landing_main.css" />

    <script src="<?= base_url(); ?>assets/js/owl.carousel.js"></script>
    <script src="<?= base_url(); ?>assets/js/landing_main.js"></script>
</head>

<body>
    <nav>
        <div class="container">
            <h2><a href="<?= base_url("home"); ?>">KEDAI IN AJA</a></h2>
            <ul>
                <li><a href="<?= base_url("home"); ?>">Home</a></li>
                <li><a href="<?= base_url("menu"); ?>">Menu</a></li>
                <?php if($user): ?>
                <li><a href="<?= base_url("client_auth/logout"); ?>">Logout</a></li>
                <li class="mode_icon">
                    <i class="dark_mode fas fa-moon"></i>
                </li>
                <li><img src="<?= base_url(); ?>assets/img/profile/<?= $user['user_image']; ?>" alt="<?= $user['user_nama']; ?>" title="<?= $user['user_nama']; ?>" width="40" /></li>
                <?php else: ?>
                <li><a href="<?= base_url("client_auth"); ?>">Login</a></li>
                <li class="mode_icon">
                    <i class="dark_mode fas fa-moon"></i>
                </li>
                <?php endif; ?>
            </ul>

            <!-- burger -->
            <div class="menu-toggle">
                <input type="checkbox" />
                <span></span>
                <span></span>
                <span></span>
            </div>
        </div>
    </nav>