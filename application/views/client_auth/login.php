<div class="container">
    <div class="row justify-content-center">
        <div class="col-lg-7">

            <div class="card card-primary o-hidden border-0 shadow-lg my-5">
                <div class="card-body p-0">
                    <div class="row">
                        <div class="col-lg">
                            <div class="p-5">
                                <div class="text-center">
                                    <b class="h3 text-gray-900 mb-4"><strong>Welcome back!</strong></b>
                                    <p class="text-muted">We're so excited to see you again!</p>
                                </div>

                                <div class="wrong-email" data-wemail="<?= $this->session->flashdata('wemail'); ?>"></div>
                                <div class="active-email" data-aemail="<?= $this->session->flashdata('aemail'); ?>"></div>
                                <div class="wrong-password" data-wpass="<?= $this->session->flashdata('wpass'); ?>"></div>
                                <div class="registration-success" data-regsucs="<?= $this->session->flashdata('regsucs'); ?>"></div>
                                <div class="wrong-token" data-wtoken="<?= $this->session->flashdata('wtoken'); ?>"></div>
                                <div class="expired-token" data-etoken="<?= $this->session->flashdata('etoken'); ?>"></div>
                                <div class="success-token" data-stoken="<?= $this->session->flashdata('stoken'); ?>"></div>
                                <div class="ex-token" data-extoken="<?= $this->session->flashdata('extoken'); ?>"></div>
                                <div class="success-reset" data-sreset="<?= $this->session->flashdata('sreset'); ?>"></div>

                                <form class="user mt-5" method="POST" action="<?= base_url(); ?>client_auth/login_user" id="form-login" data-meja="<?= $meja; ?>" data-type="<?= $type; ?>">
                                    <div class="form-group">
                                        <input type="text" class="form-control form-control-user" id="email" name="email" placeholder="Email address" autocomplete="off" value="<?= set_value('email'); ?>">
                                        <span class="email-error"></span>
                                    </div>
                                    <div class="form-group">
                                        <input type="password" class="form-control form-control-user" id="password" name="password" placeholder="Password">
                                        <span class="password-error"></span>
                                    </div>
                                    <div class="form-group">
                                        <a class="small" href="<?= base_url(); ?>client_auth/forgotpassword">Forgot your password?</a>
                                    </div>
                                    <button type="submit" class="btn btn-primary btn-user btn-block" id="btn-login">Login</button>
                                </form>
                                <div class="text-left mt-1">
                                    <span class=" small text-muted">Need an account? </span><a class="small" href="<?= base_url(); ?>client_auth/registration?type=<?= $type; ?>&meja=<?= $meja; ?>">Register!</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>

<script>
    $(function() {
        const base_url = '<?= base_url(); ?>';

        $(document).on("submit", "#form-login", function(e) {
            e.preventDefault();

            $('.email-error').html('');
            $('.password-error').html('');

            let url = $(this).attr("action");
            let meja = $(this).data("meja");
            let type = $(this).data("type");
            let formData = new FormData(this);

            $.ajax({
                url: url,
                method: 'POST',
                dataType: 'JSON',
                processData: false,
                contentType: false,
                data: formData,
                beforeSend: function(response) {
                    $("#btn-login").attr("disabled", true);
                },
                success: function(response) {
                    $("#btn-login").attr("disabled", false);

                    if (response.result == 400) {

                        $('.email-error').html(response.message.email);
                        $('.password-error').html(response.message.password);

                        $('#password').val('');
                    } else if (response.result == 401) {
                        Swal.fire({
                            icon: 'warning',
                            width: 800,
                            padding: '2em',
                            title: 'Oops, email salah!',
                            html: "<span class='text-primary'>" + response.message + "</span> tidak terdaftar. Kamu harus registrasi dulu."
                        }).then((result) => {
                            location.reload();
                        });
                    } else if (response.result == 402) {
                        Swal.fire({
                            icon: 'warning',
                            width: 800,
                            padding: '2em',
                            title: 'Oops, email kamu belum aktif!',
                            html: "<span class='text-primary'>" + response.message + "</span> belum diaktivasi. Silahkan cek email kamu untuk melakukan aktivasi."
                        }).then((result) => {
                            location.reload();
                        });
                    } else if (response.result == 403) {
                        Swal.fire({
                            icon: 'warning',
                            width: 800,
                            padding: '2em',
                            title: 'Oops, password salah!',
                            html: "Kamu lupa password? sebaiknya kamu coba fitur lupa password."
                        }).then((result) => {
                            $("#password").val('');
                        });
                    } else if (response.result == 200) {
                        document.location.href = base_url + `home?type=${type}&meja=${meja}`;
                    }
                }
            });
        });
    });
</script>