<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Prodi extends AdminController
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('Prodi_model');
        $this->load->library('form_validation');
        $this->load->library('datatables');
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
        
        $this->load->view('header_list', $data);
        $this->load->view('Prodi/prodi_list');
        $this->load->view('footer_list');
    }

    public function json()
    {
        header('Content-Type: application/json');
        echo $this->Prodi_model->json();
    }

    public function create()
    {
        if (!isset($this->session->userdata['username'])) {
            redirect(site_url("Login"));
        }

        $dataAdm = array(
        'wa'       => $this->session->userdata['nama'],
        'univ'     => $this->session->userdata['divisi'] . ' ' . $this->session->userdata['lembaga'],
        'username' => $this->session->userdata['username'],
        'level'    => $this->session->userdata['level'],
        );
        
        $data = array(
        'button' => 'Create',
        'back'   => site_url('Prodi'),
        'action' => site_url('Prodi/create_action'),
        'ID' => set_value('ID'),
        'nama' => set_value('nama'),
        'idFakultas' => set_value('idFakultas'),
        'isActive' => set_value('isActive', 'Aktif'),
        );
        
        $this->load->view('header', $dataAdm);
        $this->load->view('Prodi/prodi_form', $data);
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
            'idFakultas' => $this->input->post('idFakultas', true),
            'isActive' => $this->input->post('isActive', true),
        );

            $this->Prodi_model->insert($data);
            $this->Prodi_model->addEvent(7, $this->idUser, $this->serialize_data($data));

            flashMessage('success', 'Create Record Success.');
            redirect(site_url('Prodi'));
        }
    }

    public function update($id)
    {
        if (!isset($this->session->userdata['username'])) {
            redirect(site_url("Login"));
        }
    
        $dataAdm = array(
            'wa'       => 'Web administrator',
            'univ'     => 'LPMAI UKSW',
            'username' => $this->session->userdata['username'],
            'level'    => $this->session->userdata['level'],
        );

        $row = $this->Prodi_model->get_by_id($id);

        if ($row) {
            $data = array(
                'button' => 'Update',
                'back'   => site_url('Prodi'),
                'action' => site_url('Prodi/update_action'),
                'ID' => set_value('ID', $row->ID),
                'nama' => set_value('nama', $row->nama),
                'idFakultas' => set_value('idFakultas', $row->idFakultas),
                'isActive' => set_value('isActive', $row->isActive),
                );
            
            $this->load->view('header', $dataAdm); // Menampilkan bagian header dan object data users
            $this->load->view('Prodi/prodi_form', $data); // Menampilkan form prodi
            $this->load->view('footer'); // Menampilkan bagian footer
        } else {
            flashMessage('error', 'Record Not Found.');
            redirect(site_url('Prodi'));
        }
    }

    public function update_action()
    {
        if (!isset($this->session->userdata['username'])) {
            redirect(site_url("Login"));
        }
    
        $this->_rules();

        if ($this->form_validation->run() == false) {
            $this->update($this->input->post('id_prodi', true));
        } else {
            $data = array(
            'ID' => $this->input->post('ID', true),
            'nama' => $this->input->post('nama', true),
            'idFakultas' => $this->input->post('idFakultas', true),
            'isActive' =>   $this->input->post('isActive', true) ,
            );

            $id = $this->input->post('ID', true);
            $row = $this->Prodi_model->get_by_id($id);

            $this->Prodi_model->update($id, $data);
            $keterangan =$this->serialize_data($row) . " <<Edit>> " . $this->serialize_data($data);
            $this->Prodi_model->addEvent(8, $this->idUser, $keterangan);

            flashMessage('success', 'Update Record Success');
            redirect(site_url('Prodi'));
        }
    }

    public function delete($id)
    {
        if (!isset($this->session->userdata['username'])) {
            redirect(site_url("Login"));
        }
    
        $row = $this->Prodi_model->get_by_id($id);
        
        if ($row) {
            $result = $this->Prodi_model->delete($id);
            if ($result) {
                $this->Prodi_model->addEvent(9, $this->idUser, $this->serialize_data($row));

                flashMessage('success', 'Delete Record Success.');
            } else {
                flashMessage('error', 'Delete Record Gagal!!');
            }
            
            redirect(site_url('Prodi'));
        } else {
            flashMessage('error', 'Record Not Found');
            redirect(site_url('Prodi'));
        }
    }

    public function _rules()
    {
        $this->form_validation->set_rules('ID', 'ID', 'trim');
        $this->form_validation->set_rules('nama', 'nama prodi', 'trim|required');
        $this->form_validation->set_rules('idFakultas', 'Id Fakultas', 'trim|required');
        $this->form_validation->set_rules('isActive', 'Status Aktif', 'trim|required');
        $this->form_validation->set_error_delimiters('<span class="text-danger">', '</span>');
    }
}