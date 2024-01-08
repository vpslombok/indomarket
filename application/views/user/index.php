               <!-- Begin Page Content -->

               <div class="container-fluid">
                 <div id="carouselExampleIndicators" class="carousel slide" data-ride="carousel">
                   <ol class="carousel-indicators">
                     <li data-target="#carouselExampleIndicators" data-slide-to="0" class="active"></li>
                     <li data-target="#carouselExampleIndicators" data-slide-to="1"></li>
                     <li data-target="#carouselExampleIndicators" data-slide-to="2"></li>
                   </ol>
                   <div class="carousel-inner">
                     <div class="carousel-item active">
                       <img src="<?php echo base_url('assets/img/slider1.jpg') ?>" class="d-block w-100" alt="...">
                     </div>
                     <div class="carousel-item">
                       <img src="<?php echo base_url('assets/img/slider2.jpg') ?>" class="d-block w-100" alt="...">
                     </div>
                     <!-- <div class="carousel-item"> -->
                     <!-- <img src="..." class="d-block w-100" alt="..."> -->
                     <!-- </div> -->
                   </div>
                   <a class="carousel-control-prev" type="button" data-target="#carouselExampleIndicators" data-slide="prev">
                     <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                     <span class="sr-only">Previous</span>
                   </a>
                   <a class="carousel-control-next" type="button" data-target="#carouselExampleIndicators" data-slide="next">
                     <span class="carousel-control-next-icon" aria-hidden="true"></span>
                     <span class="sr-only">Next</span>
                   </a>
                 </div>


                 <div class="row text-center mt-2">
                   <?php foreach ($barang as $brg) : ?>
                     <div class="card ml-2 mt-1 mx-auto" style="width: 10rem;">
                       <img src="<?php echo base_url() . 'uploads/' . $brg->gambar ?>" class="card-img-top" alt="...">
                       <div class="card-body">
                         <h6 class="card-title text-sm"><?php echo $brg->nama_brg ?> </h6>
                         <small class="text-sm"><?php echo $brg->keterangan ?></small>
                         <br><span class="badge badge-pill badge-s mb-3">RP <?php echo number_format($brg->harga, 0, ',', '.')  ?></span></br>
                         <?php echo anchor('user/tambah_ke_keranjang/' . $brg->id_brg, '<div class="btn btn-sm btn-primary mx-auto">Beli</div>') ?>
                         <?php echo anchor('user/detail/' . $brg->id_brg, '<div class="btn btn-sm btn-success">Detail</div>') ?>
                       </div>
                     </div>

                   <?php endforeach; ?>
                 </div>
               </div>
               </div>
               <a class="scroll-to-top rounded" href="#page-top">
                 <i class="fas fa-angle-up"></i>
               </a>