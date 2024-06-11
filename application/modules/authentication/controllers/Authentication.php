<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Authentication extends MST_Controller
{
	function __construct()
	{
		parent::__construct();
		$this->load->model('model_authentication');
		date_default_timezone_set('Asia/Jakarta');
	}

	function index()
	{
		$this->auth->restrict(TRUE);
		$this->load->view('vauthentication');
	}

	function plogin()
	{
		$this->auth->restrict(TRUE);

		$username = $this->input->post('username');
		$password = $this->input->post('password');

		$this->form_validation->set_rules('username', 'Username', 'trim|required');
		$this->form_validation->set_rules('password', 'Password', 'trim|required');

		if ($this->form_validation->run() == FALSE) {
			// INVALID
			$this->session->set_flashdata('notificationLogin', '<div class="alert alert-warning alert-dismissible fade show" role="alert">' . validation_errors() . ' </div>');
			redirect(base_url());
		}

		// CHECK ACCOUNT
		$check = $this->check_account($username, $password);

		if ($check == 1) {
			// SUCCESS
			redirect(base_url('dashboard'));
		}
	}

	function check_account($username, $password)
	{
		$this->auth->restrict(TRUE);

		$tolower = strtolower($username);

		// GET USERNAME
		$check_user = $this->model_authentication->getloginbyuname($tolower)->row_array();

		$count = count($check_user);

		if ($count > 0) {
			// CHECK PASSWORD
			$this->check_password($tolower, $password);

			// SUCCESS
			return TRUE;
		} else {
			// FAILED
			$this->session->set_flashdata('notificationLogin', '<div class="alert alert-warning alert-dismissible fade show" role="alert"><span>Username tidak ditemukan. </span></div>');
			redirect(base_url());
		}
	}

	function check_password($username, $password)
	{
		$this->auth->restrict(TRUE);

		// ENCRYPT PASSWORD
		$password = sha1(md5(sha1('2016' . $password . 'master')));

		// GET PASSWORD
		$check_pass = $this->model_authentication->getloginbypass($username, $password)->row_array();

		// GET PERIOD
		$period = $this->model_authentication->get_period_active();

		$id = $check_pass['id'];
		$idgroup = $check_pass['idgroup'];
		$branch_code = $check_pass['branch_code'];
		$branch_name = $check_pass['branch_name'];
		$username = $check_pass['username'];
		$password = $check_pass['password'];
		$name = $check_pass['name'];
		$email = $check_pass['email'];
		$photo = $check_pass['photo'];
		$attach = $check_pass['attach'];
		$isactive = $check_pass['isactive'];
		$lastlogin = $check_pass['lastlogin'];
		$input_by = $check_pass['input_by'];
		$input_date = $check_pass['input_date'];
		$update_by = $check_pass['update_by'];
		$update_date = $check_pass['update_date'];
		$from_periode = $period['from_date'];
		$thru_periode = $period['thru_date'];

		// GET PROFIL
		$get_profile = $this->model_authentication->getProfile($id);

		$mobile = $get_profile['mobile'];
		$interest = $get_profile['interest'];
		$occupation = $get_profile['occupation'];
		$about = $get_profile['about'];
		$website = $get_profile['website'];

		// GET EMPLOYEE
		if ($idgroup <> '493956199cf4a01177c6666e222c52b0') {
			$get_employee = $this->model_authentication->getEmployee($username);

			if ($branch_code <> $get_employee['branch_code']) {
				$branch_code = $get_employee['branch_code'];
			}
		}

		if (isset($check_pass)) {
			// CREATE SESSION
			$this->session->set_userdata('islogged', TRUE);
			$this->session->set_userdata('id', $id);
			$this->session->set_userdata('idgroup', $idgroup);
			$this->session->set_userdata('branch_code', $branch_code);
			$this->session->set_userdata('branch_name', $branch_name);
			$this->session->set_userdata('username', $username);
			$this->session->set_userdata('password', $password);
			$this->session->set_userdata('name', $name);
			$this->session->set_userdata('email', $email);
			$this->session->set_userdata('photo', $photo);
			$this->session->set_userdata('attach', $attach);
			$this->session->set_userdata('isactive', $isactive);
			$this->session->set_userdata('lastlogin', $lastlogin);
			$this->session->set_userdata('mobile', $mobile);
			$this->session->set_userdata('interest', $interest);
			$this->session->set_userdata('occupation', $occupation);
			$this->session->set_userdata('about', $about);
			$this->session->set_userdata('website', $website);
			$this->session->set_userdata('from_periode', $from_periode);
			$this->session->set_userdata('thru_periode', $thru_periode);
			$this->session->set_userdata('input_by', $input_by);
			$this->session->set_userdata('input_date', $input_date);
			$this->session->set_userdata('update_by', $update_by);
			$this->session->set_userdata('update_date', $update_date);

			// UPDATE LAST LOGIN
			$table = 'mst_user';
			$date = date('Y-m-d H:i:s');

			$data = array('lastlogin' => $date);

			$param = 'id';
			$id = $check_pass['id'];

			// BEGIN TRANSACTION
			$this->db->trans_begin();
			$this->update($table, $data, $param, $id);

			if ($this->db->trans_status() === TRUE) {
				$this->db->trans_commit();

				// SUCCESS
				return TRUE;
			} else {
				$this->db->trans_commit();

				// FAILED
				$this->session->set_flashdata('notificationLogin', '<div class="alert alert-warning alert-dismissible fade show" role="alert"><span>Koneksi terputus. </span></div>');
				redirect(base_url());
			}
		} else {
			// FAILED
			$this->session->set_flashdata('notificationLogin', '<div class="alert alert-warning alert-dismissible fade show" role="alert"><span>Password tidak ditemukan. </span></div>');
			redirect(base_url());
		}
	}

	function logout()
	{
		$this->auth->restrict();

		$this->session->sess_destroy();

		redirect(base_url());
	}
}
