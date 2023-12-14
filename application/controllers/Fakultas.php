<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}


class Fakultas extends AdminController
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('Fakultas_model');
        $this->load->library('form_validation');
        $this->load->library('datatables');
    }
    
    public function index()
    {
        if (!isset($this->session->userdata['ID'])) {
            redirect(site_url("Login"));
        }

        $dataAdm = array(
            'wa'       => $this->session->userdata['nama'],
            'univ'     => $this->session->userdata['divisi'] . ' ' . $this->session->userdata['lembaga'],
            'username' => $this->session->userdata['username'],
            'level'    => $this->session->userdata['level'],
        );

        $this->load->view('header_list', $dataAdm);
        $this->load->view('Fakultas/fakultas_list');
        $this->load->view('footer_list');
    }
    
    public function json()
    {
        header('Content-Type: application/json');
        echo $this->Fakultas_model->json();
    }

    public function create()
    {
        if (!isset($this->session->userdata['username'])) {
            redirect(site_url("Login"));
        }

        $rowAdm = $this->Fakultas_model->get_by_id($this->session->userdata['ID']);
        $dataAdm = array(
            'wa'       => $this->session->userdata['nama'],
            'univ'     => $this->session->userdata['divisi'] . ' ' . $this->session->userdata['lembaga'],
            'username' => $this->session->userdata['username'],
            'level'    => $this->session->userdata['level'],
        );

        $data = array(
            'button' => 'Create',
            'back'   => site_url('Fakultas'),
            'action' => site_url('Fakultas/create_action'),
            'nama' => set_value('nama'),
            'isActive' => set_value('isActive', 'Aktif'),
        );
        
        $this->load->view('header', $dataAdm);
        $this->load->view('Fakultas/fakultas_form', $data);
        $this->load->view('footer');
    }
    

    public function create_action()
    {
        if (!isset($this->session->userdata['username'])) {
            redirect(site_url("Login"));
        }
    
        $this->_rules();

        if ($this->form_validation->run() == false) {
            $this->create();
        } else {
            $data = array(
                'nama' => $this->input->post('nama', true),
                'isActive' => $this->input->post('isActive', true),
            );

            $this->Fakultas_model->insert($data);
            $this->Fakultas_model->addEvent(4, $this->idUser, $this->serialize_data($data));

            flashMessage('success', 'Create Record Success.');
            redirect(site_url('Fakultas'));
        }
    }
    

    public function update($id)
    {
        if (!isset($this->session->userdata['username'])) {
            redirect(site_url("Login"));
        }
    
        $rowAdm = $this->Fakultas_model->get_by_id($this->session->userdata['ID']);
        $dataAdm = array(
            'wa'       => 'Web administrator',
            'univ'     => 'LPMAI UKSW',
            'username' => $this->session->userdata['username'],
            'level'    => $this->session->userdata['level'],
        );

        $row = $this->Fakultas_model->get_by_id($id);

        if ($row) {
            $data = array(
                'button' => 'Update',
                'back'   => site_url('Fakultas'),
                'action' => site_url('Fakultas/update_action'),
                'nama' => set_value('nama', $row->nama),
                'isActive' => set_value('isActive', $row->isActive),
                'ID' =>   set_value('ID', $row->ID),
            );

            $this->load->view('header', $dataAdm);
            $this->load->view('Fakultas/fakultas_form', $data);
            $this->load->view('footer');
        } else {
            flashMessage('error', 'Record Not Found.');
            redirect(site_url('Fakultas'));
        }
    }

    public function update_action()
    {
        if (!isset($this->session->userdata['ID'])) {
            redirect(site_url("Login"));
        }

        $this->_rules();
        
        if ($this->form_validation->run() == false) {
            $this->update($this->input->post('ID', true));
        } else {
            $data = array(
                'nama' => $this->input->post('nama', true),
                'isActive' =>   $this->input->post('isActive', true) ,
            );

            $id = $this->input->post('ID', true);
            $row = $this->Fakultas_model->get_by_id($id);
            $this->Fakultas_model->update($id, $data);
            $keterangan =$this->serialize_data($row) . " <<Edit>> " . $this->serialize_data($data);
            $this->Fakultas_model->addEvent(5, $this->idUser, $keterangan);
            
            flashMessage('success', 'Update Record Success.');
            redirect(site_url('Fakultas'));
        }
    }

    public function delete($id)
    {
        if (!isset($this->session->userdata['username'])) {
            redirect(site_url("Login"));
        }
    
        $row = $this->Fakultas_model->get_by_id($id);
        if ($row) {
            $result = $this->Fakultas_model->delete($id);
            if ($result) {
                $this->Fakultas_model->addEvent(6, $this->idUser, $this->serialize_data($row));

                flashMessage('success', 'Delete Record Success.');
            } else {
                flashMessage('error', 'Delete Record Gagal, Hapus Data Prodi dahulu!!');
            }
            
            redirect(site_url('Fakultas'));
        } else {
            flashMessage('error', 'Record Not Found.');
            redirect(site_url('Fakultas'));
        }
    }

    public function _rules()
    {
        $this->form_validation->set_rules('nama', 'nama', 'trim|required');
        $this->form_validation->set_rules('isActive', 'Status Aktif', 'trim|required');
        $this->form_validation->set_error_delimiters('<span class="text-danger">', '</span>');
    }
}