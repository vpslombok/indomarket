                <!-- Begin Page Content -->
                <div class="container-fluid">

                    <!-- page heading -->
                    <h1 class="h3 mb-4 text-gray-800"><?= $title; ?></h1>


                    <diiv class="row">
                        <div class="col-lg-6">
                            <?= $this->session->flashdata('ubahpassword'); ?>

                            <form action="<?= base_url('user/ubahpassword'); ?>" method="post">
                                 <div class="form-group">
                                    <label for="currnet_password">Password Lama</label>
                                    <input type="password" class="form-control" id="current_password" name="current_password">
                                    <?= form_error('current_password', '<small class="text-danger pl-3">', '</small>'); ?>
                                 </div>
                                 <div class="form-group">
                                    <label for="new_password1">Password Baru</label>
                                    <input type="password" class="form-control" id="new_password1" name="new_password1">
                                    <?= form_error('new_password1', '<small class="text-danger pl-3">', '</small>'); ?>
                                 </div>
                                 <div class="form-group">
                                    <label for="new_password2">Ulang Password Baru</label>
                                    <input type="password" class="form-control" id="new_password2" name="new_password2">
                                    <?= form_error('new_password2', '<small class="text-danger pl-3">', '</small>'); ?>
                                 </div>

                                 <div class="form-group">
                                    <button type="submit" class="btn btn-primary">Ubah Password</button>
                                 </div>
                            </form>

                        </div>
                    </diiv>

                </div>
                <!-- /.container-fluid -->

                </div>
                <!-- End of Main Content -->