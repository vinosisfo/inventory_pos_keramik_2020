
    <section>
        <!-- Left Sidebar -->
        <aside id="leftsidebar" class="sidebar">
            <!-- User Info -->
            <div class="user-info">
                <div class="image">
                    <img src="<?php echo base_url('assets/adminbsb/images/animation-bg.jpg') ?>" width="48" height="48" alt="User" />
                </div>
                <div class="info-container">
                    <div class="name" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <?php echo strtoupper($this->session->userdata("username")) ?>
                    </div>
                    <div class="btn-group user-helper-dropdown">
                        <i class="material-icons" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">keyboard_arrow_down</i>
                        <ul class="dropdown-menu pull-right">
                            <li role="separator" class="divider"></li>
                            <li><a href="<?php echo base_url('Auth/logout') ?>"><i class="material-icons">input</i>Sign Out</a></li>
                        </ul>
                    </div>
                </div>
            </div>
            <!-- #User Info -->
            <!-- Menu -->
            <div class="menu">
                <ul class="list">
                    <li class="header">MAIN MENU</li>
                    <?php 
                    $akses_admin   = $this->session->userdata("akses_admin");
                    $kode_pegawai  = $this->session->userdata("kode_pegawai");
                    $where_pegawai = ($akses_admin > 0) ? "" :" AND C.kode_pegawai='$kode_pegawai'";

                    $sql_menu_head = $this->db->query("SELECT DISTINCT A.id_menu,A.nama_menu,A.path_menu 
                                                        FROM menu A 
                                                        LEFT JOIN user_akses_menu B ON B.id_menu=A.id_menu
                                                        LEFT JOIN user_akses_user C ON C.id_akses=B.id_akses
                                                        WHERE A.parent_1 IS NULL AND A.Aktif=1
                                                        $where_pegawai
                                                        ORDER BY A.NoUrut");
                    foreach ($sql_menu_head->result() as $head_menu) { ?>
                    <li>
                        <a href="javascript:void(0);" class="menu-toggle">
                            <i class="material-icons">view_list</i>
                            <span><?php echo $head_menu->nama_menu ?></span>
                        </a>
                        <ul class="ml-menu">
                            <?php
                            $sql_menu_parent1 = $this->db->query("SELECT DISTINCT A.id_menu,A.nama_menu AS Nama_Parent,A.path_menu AS Path_Parent 
                                                                FROM menu A 
                                                                LEFT JOIN user_akses_menu B ON B.id_menu=A.id_menu
                                                                LEFT JOIN user_akses_user C ON C.id_akses=B.id_akses
                                                                WHERE A.parent_1='$head_menu->id_menu' AND A.Aktif=1
                                                                $where_pegawai
                                                                ORDER BY A.NoUrut");
                            foreach ($sql_menu_parent1->result() as $menu_parent) {
                                $path_menu = $menu_parent->Path_Parent; ?>
                            <li class="active">
                                <a href="<?php echo base_url(''.$path_menu.'')?>"><?php echo $menu_parent->Nama_Parent ?></a>
                            </li>
                            <?php } ?>
                        </ul>
                    </li>
                    <?php } ?>
                </ul>
            </div>
            <!-- #Menu -->
            <!-- Footer -->
            <div class="legal" style="max-height: 40px;">
                <div class="copyright" id="foot">
                    <?php echo foots() ?>
                </div>
            </div>
            <!-- #Footer -->
        </aside>
    </section>