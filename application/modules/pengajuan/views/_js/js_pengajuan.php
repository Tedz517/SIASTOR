<script>
	$(document).ready(function() {
		// ID
		var tableId = 't_submit';
		var t_table = $('div#t_table');
		var t_form_add = $('#t_form_add');
		var t_form_edit = $('#t_form_edit');

		var add_submit = $('#add_submit');
		var edit_submit = $('#edit_submit');
		var delete_submit = $('#delete_submit');

		var form1 = $('#form_add');
		var error1 = $('.alert-danger', form1);
		var success1 = $('.alert-success', form1);

		var form2 = $('#form_edit');
		var error2 = $('.alert-danger', form2);
		var success2 = $('.alert-success', form2);

		var form_control = $('.form-control');

		var cancel1 = $('#cancel1');
		var cancel2 = $('#cancel2');

		// HIDE ELEMENT
		t_form_add.hide();
		t_form_edit.hide();
		success1.hide();
		success2.hide();
		error1.hide();
		error2.hide();

		// BEGIN AJAX START
		var block = false;

		$(document).ajaxStart(function() {
			if (block == false) {
				$.blockUI({
					message: '<div style="padding:5px 0;">Sedang Proses, Mohon Tunggu...</div>',
					css: {
						backgroundColor: '#fff',
						color: '#000',
						fontSize: '12px'
					}
				});
			}
		}).ajaxStop($.unblockUI);

		// CONFIRMATION
		function confirmation(titles, contents) {
            swal({
                title: titles,
				text: contents,
				type: 'success',
				confirmButtonClass: 'btn btn-warning',
                });
        }

		

		function confirmAlert(titles, contents, url) {
			swal({
				title: titles,
				text: contents,
				type: 'error',
				confirmButtonClass: 'btn btn-danger',
				backgroundDismiss: false,
				confirm: function() {
					window.location = url;
				}
			});
		}

		// DATEPICKER
		$('input.datepicker').datetimepicker({
			format: 'DD/MM/YYYY'
		});

		$('input.datepicker').inputmask('99/99/9999', {
			'placeholder': 'dd/mm/yyyy'
		});

		// NUMERIC
		$('input.numeric').numeric({
			decimal: false,
			negative: false
		});

		// BEGIN MULTIPLE SCREEN
		var max_fields_x = 16;
		var x = 2;

		$(document).on('click', '#add_barang', function(e) {
			e.preventDefault();

			if (x < max_fields_x) {
				$('#screen_barang', form1).append('<span><div class="row" style="margin-bottom: 5px;"><div class="col-md-5"><div class="form-group"><div name="id_barang" class="form-group" id="id_barang" required><select name="id_barang[]" class="custom-select"><option value="">--Pilih--</option><?php foreach ($nama_barang as $nb) { ?><option value="<?php echo $nb['id']; ?>"><?php echo $nb['nama_barang']; ?></option><?php } ?></select></div></div></div><div class="col-md-2"><div class="form-group"><input name="stok_sebelum[]" type="number" id="stok_sebelum" class="form-control" required="required"></div></div><div class="col-md-2"><div class="form-group"><input name="stok_terpakai[]" type="number" id="stok_terpakai" class="form-control" required="required"></div></div><div class="col-md-2"><div class="form-group"><div name="stok_diajukan" class="form-group" id="stok_diajukan" required><select name="stok_diajukan[]" class="custom-select"><option value="">--Pilih--</option><option value="5">5</option><option value="10">10</option><option value="25">25</option><option value="50">50</option><option value="75">75</option><option value="100">100</option><option value="150">150</option><option value="200">200</option><option value="250">250</option></select></div></div></div><div class="col-md-1"><a href="javascript:;" class="badge badge-danger btn-sm" id="remove">Hapus</a></div></span>');
				x++;
			}
		});

		$('#screen_barang', form1).on('click', '#remove', form1, function(e) {
			e.preventDefault();
			$(this).closest('span').remove();
			x--;
		});

		// BEGIN FORM ADD VALIDATION

		$(document).on('click', '#add_submit', function() {
			t_table.fadeOut();
			t_form_add.fadeIn();
			form1.trigger('reset');
			form_control.parent('.input-icon').children('i').removeClass('fa-check');
			form_control.parent('.input-icon').children('i').removeClass('fa-warning');
			form_control.closest('.form-group').removeClass('has-error');
			form_control.closest('.form-group').removeClass('has-success');
		});

		form1.validate({
			errorElement: 'span', //default input error message container
			errorClass: 'help-block help-block-error', // default input error message class
			focusInvalid: false, // do not focus the last invalid input
			ignore: '', // validate all fields including form hidden input

			messages: {
				//
			},

			rules: {
				branch_code: {
					required: true
				},
				id_barang: {
					required: true
				},
				stok_sebelum: {
					required: true
				},
				stok_terpakai: {
					required: true
				},
				stok_diajukan: {
					required: true
				},
				keterangan: {
					required: true
				}
			},

			invalidHandler: function(event, validator) {
				// display error alert on form submit
				success1.fadeOut();
				error1.fadeIn();
				hbody.animate({
					scrollTop: error1.offset().top
				}, 300);
			},

			errorPlacement: function(error, element) {
				// render error placement for each input type
				var icon = $(element).parent('.input-icon').children('i');
				icon.removeClass('fa-check').addClass("fa-warning");
				icon.attr("data-original-title", error.text()).tooltip({
					'container': 'body'
				});
			},

			highlight: function(element) {
				$(element).closest('.form-group').removeClass("has-success").addClass('has-error'); // set error class to the control group   
			},

			unhighlight: function(element) {
				// revert the change done by hightlight
			},

			success: function(label, element) {
				var icon = $(element).parent('.input-icon').children('i');
				$(element).closest('.form-group').removeClass('has-error').addClass('has-success'); // set success class to the control group
				icon.removeClass("fa-warning").addClass("fa-check");
			},

			submitHandler: function(form) {
				error1.fadeOut();
				success1.fadeOut();

				form1.ajaxSubmit({
					data: 'POST',
					url: 'pengajuan/papengajuan',
					dataType: 'json',
					success: function(response) {
						var result = response.result;
						var message = response.message;

						if (result == true) {
							confirmation('Sukses!', message);
							cancel1.trigger('click');
							dTreload(tableId);
						} else {
							confirmAlert('Gagal!', message);
						}
					},
					error: function() {
						confirmAlert('Peringatan!', 'Tidak ada koneksi internet.');
					}
				});
			}
		});


		// JQGRID BRANCH
		$('#' + tableId).jqGrid({
			url: 'pengajuan/apipengajuan',
			datatype: 'json',
			mtype: 'POST',
			height: 500,
			rowNum: 100,
			autowidth: true,
			shrinkToFit: true,
			rowList: [15, 30, 60, 120, 240],
			colNames: ['ID', 'Cabang', 'Pemohon', 'Tanggal Pengajuan', 'Status', 'Tindakan'],
			colModel: [{
					name: 'id',
					index: 'id',
					hidden: true
				},
				{
					name: 'branch_name',
					index: 'branch_name',
					align: 'center'
				},
				{
					name: 'name',
					index: 'name',
                    align: 'center'
				},
                {
					name: 'tanggal_pengajuan',
					index: 'tanggal_pengajuan',
                    align: 'center'
				},
				{
					name: 'status',
					index: 'status',
					align: 'center'
				},
				{
					name: 'action',
					index: 'action',
					align: 'center'
				}
			],
			pager: '#pager_submit',
			viewrecords: true,
			multiselect: true,
			grouping: true,
			sortname: 'branch_code'
		});

		cancel1.click(function() {
			t_table.fadeIn();
			t_form_add.fadeOut();
			error1.fadeOut();
			success1.fadeOut();
			error2.fadeOut();
			success2.fadeOut();
		});

		$(document).on('click', '#edit_submit', function() {
			t_table.fadeOut();
			t_form_add.fadeOut();
			t_form_edit.fadeIn();

			form2.trigger('reset');
			form_control.parent('.input-icon').children('i').removeClass('fa-check');
			form_control.parent('.input-icon').children('i').removeClass('fa-warning');
			form_control.closest('.form-group').removeClass('has-error');

			var b_id = $(this).attr('b_id');

			var e_id = $('#id', form2);
			var e_nama_aset = $('#nama_aset', form2);

			$.ajax({
				type: 'POST',
				url: 'pengajuan/get_pengajuan_by_id',
				data: {
					b_id: b_id
				},
				dataType: 'json',
				success: function(response) {
					var id = response.id;
					var nama_aset = response.nama_aset;

					e_id.val(id);
					e_nama_aset.val(nama_aset);
				},
				error: function() {
					confirmAlert('Peringatan!', 'Tidak ada koneksi internet.');
					t_table.fadeIn();
					t_form_add.fadeOut();
					t_form_edit.fadeOut();
				}
			});
		});

		cancel2.click(function() {
			t_table.fadeIn();
			t_form_edit.fadeOut();
			error1.fadeOut();
			success1.fadeOut();
			error2.fadeOut();
			success2.fadeOut();
		});

		// BEGIN FORM EDIT VALIDATION
		form2.validate({
			errorElement: 'span', //default input error message container
			errorClass: 'help-block help-block-error', // default input error message class
			focusInvalid: false, // do not focus the last invalid input
			ignore: '', // validate all fields including form hidden input

			messages: {
				//
			},

			rules: {
				nama_aset: {
					required: true
				}
			},

			invalidHandler: function(event, validator) {
				// display error alert on form submit
				success2.fadeOut();
				error2.fadeIn();
				hbody.animate({
					scrollTop: error2.offset().top
				}, 500);
			},

			errorPlacement: function(error, element) {
				// render error placement for each input type
				var icon = $(element).parent('.input-icon').children('i');
				icon.removeClass('fa-check').addClass("fa-warning");
				icon.attr("data-original-title", error.text()).tooltip({
					'container': 'body'
				});
			},

			highlight: function(element) {
				$(element).closest('.form-group').removeClass("has-success").addClass('has-error'); // set error class to the control group   
			},

			unhighlight: function(element) {
				// revert the change done by hightlight
			},

			success: function(label, element) {
				var icon = $(element).parent('.input-icon').children('i');
				$(element).closest('.form-group').removeClass('has-error').addClass('has-success'); // set success class to the control group
				icon.removeClass("fa-warning").addClass("fa-check");
			},

			submitHandler: function(form) {
				error2.fadeOut();
				success2.fadeOut();

				$.ajax({
					type: 'POST',
					url: 'submit/pesubmit',
					dataType: 'json',
					data: form2.serialize(),
					success: function(response) {
						var result = response.result;
						var message = response.message;

						if (result == true) {
							confirmation('Sukses!', message);
							cancel2.trigger('click');
							dTreload(tableId);
						} else {
							confirmation('Gagal!', message);
						}
					},
					error: function() {
						confirmAlert('Peringatan!', 'Tidak ada koneksi internet.');
					}
				});
			}
		});

		// BEGIN SHOW BRANCH
		$(document).on('click', '#show_submit', function() {
			var rowKey = $('#' + tableId).getGridParam('selrow');

			if (!rowKey) {
				alert('Item belum dipilih');
			} else {
				var conf = confirm('Data ingin diaktifkan?');
				var selectedIDs = $('#' + tableId).getGridParam('selarrrow');

				if (conf) {
					$.ajax({
						type: 'POST',
						url: 'submit/pssubmit',
						data: {
							object: selectedIDs
						},
						dataType: 'json',
						success: function(response) {
							var result = response.result;
							var message = response.message;

							if (result == true) {
								confirmation('Sukses!', message);
								dTreload(tableId);
							} else {
								confirmation('Gagal!', message);
							}
						},
						error: function() {
							confirmAlert('Peringatan!', 'Tidak ada koneksi internet.');
						}
					});
				}
			}
		});

		// BEGIN HIDE BRANCH
		$(document).on('click', '#hide_submit', function() {
			var rowKey = $('#' + tableId).getGridParam('selrow');

			if (!rowKey) {
				alert('Item belum dipilih');
			} else {
				var conf = confirm('Data ingin di-non-aktifkan?');
				var selectedIDs = $('#' + tableId).getGridParam('selarrrow');

				if (conf) {
					$.ajax({
						type: 'POST',
						url: 'submit/phsubmit',
						data: {
							object: selectedIDs
						},
						dataType: 'json',
						success: function(response) {
							var result = response.result;
							var message = response.message;

							if (result == true) {
								confirmation('Sukses!', message);
								dTreload(tableId);
							} else {
								confirmation('Gagal!', message);
							}
						},
						error: function() {
							confirmAlert('Peringatan!', 'Tidak ada koneksi internet.');
						}
					});
				}
			}
		});

		// BEGIN DELETE BRANCH
		$(document).on('click', '#delete_submit', function() {
			var rowKey = $('#' + tableId).getGridParam('selrow');

			if (!rowKey) {
				alert('Item belum dipilih');
			} else {
				var conf = confirm('Data ingin dihapus?');
				var selectedIDs = $('#' + tableId).getGridParam('selarrrow');

				if (conf) {
					$.ajax({
						type: 'POST',
						url: 'submit/pdsubmit',
						data: {
							object: selectedIDs
						},
						dataType: 'json',
						success: function(response) {
							var result = response.result;
							var message = response.message;

							if (result == true) {
								confirmation('Sukses!', message);
								dTreload(tableId);
							} else {
								confirmation('Gagal!', message);
							}
						},
						error: function() {
							confirmAlert('Peringatan!', 'Tidak ada koneksi internet.');
						}
					});
				}
			}
		});

		
	});

	function dTreload(tableId) {
		$('#' + tableId).trigger('reloadGrid');
	}
</script>