<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Login extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();

        $this->load->model(array('Login_model'));
    }

    public function index()
    {
        if ($this->session->userdata('username')) {
            redirect(site_url('Dashboard'));
        }


        $dataSistem = $this->Login_model->getInfoSistem();

        $data = array(
            'wa'       => $dataSistem->nama ,
            'univ'     => $dataSistem->divisi . ' ' .  $dataSistem->lembaga,
        );


        $this->load->view('login', $data);
    }

    public function proses()
    {
        $this->form_validation->set_rules('username', 'username', 'required|trim|xss_clean');
        $this->form_validation->set_rules('password', 'password', 'required|trim|xss_clean');

        if ($this->form_validation->run() == false) {
            $this->index();
        } else {
            $username = $this->input->post('username');
            $password = $this->input->post('password');
            $user   = $username;
            $pass   = md5($password);
            $cek    = $this->Login_model->cek($user, $pass);

            if ($cek->num_rows() > 0) {
                $dataInfo = $this->Login_model->getInfoSistem();
                foreach ($cek->result() as $qad) {
                    $sess_data['username'] = $qad->username;
                    $sess_data['ID']    = $qad->ID;
                    $sess_data['level']    = $qad->level;
                    $sess_data['idProdi']    = $qad->idProdi;
                    $sess_data['prodi']    = $qad->idProdi ? $this->Login_model->get_by_id_table($qad->idProdi, "tprodi_unit")->nama : "";
                    $sess_data['nama']    = $dataInfo->nama;
                    $sess_data['divisi']    = $dataInfo->divisi;
                    $sess_data['lembaga']    = $dataInfo->lembaga;
                    $sess_data['webUtama']    = $dataInfo->webUtama;
                    $this->session->set_userdata($sess_data);
                }

                $this->session->set_flashdata('success', 'Login Berhasil !');
                redirect(site_url('Dashboard'));
            } else {
                $this->session->set_flashdata('result_login', '<br>Username atau Password yang anda masukkan salah.');
                redirect(site_url('Login'));
            }
        }
    }

    public function logout()
    {
        $this->session->sess_destroy();
        redirect(site_url('Login'));
    }
}