<script>
	$(document).ready(function() {
		// ID
		var tableId = 't_asset';
		var t_table = $('div#t_table');
		var t_form_add = $('#t_form_add');
		var t_form_edit = $('#t_form_edit');

		var add_asset = $('#add_asset');
		var edit_asset = $('#edit_asset');
		var delete_asset = $('#delete_asset');

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


		// BEGIN FORM ADD VALIDATION
		form1.validate({
			errorElement: 'span', //default input error message container
			errorClass: 'help-block help-block-error', // default input error message class
			focusInvalid: false, // do not focus the last invalid input
			ignore: '', // validate all fields including form hidden input

			messages: {
				//
			},

			rules: {
				kode_aset: {
					required: true
				},
				nama_aset: {
					required: true
				},
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

				$.ajax({
					type: 'POST',
					url: 'asset/paasset',
					dataType: 'json',
					data: form1.serialize(),
					success: function(response) {
						var result = response.result;
						var message = response.message;

						if (result == true) {
							confirmation('Sukses!', message);
							cancel1.trigger('click');
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


		// JQGRID BRANCH
		$('#' + tableId).jqGrid({
			url: 'asset/apiasset',
			datatype: 'json',
			mtype: 'POST',
			height: 500,
			rowNum: 100,
			autowidth: true,
			shrinkToFit: true,
			rowList: [15, 30, 60, 120, 240],
			colNames: ['ID', 'Kode Asset', 'Nama Asset', 'Status', 'Tindakan'],
			colModel: [{
					name: 'id',
					index: 'id',
					hidden: true
				},
				{
					name: 'kode_aset',
					index: 'kode_aset',
					align: 'center'
				},
				{
					name: 'nama_aset',
					index: 'nama_aset'
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
			pager: '#pager_asset',
			viewrecords: true,
			multiselect: true,
			grouping: true,
			sortname: 'kode_aset'
		});

		$(document).on('click', '#add_asset', function() {
			t_table.fadeOut();
			t_form_add.fadeIn();
			form1.trigger('reset');
			form_control.parent('.input-icon').children('i').removeClass('fa-check');
			form_control.parent('.input-icon').children('i').removeClass('fa-warning');
			form_control.closest('.form-group').removeClass('has-error');
			form_control.closest('.form-group').removeClass('has-success');
		});

		cancel1.click(function() {
			t_table.fadeIn();
			t_form_add.fadeOut();
			error1.fadeOut();
			success1.fadeOut();
			error2.fadeOut();
			success2.fadeOut();
		});

		$(document).on('click', '#edit_asset', function() {
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
				url: 'asset/get_asset_by_id',
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
					url: 'asset/peasset',
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
		$(document).on('click', '#show_asset', function() {
			var rowKey = $('#' + tableId).getGridParam('selrow');

			if (!rowKey) {
				alert('Item belum dipilih');
			} else {
				var conf = confirm('Data ingin diaktifkan?');
				var selectedIDs = $('#' + tableId).getGridParam('selarrrow');

				if (conf) {
					$.ajax({
						type: 'POST',
						url: 'asset/psasset',
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
		$(document).on('click', '#hide_asset', function() {
			var rowKey = $('#' + tableId).getGridParam('selrow');

			if (!rowKey) {
				alert('Item belum dipilih');
			} else {
				var conf = confirm('Data ingin di-non-aktifkan?');
				var selectedIDs = $('#' + tableId).getGridParam('selarrrow');

				if (conf) {
					$.ajax({
						type: 'POST',
						url: 'asset/phasset',
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
		$(document).on('click', '#delete_asset', function() {
			var rowKey = $('#' + tableId).getGridParam('selrow');

			if (!rowKey) {
				alert('Item belum dipilih');
			} else {
				var conf = confirm('Data ingin dihapus?');
				var selectedIDs = $('#' + tableId).getGridParam('selarrrow');

				if (conf) {
					$.ajax({
						type: 'POST',
						url: 'asset/pdasset',
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