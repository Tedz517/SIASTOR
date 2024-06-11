<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Model_asset extends CI_Model
{
    function __construct()
    {
        parent::__construct();
    }

    function apiasset($sidx, $sord, $limit_rows, $start)
    {
        $sql = "SELECT * FROM inv_asset ";

        if ($sidx != '') {
            $sql .= 'ORDER BY ' . $sidx . ' ' . $sord . ' ';
        }

        if ($limit_rows != '' and $start != '') {
            $sql .= 'LIMIT ' . $limit_rows . ' OFFSET ' . $start;
        }

        $query = $this->db->query($sql);

        return $query->result_array();
    }

    function get_asset_by_id($id)
    {
        $sql = "SELECT * FROM inv_asset WHERE id = ?";

        $param = array($id);

        $query = $this->db->query($sql, $param);

        return $query->row_array();
    }

}
