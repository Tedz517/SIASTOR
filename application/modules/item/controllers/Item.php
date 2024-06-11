<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Item extends MST_Controller
{

    function __construct()
    {
        parent::__construct();
        $this->auth->restrict();
        $this->load->model('model_item');
        @date_default_timezone_set('Asia/Jakarta');
    }

    function index()
    {
        $this->item();
    }

    function item()
    {
        $data = array();

        // USER PRIVILLEGES
        $uri = $this->getUri();
        $url = $this->getSubUri($uri);

        
        // MAIN PAGE
        $data['body'] = 'item/vitem';
        $data['title'] = 'Barang | Sistem Inventory & Asset Kantor';
        $data['f_title'] = 'Barang';
        $data['s_title'] = 'pengaturan barang';
        $data['s_breadcrumb'] = 'Pengaturan Barang';

        // LIBRARY
        $data['csspage'] = 'item/_css/csspage_item';
        $data['jslib'] = 'item/_js/jslib_item';
        $data['jsscript'] = 'item/_js/js_item';

        // LOAD DATA
        $data['uri'] = $uri;
        $data['suburi'] = $this->getSubUri($uri);
        $data['menu'] = $this->show_menu(1);
        $data['subparent'] = $this->getSubParent($url['subparent']);
        $data['joinDate'] = $this->getJoinDate();
        $data['nama_aset'] = $this->model_item->get_asset();

        $this->load->vars($data);
        $this->load->view('view_dashboard');
    }

    function apiitem()
    {
        if (!$this->input->is_ajax_request()) {
            redirect(base_url('item'));
        }

        $page = isset($_POST['page']) ? $_POST['page'] : '';
        $limit_rows = isset($_POST['rows']) ? $_POST['rows'] : '';
        $sidx = isset($_POST['sidx']) ? $_POST['sidx'] : '';
        $sord = isset($_POST['sord']) ? $_POST['sord'] : '';
        $totalrows = isset($_POST['totalrows']) ? $_POST['totalrows'] : FALSE;

        if ($totalrows) {
            $limit_rows = $totalrows;
        }

        $sum = $this->model_item->apiitem('', '', '', '');

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

        $result = $this->model_item->apiitem($sidx, $sord, $limit_rows, $start);

        $response = array();

        $response['page'] = $page;
        $response['total'] = $total_pages;
        $response['records'] = $count;

        foreach ($result as $row) {
            $id = $row['id'];
            $nama_aset = $row['nama_aset'];
            $kode_barang = $row['kode_barang'];
            $nama_barang = $row['nama_barang'];
            $isactive = $row['status'];

            if ($isactive == '1') {
                $status = '<span class="badge badge-success">AKTIF</span>'; 
            } else {
                $status = '<span class="badge badge-danger">TIDAK AKTIF</button>';
            }

            $action = '<a href="javascript:;" id="edit_item" class="badge badge-info" title="Ubah" i_id="' . $id . '"><i class="fa fa-edit"></i> Ubah </a>';

            $response['rows'][] = array(
                'id' => $id,
                'nama_aset' => $nama_aset,
                'kode_barang' => $kode_barang,
                'nama_barang' => $nama_barang,
                'status' => $status,
                'action' => $action
            );
        }

        echo json_encode($response);
    }

    function paitem()
    {
        if (!$this->input->is_ajax_request()) {
            redirect(base_url('item'));
        }

        $kode_aset = $this->input->post('kode_aset');
        $kode_barang = $this->input->post('kode_barang');
        $nama_barang = $this->input->post('nama_barang');

        $created_by = $this->session->userdata('id');
        $created_date = date('Y-m-d H:i:s');

        $confirm = TRUE;

        $DBCore = $this->coreDB();

        $this->form_validation->set_rules('kode_aset', 'Kode Asset', 'required|trim');
        $this->form_validation->set_rules('kode_barang', 'Kode Barang', 'required|trim');
        $this->form_validation->set_rules('nama_barang', 'Nama Barang', 'required|trim');

        if ($this->form_validation->run() == FALSE) {
            // INVALID
            $confirm = FALSE;
            $result = array(
                'result' => FALSE,
                'message' => validation_errors()
            );
        }

        if ($confirm == TRUE) {
            $data = array(
                'kode_aset' => $kode_aset,
                'kode_barang' => $kode_barang,
                'nama_barang' => $nama_barang,
                'created_by' => $created_by,
                'created_date' => $created_date
            );
        }

        if ($confirm ==  TRUE) {
            // DATABASE TRANSACTION
            $DBCore->trans_begin();
            $this->insert('inv_barang', $data);


            if ($DBCore->trans_status() === TRUE) {
                $DBCore->trans_commit();

                $result = array(
                    'result' => TRUE,
                    'message' => 'Barang Kantor Berhasil ditambahkan.'
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

    function get_item_by_id()
    {
        if (!$this->input->is_ajax_request()) {
            redirect(base_url('item'));
        }

        $id = $this->input->post('i_id');

        $show = $this->model_item->get_item_by_id($id);

        $id = $show['id'];
        $nama_barang = $show['nama_barang'];

        $result = array(
            'id' => $id,
            'nama_barang' => $nama_barang
        );

        echo json_encode($result);
    }

    function peitem()
    {
        if (!$this->input->is_ajax_request()) {
            redirect(base_url('item'));
        }

        $id = $this->input->post('id');
        $nama_barang = $this->input->post('nama_barang');

        $modified_by = $this->session->userdata('id');
        $modified_date = date('Y-m-d H:i:s');

        $confirm = TRUE;

        $DBCore = $this->coreDB();

        $this->form_validation->set_rules('id', 'ID', 'required|trim');
        $this->form_validation->set_rules('nama_barang', 'Nama Barang', 'required|trim');

        if ($this->form_validation->run() == FALSE) {
            // INVALID
            $confirm = FALSE;
            $result = array(
                'result' => FALSE,
                'message' => validation_errors()
            );
        }

        if ($confirm == TRUE) {
            $data = array(
                'nama_barang' => $nama_barang,
                'modified_by' => $modified_by,
                'modified_date' => $modified_date
            );

            // DATABASE TRANSACTION
            $DBCore->trans_begin();
            $this->update('inv_barang', $data, 'id', $id);

            if ($DBCore->trans_status() === TRUE) {
                $DBCore->trans_commit();

                $result = array(
                    'result' => TRUE,
                    'message' => 'Nama Barang berhasil diubah.'
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

    function psitem()
    {
        if (!$this->input->is_ajax_request()) {
            redirect(base_url('item'));
        }

        $show = $this->input->post('object');
        $count = count($show);

        $DBCore = $this->coreDB();

        // DATABASE TRANSACTION
        $DBCore->trans_begin();

        for ($i = 0; $i < $count; $i++) {
            $data = array('status' => '1');
            $this->update('inv_barang', $data, 'id', $show[$i]);
        }

        if ($DBCore->trans_status() === TRUE) {
            $DBCore->trans_commit();

            $result = array(
                'result' => TRUE,
                'message' => 'Barang Kantor berhasil diaktifkan.'
            );
        } else {
            $DBCore->trans_rollback();

            $result = array(
                'result' => FALSE,
                'message' => 'Koneksi internet Anda terputus.'
            );
        }

        echo json_encode($result);
    }

    function phitem()
    {
        if (!$this->input->is_ajax_request()) {
            redirect(base_url('item'));
        }

        $hide = $this->input->post('object');
        $count = count($hide);

        $DBCore = $this->coreDB();

        // DATABASE TRANSACTION
        $DBCore->trans_begin();

        for ($i = 0; $i < $count; $i++) {
            $data = array('status' => '0');
            $this->update('inv_barang', $data, 'id', $hide[$i]);
        }

        if ($DBCore->trans_status() === TRUE) {
            $DBCore->trans_commit();

            $result = array(
                'result' => TRUE,
                'message' => 'Barang Kantor berhasil di-non-aktifkan.'
            );
        } else {
            $DBCore->trans_rollback();

            $result = array(
                'result' => FALSE,
                'message' => 'Koneksi internet Anda terputus.'
            );
        }

        echo json_encode($result);
    }

    function pditem()
    {
        if (!$this->input->is_ajax_request()) {
            redirect(base_url('item'));
        }

        $del = $this->input->post('object');
        $count = count($del);

        $DBCore = $this->coreDB();

        // DATABASE TRANSACTION
        $DBCore->trans_begin();

        for ($i = 0; $i < $count; $i++) {
            $this->delete('inv_barang', 'id', $del[$i]);
        }

        if ($DBCore->trans_status() === TRUE) {
            $DBCore->trans_commit();

            $result = array(
                'result' => TRUE,
                'message' => 'Barang Kantor berhasil dihapus.'
            );
        } else {
            $DBCore->trans_rollback();

            $result = array(
                'result' => FALSE,
                'message' => 'Koneksi internet Anda terputus.'
            );
        }

        echo json_encode($result);
    }


}
