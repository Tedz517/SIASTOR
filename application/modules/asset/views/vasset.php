<!-- basic table  Start -->
<div class="page-header">
	<div class="row">
		<div class="col-md-6 col-sm-12">
			<div class="title">
				<h4>Asset</h4>
			</div>
			<nav aria-label="breadcrumb" role="navigation">
				<ol class="breadcrumb">
					<li class="breadcrumb-item">
						<a href="dashboard">Beranda</a>
					</li>
					<li class="breadcrumb-item active" aria-current="page">
						Asset
					</li>
				</ol>
			</nav>
		</div>
	</div>
</div>
<div class="pd-20 card-box mb-30">
	<div class="clearfix mb-20">
		<div class="pull-left">
			<h4 class="text-black h4">Asset Kantor</h4>
		</div>
		<div class="box-header" id="t_table">
			<h3 class="box-title">&nbsp;</h3>
			<div class="box-tools">
				<span class="no-margin pull-right">
					<button class="btn btn-sm btn-primary" type="button" id="add_asset"><i class="fa fa-plus"></i> Tambah</button>
					<button class="btn btn-sm btn-success" type="button" id="show_asset"><i class="fa fa-thumbs-up"></i> Aktifkan</button>
            		<button class="btn btn-sm btn-warning" type="button" id="hide_asset"><i class="fa fa-thumbs-down"></i> Non-Aktifkan</button>
					<button class="btn btn-sm btn-danger" type="button" id="delete_asset"><i class="fa fa-times"></i> Hapus</button>
				</span>
			</div>
		</div>
	</div>
	<div id="t_table">
		<table id="t_asset"></table>
		<div id="pager_asset"></div>
	</div>
	<div id="t_form_add">
		<form action="javascript:;" method="post" class="form-horizontal" id="form_add">
			<div class="alert alert-success" id="success">
                <button class="close" data-close="alert"></button>
                Berhasil! Terima kasih.
            </div>
            <div class="alert alert-danger" id="error">
                <button class="close" data-close="alert"></button>
                Gagal! Silakkan cek kembali.
            </div>
			<div class="form-group row">
				<label class="col-sm-12 col-md-2 col-form-label">Kode Asset</label>
				<div class="col-sm-12 col-md-10">
					<input name="kode_aset" type="number" id="kode_aset" class="form-control" required="required">
					<input name="id" type="hidden" id="id">
				</div>
			</div>
			<div class="form-group row">
				<label class="col-sm-12 col-md-2 col-form-label">Nama Asset</label>
				<div class="col-sm-12 col-md-10">
					<input name="nama_aset" type="text" id="nama_aset" class="form-control" required="required">
				</div>
			</div>
			<hr />
			<div class="form-actions text-center">
				<div>
					<a href="javascript:" class="btn btn-secondary" id="cancel1"><i class="fa fa-arrow-left"></i> Kembali</a>
					<button type="submit" class="btn btn-success"><i class="fa fa-edit"></i> Proses</button>
				</div>
			</div>
		</form>
	</div>
	<div id="t_form_edit">
		<form action="javascript:;" method="post" class="form-horizontal" id="form_edit">
			<div class="alert alert-success" id="success">
				<button class="close" data-close="alert"></button>
					Berhasil! Terima kasih.
			</div>
			<div class="alert alert-danger" id="error">
				<button class="close" data-close="alert"></button>
					Gagal! Silakkan cek kembali.
			</div>
			<div class="form-group row">
				<label class="col-sm-12 col-md-2 col-form-label">Nama Asset</label>
				<div class="col-sm-12 col-md-10">
					<input name="nama_aset" type="text" id="nama_aset" class="form-control" required="required">
					<input name="id" type="hidden" id="id">
				</div>
			</div>
			<hr />
			<div class="form-actions text-center">
				<div>
					<a href="javascript:" class="btn btn-info" id="cancel2"><i class="fa fa-arrow-left"></i> Kembali</a>
					<button type="submit" class="btn btn-success"><i class="fa fa-edit"></i> Proses</button>
				</div>
			</div>
		</form>
	</div>
</div>
<!-- basic table  End -->
