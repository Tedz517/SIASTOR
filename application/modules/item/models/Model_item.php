<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Model_item extends CI_Model
{
    function __construct()
    {
        parent::__construct();
    }

    function apiitem($sidx, $sord, $limit_rows, $start)
    {
        $sql = "SELECT inv_barang.*, inv_asset.nama_aset
        FROM inv_barang
        INNER JOIN inv_asset ON inv_barang.kode_aset = inv_asset.kode_aset ";

        if ($sidx != '') {
            $sql .= 'ORDER BY ' . $sidx . ' ' . $sord . ' ';
        }

        if ($limit_rows != '' and $start != '') {
            $sql .= 'LIMIT ' . $limit_rows . ' OFFSET ' . $start;
        }

        $query = $this->db->query($sql);

        return $query->result_array();
    }

    function get_item_by_id($id)
    {
        $sql = "SELECT * FROM inv_barang WHERE id = ? ";

        $param = array($id);

        $query = $this->db->query($sql, $param);

        return $query->row_array();
    }

    function get_asset()
    {
        $sql = "SELECT * FROM inv_asset WHERE status = 1 ORDER BY id ";

        $query = $this->db->query($sql);

        return $query->result_array();
    }

}
