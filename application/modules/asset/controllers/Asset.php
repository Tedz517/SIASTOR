<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Asset extends MST_Controller
{

    function __construct()
    {
        parent::__construct();
        $this->auth->restrict();
        $this->load->model('model_asset');
        @date_default_timezone_set('Asia/Jakarta');
    }

    function index()
    {
        $this->asset();
    }

    function asset()
    {
        $data = array();

        // USER PRIVILLEGES
        $uri = $this->getUri();
        $url = $this->getSubUri($uri);

        
        // MAIN PAGE
        $data['body'] = 'asset/vasset';
        $data['title'] = 'Asset | Sistem Inventory & Asset Kantor';
        $data['f_title'] = 'Asset';
        $data['s_title'] = 'pengaturan Asset';
        $data['s_breadcrumb'] = 'Pengaturan Asset';

        // LIBRARY
        $data['csspage'] = 'asset/_css/csspage_asset';
        $data['jslib'] = 'asset/_js/jslib_asset';
        $data['jsscript'] = 'asset/_js/js_asset';

        // LOAD DATA
        $data['uri'] = $uri;
        $data['suburi'] = $this->getSubUri($uri);
        $data['menu'] = $this->show_menu(1);
        $data['subparent'] = $this->getSubParent($url['subparent']);
        $data['joinDate'] = $this->getJoinDate();

        $this->load->vars($data);
        $this->load->view('view_dashboard');
    }

    function apiasset()
    {
        if (!$this->input->is_ajax_request()) {
            redirect(base_url('asset'));
        }

        $page = isset($_POST['page']) ? $_POST['page'] : '';
        $limit_rows = isset($_POST['rows']) ? $_POST['rows'] : '';
        $sidx = isset($_POST['sidx']) ? $_POST['sidx'] : '';
        $sord = isset($_POST['sord']) ? $_POST['sord'] : '';
        $totalrows = isset($_POST['totalrows']) ? $_POST['totalrows'] : FALSE;

        if ($totalrows) {
            $limit_rows = $totalrows;
        }

        $sum = $this->model_asset->apiasset('', '', '', '');

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

        $result = $this->model_asset->apiasset($sidx, $sord, $limit_rows, $start);

        $response = array();

        $response['page'] = $page;
        $response['total'] = $total_pages;
        $response['records'] = $count;

        foreach ($result as $row) {
            $id = $row['id'];
            $kode_aset = $row['kode_aset'];
            $nama_aset = $row['nama_aset'];
            $isactive = $row['status'];

            if ($isactive == '1') {
                $status = '<span class="badge badge-success">AKTIF</span>'; 
            } else {
                $status = '<span class="badge badge-danger">TIDAK AKTIF</button>';
            }

            $action = '<a href="javascript:;" id="edit_asset" class="badge badge-info" title="Ubah" b_id="' . $id . '"><i class="fa fa-edit"></i> Ubah </a>';

            $response['rows'][] = array(
                'id' => $id,
                'kode_aset' => $kode_aset,
                'nama_aset' => $nama_aset,
                'status' => $status,
                'action' => $action
            );
        }

        echo json_encode($response);
    }

    function paasset()
    {
        if (!$this->input->is_ajax_request()) {
            redirect(base_url('asset'));
        }

        $kode_aset = $this->input->post('kode_aset');
        $nama_aset = $this->input->post('nama_aset');

        $created_by = $this->session->userdata('id');
        $created_date = date('Y-m-d H:i:s');

        $confirm = TRUE;

        $DBCore = $this->coreDB();

        $this->form_validation->set_rules('kode_aset', 'Kode Asset', 'required|trim');
        $this->form_validation->set_rules('nama_aset', 'Nama Asset', 'required|trim');

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
                'nama_aset' => $nama_aset,
                'created_by' => $created_by,
                'created_date' => $created_date
            );
        }

        if ($confirm ==  TRUE) {
            // DATABASE TRANSACTION
            $DBCore->trans_begin();
            $this->insert('inv_asset', $data);


            if ($DBCore->trans_status() === TRUE) {
                $DBCore->trans_commit();

                $result = array(
                    'result' => TRUE,
                    'message' => 'Asset Kantor Berhasil ditambahkan.'
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

    function get_asset_by_id()
    {
        if (!$this->input->is_ajax_request()) {
            redirect(base_url('asset'));
        }

        $id = $this->input->post('b_id');

        $show = $this->model_asset->get_asset_by_id($id);

        $id = $show['id'];
        $nama_aset = $show['nama_aset'];

        $result = array(
            'id' => $id,
            'nama_aset' => $nama_aset
        );

        echo json_encode($result);
    }

    function peasset()
    {
        if (!$this->input->is_ajax_request()) {
            redirect(base_url('asset'));
        }

        $id = $this->input->post('id');
        $nama_aset = $this->input->post('nama_aset');

        $modified_by = $this->session->userdata('id');
        $modified_date = date('Y-m-d H:i:s');

        $confirm = TRUE;

        $DBCore = $this->coreDB();

        $this->form_validation->set_rules('id', 'ID', 'required|trim');
        $this->form_validation->set_rules('nama_aset', 'Nama Asset', 'required|trim');

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
                'nama_aset' => $nama_aset,
                'modified_by' => $modified_by,
                'modified_date' => $modified_date
            );

            // DATABASE TRANSACTION
            $DBCore->trans_begin();
            $this->update('inv_asset', $data, 'id', $id);

            if ($DBCore->trans_status() === TRUE) {
                $DBCore->trans_commit();

                $result = array(
                    'result' => TRUE,
                    'message' => 'Nama Asset berhasil diubah.'
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

    function psasset()
    {
        if (!$this->input->is_ajax_request()) {
            redirect(base_url('asset'));
        }

        $show = $this->input->post('object');
        $count = count($show);

        $DBCore = $this->coreDB();

        // DATABASE TRANSACTION
        $DBCore->trans_begin();

        for ($i = 0; $i < $count; $i++) {
            $data = array('status' => '1');
            $this->update('inv_asset', $data, 'id', $show[$i]);
        }

        if ($DBCore->trans_status() === TRUE) {
            $DBCore->trans_commit();

            $result = array(
                'result' => TRUE,
                'message' => 'Asset berhasil diaktifkan.'
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

    function phasset()
    {
        if (!$this->input->is_ajax_request()) {
            redirect(base_url('asset'));
        }

        $hide = $this->input->post('object');
        $count = count($hide);

        $DBCore = $this->coreDB();

        // DATABASE TRANSACTION
        $DBCore->trans_begin();

        for ($i = 0; $i < $count; $i++) {
            $data = array('status' => '0');
            $this->update('inv_asset', $data, 'id', $hide[$i]);
        }

        if ($DBCore->trans_status() === TRUE) {
            $DBCore->trans_commit();

            $result = array(
                'result' => TRUE,
                'message' => 'Asset berhasil di-non-aktifkan.'
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

    function pdasset()
    {
        if (!$this->input->is_ajax_request()) {
            redirect(base_url('asset'));
        }

        $del = $this->input->post('object');
        $count = count($del);

        $DBCore = $this->coreDB();

        // DATABASE TRANSACTION
        $DBCore->trans_begin();

        for ($i = 0; $i < $count; $i++) {
            $this->delete('inv_asset', 'id', $del[$i]);
        }

        if ($DBCore->trans_status() === TRUE) {
            $DBCore->trans_commit();

            $result = array(
                'result' => TRUE,
                'message' => 'Asset berhasil dihapus.'
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
