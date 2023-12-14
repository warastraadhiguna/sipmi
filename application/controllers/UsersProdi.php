<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class UsersProdi extends ProdiController
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('UsersProdi_model');
        $this->load->library('form_validation');
        $this->load->library('datatables');
    }
    
    public function index()
    {
        if (!isset($this->session->userdata['username'])) {
            redirect(site_url("Login"));
        }

        $rowAdm = $this->UsersProdi_model->get_by_id($this->idUser);

        $dataAdm = array(
            'wa'       => $this->session->userdata['nama'],
            'univ'     => $this->session->userdata['divisi'] . ' ' . $this->session->userdata['lembaga'],
            'username' => $rowAdm->username,
            'level'    => $rowAdm->level,
        );
        
        $this->load->view('header_list', $dataAdm);
        $this->load->view('UsersProdi/users_list');
        $this->load->view('footer_list');
    }
    
    public function json()
    {
        header('Content-Type: application/json');
        echo $this->UsersProdi_model->json();
    }

    public function create()
    {
        if (!isset($this->session->userdata['username'])) {
            redirect(site_url("Login"));
        }

        $rowAdm = $this->UsersProdi_model->get_by_id($this->idUser);
        $dataAdm = array(
            'wa'       => $this->session->userdata['nama'],
            'univ'     => $this->session->userdata['divisi'] . ' ' . $this->session->userdata['lembaga'],
            'username' => $rowAdm->username,
            'level'    => $rowAdm->level,
        );

        $data = array(
            'button' => 'Create',
            'back'   => site_url('UsersProdi'),
            'action' => site_url('UsersProdi/create_action'),
            'username' => set_value('username'),
            'password' => set_value('password'),
            'nama' => set_value('nama'),
            'isActive' => set_value('isActive'),
            'idProdi'=> $this->session->userdata['idProdi'],
        );
        
        $this->load->view('header', $dataAdm);
        $this->load->view('UsersProdi/users_form', $data);
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
                'username' => $this->input->post('username', true),
                'password' => md5($this->input->post('password', true)),
                'nama' => $this->input->post('nama', true),
                'level' => 'dosen',
                'isActive' => $this->input->post('isActive', true),
                'id_sessions' => md5($this->input->post('password', true)),
                'idProdi'=> $this->session->userdata['idProdi'],
            );

            if ($this->UsersProdi_model->insert($data)) {
                $this->UsersProdi_model->addEvent(57, $this->idUser, $this->serialize_data($data));
                flashMessage('success', 'Create Record Success.');
            } else {
                flashMessage('error', 'Create Record Gagal.');
            }
            redirect(site_url('UsersProdi'));
        }
    }
    

    public function update($id)
    {
        if (!isset($this->session->userdata['username'])) {
            redirect(site_url("Login"));
        }
    
        $rowAdm = $this->UsersProdi_model->get_by_id($this->session->userdata['ID']);
        $dataAdm = array(
            'wa'       => 'Web administrator',
            'univ'     => 'LPMAI UKSW',
            'username' => $rowAdm->username,
            'level'    => $rowAdm->level,
        );

        $row = $this->UsersProdi_model->get_by_id($id);
        if ($row) {
            $data = array(
                'button' => 'Update',
                'back'   => site_url('UsersProdi'),
                'action' => site_url('UsersProdi/update_action'),
                'username' => set_value('username', $row->username),
                'nama' => set_value('nama', $row->nama),
                'ID' => set_value('ID', $row->ID),
                'level' => set_value('level', $row->level),
                'isActive' => set_value('isActive', $row->isActive),
                'idProdi'=> set_value('level', $row->idProdi),
            );

            $this->load->view('header', $dataAdm);
            $this->load->view('UsersProdi/users_form', $data);
            $this->load->view('footer');
        } else {
            flashMessage('error', 'Record Not Found.');
            redirect(site_url('UsersProdi'));
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
            $id = $this->input->post('ID', true);
            $data = array(
                'username' => $this->input->post('username', true),
                'nama' => $this->input->post('nama', true),
                'ID' =>    $id,
                'isActive' => $this->input->post('isActive', true),
                'id_sessions' => md5($this->input->post('password', true)),
            );

            $row = $this->UsersProdi_model->get_by_id($id);
            if ($this->UsersProdi_model->update($this->input->post('ID', true), $data)) {
                $keterangan =$this->serialize_data($row) . " <<Edit>> " . $this->serialize_data($data);
                $this->UsersProdi_model->addEvent(58, $this->idUser, $keterangan);
                flashMessage('success', 'Update Record Success.');
            } else {
                flashMessage('error', 'Update Record Gagal.');
            }
            redirect(site_url('UsersProdi'));
        }
    }

    public function update_password_action()
    {
        if (!isset($this->session->userdata['ID'])) {
            redirect(site_url("Login"));
        }

        $data = array(
            'password' => md5($this->input->post('password', true))
        );

        $idUser = $this->input->post('IdUser', true);
        $row = $this->UsersProdi_model->get_by_id($idUser);

        if ($this->UsersProdi_model->update($idUser, $data)) {
            $keterangan =$this->serialize_data($row) . " <<Ganti Password>> " . $this->serialize_data($data);
            $this->UsersProdi_model->addEvent(60, $this->idUser, $keterangan);

            flashMessage('success', 'Update Password Success.');
        } else {
            flashMessage('error', 'Update Password Gagal.');
        }
        redirect(site_url('UsersProdi'));
    }

    public function delete($id)
    {
        if (!isset($this->session->userdata['username'])) {
            redirect(site_url("Login"));
        }
    
        $row = $this->UsersProdi_model->get_by_id($id);
        if ($row) {
            $this->UsersProdi_model->delete($id);
            $this->UsersProdi_model->addEvent(59, $this->idUser, $this->serialize_data($row));
            
            flashMessage('success', 'Delete Record Success.');
            redirect(site_url('UsersProdi'));
        } else {
            flashMessage('error', 'Record Not Found.');
            redirect(site_url('UsersProdi'));
        }
    }

    public function _rules()
    {
        $this->form_validation->set_rules('username', 'username', 'trim|required');
        $this->form_validation->set_rules('nama', 'nama', 'trim|required');
        $this->form_validation->set_rules('isActive', 'isActive', 'trim|required');
        $this->form_validation->set_rules('username', 'username', 'trim');
        $this->form_validation->set_error_delimiters('<span class="text-danger">', '</span>');
    }
}