<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class InfoSistem extends SuperAdminController
{
    private $uploadingDirectory = "./file/tahunPelaksanaan/";
    private $subDirectory;

    public function __construct()
    {
        parent::__construct();
        $this->load->model('InfoSistem_model');
        $this->load->library('form_validation');
        $this->load->library('datatables');

        $this->subDirectory = $this->InfoSistem_model->getDefaultTahunPelaksanaan()->tahun . '/panduan/';
    }
    
    public function index()
    {
        if (!isset($this->session->userdata['username'])) {
            redirect(site_url("Login"));
        }
    
        $data = array(
            'wa'       => $this->session->userdata['nama'],
            'univ'     => $this->session->userdata['divisi'] . ' ' . $this->session->userdata['lembaga'],
            'username' => $this->session->userdata['username'],
            'level'    => $this->session->userdata['level'],
        );

        $row = $this->InfoSistem_model->get_by_id(1);
        $dataTambahan = array(
            'button' => 'Update',
            'back'   => site_url('InfoSistem'),
            'action' => site_url('InfoSistem/update_text_action'),
            'nama' => set_value('nama', $row->nama),
            'divisi' => set_value('divisi', $row->divisi),
            'lembaga' => set_value('lembaga', $row->lembaga),
            'webUtama' => set_value('webUtama', $row->webUtama),
        );
        
        $this->load->view('header_list', $data);
        $this->load->view('InfoSistem/infosistem_list', $dataTambahan);
        $this->load->view('footer_list');
    }
    
    public function update_text_action()
    {
        if (!isset($this->session->userdata['username'])) {
            redirect(base_url("Login"));
        }

        $data = array(
            'nama' => $this->input->post('nama', true),
            'divisi' => $this->input->post('divisi', true),
            'lembaga' => $this->input->post('lembaga', true),
            'webUtama' => $this->input->post('webUtama', true),
        );

        if ($this->InfoSistem_model->update(1, $data)) {
            flashMessage('success', 'Update Record Success.');
        } else {
            flashMessage('error', 'Update Record gagal!!!');
        }

        redirect(site_url('InfoSistem'));
    }
}