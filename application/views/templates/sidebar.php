        <!-- Sidebar -->
        <!-- <style>
            /* Add this in your CSS file or within <style> tags */
            @media (max-width: 768px) {
                .sidebar {
                    display: none;
                }

                .sidebar.toggled {
                    display: none;
                }

                .sidebar.toggled .collapse {
                    display: none;
                }

                .sidebar-toggled {
                    margin-left: 0;
                }
            }
        </style> -->

        <ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">

            <!-- Sidebar - Brand -->
            <a class="sidebar-brand d-flex align-items-center justify-content-center" href="<?= base_url('user'); ?>">
                <div class="sidebar-brand-icon">
                    <i class="fas fa-store"></i>
                </div>
                <div class="sidebar-brand-text mx-3"><?php echo ($title); ?></div>
            </a>

            <!-- Divider -->
            <hr class="sidebar-divider">
            <!-- QUERY MENU -->
            <?php
            $data['user'] = $this->db->get_where('user', ['email' => $this->session->userdata('email')])->row_array();

            // Periksa apakah role_id ada dalam $data['user']
            if (isset($data['user']['role_id'])) {
                $role_id = $data['user']['role_id'];

                $queryMenu = "SELECT `user_menu`.`id`, `menu`
                  FROM `user_menu` JOIN `user_access_menu`
                  ON `user_menu`.`id` = `user_access_menu`.`menu_id`
                  WHERE `user_access_menu`.`role_id` = $role_id
                  ORDER BY `user_access_menu`.`menu_id` ASC";

                $menu = $this->db->query($queryMenu)->result_array();
            } else {
                echo "Role ID tidak valid.";
            }
            ?>
            <!-- LOOPING MENU-->
            <?php foreach ($menu as $m) : ?>
                <div class="sidebar-heading">
                    <?= $m['menu']; ?>
                </div>

                <!-- SIAPKAN SUB-MENU SESUAI MENU -->
                <?php
                $menuId = $m['id'];

                $querySubMenu = "SELECT *
                 FROM `user_sub_menu` JOIN `user_menu`
                 ON `user_sub_menu`.`menu_id` = `user_menu`.`id`
                 WHERE `user_sub_menu`.`menu_id` = $menuId
                 AND `user_sub_menu`.`is_active` = 1
                ";
                $subMenu = $this->db->query($querySubMenu)->result_array();
                ?>


                <?php foreach ($subMenu as $sm) : ?>
                    <?php if ($title == $sm['title']) : ?>
                        <li class="nav-item active">
                        <?php else : ?>
                        <li class="nav-item">
                        <?php endif; ?>
                        <a class="nav-link pb-0" href="<?= base_url($sm['url']); ?>">
                            <i class="<?= $sm['icon']; ?>"></i>
                            <span><?= $sm['title']; ?></span></a>
                        </li>

                    <?php endforeach; ?>

                    <hr class="sidebar-divider mt-3">
                <?php endforeach; ?>

                <!-- Divider -->

                <li class="nav-item">
                    <a class="nav-link" href="" data-toggle="modal" data-target="#logoutModal">
                        <i class="fas fa-fw fa-sign-out-alt"></i>
                        <span>logout</span></a>
                </li>
                <!-- Divider -->
                <hr class="sidebar-divider d-none d-md-block">

                <!-- Sidebar Toggler (Sidebar) -->
                <div class="text-center d-none d-md-inline">
                    <button class="rounded-circle border-0" id="sidebarToggle"></button>
                </div>

        </ul>
        <!-- End of Sidebar -->

        <!-- Content Wrapper -->
        <div id="content-wrapper" class="d-flex flex-column">

            <!-- Main Content -->
            <div id="content">

                <!-- Topbar -->
                <nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 static-top shadow">


                    <!-- Sidebar Toggle (Topbar) -->
                    <button id="sidebarToggleTop" class="btn btn-link d-md-none rounded-circle mr-3">
                        <i class="fa fa-bars"></i>
                    </button>

                    <?php
                    // Simulasi total item dari variabel sesi
                    $totalItem = $this->cart->total_items();
                    ?>
                    <style>
                        /* Gaya CSS untuk lingkaran bundar */
                        .badge {
                            background-color: #dc3545;
                            color: #fff;
                            border-radius: 50%;
                            padding: 4px 4px;
                            font-size: 9px;
                            position: absolute;
                            top: 4px;
                            right: 4px;
                        }
                    </style>

                    <!-- keranjang belanja -->
                    <div class="col-6">
                        <a href="<?= base_url('user/keranjang'); ?>" class="btn btn-info btn-icon-split">
                            <span class="icon text-white">
                                <i class="fas fa-shopping-cart"></i>
                                <span class="badge"><?= $totalItem; ?></span>
                        </a>
                    </div>


                    <!-- Topbar Navbar -->
                    <ul class="navbar-nav ml-auto">
                        <div class="topbar-divider d-none d-sm-block"></div>


                        <!-- Nav Item - User Information -->
                        <li class="nav-item dropdown no-arrow">
                            <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <span class="mr-2 d-none d-lg-inline text-gray-600 small"><?php if ($user) : ?>
                                        <span><?php echo $user['name']; ?></span>
                                        <img class="img-profile rounded-circle" src="<?php echo base_url('assets/img/profile/') . $user['image']; ?>">
                                    <?php endif; ?>

                            </a>
                            <!-- Dropdown - User Information -->
                            <div class="dropdown-menu dropdown-menu-right shadow animated--grow-in" aria-labelledby="userDropdown">
                                <a class="dropdown-item" href="<?php echo base_url('user/myprofile'); ?>">
                                    <i class="fas fa-user fa-sm fa-fw mr-2 text-gray-400"></i>
                                    My Profile
                                </a>
                                <div class="dropdown-divider"></div>
                                <a class="dropdown-item" href="<?php echo base_url('auth/logout'); ?>" data-toggle="modal" data-target="#logoutModal">
                                    <i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-gray-400"></i>
                                    Logout
                                </a>
                            </div>
                        </li>

                    </ul>

                </nav>
                <!-- End of Topbar -->