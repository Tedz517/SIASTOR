<?php
defined('BASEPATH') or exit('No direct script access allowed');

class MST_Controller extends CI_Controller
{

    function __construct()
    {
        parent::__construct();
        $this->load->model('all/model_all');
        date_default_timezone_set('Asia/Jakarta');
    }

    function coreDB()
    {
        $db = $this->db;

        return $db;
    }

    function ip_engine()
    {
        $branch_code = '99999';

        $get = $this->model_all->get_institution($branch_code);

        $ip = $get['ip_address'];

        return $ip;
    }

    function insert($table, $data)
    {
        $insert = $this->model_all->insert($table, $data);
    }

    function insert_batch($table, $data)
    {
        $insert = $this->model_all->insert_batch($table, $data);
    }

    function update($table, $data, $param, $id)
    {
        $update = $this->model_all->update($table, $data, $param, $id);
    }

    function update2($table, $data, $param)
    {
        $update = $this->model_all->update2($table, $data, $param);
    }

    function update_batch($table, $data, $param)
    {
        $this->model_all->update_batch($table, $data, $param);
    }

    function update_batch_multiple($table, $data, $param, $field, $param2)
    {
        $this->model_all->update_batch_multiple($table, $data, $param, $field, $param2);
    }

    function update_sortable($table, $data, $id)
    {
        $this->model_all->update_sortable($table, $data, $id);
    }

    function delete($table, $param, $id)
    {
        $this->model_all->delete($table, $param, $id);
    }

    function delete2($table, $param)
    {
        $this->model_all->delete2($table, $param);
    }

    function delete_without($table)
    {
        $this->model_all->delete_without($table);
    }

    function show_menu($isactive)
    {
        $idgroup = $this->session->userdata('idgroup');
        $menu = $this->model_all->show_menu($isactive, $idgroup);

        return $menu;
    }

    function show_group($isactive)
    {
        $group = $this->model_all->show_group($isactive);

        return $group;
    }

    function show_branch($isactive)
    {
        $branch = $this->model_all->show_branch($isactive);

        return $branch;
    }

    function privilleges($control)
    {
        $isactive = '1';
        $idgroup = $this->session->userdata('idgroup');
        $priv = $this->model_all->show_privilleges($isactive, $idgroup, $control);

        $jumlah = $priv['jumlah'];

        return $jumlah;
    }

    function getJoinDate()
    {
        $joinDate = $this->session->userdata('input_date');
        $joinDate = substr($joinDate, 0, 7);
        $explode = explode('-', $joinDate);

        $year = $explode[0];
        $month = $explode[1];

        $joinDate = joinDate($month, $year);

        return $joinDate;
    }

    function getUri()
    {
        $uri1 = $this->uri->segment(1);
        $uri2 = $this->uri->segment(2);

        if ($uri2) {
            $uri = $uri1 . '/' . $uri2;
        } else {
            $uri = $uri1;
        }

        return $uri;
    }

    function getSubUri($uri)
    {
        $url = $this->model_all->getSubUri($uri);

        return $url;
    }

    function getSubParent($parent)
    {
        $url = $this->model_all->getSubParent($parent);

        return $url;
    }

    function RegisStaff($ip, $key, $pin, $name)
    {
        $connect = fsockopen($ip, '80', $errno, $errstr, 1);

        $soap_request = '<SetUserInfo><ArgComKey Xsi:type="xsd:integer">' . $key . '</ArgComKey><Arg><PIN>' . $pin . '</PIN><Name>' . $name . '</Name></Arg></SetUserInfo>';

        $newLine = "\r\n";

        fputs($connect, 'POST /iWsService HTTP/1.0' . $newLine);
        fputs($connect, 'Content-Type: text/xml' . $newLine);
        fputs($connect, 'Content-Length: ' . strlen($soap_request) . $newLine . $newLine);
        fputs($connect, $soap_request . $newLine);

        $buffer = '';

        while ($Response = fgets($connect, 1024)) {
            $buffer = $buffer . $Response;
        }

        $buffer = buffer($buffer, '<Information>', '</Information>');
    }

    function DeleteStaff($ip, $key, $pin)
    {
        $connect = fsockopen($ip, '80', $errno, $errstr, 1);

        $soap_request = '<DeleteUser><ArgComKey xsi:type="xsd:integer">' . $key . '</ArgComKey><Arg><PIN xsi:type="xsd:integer">' . $pin . '</PIN></Arg></DeleteUser>';

        $newLine = "\r\n";

        fputs($connect, 'POST /iWsService HTTP/1.0' . $newLine);
        fputs($connect, 'Content-Type: text/xml' . $newLine);
        fputs($connect, 'Content-Length: ' . strlen($soap_request) . $newLine . $newLine);
        fputs($connect, $soap_request . $newLine);

        $buffer = '';

        while ($Response = fgets($connect, 1024)) {
            $buffer = $buffer . $Response;
        }

        $buffer = buffer($buffer, '<DeleteUserResponse>', '</DeleteUserResponse>');
        $buffer = buffer($buffer, '<Information>', '</Information>');
    }

    function getPresence($ip, $key)
    {
        $connect = fsockopen($ip, '80', $errno, $errstr, 1);

        $soap_request = '<GetAttLog><ArgComKey xsi:type="xsd:integer">' . $key . '</ArgComKey><Arg><PIN xsi:type="xsd:integer">All</PIN></Arg></GetAttLog>';

        $newLine = "\r\n";

        fputs($connect, 'POST /iWsService HTTP/1.0' . $newLine);
        fputs($connect, 'Content-Type: text/xml' . $newLine);
        fputs($connect, 'Content-Length: ' . strlen($soap_request) . $newLine . $newLine);
        fputs($connect, $soap_request . $newLine);

        $buffer = '';

        while ($Response = fgets($connect, 1024)) {
            $buffer = $buffer . $Response;
        }

        $buffer = buffer($buffer, '<GetAttLogResponse>', '</GetAttLogResponse>');

        $buffer = explode("\r\n", $buffer);

        return $buffer;
    }

    function deletePresence($ip, $key)
    {
        $connect = fsockopen($ip, '80', $errno, $errstr, 1);

        $soap_request = '<ClearData><ArgComKey xsi:type="xsd:integer">' . $key . '</ArgComKey><Arg><Value xsi:type="xsd:integer">3</Value></Arg></ClearData>';

        $newLine = "\r\n";

        fputs($connect, 'POST /iWsService HTTP/1.0' . $newLine);
        fputs($connect, 'Content-Type: text/xml' . $newLine);
        fputs($connect, 'Content-Length: ' . strlen($soap_request) . $newLine . $newLine);
        fputs($connect, $soap_request . $newLine);

        $buffer = '';

        while ($Response = fgets($connect, 1024)) {
            $buffer = $buffer . $Response;
        }

        $buffer = buffer($buffer, '<Information>', '</Information>');
    }

    function calculate_thru_date()
    {
        $total_day = $this->input->post('total_day');
        $from_date = datepicker($this->input->post('from_date'));

        if ($total_day > 1) {
            $thru_date = date('Y-m-d', strtotime($from_date . '+' . $total_day . ' DAYS - 1 DAY'));
        } else {
            $thru_date = $from_date;
        }

        $return = array('thru_date' => date_indo($thru_date));

        echo json_encode($return);
    }
}
