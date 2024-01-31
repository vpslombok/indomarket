<?php 
 
?>
<div class="container-fluid">

     <!-- Page Heading -->
     <h1 class="h3 mb-4 text-gray-800"><?php echo $title; ?></h1>

     <div class="card mb-3" style="max-width: 540px;">
         <div class="row no-gutters">
             <div class="col-md-4">
                 <img src="<?php echo base_url('assets/img/profile/') . $user['image']; ?>" class="card-img" alt="...">
             </div>
             <div class="col-md-8">
                 <div class="card-body">
                     <h5 class="card-title"><?= $user['name']; ?></h5>
                     <p class="card-text"><?= $user['email']; ?></p>
                     <p class="card-text"><small class="text-muted">di buat pada <?= date('d F Y', $user['date_created']); ?></small></p>
                     <p class="card-text"><small class="text-muted">sebagai <?= $user_role; ?></small></p>
                     <td>
                         <a href="<?php echo base_url('user/editprofile'); ?>" class="btn btn-primary">Edit Profile</a>
                     </td>
                 </div>
             </div>
         </div>
     </div>
</div>
</div>
