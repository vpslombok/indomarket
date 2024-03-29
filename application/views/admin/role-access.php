                <!-- Begin Page Content -->
                <div class="container-fluid">

                    <h1 class="h3 mb-4 text-gray-800"><?= $title; ?></h1>




                    <div class="row">
                        <div class="col-lg-6">
                            <?= $this->session->flashdata('role-access'); ?>
                            <h5>Role : <?= $role['role']; ?></h5>


                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th scope="col">#</th>
                                        <th scope="col">Menu</th>
                                        <th scope="col">Access</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($menu as $i => $m) : ?>
                                        <tr>
                                            <th scope="row"><?= $i + 1; ?></th>
                                            <td><?= $m['menu']; ?></td>
                                            <td>
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" <?= check_access($role['id'], $m['id']);?>
                                                    data-role="<?php echo $role['id']; ?>"
                                                    data-menu="<?php echo $m['id']; ?>">
                                                    
                                                    
                                                </div>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>

                            </table>
                        </div>
                    </div>



                </div>
                <!-- /.container-fluid -->

                </div>
                <!-- End of Main Content -->