<!-- Default Basic Forms Start -->
<div class="page-header">
	<div class="row">
		<div class="col-md-6 col-sm-12">
			<div class="title">
				<h4>Pengajuan Buku</h4>
			</div>
			<nav aria-label="breadcrumb" role="navigation">
				<ol class="breadcrumb">
					<li class="breadcrumb-item">
						<a href="dashboard">Beranda</a>
					</li>
					<li class="breadcrumb-item active" aria-current="page">
						Pengajuan Buku
					</li>
				</ol>
			</nav>
		</div>
	</div>
</div>
<div class="pd-20 card-box mb-30">
	<div class="clearfix mb-20">
		<div class="pull-left">
			<p>Daftar Pengajuan Buku</p>
		</div>
		<div class="box-header" id="t_table">
			<h3 class="box-title">&nbsp;</h3>
			<div class="box-tools">
				<span class="no-margin pull-right">
					<button class="btn btn-sm btn-primary" type="button" id="add_submit"><i class="fa fa-plus"></i> Tambah</button>
					<button class="btn btn-sm btn-danger" type="button" id="delete_submit"><i class="fa fa-times"></i> Hapus</button>
				</span>
			</div>
		</div>
	</div>
	<div id="t_table">
		<table id="t_submit"></table>
		<div id="pager_submit"></div>
	</div>
	<div id="t_form_add">
		<form action="javascript:;" method="post" enctype="multipart/form-data" class="form-horizontal" id="form_add">
			<div class="alert alert-success" id="success">
                <button class="close" data-close="alert"></button>
                Berhasil! Terima kasih.
            </div>
            <div class="alert alert-danger" id="error">
                <button class="close" data-close="alert"></button>
                Gagal! Silakkan cek kembali.
            </div>
			<div class="row">
				<div class="col-md-6 col-sm-12">
					<div class="form-group">
						<label>Cabang</label>
						<input name="id" type="hidden" id="id">
						<div name="branch_code" class="form-group" id="branch_code" required>
                    		<select name="branch_code" class="custom-select">
                        		<option value="">--Pilih--</option>
								<?php foreach ($branch_code as $bn) { ?>
                      			<option value="<?php echo $bn['branch_code']; ?>"><?php echo $bn['branch_name']; ?></option>
                    			<?php } ?>
                    		</select>
                		</div>
					</div>
				</div>
				<div class="col-md-6 col-sm-12">
					<div class="form-group">
						<label>Tanggal Pengajuan</label>
                      	<input name="tanggal_pengajuan" type="text" id="tanggal_pengajuan" class="form-control date-picker" required>
					</div>
				</div>
			</div>
			<hr />
			<center><a class="btn btn-primary btn-sm" href="javascript:;" id="add_barang"><i class="fa fa-plus"></i> Tambah Barang </a></center>
			&nbsp;
			<div class="row" id="row_barang" style="margin-bottom: 5px;">
				<div class="col-md-5">
					<div class="form-group">
						<label>Nama Barang</label>
						<div name="id_barang" class="form-group-row" id="id_barang" required>
							<select name="id_barang[]" class="custom-select">
                        		<option value="">--Pilih--</option>
							<?php foreach ($nama_barang as $nb) { ?>
                      			<option value="<?php echo $nb['id']; ?>"><?php echo $nb['nama_barang']; ?></option>
                    		<?php } ?>
                    		</select>
						</div>
					</div>
				</div>
				<div class="col-md-2">
					<div class="form-group">
						<label>Stok Sebelumnya</label>
						<input name="stok_sebelum[]" type="number" id="stok_sebelum" class="form-control" required="required">
					</div>
				</div>
				<div class="col-md-2">
					<div class="form-group">
						<label>Stok Terpakai</label>
						<input name="stok_terpakai[]" type="number" id="stok_terpakai" class="form-control" required="required">
					</div>
				</div>
				<div class="col-md-2">
					<div class="form-group">
						<label>Pengajuan Stok</label>
						<div name="stok_diajukan" class="form-group" id="stok_diajukan" required>
                    		<select name="stok_diajukan[]" class="custom-select">
								<option value="">--Pilih--</option>
								<option value="5">5</option>
								<option value="10">10</option>
								<option value="25">25</option>
								<option value="50">50</option>
								<option value="75">75</option>
								<option value="100">100</option>
								<option value="150">150</option>
								<option value="200">200</option>
								<option value="250">250</option>
                    		</select>
                		</div>
					</div>
				</div>
			</div>
			<div id="screen_barang"></div>
			<div class="form-group row">
                <label class="col-sm-12 col-md-2 col-form-label">Keterangan</label>
                <div name="keterangan" class="col-sm-12 col-md-12" id="keterangan" required>
                    <select name="keterangan" class="custom-select col-12">
                        <option value="">--Pilih--</option>
                      	<option value="Saya siap membawa potongan buku anggota sesuai jumlah yang telah habis digunakan sebagai lampiran dan bukti sekaligus pengantar permohonan stok buku.">Saya siap membawa potongan buku anggota sesuai jumlah yang telah habis digunakan sebagai lampiran dan bukti sekaligus pengantar permohonan stok buku.</option> 
						<option value="Permintaan stok hanya untuk anggota baru, saya tidak akan melampirkan potongan lembar buku habis.">Permintaan stok hanya untuk anggota baru, saya tidak akan melampirkan potongan lembar buku habis.</option>
						<option value="Saya tidak melampirkan apapun, karena tidak mengajukan permintaan Buku SPPA/SIFITRI.">Saya tidak melampirkan apapun, karena tidak mengajukan permintaan Buku SPPA/SIFITRI.</option>
                    </select>
                </div>
            </div>
			<hr />
			<div class="form-group">
				<label>Input ID Anggota</label>
				<input type="file" name="userfile" class="form-control-file form-control height-auto"/>
			</div>
			<!-- browse file -->
			<div class="form-actions text-center">
				<div>
					<a href="javascript:" class="btn btn-secondary btn-sm" id="cancel1"><i class="fa fa-arrow-left"></i> Kembali</a>
					<button type="submit" class="btn btn-success btn-sm"><i class="fa fa-edit"></i> Proses</button>
				</div>
			</div>
		</form>
	</div>
</div>