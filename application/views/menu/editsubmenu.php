                <!-- Begin Page Content -->
                <div class="container-fluid">

                    <!-- page heading -->
                    <h1 class="h3 mb-4 text-gray-800"><?= $title; ?></h1>


                    <diiv class="row">
                        <div class="col-lg-6">
                            <?= $this->session->flashdata('edit submenu'); ?>

                            <form action="<?= base_url('menu/editsubmenu'); ?>" method="post">
                                 <div class="form-group">
                                    <label for="title">Nama submenu</label>
                                    <input type="text" class="form-control" id="title" name="title">
                                    <?= form_error('title', '<small class="text-danger pl-3">', '</small>'); ?>
                                 </div>
                                 <div class="form-group">
                                    <label for="menu_id"></label>
                                    <input type="text" class="form-control" id="menu" name="menu">
                                    <?= form_error('menu', '<small class="text-danger pl-3">', '</small>'); ?>
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