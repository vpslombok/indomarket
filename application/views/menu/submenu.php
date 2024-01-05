                <!-- Begin Page Content -->
                <div class="container-fluid">

                  <h1 class="h3 mb-4 text-gray-800"><?= $title; ?></h1>

                  <?= $this->session->flashdata('submenu'); ?>

                  <div class="table-responsive">
                    <div class="col-lg-10">
                      <?php if(validation_errors()) : ?>
                        <div class="alert alert-danger" role="alert">
                          <?= validation_errors(); ?>
                        <?php endif; ?>
                        </div>
                      <a href="" class="btn btn-primary mb-2" data-toggle="modal" data-target="#newSubMenuModal">Add New Submenu</a>
                      <table class="table table-bordered">
                        <thead>
                          <tr>
                            <th scope="col">#</th>
                            <th scope="col">Title</th>
                            <th scope="col">Menu</th>
                            <th scope="col">Url</th>
                            <th scope="col">Icon</th>
                            <th scope="col">Active</th>
                            <th scope="col">Action</th>
                          </tr>
                        </thead>
                        <tbody>
                          <?php foreach ($subMenu as $i => $m) : ?>
                            <tr>
                              <th scope="row"><?= $i + 1; ?></th>
                              <td><?= $m['title']; ?></td>
                              <td><?= $m['menu']; ?></td>
                              <td><?= $m['url']; ?></td>
                              <td><?= $m['icon']; ?></td>
                              <td><?= $m['is_active']; ?></td>
                              <td>
                                <a href="<?= base_url('menu/editsubmenu/' . $m['id']); ?>" class="badge badge-success">edit</a>
                                <a href="<?= base_url('menu/deletesubmenu/' . $m['id']); ?>" class="badge badge-danger">delete</a>
                              </td>
                            </tr>
                          <?php endforeach; ?>
                        </tbody>

                      </table>
                    </div>
                  </div>
                </div>
                <!-- /.container-fluid -->
                
                <!-- End of Main Content -->

                <!-- Modal -->
                <div class="modal fade" id="newSubMenuModal" tabindex="-1" aria-labelledby="newSubMenuModalLabel" aria-hidden="true">
                  <div class="modal-dialog">
                    <div class="modal-content">
                      <div class="modal-header">
                        <h5 class="modal-title" id="newSubMenuModalLabel">Add New Submenu</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                          <span aria-hidden="true">&times;</span>
                        </button>
                      </div>
                      <form action="<?= base_url('menu/submenu'); ?>" method="post">
                        <div class="modal-body">
                          <div class="form-group">
                            <input type="text" class="form-control" id="title" name="title" placeholder="submenu title">
                          </div>
                          <div class="form-group">
                            <select name="menu_id" id="menu_id" class="form-control">
                              <option value="">Select Menu</option>
                              <?php foreach ($menu as $m) : ?>
                                <option value="<?= $m['id']; ?>"><?= $m['menu']; ?></option>
                              <?php endforeach; ?>
                            </select>
                          </div>
                          <div class="form-group">
                            <input type="text" class="form-control" id="url" name="url" placeholder="submenu url">
                          </div>
                          <div class="form-group">
                            <input type="text" class="form-control" id="icon" name="icon" placeholder="submenu icon">
                          </div>
                          <div class="form-group">
                            <div class="form-check">
                              <input class="form-check-input" type="checkbox" value="1" name="is_active" id="is_active" checked>
                              <label class="form-check-label" for="is_active">
                                Active?
                              </label>
                            </div>
                          </div>
                        </div>
                        <div class="modal-footer">
                          <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                          <button type="submit" class="btn btn-primary">Add</button>
                        </div>
                      </form>
                    </div>
                  </div>
                </div>