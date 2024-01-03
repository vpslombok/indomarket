<!-- Begin Page Content -->
<div class="container-fluid">

    <!-- Page Heading -->
    <button class="btn btn-sm btn-primary mb-1" data-toggle="modal" data-target="#tambah_user"><i class="fas fa-plus fa-sm"></i> Tambah User</button>
    <table class="table table-bordered " style="width: 100%;">
        <tr>
            <th style="font-size: 10px; text-align: center;">NO</th>
            <th style="font-size: 10px; text-align: center;">NAMA</th>
            <th style="font-size: 10px; text-align: center;">EMAIL</th>
            <th style="font-size: 10px; text-align: center;">BERGABUNG</th>
            <th style="font-size: 10px; text-align: center;">ROLE</th>
            <th style="font-size: 10px; text-align: center;">STATUS</th>
            <th style="font-size: 10px; text-align: center;">STATUS ONLINE</th>
            <th colspan="3" style="font-size: 10px; text-align: center;">AKSI</th>
        </tr>

        <?php
        $no = 1;
        foreach ($users as $user) : ?>
            <tr>
                <td class="" style="font-size: 10Px;"><?= $no++ ?></td>
                <td class="" style="font-size: 10Px;"><?= $user->name ?></td>
                <td class="" style="font-size: 10Px;"><?= $user->email ?></td>
                <td class="" style="font-size: 10Px;"><?= date('d F Y', $user->date_created) ?></td>
                <td class="" style="font-size: 10Px;">
                    <?php
                    // Ganti role ID menjadi teks sesuai kondisi
                    echo ($user->role_id == 1) ? 'Admin' : 'User';
                    ?>
                </td>
                <td class="" style="font-size: 10Px;">
                    <?php
                    // Ganti status aktif atau tidak aktif menjadi teks sesuai kondisi
                    echo ($user->is_active == 1) ? 'Aktif' : 'Tidak Aktif';
                    ?>
                </td>
                <td id="statusCell" class="status-cell" style="font-size: 14px; color: <?= ($user->online_status == 'online') ? 'green' : 'red'; ?>; font-weight: bold;">
                    <?= $user->online_status ?>
                </td>



                <td class="" style="font-size: 10Px;"><a href="<?= base_url('admin/user/edit/' . $user->id) ?>" class="btn btn-sm btn-success"><i class="fas fa-edit"></i></a></td>
                <td class="" style="font-size: 10Px;"><a href="<?= base_url('admin/user/hapus/' . $user->id) ?>" class="btn btn-sm btn-danger"><i class="fas fa-trash"></i></a></td>
                <td class="" style="font-size: 10Px;"><a href="<?= base_url('admin/user/detail/' . $user->id) ?>" class="btn btn-sm btn-primary"><i class="fas fa-search-plus"></i></a></td>
            </tr>

        <?php endforeach; ?>

    </table>
    <!-- /.container-fluid -->

</div>
<!-- End of Main Content -->