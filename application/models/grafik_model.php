<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Fungsi Model
 */

class Grafik_model extends CI_Model {

    function __construct()
    {
        parent::__construct();
    }
	
	// Tampilkan semua master data fungsi
	//function getAll($limit, $offset)
    function creategraph()
    {
        $this->load->library('graph');
        $data_1 = array();
        for( $i=0; $i<7; $i++ )
        {
            $data_1[] = rand(30,550);
        }
        $ff = new graph();
        $ff->set_data( $data_1 );
        $ff->title( 'Traffic SMS 1 Minggu Terakhir', '{font-size: 14px; color: #3D5570;font-family:calibri;}' );
        $ff->line_dot( 3, 5, '#8B6122', 'SMS Jarkom', 10 );
        
        $ff->bg_colour = '#FFFFFF';
        $ff->x_axis_colour( '#818D9D', '#ADB5C7' );
        $ff->y_axis_colour( '#818D9D', '#ADB5C7' );
        $ff->set_x_labels( array( '25/12','26/12','27/12','28/12','29/12','30/12','31/12' ) );
        $ff->set_y_max( 600 );
        $ff->y_label_steps( 10 );
        $ff->set_y_legend( 'Jumlah SMS', 12, '#3D5570' );
        $ff->set_x_legend( 'Tanggal', 12, '#3D5570' );
        $ff->set_output_type('js');
        $ff->width = '90%';
        $ff->height = '300';
        return $ff->render();
    }
        
}

/* End of file fungsi_model.php */
/* Location: ./application/models/fungsi_model.php */