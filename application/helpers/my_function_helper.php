<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

function flashMessage($type, $message)
{
    $CI =& get_instance();
    $CI->load->library('session');
    $CI->session->set_flashdata($type, $message);
}

function showFlashMessage()
{
    $CI =& get_instance();
    $CI->load->library('session');

    $success = $CI->session->flashdata('success');
    $warning = $CI->session->flashdata('warning');
    $error   = $CI->session->flashdata('error');

    if ($success) {
        $alertStatus = 'alert-success';
        $message = $success;
    }

    if ($warning) {
        $alertStatus = 'alert-warning';
        $message = $warning;
    }

    if ($error) {
        $alertStatus = 'alert-danger';
        $message = $error;
    }

    $str = '';
    if ($success || $warning || $error) {
        $str  = '<div class="alert ' . $alertStatus . ' alert-dismissible" role="alert">';
        $str .= '<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>';
        $str .= $message;
        $str .= '</div>';
    }

    return $str;
}


// Fungsi untuk membuat tanggal dengan format Indonesia
function tgl_indo($tgl){
	$tanggal = substr($tgl,8,2);
	$bulan = getBulan(substr($tgl,5,2));
	$tahun = substr($tgl,0,4);
	return $tanggal.' '.$bulan.' '.$tahun;		 
}

// Fungsi untuk membuat bulan dengan format Indonesia
function getBulan($bln){
				switch ($bln){
					case 1: 
						return "Januari";
						break;
					case 2:
						return "Februari";
						break;
					case 3:
						return "Maret";
						break;
					case 4:
						return "April";
						break;
					case 5:
						return "Mei";
						break;
					case 6:
						return "Juni";
						break;
					case 7:
						return "Juli";
						break;
					case 8:
						return "Agustus";
						break;
					case 9:
						return "September";
						break;
					case 10:
						return "Oktober";
						break;
					case 11:
						return "November";
						break;
					case 12:
						return "Desember";
						break;
				}
	}
	

// Fungsi untuk melakukan input data	
function inputtext($name, $table, $field, $primary_key, $selected){
	$ci = get_instance();
	$data = $ci->db->get($table)->result();
	foreach($data as $t){
		if($selected == $t->$primary_key){
		$txt = $t->$field;
		}
	}
	return $txt;
}

// Fungsi untuk menampilkan data dalam bentuk combobox
function combobox($name, $table, $field, $primary_key, $selected,$where = '', $orderBy =''){
	$ci = get_instance();
	$cmb = "<select name='$name' class='form-control'>";

	if($orderBy)
	{
		$ci->db->order_by($orderBy, 'desc');
	}
	if($where)
	{
		$ci->db->where($where);
	}	
	
	$data = $ci->db->get($table)->result();
	$cmb .="<option value=''>-- PILIH --</option>";
			
	foreach($data as $d)
	{		
		$isPassed = 0;
		if(array_key_exists("isActive",$d)) 
		{
			if($d->isActive == "Tidak Aktif")
			{
				$isPassed = 1;
			}
		}

		if($isPassed == 0)
		{
			$cmb .="<option value='".$d->$primary_key."'";
			$cmb .= $selected==$d->$primary_key?"selected='selected'":'';
			$cmb .=">". strtoupper($d->$field)."</option>";
		}
	}
	$cmb .="</select>";
	return $cmb;
}

function comboboxdatatables($name, $table, $field, $primary_key, $selected,$where = '', $orderBy =''){
	$ci = get_instance();
	$cmb = "<select name='$name' class='form-control'>";

	if($orderBy)
	{
		$ci->db->order_by($orderBy, 'desc');
	}
	if($where)
	{
		$ci->db->where($where);
	}	
		
	$data = $ci->db->get($table)->result();
			
	foreach($data as $d)
	{		
		$isPassed = 0;
		if(array_key_exists("isActive",$d)) 
		{
			if($d->isActive == "Tidak Aktif")
			{
				$isPassed = 1;
			}
		}

		if($isPassed == 0)
		{
			$cmb .="<option value='".$d->$primary_key."'";
			$cmb .= $selected==$d->$primary_key?"selected='selected'":'';
			$cmb .=">". strtoupper($d->$field). "</option>";
		}
	}
	$cmb .="</select>";
	return $cmb;
}

//fungsi SEO
function seo_title($s) {
    $c = array (' ');
    $d = array ('-','/','\\',',','.','#',':',';','\'','"','[',']','{','}',')','(','|','`','~','!','@','%','$','^','&','*','=','?','+');

    $s = str_replace($d, '', $s); // Hilangkan karakter yang telah disebutkan di array $d
    
    $s = strtolower(str_replace($c, '-', $s)); // Ganti spasi dengan tanda - dan ubah hurufnya menjadi kecil semua
    return $s;
} 


