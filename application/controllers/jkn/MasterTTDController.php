<?php if (!defined('BASEPATH')) exit('No direct script access allowed');


class MasterTTDController extends CI_Controller
{

    function __contruct()
    {
        parent::__construct();
    }
    public function index()
    {
        $data['page_title'] = 'MASTER TTD JKN';
        $this->template->set('title', 'MASTER TTD JKN');
        $this->template->load('template', 'jkn/master/ttd', $data);
    }

    function config_skpd()
    {
        $skpd       = $this->session->userdata('kdskpd');
        $sql        = "SELECT kd_skpd,nm_skpd FROM  ms_skpd_jkn a WHERE kd_skpd = '$skpd'";
        $query1     = $this->db->query($sql);

        $test = $query1->num_rows();

        $ii = 0;
        foreach ($query1->result_array() as $resulte) {
            $result = array(
                'id' => $ii,
                'kd_skpd' => $resulte['kd_skpd'],
                'nm_skpd' => $resulte['nm_skpd']
            );
            $ii++;
        }
        echo json_encode($result);
        $query1->free_result();
    }
}
