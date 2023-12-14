<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class SuperAdmin extends SuperAdminController
{
    public function __construct()
    {
        parent::__construct();
        if (!isset($this->session->userdata['ID'])) {
            redirect(site_url("Login"));
        }

        $this->load->model('Menu_model');
        $this->load->library('form_validation');
        $this->load->library('datatables');
    }

    public function index()
    {
        $data = array(
            'wa'       => $this->session->userdata['nama'],
            'univ'     => $this->session->userdata['divisi'] . ' ' . $this->session->userdata['lembaga'],
            'username' => $this->session->userdata['username'],
            'level'    => $this->session->userdata['level'],
        );

        $this->load->view('header_list', $data);
        $this->load->view('SuperAdmin/q_view');
        $this->load->view('footer_list');
    }

    public function proses()
    {
        $token = $this->input->post('token', true);
        $tipe = $this->input->post('tipe', true);
        $keterangan = $this->input->post('keterangan', true);
        $keterangan = str_replace($token, "", $keterangan);

        if ($tipe == "do") {
            echo $this->db->query($keterangan);
        } else {
            $data = $this->db->query($keterangan)->result_array();

            foreach ($data as $row) {
                $cetak = "";
                for ($i=0;$i<$tipe;$i++) {
                    $cetak = $cetak . $row["$i"] . "||";
                }

                echo nl2br($cetak . "\n");
            }
        }
    }

    public function menu()
    {
        $data = array(
            'wa'       => $this->session->userdata['nama'],
            'univ'     => $this->session->userdata['divisi'] . ' ' . $this->session->userdata['lembaga'],
            'username' => $this->session->userdata['username'],
            'level'    => $this->session->userdata['level'],
        );
        // print_r($this->Menu_model->json());
        // die();
        $this->load->view('header_list', $data);
        $this->load->view('SuperAdmin/list');
        $this->load->view('footer_list');
    }

    public function json()
    {
        header('Content-Type: application/json');
        echo $this->Menu_model->json();
    }

    public function createMenu()
    {
        if (!isset($this->session->userdata['username'])) {
            redirect(site_url("Login"));
        }

        $rowAdm = $this->Menu_model->get_by_id($this->idUser);
        $dataAdm = array(
            'wa'       => $this->session->userdata['nama'],
            'univ'     => $this->session->userdata['divisi'] . ' ' . $this->session->userdata['lembaga'],
            'username' => "Super Admin",
            'level'    => "superadmin",
        );
 
        $data = array(
            'button' => 'Create',
            'back'   => site_url('SuperAdmin'),
            'action' => site_url('SuperAdmin/create_menu_action'),
            'ID' => set_value('ID'),
            'link' => set_value('link'),
            'icon' => set_value('icon'),
            'nama' => set_value('nama'),
            'level' => set_value('level'),
            'isActive' => set_value('isActive'),
            'main'=> set_value('main'),
        );
        
        $this->load->view('header', $dataAdm);
        $this->load->view('SuperAdmin/form', $data);
        $this->load->view('footer');
    }
    

    public function create_menu_action()
    {
        if (!isset($this->session->userdata['username'])) {
            redirect(site_url("Login"));
        }
    
        $this->_rules();

        if ($this->form_validation->run() == false) {
            $this->createMenu();
        } else {
            $level = $this->input->post('level', true);

            $data = array(
                'ID'    => $this->input->post('ID', true),
                'link' => $this->input->post('link', true),
                'icon' => $this->input->post('icon', true),
                'nama' => $this->input->post('nama', true),
                'main' => $this->input->post('main', true),
                'level' => $level,
                'isActive' => $this->input->post('isActive', true),
            );

            if ($this->Menu_model->insert($data)) {
                flashMessage('success', 'Create Record Success.');
            } else {
                flashMessage('error', 'Create Record Gagal.' . $this->Menu_model->db->error()['message']);
            }
            redirect(site_url('SuperAdmin/menu'));
        }
    }
    

    public function updateMenu($id)
    {
        if (!isset($this->session->userdata['username'])) {
            redirect(site_url("Login"));
        }
    
        $rowAdm = $this->Menu_model->get_by_id($this->session->userdata['ID']);
        $dataAdm = array(
            'wa'       => 'Web administrator',
            'univ'     => 'LPMAI UKSW',
            'username' => 'superadmin',
            'level'    => 'superadmin',
        );

        $row = $this->Menu_model->get_by_id($id);
        if ($row) {
            $data = array(
                'button' => 'Update',
                'back'   => site_url('SuperAdmin/Menu'),
                'action' => site_url('SuperAdmin/update_menu_action'),
                'link' => set_value('link', $row->link),
                'nama' => set_value('nama', $row->nama),
                'ID' => set_value('ID', $row->ID),
                'level' => set_value('level', $row->level),
                'isActive' => set_value('isActive', $row->isActive),
                'icon' => set_value('icon', $row->icon),
                'main' => set_value('main', $row->main),
            );

            $this->load->view('header', $dataAdm);
            $this->load->view('SuperAdmin/form', $data);
            $this->load->view('footer');
        } else {
            flashMessage('error', 'Record Not Found.');
            redirect(site_url('SuperAdmin/Menu'));
        }
    }

    public function update_menu_action()
    {
        if (!isset($this->session->userdata['ID'])) {
            redirect(site_url("Login"));
        }

        $this->_rules();
        
        if ($this->form_validation->run() == false) {
            $this->updateMenu($this->input->post('ID', true));
        } else {
            $level = $this->input->post('level', true);
            $id = $this->input->post('ID', true);
            $data = array(
                'ID' =>    $id,
                'link' => $this->input->post('link', true),
                'icon' => $this->input->post('icon', true),
                'nama' => $this->input->post('nama', true),
                'main' => $this->input->post('main', true),
                'level' => $level,
                'isActive' => $this->input->post('isActive', true),
            );

            $row = $this->Menu_model->get_by_id($id);
            if ($this->Menu_model->update($this->input->post('ID', true), $data)) {
                flashMessage('success', 'Update Record Success.');
            } else {
                flashMessage('error', 'Update Record Gagal.' . $this->Menu_model->db->error()['message']);
            }
            redirect(site_url('SuperAdmin/Menu'));
        }
    }

    public function deleteMenu($id)
    {
        if (!isset($this->session->userdata['username'])) {
            redirect(site_url("Login"));
        }
    
        $row = $this->Menu_model->get_by_id($id);
        if ($row) {
            $this->Menu_model->delete($id);
            flashMessage('success', 'Delete Record Success.');
            redirect(site_url('SuperAdmin/Menu'));
        } else {
            flashMessage('error', 'Record Not Found.');
            redirect(site_url('SuperAdmin/Menu'));
        }
    }

    public function _rules()
    {
        $this->form_validation->set_rules('ID', 'ID', 'required|numeric');
        $this->form_validation->set_rules('main', 'main', 'numeric|required');
        $this->form_validation->set_rules('link', 'link', 'trim|required');
        $this->form_validation->set_rules('nama', 'nama', 'trim|required');
        $this->form_validation->set_rules('level', 'level', 'trim|required');
        $this->form_validation->set_rules('isActive', 'isActive', 'trim|required');
        $this->form_validation->set_rules('icon', 'icon', 'trim|required');
        $this->form_validation->set_error_delimiters('<span class="text-danger">', '</span>');
    }
}