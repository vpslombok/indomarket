<div class="container-fluid">
<h1 class="h3 mb-4 text-gray-800"><?= $title; ?></h1>
    <button class="btn btn-sm btn-primary mb-3" data-toggle="modal" data-target="#tambah_barang"><i class="fas fa-plus fa-sm"></i> Tambah Barang</button>
    <?= $this->session->flashdata('data-barang'); ?>

    <table class="table table-bordered " style="width: 100%;">
    
        <tr>
            <th style="font-size: 10px; text-align: center;">NO</th>
            <th style="font-size: 10px; text-align: center;">NAMA PRODUK</th>
            <th style="font-size: 10px; text-align: center;">KETERANGAN</th>
            <th style="font-size: 10px; text-align: center;">KATEGORI</th>
            <th style="font-size: 10px; text-align: center;">HARGA</th>
            <th style="font-size: 10px; text-align: center;">STOK</th>
            <th colspan="3" style="font-size: 10px; text-align: center;">AKSI</th>
        </tr>
        

        <?php
        $no = 1;
        foreach ($barang as $brg) : ?>

            <tr>
                <td><?php echo $no++ ?></td>
                <td><?php echo $brg->nama_brg ?></td>
                <td><?php echo $brg->keterangan ?></td>
                <td><?php echo $brg->kategori ?></td>
                <td><?php echo $brg->harga ?></td>
                <td><?php echo $brg->stok ?></td>
                <td><?php echo anchor('admin/detail/' . $brg->id_brg, '<div class="btn btn-success btn-sm"><i class="fas fa-search-plus"></i></div>') ?>
                </td>
                <td><?php echo anchor('admin/edit/' . $brg->id_brg, '<div class="btn btn-primary btn-sm"><i class="fas fa-edit"></i></div>') ?></td>                   
                </td>
                <td><?php echo anchor('admin/hapus_produk/' . $brg->id_brg, '<div class="btn btn-danger btn-sm"><i class="fas fa-trash"></i></div>') ?></td>                   
                </td>
            </tr>

        <?php endforeach; ?>

    </table>
</div>

<!-- Modal -->
<div class="modal fade" id="tambah_barang" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Form Input Produk</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="<?php echo base_url(). 'admin/tambah_barang/';
                                ?>" method="post" enctype="multipart/form-data">

                    <div class="form-group">
                        <label>Nama Barang</label>
                        <input type="text" name="nama_brg" class="form-control">
                    </div>

                    <div class="form-group">
                        <label>Keterangan</label>
                        <input type="text" name="keterangan" class="form-control">
                    </div>

                    <div class="form-group">
                        <label for="kategori">Kategori</label>
                        <select name="kategori" id="kategori" class="form-control">
                        <option value=""></option>
                            <option value="elektronik">elektronik</option>
                            <option value="pakaian_pria">Pakaina Pria</option>
                            <option value="pakaian_wanita">Pakainan Wanita</option>
                            <option value="pakaian_anak_anak">Pakainan Anak-anak</option>
                            <option value="makanan_dan_minuman">Makanan & Minuman</option>
                            <!-- Tambahkan opsi sesuai kebutuhan -->
                        </select>
                    </div>


                    <div class="form-group">
                        <label>Harga</label>
                        <input type="text" name="harga" class="form-control">
                    </div>

                    <div class="form-group">
                        <label>Stok</label>
                        <input type="text" name="stok" class="form-control">
                    </div>

                    <div class="form-group">
                        <label>Gambar Produk</label><br>
                        <input type="file" name="gambar" class="form-control">
                    </div>


            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-dismiss="modal">Keluar</button>
                <button type="submit" class="btn btn-primary">Simpan</button>
            </div>
            </form>
        </div>
    </div>
</div>
        </div>