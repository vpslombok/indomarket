<div class="container">

    <!-- Outer Row -->
    <div class="row justify-content-center">

        <div class="col-lg-6">

            <div class="card o-hidden border-0 shadow-lg my-5">
                <div class="card-body p-0">
                    <!-- Nested Row within Card Body -->
                    <div class="row">
                        <div class="col-lg">
                            <div class="p-5">
                                <div class="text-center">
                                    <h1 class="h4 text-gray-900 mb-4">Lupa Password</h1>
                                </div>

                                <?= $this->session->flashdata('lupapassword'); ?>
                                <form class="user" method="post" action="<?= base_url('Auth/forgotpassword'); ?>">
                                    <div class="form-group">
                                        <input type="text" class="form-control form-control-user" id="emial" name="email" aria-describedby="emailHelp" placeholder="Input Email" value="<?php echo set_value('email'); ?>">
                                        <?= form_error('email', '<small class="text-danger pl-3">', '</small>'); ?>
                                    </div>
                                    <button type="submit" class="btn btn-primary btn-user btn-block">
                                        Ubah Password
                                    </button>
                                </form>
                                <br>
                                <div class="text-center">
                                    <a class="small" href="<?= base_url('auth') ?>">Kembali Ke Halaman login</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>

    </div>

</div>