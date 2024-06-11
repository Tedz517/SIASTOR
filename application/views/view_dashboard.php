<!DOCTYPE html>
<html>
	<head>
		<!-- Basic Page Info -->
		<meta charset="utf-8" />
		<title>Siastor - Sistem Asset & Inventory Kantor</title>

		<!-- Site favicon -->
		<link
			rel="apple-touch-icon"
			sizes="180x180"
			href="assets/vendors/images/apple-touch-icon.png"
		/>

		<!-- Mobile Specific Metas -->
		<meta
			name="viewport"
			content="width=device-width, initial-scale=1, maximum-scale=1"
		/>

		<base href="<?php echo base_url(); ?>">
		
		<!-- Google Font -->
		<link
			href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap"
			rel="stylesheet"
		/>
		<!-- CSS -->
		<link rel="stylesheet" type="text/css" href="assets/vendors/styles/core.css" />
		<link
			rel="stylesheet"
			type="text/css"
			href="assets/vendors/styles/icon-font.min.css"
		/>
		<link
			rel="stylesheet"
			type="text/css"
			href="assets/src/plugins/datatables/css/dataTables.bootstrap4.min.css"
		/>
		<link
			rel="stylesheet"
			type="text/css"
			href="assets/src/plugins/datatables/css/responsive.bootstrap4.min.css"
		/>
		<link rel="stylesheet" type="text/css" href="assets/vendors/styles/style.css" />

		<!-- BEGIN PAGE STYLE -->
		<?php echo $this->load->view($csspage); ?>
		<!-- END PAGE STYLE -->
	</head>
	<body>
		<div class="pre-loader">
			<?php echo $this->load->view('view_loader'); ?>
		</div>

		<div class="header">
		<?php echo $this->load->view('view_navigation'); ?>
		</div>

		<div class="right-sidebar">
			<div class="right-sidebar-body customscroll">
				<div class="right-sidebar-body-content">
					<h4 class="weight-600 font-18 pb-10">Sidebar Background</h4>
					<div class="sidebar-btn-group pb-30 mb-10">
						<a
							href="javascript:void(0);"
							class="btn btn-outline-primary sidebar-light"
							>White</a
						>
					</div>
				</div>
			</div>
		</div>

		<div class="left-side-bar">
		<?php echo $this->load->view('view_sidebar'); ?>
		</div>
		<div class="mobile-menu-overlay"></div>

		<div class="main-container">
			<div class="xs-pd-20-10 pd-ltr-20">
				<?php echo $this->load->view($body); ?>
			</div>
			<div class="footer-wrap pd-20 mb-20 card-box">
				<?php echo $this->load->view('view_footer'); ?>
			</div>
		</div>
		<!-- js -->
		<script src="assets/vendors/scripts/core.js"></script>
		<script src="assets/vendors/scripts/script.min.js"></script>
		<script src="assets/vendors/scripts/process.js"></script>
		<script src="assets/vendors/scripts/layout-settings.js"></script>
		<!-- BEGIN JAVASCRIPT LIBRARY -->
		<?php echo $this->load->view($jslib); ?>
		<!-- END JAVASCRIPT LIBRARY -->
		<!-- BEGIN SCRIPT -->
		<?php echo $this->load->view($jsscript); ?>
		<!-- END SCRIPT -->
	</body>
</html>
