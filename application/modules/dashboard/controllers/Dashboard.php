<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Dashboard extends MST_Controller
{

    function __construct()
    {
        parent::__construct();
        $this->auth->restrict();
        $this->load->model('model_dashboard');
        @date_default_timezone_set('Asia/Jakarta');
    }

    function index()
    {
        $data = array();

        // URI SEGMENT
        $uri = $this->getUri();
        $url = $this->getSubUri($uri);

        // MAIN PAGE
        $data['body'] = 'dashboard/vdashboard';
        $data['title'] = 'Beranda | Siastor';
        $data['f_title'] = 'Beranda';
        $data['s_title'] = 'Statistik Aset dan Pemesanan Buku';
        $data['s_breadcrumb'] = 'Beranda';
        $data['joinDate'] = $this->getJoinDate();

        // LIBRARY
        $data['csspage'] = 'dashboard/_css/csspage_dashboard';
        $data['jslib'] = 'dashboard/_js/jslib_dashboard';
        $data['jsscript'] = 'dashboard/_js/js_dashboard';

        // LOAD DATA
        $data['uri'] = $uri;
        $data['menu'] = $this->show_menu(1);
        $data['suburi'] = $this->getSubUri($uri);
        $data['subparent'] = $this->getSubParent($url['subparent']);

        $this->load->vars($data);
        $this->load->view('view_dashboard');
    }
}
