<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Pengajuan extends MST_Controller
{

    function __construct()
    {
        parent::__construct();
        $this->auth->restrict();
        $this->load->config('path');
        $this->load->model('model_pengajuan');
        $this->load->library(array('phpexcel', 'PHPExcel/IOFactory'));
        @date_default_timezone_set('Asia/Jakarta');
    }

    function index()
    {
        $this->pengajuan();
    }

    function pengajuan()
    {
        $data = array();

        // URI SEGMENT
        $uri = $this->getUri();
        $url = $this->getSubUri($uri);

        // MAIN PAGE
        $data['body'] = 'pengajuan/vpengajuan';
        $data['title'] = 'Pengajuan BUKU | Siastor';
        $data['f_title'] = 'Pengajuan Buku';
        $data['s_title'] = 'pengajuan buku';
        $data['s_breadcrumb'] = 'Pengajuan BUKU';
        $data['joinDate'] = $this->getJoinDate();

        // LIBRARY
        $data['csspage'] = 'pengajuan/_css/csspage_pengajuan';
        $data['jslib'] = 'pengajuan/_js/jslib_pengajuan';
        $data['jsscript'] = 'pengajuan/_js/js_pengajuan';

        // LOAD DATA
        $data['uri'] = $uri;
        $data['menu'] = $this->show_menu(1);
        $data['suburi'] = $this->getSubUri($uri);
        $data['subparent'] = $this->getSubParent($url['subparent']);
        $data['branch_code'] = $this->model_pengajuan->get_branch_name();
        $data['nama_barang'] = $this->model_pengajuan->get_barang();

        $this->load->vars($data);
        $this->load->view('view_dashboard');
    }

    function apipengajuan()
    {
        if (!$this->input->is_ajax_request()) {
            redirect(base_url('pengajuan'));
        }

        $page = isset($_POST['page']) ? $_POST['page'] : '';
        $limit_rows = isset($_POST['rows']) ? $_POST['rows'] : '';
        $sidx = isset($_POST['sidx']) ? $_POST['sidx'] : '';
        $sord = isset($_POST['sord']) ? $_POST['sord'] : '';
        $totalrows = isset($_POST['totalrows']) ? $_POST['totalrows'] : FALSE;

        if ($totalrows) {
            $limit_rows = $totalrows;
        }

        $sum = $this->model_pengajuan->apipengajuan('', '', '', '');

        $count = count($sum);

        if ($count > 0) {
            $total_pages = ceil($count / $limit_rows);
        } else {
            $total_pages = 0;
        }

        if ($page > $total_pages) {
            $page = $total_pages;
        }

        $start = ($limit_rows * $page) - $limit_rows;

        if ($start < 0) {
            $start = 0;
        }

        $result = $this->model_pengajuan->apipengajuan($sidx, $sord, $limit_rows, $start);

        $response = array();

        $response['page'] = $page;
        $response['total'] = $total_pages;
        $response['records'] = $count;

        foreach ($result as $row) {
            $id = $row['id'];
            $branch_name = $row['branch_name'];
            $name = $row['name'];
            $tanggal_pengajuan = $row['tanggal_pengajuan'];
            $isactive = $row['status'];

            if ($isactive == '0') {
                $status = '<span class="badge badge-primary">PENGAJUAN</span>';
            } elseif ($isactive == '1') {
                $status = '<span class="badge badge-warning">KIRIM SURAT JALAN</span>';
            } elseif ($isactive == '2') {
                $status = '<span class="badge badge-success">DISETUJUI</span>';
            }

            $action = '<a href="javascript:;" id="edit_pengajuan" class="badge badge-info" title="Ubah" b_id="' . $id . '"><i class="fa fa-edit"></i> Ubah </a>';

            $response['rows'][] = array(
                'id' => $id,
                'branch_name' => $branch_name,
                'name' => $name,
                'tanggal_pengajuan' => $tanggal_pengajuan,
                'status' => $status,
                'action' => $action
            );
        }

        echo json_encode($response);
    }

    function papengajuan()
    {
        if (!$this->input->is_ajax_request()) {
            redirect(base_url('pengajuan'));
        }

        $branch_code = $this->input->post('branch_code');
        $tanggal_pengajuan = $this->input->post('tanggal_pengajuan');
        $id_barang = $this->input->post('id_barang');
        $stok_sebelum = $this->input->post('stok_sebelum');
        $stok_terpakai = $this->input->post('stok_terpakai');
        $stok_diajukan = $this->input->post('stok_diajukan');
        $keterangan = $this->input->post('keterangan');
 
        $tanggal_pengajuan = datepicker($tanggal_pengajuan);

        $nik = $this->session->userdata('username');
        $created_by = $this->session->userdata('id');
        $created_date = date('Y-m-d H:i:s');

        $confirm = TRUE;

        $DBCore = $this->coreDB();

        $this->form_validation->set_rules('branch_code', 'Cabang', 'required|trim');
        $this->form_validation->set_rules('tanggal_pengajuan', 'Tanggal Pengajuan', 'required|trim');

        if ($this->form_validation->run() == FALSE) {
            // INVALID
            $confirm = FALSE;
            $result = array(
                'result' => FALSE,
                'message' => validation_errors()
            );
        }

        if ($confirm == TRUE) {

            $data1 = array(
                'branch_code' => $branch_code,
                'nik' => $nik,
                'tanggal_pengajuan' => $tanggal_pengajuan,
                'status' => 0,
                'created_by' => $created_by,
                'created_date' => $created_date
            );

            $data2 = array();

            $count_barang = 0;
            
        }

        if ($confirm ==  TRUE) {
            // DATABASE TRANSACTION
            try {
                $this->insert('inv_pengajuan_barang', $data1);

                $id_pengajuan_barang = $this->db->insert_id();

                for ($j = 0; $j < count($id_barang); $j++) {
                    if ($id_barang[$j] <> '') {
                        $data2[] = array(
                            'id_pengajuan_barang' => $id_pengajuan_barang,
                            'id_barang' => $id_barang[$j],
                            'stok_sebelum' => $stok_sebelum[$j],
                            'stok_terpakai' => $stok_terpakai[$j],
                            'stok_diajukan' => $stok_diajukan[$j],
                            'keterangan' => $keterangan,
                            'created_by' => $created_by,
                            'created_date' => $created_date
                        );
        
                        $count_barang++;
                    }
                }
    
                if ($count_barang > 0) {
                    $this->insert_batch('inv_pengajuan_detail', $data2);
                }

                $path = $this->config->item('pfile');
                $location = $this->config->item('plocation');
                $config['upload_path'] = $path;
                $config['allowed_types'] = 'xlsx';
                $config['encrypt_name'] = TRUE;

                $this->upload->initialize($config);

                if ($this->upload->do_upload()) {
                    // UPLOAD SUCCESS
                    $detail = $this->upload->data();
                    $file = $detail['file_name'];

                    try {
                        $inputFileName = $location . $file;
                        $inputFileType = IOFactory::identify($inputFileName);
                        $objReader = IOFactory::createReader($inputFileType);
                        $objPHPExcel = $objReader->load($inputFileName);
                    } catch (Exception $e) {
                        die('Error loading file "' . pathinfo($inputFileName, PATHINFO_BASENAME) . '": ' . $e->getMessage());
                    }
        
                    $sheet = $objPHPExcel->getSheet(0);
                    $highestRow = $sheet->getHighestRow();
                    $highestColumn = $sheet->getHighestColumn();
        
                    $insert_cif = array();
        
                    for ($row = 4; $row <= $highestRow; $row++) {
                        $rowData = $sheet->rangeToArray('A' . $row . ':' . $highestColumn . $row, NULL, TRUE, FALSE);
        
                        $cif_baru = $rowData[0][1];
                        $cif_habis = $rowData[0][2];
                        $cif_sifitri = $rowData[0][3];

                        if ($nik != '') {
                            $insert_cif[] = array(
                                'id_pengajuan_barang' => $id_pengajuan_barang,
                                'cif_baru' => $cif_baru,
                                'cif_habis' => $cif_habis,
                                'cif_sifitri' => $cif_sifitri,
                                'created_by' => $created_by,
                                'created_date' => $created_date
                            );
        
                        }
                    }

                    if (!empty($insert_cif)) {
                        $this->insert_batch('inv_buku_detail', $insert_cif);
                    }
                }

                $result = array(
                    'result' => TRUE,
                    'message' => 'Pengajuan Barang Berhasil ditambahkan.'
                );
            } catch (Exception $e) {
                $result = array(
                    'result' => FALSE,
                    'message' => $e->getMessage()
                );
            }
        }

        echo json_encode($result);
    }

    function import()
    {
        $nik = $this->session->userdata('username');

        $created_by = $this->session->userdata('id');
        $created_date = date('Y-m-d H:i:s');

        $id = md5(sha1($created_by . ' ' . $created_date . ' ' . rand()));

        $confirm = TRUE;

        $DBCore = $this->coreDB();

        $table = 'import_id_anggota';

        $path = $this->config->item('pfile');
        $location = $this->config->item('plocation');
        $config['upload_path'] = $path;
        $config['allowed_types'] = 'xlsx';
        $config['encrypt_name'] = TRUE;

        $this->upload->initialize($config);

        if ($this->upload->do_upload()) {
            // UPLOAD SUCCESS
            $detail = $this->upload->data();
            $orig = $detail['orig_name'];
            $file = $detail['file_name'];

            $insert = array(
                'id' => $id,
                'orig_name' => $orig,
                'file_name' => $file,
                'created_by' => $created_by,
                'created_date' => $created_date
            );
        } else {
            // UPLOAD FAILED
            $msg_err = $this->upload->display_errors();

            if ($msg_err == '<p>You did not select a file to upload.</p>') {
                // IT DOESN'T MATTER
                $orig = '';
                $file = '';
            } else {
                //  FAILED
                $confirm = FALSE;
                $result = array(
                    'result' => FALSE,
                    'message' => $msg_err
                );

                $result = array(
                    'result' => FALSE,
                    'message' => 'Koneksi internet Anda terputus.'
                );
            }
        }

        if ($confirm == TRUE) {
            $table2 = 'inv_buku_detail';

            try {
                $inputFileName = $location . $file;
                $inputFileType = IOFactory::identify($inputFileName);
                $objReader = IOFactory::createReader($inputFileType);
                $objPHPExcel = $objReader->load($inputFileName);
            } catch (Exception $e) {
                die('Error loading file "' . pathinfo($inputFileName, PATHINFO_BASENAME) . '": ' . $e->getMessage());
            }

            $sheet = $objPHPExcel->getSheet(0);
            $highestRow = $sheet->getHighestRow();
            $highestColumn = $sheet->getHighestColumn();

            $insert_cif = array();

            for ($row = 2; $row <= $highestRow; $row++) {
                $rowData = $sheet->rangeToArray('A' . $row . ':' . $highestColumn . $row, NULL, TRUE, FALSE);

                $cif_baru = $rowData[7][3];
                $cif_habis = $rowData[7][4];


                $id = md5(sha1($created_by . ' ' . $created_date . ' ' . rand() . ' ' . $nik));


                if ($nik != '') {
                    $insert_cif[] = array(
                        'id' => $id,
                        'id_pengajuan_barang' => $this->db->insert_id(),
                        'cif_baru' => $cif_baru,
                        'cif_habis' => $cif_habis,
                        'created_by' => $created_by,
                        'created_date' => $created_date
                    );

                }
            }
        }

        if ($confirm == TRUE) {
            // DATABASE TRANSACTION
            $DBCore->trans_begin();

            $this->insert($table, $insert);
            $this->insert_batch($table2, $insert_cif);

            if ($DBCore->trans_status() === TRUE) {
                $DBCore->trans_commit();

                $result = array(
                    'result' => TRUE,
                    'message' => 'ID Anggota ditambahkan.'
                );
            } else {
                $DBCore->trans_rollback();

                $result = array(
                    'result' => FALSE,
                    'message' => 'Koneksi internet Anda terputus.'
                );
            }
        }

        echo json_encode($result);
    }
}
