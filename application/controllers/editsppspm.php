<?php
class editsppspm extends CI_Controller {

        public function index()
        {
            $data['page_title'] = 'EDIT SPP SPM';
            $this->template->set('title', 'EDIT SPP SPM');
            $this->template->load('template', 'edit/editsppspm', $data);
        }
}