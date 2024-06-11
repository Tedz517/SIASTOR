<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Model_authentication extends CI_Model
{
    function __construct()
    {
        parent::__construct();
    }

    function getloginbyuname($username)
    {
        $sql = "SELECT * FROM mst_user WHERE username = ? AND isactive = '1'";

        $param = array($username);

        $query = $this->db->query($sql, $param);

        return $query;
    }

    function getloginbypass($username, $password)
    {
        $sql = "SELECT
        mu.*,
        b.branch_name
        FROM mst_user AS mu
        JOIN branch AS b ON b.branch_code = mu.branch_code
        WHERE mu.username = ? AND mu.password = ? AND mu.isactive = '1'";

        $param = array($username, $password);

        $query = $this->db->query($sql, $param);

        return $query;
    }

    function get_period_active()
    {
        $sql = "SELECT * FROM period";

        $query = $this->db->query($sql);

        return $query->row_array();;
    }

    function getemail($email)
    {
        $sql = "SELECT id,email FROM mst_user WHERE email = ?";

        $param = array($email);

        $query = $this->db->query($sql, $param);

        return $query;
    }

    function getProfile($iduser)
    {
        $sql = "SELECT * FROM mst_user_profile WHERE iduser = ?";

        $param = array($iduser);

        $query = $this->db->query($sql, $param);

        return $query->row_array();
    }

    function getEmployee($nik)
    {
        $sql = "SELECT * FROM employee WHERE nik = ?";

        $param = array($nik);

        $query = $this->db->query($sql, $param);

        return $query->row_array();
    }
}
