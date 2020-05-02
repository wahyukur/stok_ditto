<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=yes">
	<meta name="description" content="">
	<meta name="author" content="">

	<title>Coffe@Ditto</title>

	<!-- Custom fonts for this template-->
	<link href="<?php echo base_url('assets/vendor/fontawesome-free/css/all.min.css') ?>" rel="stylesheet" type="text/css">

	<!-- Custom styles for this template-->
	<link href="<?php echo base_url('assets/css/sb-admin-2.min.css') ?>" rel="stylesheet">
	<link href="<?php echo base_url('assets/css/style.css') ?>" rel="stylesheet">
	<link href="<?php echo base_url('assets/vendor/datatables/dataTables.bootstrap4.min.css') ?>" rel="stylesheet">

	<link href="<?php echo base_url('assets/asetku/bootstrap/css/bootstrap.min.css')?>" rel="stylesheet">
    <link href="<?php echo base_url('assets/asetku/bootstrap-datepicker/css/bootstrap-datepicker3.min.css')?>" rel="stylesheet">

<body id="page-top">
	<!-- Page Wrapper -->
	<div id="wrapper">
		<!-- Sidebar -->
		<ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">
			<!-- Sidebar - Brand -->
			<a class="sidebar-brand d-flex align-items-center justify-content-center" href="<?php echo base_url('index.php/dashboard') ?>">
				<div class="sidebar-brand-icon rotate-n-15">
					<i class="fas fa-coffee"></i>
				</div>
				<div class="sidebar-brand-text mx-3">Coffe<sup>@</sup>Ditto</div>
			</a>
			<!-- Divider -->
			<hr class="sidebar-divider my-0">
			<!-- Nav Item - Dashboard -->
			<li class="nav-item active">
				<a class="nav-link" href="<?php echo base_url('index.php/dashboard') ?>">
					<i class="fas fa-fw fa-tachometer-alt"></i>
					<span>Dashboard</span>
				</a>
			</li>
			<li class="nav-item">
				<a class="nav-link" href="<?php echo base_url('index.php/laporan_keluar') ?>">
					<i class="fas fa-fw fa-box-open"></i>
					<span>Laporan Keluar</span>
				</a>
			</li>
			<li class="nav-item">
				<a class="nav-link" href="<?php echo base_url('index.php/laporan_masuk') ?>">
					<i class="fas fa-fw fa-box-open"></i>
					<span>Laporan Masuk</span>
				</a>
			</li>
			
			<hr class="sidebar-divider">
			<!-- Heading -->
			<div class="sidebar-heading">
				Master
			</div>
			<li class="nav-item">
				<a class="nav-link" href="<?php echo base_url('index.php/menu') ?>">
					<i class="fas fa-fw fa-mug-hot"></i>
					<span>Master Menu</span>
				</a>
			</li>
			<!-- <li class="nav-item">
				<a class="nav-link" href="<!?php echo base_url('index.php/Periode') ?>">
					<i class="fas fa-fw fa-box-open"></i>
					<span>Daftar Periode</span>
				</a>
			</li> -->
			<li class="nav-item">
				<a class="nav-link" href="<?php echo base_url('index.php/bahan') ?>">
					<i class="fas fa-fw fa-box-open"></i>
					<span>Master Bahan</span>
				</a>
			</li>
			<li class="nav-item">
				<a class="nav-link" href="<?php echo base_url('index.php/stok_bahan') ?>">
					<i class="fas fa-fw fa-box-open"></i>
					<span>Stok Bahan</span>
				</a>
			</li>
			<li class="nav-item">
				<a class="nav-link" href="<?php echo base_url('index.php/unit_group') ?>">
					<i class="fas fa-fw fa-box-open"></i>
					<span>Master Unit</span>
				</a>
			</li>
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
					<!-- Topbar Navbar -->
					<ul class="navbar-nav ml-auto">
						<div class="topbar-divider d-none d-sm-block"></div>
						<!-- Nav Item - User Information -->
						<li class="nav-item">
							<a class="nav-link" href="<?php echo base_url('index.php/auth/logout') ?>" id="userDropdown">
								<span class="mr-2 d-none d-lg-inline text-gray-600 small">Logout</span>
								<i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-gray-400"></i>
							</a>
                		</li>
                	</ul>
                </nav>
                <!-- End of Topbar -->
                <!-- Begin Page Content -->
                <div class="container-fluid">
                	<!-- CONTENT -->
                	<?php $this->load->view($content); ?>
                </div>
                <!-- /.container-fluid -->
            </div>
            <!-- End of Main Content -->

            <!-- Footer -->
            <footer class="sticky-footer bg-white">
            	<div class="container my-auto">
            		<div class="copyright text-center my-auto">
            			<span>Copyright &copy; Coffe@Ditto</span>
            		</div>
            	</div>
            </footer>
            <!-- End of Footer -->
        </div>
        <!-- End of Content Wrapper -->
    </div>
    <!-- End of Page Wrapper -->
    <!-- Scroll to Top Button-->
    <a class="scroll-to-top rounded" href="#page-top">
    	<i class="fas fa-angle-up"></i>
    </a>

    <!-- Core plugin JavaScript-->
    <script src="<?php echo base_url('assets/vendor/jquery-easing/jquery.easing.min.js') ?>"></script>
    <!-- Custom scripts for all pages-->
    <script src="<?php echo base_url('assets/js/sb-admin-2.min.js') ?>"></script>
    <!-- Page level plugins -->
    <script src="<?php echo base_url('assets/vendor/chart.js/Chart.min.js') ?>"></script>
    <!-- Bootstrap core JavaScript-->
	<script src="<?php echo base_url('assets/asetku/bootstrap/js/bootstrap.min.js')?>"></script>

</body>
</html>
