<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Model_pengajuan extends CI_Model
{

	function __construct()
	{
		parent::__construct();
	}

    function apipengajuan($sidx, $sord, $limit_rows, $start)
    {
        $sql = "SELECT inv_pengajuan_barang.*, branch.branch_name, employee.name FROM inv_pengajuan_barang
        INNER JOIN branch ON inv_pengajuan_barang.branch_code = branch.branch_code
        INNER JOIN employee ON inv_pengajuan_barang.nik = employee.nik ";

        if ($sidx != '') {
            $sql .= 'ORDER BY ' . $sidx . ' ' . $sord . ' ';
        }

        if ($limit_rows != '' and $start != '') {
            $sql .= 'LIMIT ' . $limit_rows . ' OFFSET ' . $start;
        }

        $query = $this->db->query($sql);

        return $query->result_array();
    }

    function get_branch_name()
    {
        $sql = "SELECT * FROM branch ORDER BY id ";

        $query = $this->db->query($sql);

        return $query->result_array();
    }

    function get_barang()
    {
        $sql = "SELECT * FROM inv_barang WHERE status = 1 ORDER BY id ";

        $query = $this->db->query($sql);

        return $query->result_array();
    }

}
