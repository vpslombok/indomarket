<div class="container-fluid">
    <h3 class="h3 mb-4 text-gray-800"><?php echo $title; ?></h3>

    <div class="card">
        <div class="card-body">
            <?php echo form_open_multipart('user/editprofileaksi'); ?>

            <div class="form-group">
                <label for="email">Email</label>
                <input type="text" class="form-control" id="email" name="email" value="<?php echo $user['email']; ?>" readonly>
                <?php echo form_error('email', '<small class="text-danger pl-3">', '</small>'); ?>
            </div>

            <div class="form-group">
                <label for="name">Nama</label>
                <input type="text" class="form-control" id="name" name="name" value="<?php echo $user['name']; ?>">
                <?php echo form_error('name', '<small class="text-danger pl-3">', '</small>'); ?>
            </div>

            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" class="form-control" id="password" name="password" placeholder="Password">
                <?php echo form_error('password', '<small class="text-danger pl-3">', '</small>'); ?>
            </div>

            <div class="form-group">
                <label for="image">Foto</label>
                <div class="row">
                    <div class="col-sm-3">
                        <img src="<?php echo base_url('assets/img/profile/') . $user['image']; ?>" class="img-thumbnail">
                    </div>
                    <div class="col-sm-9">
                        <div class="custom-file">
                            <input type="file" class="custom-file-input" id="image" name="image">
                            <label class="custom-file-label" for="image">Pilih Foto</label>
                        </div>
                    </div>
                </div>
            </div>

            <div class="form-group row justify-content-end mx-auto">
                <div class="col-sm-10">
                    <button type="submit" class="btn btn-primary">Edit</button>
                </div>
            </div>
            <?php echo form_close(); ?>
        </div>
    </div>
</div>
