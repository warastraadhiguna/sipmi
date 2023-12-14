<?php
class MY_Controller extends CI_Controller
{
    public $idUser;
    
    public function __construct()
    {
        parent::__construct();
        if ($this->session->userdata('nama') and $this->session->userdata('divisi')) {
            $this->load->vars([
            'namaSistem' => $this->session->userdata['nama'],
            'divisi' => $this->session->userdata['divisi'],
            'lembaga' => $this->session->userdata['lembaga'],
            'webUtama' => $this->session->userdata['webUtama'],
        ]);
            $this->idUser = $this->session->userdata['ID'];
        } else {
            redirect(site_url('Login'));
        }
    }

    public function serialize_data($data)
    {
        $result = print_r($data, true);
        $result = str_replace("Array", "", $result);
        $result = str_replace("stdClass Object", "", $result);
        $result = str_replace("(", "", $result);
        $result = str_replace(")", "", $result);

        return $result;
    }
}