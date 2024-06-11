<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Model_all extends CI_Model
{

    function __construct()
    {
        parent::__construct();
    }

    function insert($table, $data)
    {
        $this->db->insert($table, $data);
    }

    function insert_batch($table, $data)
    {
        $this->db->insert_batch($table, $data);
    }

    function show_menu($isactive, $idgroup)
    {
        $sql = "SELECT
        menu.*
        FROM mst_menu AS menu
        JOIN mst_role AS role ON (role.idmenu = menu.id AND role.idgroup = ?)
        WHERE menu.istype = 0 AND menu.isactive = ?
        ORDER BY menu.position ASC";

        $param = array($idgroup, $isactive);

        $query = $this->db->query($sql, $param);

        return $query->result_array();
    }

    function show_privilleges($isactive, $idgroup, $control)
    {
        $sql = "SELECT
        COUNT(*) AS jumlah
        FROM mst_menu AS menu
        JOIN mst_role AS role ON (role.idmenu = menu.id AND role.idgroup = ?)
        WHERE menu.isactive = ? AND menu.istype = 0 AND menu.control = ?
        ORDER BY menu.position ASC";

        $param = array($idgroup, $isactive, $control);

        $query = $this->db->query($sql, $param);

        return $query->row_array();
    }

    function show_group($isactive)
    {
        $sql = "SELECT * FROM mst_group WHERE isactive = ? ORDER BY `group`";

        $param = array($isactive);

        $query = $this->db->query($sql, $param);

        return $query->result_array();
    }

    function show_branch($isactive)
    {
        $sql = "SELECT * FROM branch WHERE isactive = ? AND branch_class IN('0','1','3') AND branch_code NOT IN('10000','20000') ORDER BY branch_code";

        $param = array($isactive);

        $query = $this->db->query($sql, $param);

        return $query->result_array();
    }

    function update($table, $data, $param, $id)
    {
        $this->db->where($param, $id);
        $this->db->update($table, $data);
    }

    function update2($table, $data, $param)
    {
        $this->db->update($table, $data, $param);
    }

    function update_batch($table, $data, $param)
    {
        $this->db->update_batch($table, $data, $param);
    }

    function update_batch_multiple($table, $data, $param, $field, $param2)
    {
        $this->db->where_in($field, $param2);
        $this->db->update_batch($table, $data, $param);
    }

    function update_sortable($table, $data, $id)
    {
        $this->db->update($table, $data, $id);
    }

    function delete($table, $param, $id)
    {
        $this->db->where($param, $id);
        $this->db->delete($table);
    }

    function delete2($table, $param)
    {
        $this->db->delete($table, $param);
    }

    function delete_without($table)
    {
        $this->db->empty_table($table);
    }

    function getSubUri($url)
    {
        $sql = "SELECT subparent FROM mst_menu WHERE istype = 0 AND control = ?";

        $param = array($url);

        $query = $this->db->query($sql, $param);

        return $query->row_array();
    }

    function getSubParent($parent)
    {
        $sql = "SELECT subparent FROM mst_menu WHERE parent = ?";

        $param = array($parent);

        $query = $this->db->query($sql, $param);

        return $query->row_array();
    }

    function get_institution($branch_code)
    {
        $sql = "SELECT * FROM institution WHERE branch_code = ?";

        $param = array($branch_code);

        $query = $this->db->query($sql, $param);

        return $query->row_array();
    }
}
