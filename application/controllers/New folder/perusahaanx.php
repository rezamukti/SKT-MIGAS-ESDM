<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Perusahaan extends CI_Controller
{
	
    public function __construct()
    {
        parent::__construct();

        $this->load->database();
        $this->load->helper('url');
        $this->load->library('pagination');

        $this->load->library('grocery_CRUD');

        if ($this->session->userdata('level') == NULL) {
            $this->logs('Anda tidak berhak mengakses halaman ini!');
            // $this->logout();
        }

    }
//####################################################################################################################################
// 														UNTUK PERUSAHAAN
//####################################################################################################################################	

// 5.1 FUNGSI BIDANG_USAHA

    public function bidang_usaha()
    {
        $bidang_usaha = $this->input->post('bidang_usaha', TRUE);
        $sub_bidang = $this->model->selects('*', 'ref_sub_bidang', array('bidang_usaha' => $bidang_usaha));

        $sub_bidang_obj = '<label>Sub Bidang</label>: <select name="sub_bidang" id="sub_bidang">';
        $sub_bidang_obj .= '<option value="">-Pilih Sub Bidang-</option>';

        foreach ($sub_bidang as $key => $sbdg) {
            $sub_bidang_obj .= '<option name="sub_bidang" id="sb-' . $sbdg->id_sub_bidang . '" onclick="sub_bidang(' . $sbdg->id_sub_bidang . ')" value="' . $sbdg->id_sub_bidang . '">' . $sbdg->sub_bidang . '</option>';
        }

        $sub_bidang_obj .= '</select>';

        $json['bidangusaha'] = $sub_bidang_obj;
        //$json['menu'] = '';
        echo json_encode($json);
    }

//***************************************************************************************************************************
// 5.2 FUNGSI SUB_BIDANG

    public function sub_bidang()
    {
        $sub_bidang = $this->input->post('sub_bidang', TRUE);
        $bagian_sub_bidang = $this->model->selects('*', 'ref_bagian_sub_bidang', array('id_sub_bidang' => $sub_bidang));

        $bagian_sub_bidang_obj = '<label>Bagian Sub Bidang<br/>& Sub Bagian Sub Bidang</label>: <br/>';
        // $sub_bidang_obj .= '<option value="">-Pilih Sub Bidang-</option>';

        foreach ($bagian_sub_bidang as $key => $sbdg) {
            $bagian_sub_bidang_obj .= '<input class="checkbox-bsb" type="checkbox" name="bagian_sub_bidang[]" id="bsb-' . $sbdg->id_bagian_sub_bidang . '" onchange="bagian_sub_bidang(' . $sbdg->id_bagian_sub_bidang . ')" value="' . $sbdg->bagian_sub_bidang . '"> ' . $sbdg->bagian_sub_bidang . '<div class="chk-sbsb" id="sub-bagian-sub-bidang-' . $sbdg->id_bagian_sub_bidang . '"></div>';

        }
        $json['subbidang'] = $bagian_sub_bidang_obj;
        //$json['menu'] = '';
        echo json_encode($json);
    }

//***************************************************************************************************************************
// 5.3 FUNGSI BAGIAN_SUB_BIDANG

    public function bagian_sub_bidang()
    {
        $bagian_sub_bidang = $this->input->post('bagian_sub_bidang', TRUE);
        $sub_bagian_sub_bidang = $this->model->selects('*', 'ref_sub_bagian_sub_bidang', array('id_bagian_sub_bidang' => $bagian_sub_bidang));
        $sub_bagian_sub_bidang_obj = '';
        foreach ($sub_bagian_sub_bidang as $key => $sbsbdg) {
            $sub_bagian_sub_bidang_obj .= '<input class="checkbox-sbsb" type="checkbox" id="sbsb-' . $sbsbdg->id_sub_bagian_sub_bidang . '" name="sub_bagian_sub_bidang[]" onchange="sub_bagian_sub_bidang(' . $sbsbdg->id_sub_bagian_sub_bidang . ')" value="' . $sbsbdg->sub_bagian_sub_bidang . '"> ' . $sbsbdg->sub_bagian_sub_bidang . '<br/>';
        }
        $sub_bagian_sub_bidang_obj .= '<hr/>';

        $json['bagiansubbidang'] = $sub_bagian_sub_bidang_obj;
        //$json['menu'] = '';
        echo json_encode($json);
    }

//***************************************************************************************************************************
// 5.4 FUNGSI PENGAJUAN

    public function pengajuan()
    {
        $level = $this->session->userdata('level');

        if ($level != NULL) {
            if ($level == 1) {
                $this->load->view('level1/skt_tabel');
            } else {
                redirect('all_users/dashboard');
            }
        } elseif ($level == NULL) {
            redirect('umum/logout');
        }
    }

//***************************************************************************************************************************
// 5.5 FUNGSI DATA_UMUM

    public function data_umum()
    {
        $c = new grocery_crud();
        $c->set_table('data_umum');
        $c->where('id_perusahaan', $this->session->userdata('id_perusahaan'));
        $this->load->config('grocery_crud');
        $this->config->set_item('grocery_crud_file_upload_allow_file_types', 'pdf');

        // $c->unset_delete();
        // $c->unset_export();
        // $c->unset_print();


        $c->required_fields('nomor', 'jenis_dokumen', 'penerbit', 'tanggal_terbit', 'akhir_masa_berlaku', 'file_dokumen');
        $c->set_field_upload('file_dokumen', 'assets/uploads/files');
        $c->set_crud_url_path(base_url(strtolower(__CLASS__ . "/" . __FUNCTION__)), base_url(strtolower(__CLASS__ . "/data_pemohon")));

        //field type
        $c->field_type('id_perusahaan', 'hidden', $this->session->userdata('id_perusahaan'));

        // unset field
        $c->unset_fields('status', 'catatan_petugas', 'status_pemakaian');

        // unset columns
        $c->unset_columns('id_perusahaan', 'status', 'catatan_petugas', 'status_pemakaian');

        // set relation
        $c->set_relation('jenis_dokumen', 'ref_jenis_dokumen', 'jenis_dokumen');

        // display as
        $c->display_as('jenis_dokumen', 'Dokumen');
        $c->display_as('akhir_masa_berlaku', 'Berlaku Hingga Tanggal');

        $output = $c->render();
        $this->logs();

        if ($c->getState() != 'list') {
            $this->data_pemohon($output);
        } else {
            return $output;
        }

    }

//***************************************************************************************************************************
// 5.6 FUNGSI DATA_KHUSUS

    public function data_khusus()
    {
        $c = new grocery_crud();
        $c->set_table('data_khusus');
        $c->where('id_perusahaan', $this->session->userdata('id_perusahaan'));
        $this->load->config('grocery_crud');
        $this->config->set_item('grocery_crud_file_upload_allow_file_types', 'pdf');
        
		
		if($this->session->userdata('id_permohonan') != ''){
			// $c->unset_delete();
			// $c->unset_read();
			// $c->unset_edit();
			
			// $c->add_action('Pilih', 'sd', 'pilih/data','ui-icon-plus');
		}
		
		
        $c->required_fields('nomor', 'jenis_dokumen', 'penerbit', 'tanggal_terbit', 'akhir_masa_berlaku', 'file_dokumen');
        $c->set_field_upload('file_dokumen', 'assets/uploads/files');
        $c->set_crud_url_path(base_url(strtolower(__CLASS__ . "/" . __FUNCTION__)), base_url(strtolower(__CLASS__ . "/data_pemohon")));

        //field type
        $c->field_type('id_perusahaan', 'hidden', $this->session->userdata('id_perusahaan'));
        $c->field_type('id_permohonan', 'hidden', $this->session->userdata('id_permohonan'));

        // unset field
        $c->unset_fields('status', 'id_sub_bidang', 'catatan_petugas', 'status_pemakaian');

        // unset columns
        $c->unset_columns('id_perusahaan', 'status', 'id_sub_bidang', 'id_permohonan', 'catatan_petugas', 'status_pemakaian');

        // display as
        $c->display_as('jenis_dokumen', 'Dokumen');
        $c->display_as('akhir_masa_berlaku', 'Berlaku Hingga Taggal');


        $output = $c->render();
        $this->logs();

        if ($c->getState() != 'list') {
            $this->data_pemohon($output);
        } else {
            return $output;
        }
    }


//***************************************************************************************************************************
// 5.8 FUNGSI DATA_PEMOHON

    function data_pemohon()
    { //multigrid
        $this->config->load('grocery_crud');
        $this->config->set_item('grocery_crud_dialog_forms', true);
        $this->config->set_item('grocery_crud_default_per_page', 10);

        $output2 = $this->data_umum();
        $output3 = $this->data_khusus();

        $js_files =  $output2->js_files + $output3->js_files;
        $css_files =  $output2->css_files + $output3->css_files;
        $output = "<h2>1. Data Umum</h2>" . $output2->output . "<br/><hr/><h2>2. Data Khusus</h2>" . $output3->output .
            '<span style="color:red; font-size:12px">Keterangan: <br/>1. *)Persetujuan Penanaman Modal Asing (dari BKPM) & Izin Usaha Tetap (dari BKPM)* Wajib diisi apabila pemohon merupakan perusahaan Penanaman Modal Asing (PMA).
		<br/>2. **)Surat Izin usaha sesuai dengan bidang usaha yang dimohonkan contoh: SIUJK, SIUP, Surat Tanda Pendafataran bagi Agen Tunggal/Distributor, dst.</span>';

        $level = $this->session->userdata('level');
        if ($level != NULL) {
            if ($level == 1) {
                $this->load->view('level1/skt_tabel', (object)array(
                    'js_files' => $js_files,
                    'css_files' => $css_files,
                    'output' => $output
                ));
            } else {
                redirect('all_users/dashboard');
            }
        } elseif ($level == NULL) {
            redirect('umum/logout');
        }
    }

//***************************************************************************************************************************
// 5.9 FUNGSI KEANGGOTAAN_ASOSIASI

    public function keanggotaan_asosiasi()
    {
        $c = new grocery_crud();
        $c->set_table('keanggotaan_asosiasi');

        $c->where('id_perusahaan', $this->session->userdata('id_perusahaan'));
        $c->unset_columns('id_perusahaan', 'catatan_petugas', 'id_permohonan', 'status_pemakaian');
        $c->required_fields('asosiasi', 'nomor_anggota', 'berlaku_hingga', 'file_keanggotaan_asosiasi');

        $c->field_type('id_perusahaan', 'hidden', $this->session->userdata('id_perusahaan'));

        if ($this->session->userdata('id_permohonan') != '') {
            $sess_id_permohonan =  $this->session->userdata('id_permohonan');
        }else{
            $sess_id_permohonan =  '';
        }
        
        $c->field_type('id_permohonan', 'hidden', $sess_id_permohonan);
        $c->set_field_upload('file_keanggotaan_asosiasi', 'assets/uploads/file_keanggotaan_asosiasi');

        $c->unset_fields('catatan_petugas', 'status_pemakaian');
        //$c->unset_delete();

        $output = $c->render();
        $this->logs();

        $level = $this->session->userdata('level');
        if ($level != NULL) {
            if ($level == 1) {
                $this->load->view('level1/skt_tabel', $output);
            } else {
                redirect('all_users/dashboard');
            }
        } elseif ($level == NULL) {
            $this->logs();
            redirect('umum/logout');
        }
    }

//***************************************************************************************************************************
// 5.10 FUNGSI TENAGA_KERJA

    public function tenaga_kerja()
    {
        $c = new grocery_crud();
        $c->set_table('tenaga_kerja');
        $c->where('tenaga_kerja.id_perusahaan', $this->session->userdata('id_perusahaan'));
        $this->load->config('grocery_crud');
        $this->config->set_item('grocery_crud_file_upload_allow_file_types', 'pdf');
        //$c->unset_delete();
        //$c->required_fields('nama_lengkap', 'status', 'jabatan', 'jenjang_pendidikan', 'jurusan_pendidikan', 'file_ijazah');
        $c->fields('nama_lengkap', 'id_perusahaan', 'status', 'jabatan', 'jenjang_pendidikan', 'jurusan_pendidikan', 'file_ijazah', 'sertifikasi');
        $c->set_crud_url_path(base_url(strtolower(__CLASS__ . "/" . __FUNCTION__)), base_url(strtolower(__CLASS__ . "/data_tenaga_kerja")));

        //field type
        $c->field_type('id_perusahaan', 'hidden', $this->session->userdata('id_perusahaan'));
        $c->field_type('status', 'enum', array('Permanen', 'Non Permanen'));
        $c->set_field_upload('file_ijazah', 'assets/uploads/file_ijazah_tenaga_ahli');
        $c->field_type('id_permohonan', 'hidden', $this->session->userdata('id_permohonan'));

        // unset field
        $c->unset_fields('id_sub_bidang', 'catatan_petugas', 'status_pemakaian');

        // unset columns
        $c->unset_columns('id_perusahaan', 'catatan_petugas', 'id_permohonan', 'status_pemakaian', 'sertifikasi');

        // set relation
        $c->set_relation('jenjang_pendidikan', 'ref_jenjang_pendidikan', 'jenjang_pendidikan', null, 'id_jenjang_pendidikan');

		$c->callback_field('sertifikasi', array($this, 'callback_sertifikasi'));
        // display as
        $c->display_as('jenjang_pendidikan', 'Pendidikan Terakhir');
        $c->display_as('jurusan_pendidikan', 'Jurusan');
        $c->display_as('jabatan', 'Keahlian');
        $c->display_as('status', 'Status Kepegawaian');
		
		$c->callback_after_insert(array($this,'callback_before_insert_or_update'));		

        $output = $c->render();
        $this->logs();

        if ($c->getState() != 'list') {
            $this->data_tenaga_kerja($output);
        } else {
            return $output;
        }

    }


    public function callback_before_insert_or_update($post_array, $primary_key){
	
		//echo '<script> alert('.json_encode($post_array['judul_pelatihan'][1]).'); </script>';
		$juduls = $post_array['judul_pelatihan'];
		$nomors = $post_array['nomor_sertifikat'];
		
		//if($primary_key != NULL){
			//if($juduls != ""){
					$count = count($juduls);
					$data = array();
					for($i=0; $i<$count; $i++) {
						$data[$i] = array(
							'id_tenaga_kerja' => $primary_key,
							'id_perusahaan' => $this->session->userdata('id_perusahaan'),
							'judul_pelatihan' => $juduls[$i],
							'nomor_sertifikat' => $nomors[$i],
							'file_sertifikat' => NULL
							);
					}
				//}
				
        
				$this->db->insert_batch('sertifikasi_tenaga_kerja', $data);				
				return TRUE;
			//} 
			
			
	}
	
//***************************************************************************************************************************
// 5.11 FUNGSI CALLBACK_SERTIFIKASI
	
    public function callback_sertifikasi(){
        //You can do it strait forward
		$output = '<script> $(document).ready(function (){ var counter = 2;	var limit = 11;	';
		$output .= '$("#btn_add").click(function(){';
		$output .= 'if (counter == limit){ alert("Anda terlalu banyak menambahkan 10 data!");	} ';
		$output .= 'else {';
		$output .= 'var clone1 = $("#cloneObject1").clone(); ';
		$output .= 'clone1.attr("id","cloneObject" +counter); clone1.empty(); ';
		$output .= 'clone1.append("<td id=\'td"+counter+"_1\'></td><td id=\'td"+counter+"_2\'></td><td id=\'td"+counter+"_3\'></td><td id=\'td"+counter+"_4\'></td>"); ';
		$output .= 'clone1.appendTo("#cloneMother"); ';
		$output .= 'var clone2 = $("#judul_pelatihan1").clone().val(""); clone2.attr("id","judul_pelatihan" +counter); ';
		$output .= 'clone2.appendTo("#td"+counter+"_1"); ';
		$output .= 'var clone3 = $("#nomor_sertifikat1").clone().val(""); clone3.attr("id","nomor_sertifikat" + counter); ';
		$output .= 'clone3.appendTo("#td"+counter+"_2"); ';
		$output .= 'var clone4 = $("#file_sertifikat1").clone().val(""); clone4.attr("id","file_sertifikat" + counter); ';
		$output .= 'clone4.appendTo("#td"+counter+"_3"); ';
		$output .= 'var clone5 = $("#btn_add").clone(); clone5.attr({id: "btn_del"+counter, onclick:"delput("+counter+")", name:"btn_del", class:"input-del"}); ';
		$output .= 'clone5.appendTo("#td"+counter+"_4"); counter++; ';
		$output .= '} }); ';
		$output .= 'function delput(d){';
		$output .= '$("#cloneObject" + d).remove();';
		$output .= '}';
		$output .= '}); </script>';
		$output .= '<div class="div-sertifikasi"><table class="tabel-sertifikasi"><thead><tr>';
		$output .= '<th class="title-sertifikat">Judul Pelatihan</th>';
		$output .= '<th class="title-sertifikat">Nomor Sertifikat</th>';
		$output .= '<th class="title-sertifikat">File Sertifikat</th>';
		$output .= '<th></th>';
		$output .= '</tr></thead>';
		$output .= '<tbody id="cloneMother"><tr id="cloneObject1">';
		$output .= '<td id="td1_1"><input class="input-sertifikat" type="text" name="judul_pelatihan[]" placeholder="Judul.." id="judul_pelatihan1" required /></td>';
		$output .= '<td id="td1_2"><input class="input-sertifikat" type="text" name="nomor_sertifikat[]" placeholder="Nomor.." id="nomor_sertifikat1" /></td>';
		$output .= '<td id="td1_3"><input class="input-sertifikat" type="file" name="file_sertifikat[]" placeholder="File" id="file_sertifikat1" /></td>';
		$output .= '<td id="td1_4"><input class="input-add" type="button" name="btn_add" id="btn_add" /></td>';
		$output .= '</tr></tbody>';
		$output .= '</table></div>';

		//Or with a view
		//$output = $this->load->view('whatever',array('value'=>$value),true);

		return $output;
    }
	
//***************************************************************************************************************************
// 5.11 FUNGSI SERTIFIKASI_TENAGA_KERJA

    public function sertifikasi_tenaga_kerja()
    {
        $c = new grocery_crud();
        $c->set_table('sertifikasi_tenaga_kerja');
        $c->where('sertifikasi_tenaga_kerja.id_perusahaan', $this->session->userdata('id_perusahaan'));
        $this->load->config('grocery_crud');
        
        $c->required_fields('id_tenaga_kerja', 'judul_pelatihan');
        $c->set_crud_url_path(base_url(strtolower(__CLASS__ . "/" . __FUNCTION__)), base_url(strtolower(__CLASS__ . "/data_tenaga_kerja")));

        //field type
        $c->field_type('id_perusahaan', 'hidden', $this->session->userdata('id_perusahaan'));

        // unset columns
		$c->unset_add();
		$c->unset_print();
		$c->unset_export();
        $c->unset_columns('id_perusahaan');
        $c->display_as('id_tenaga_kerja', 'Nama Tenaga Kerja');
        $c->set_field_upload('file_sertifikat', 'assets/uploads/file_sertifikat_tenaga_ahli');

        // set relation
        $c->set_relation('id_tenaga_kerja', 'tenaga_kerja', 'nama_lengkap', array('id_perusahaan' => $this->session->userdata('id_perusahaan')));


        $output = $c->render();
        $this->logs();

        if ($c->getState() != 'list') {
            $this->data_tenaga_kerja($output);
        } else {
            return $output;
        }
    }
	
//***************************************************************************************************************************
// 5.11 FUNGSI JUMLAH_TENAGA_KERJA

    public function jumlah_tenaga_kerja()
    {
        $c = new grocery_crud();
        $c->set_table('jumlah_tenaga_kerja');
        $c->where('id_perusahaan', $this->session->userdata('id_perusahaan'));
        $this->load->config('grocery_crud');
        //$c->unset_delete();
        $c->required_fields('tipe_tenaga_kerja', 'sd', 'smp', 'sma', 'diploma', 'sarjana', 'pasca_sarjana', 'doktor');
        $c->set_crud_url_path(base_url(strtolower(__CLASS__ . "/" . __FUNCTION__)), base_url(strtolower(__CLASS__ . "/data_tenaga_kerja")));

        //field type
        $c->field_type('id_perusahaan', 'hidden', $this->session->userdata('id_perusahaan'));

        // unset columns
        $c->unset_columns('id_perusahaan', 'catatan_petugas', 'status_pemakaian');
        $c->unset_fields('catatan_petugas', 'status_pemakaian');

        // set relation
        $c->set_relation('tipe_tenaga_kerja', 'ref_tipe_tenaga_kerja', 'tipe_tenaga_kerja');

        // display as
        $c->display_as('tipe_tenaga_kerja', 'Tenaga Kerja');
        $c->display_as('sd', 'SD');
        $c->display_as('smp', 'SMP');
        $c->display_as('sma', 'SMA');
        $c->display_as('diploma', 'Diploma (D-3)');

        $output = $c->render();
        $this->logs();

        if ($c->getState() != 'list') {
            $this->data_tenaga_kerja($output);
        } else {
            return $output;
        }
    }

//***************************************************************************************************************************
// 5.12 FUNGSI DATA_TENAGA_KERJA

    function data_tenaga_kerja()
    { //multigrid
        $this->config->load('grocery_crud');
        $this->config->set_item('grocery_crud_dialog_forms', true);
        $this->config->set_item('grocery_crud_default_per_page', 10);

        $output1 = $this->tenaga_kerja();
        $output2 = $this->sertifikasi_tenaga_kerja();
        $output3 = $this->jumlah_tenaga_kerja();

        $js_files = $output1->js_files + $output2->js_files + $output3->js_files;
        $css_files = $output1->css_files + $output2->css_files + $output3->css_files;

        $output = '<h2>1. Daftar Tenaga Kerja Ahli sesuai Bidang Usaha yang dimohon /  Quality
		Assurance / Quality Control (QA/QC)</h2><span style="color:red; font-size:12px">(dilampirkan ijazah terakhir, sertifikat kompetensi, dan riwayat pekerjaan untuk setiap tenaga ahli).</span>' . $output1->output . '
		<span style="color:red; font-size:12px">Keterangan: 
		<br/>- Pelatihan yang dicantumkan hanya pelatihan yang berhubungan dengan posisi atau jabatan.  
		<br/>- Untuk warga negara asing, wajib mencantumkan nomor IMTA pada kolom KETERANGAN.
		<br/></span>
		<hr/><h2>2. Sertifikasi Tenaga Kerja</h2>' . $output2->output.'<hr/><h2>3. Jumlah Tenaga Kerja</h2>' . $output3->output;

        $level = $this->session->userdata('level');
        if ($level != NULL) {
            if ($level == 1) {
                $this->load->view('level1/skt_tabel', (object)array(
                    'js_files' => $js_files,
                    'css_files' => $css_files,
                    'output' => $output
                ));
            } else {
                redirect('all_users/dashboard');
            }
        } elseif ($level == NULL) {
            redirect('umum/logout');
        }
    }

//***************************************************************************************************************************
// 5.13 FUNGSI PELATIHAN_TENAGA_KERJA_INTERNAL

    public function pelatihan_tenaga_kerja_internal()
    {
        $c = new grocery_crud();
        $c->set_table('pelatihan_tenaga_kerja_internal');
        $c->where('id_perusahaan', $this->session->userdata('id_perusahaan'));
        $this->load->config('grocery_crud');
        //$c->unset_delete();
        $c->required_fields('jenis_pelatihan', 'keterangan');
        $c->set_crud_url_path(base_url(strtolower(__CLASS__ . "/" . __FUNCTION__)), base_url(strtolower(__CLASS__ . "/pelatihan_tenaga_kerja")));

        //field type
        $c->field_type('id_perusahaan', 'hidden', $this->session->userdata('id_perusahaan'));
        $c->field_type('id_permohonan', 'hidden', $this->session->userdata('id_permohonan'));
        $c->field_type('keterangan', 'text');
        // unset columns
        $c->unset_columns('id_perusahaan', 'catatan_petugas', 'id_permohonan', 'status_pemakaian');
        $c->unset_fields('catatan_petugas', 'status_pemakaian');

        $output = $c->render();
        $this->logs();

        if ($c->getState() != 'list') {
            $this->pelatihan_tenaga_kerja($output);
        } else {
            return $output;
        }

    }

//***************************************************************************************************************************
// 5.14 FUNGSI PELATIHAN_TENAGA_KERJA_EKSTERNAL

    public function pelatihan_tenaga_kerja_eksternal()
    {
        $c = new grocery_crud();
        $c->set_table('pelatihan_tenaga_kerja_eksternal');
        /* $tenaga_kerja = $this->model->select('*', 'tenaga_kerja', array('id_perusahaan' => $this->session->userdata('id_perusahaan'))); */
        $c->where('pelatihan_tenaga_kerja_eksternal.id_perusahaan', $this->session->userdata('id_perusahaan'));
        $this->load->config('grocery_crud');
        //$c->unset_delete();
        $c->required_fields('id_tenaga_kerja', 'jenis_pelatihan');
        $c->set_crud_url_path(base_url(strtolower(__CLASS__ . "/" . __FUNCTION__)), base_url(strtolower(__CLASS__ . "/pelatihan_tenaga_kerja")));

        //field type
        $c->field_type('id_perusahaan', 'hidden', $this->session->userdata('id_perusahaan'));
        //$c->field_type('jenis_pelatihan', 'multiselect', array("Mutu" => "Mutu", "K3L" => "K3L", "ISO" => "ISO"));
        //$c->field_type('jenis_pelatihan','dropdown', array('1' => 'active', '2' => 'private','3' => 'spam' , '4' => 'deleted'));


        // unset columns
        $c->unset_columns('id_perusahaan', 'catatan_petugas', 'status_pemakaian');
        $c->unset_fields('catatan_petugas', 'status_pemakaian');

        // set relation
        $c->set_relation('id_tenaga_kerja', 'tenaga_kerja', 'nama_lengkap', array('id_perusahaan' => $this->session->userdata('id_perusahaan')));

        // display as
        $c->display_as('id_tenaga_kerja', 'Nama Tenaga Kerja');


        $output = $c->render();
        $this->logs();

        if ($c->getState() != 'list') {
            $this->pelatihan_tenaga_kerja($output);
        } else {
            return $output;
        }
    }

//***************************************************************************************************************************
// 5.15 FUNGSI PELATIHAN_TENAGA_KERJA

    function pelatihan_tenaga_kerja()
    { //multigrid
        $this->config->load('grocery_crud');
        $this->config->set_item('grocery_crud_dialog_forms', true);
        $this->config->set_item('grocery_crud_default_per_page', 10);

        $output1 = $this->pelatihan_tenaga_kerja_internal();

        $output2 = $this->pelatihan_tenaga_kerja_eksternal();

        $js_files = $output1->js_files + $output2->js_files;
        $css_files = $output1->css_files + $output2->css_files;
        $output = "<h2>1. Tabel Pelatihan Tenaga Kerja inhouse </h2>" . $output1->output . "<br/><hr/><h2>2. Tabel Program Pelatihan Tenaga Kerja eksternal</h2>" . $output2->output . '<span style="color:red; font-size:12px">Keterangan:
<br/>*) Disesuaikan dengan bidang usaha yang dimohonkan.</span>';

        $level = $this->session->userdata('level');
        if ($level != NULL) {
            if ($level == 1) {
                $this->load->view('level1/skt_tabel', (object)array(
                    'js_files' => $js_files,
                    'css_files' => $css_files,
                    'output' => $output
                ));
            } elseif ($level == NULL) {
                redirect('all_users/dashboard');
            }
        } elseif ($level == NULL) {
            redirect('umum/logout');
        }
    }

//***************************************************************************************************************************
// 5.16 FUNGSI PERALATAN

    public function peralatan_utama(){
        $this->config->load('grocery_crud');
        $this->config->set_item('grocery_crud_dialog_forms', true);
        $this->config->set_item('grocery_crud_default_per_page', 10);
        $this->config->set_item('grocery_crud_file_upload_allow_file_types', 'pdf');
        $c = new grocery_crud();
        $c->set_table('peralatan');

        $c->where('id_perusahaan', $this->session->userdata('id_perusahaan'));
        $c->where('golongan_alat', 'Peralatan Utama');

        // unset field
        $c->unset_fields('catatan_petugas', 'status_pemakaian');

        $c->unset_columns('id_perusahaan', 'catatan_petugas', 'id_permohonan', 'golongan_alat', 'status_pemakaian');
        $c->required_fields('nama_alat', 'tipe_alat', 'jumlah', 'lokasi', 'status_kepemilikan', 'file_kepemilikan_alat');

        $c->field_type('catatan', 'text');
        $c->field_type('status_kepemilikan', 'enum', array('Milik Sendiri', 'Sewa'));
        $c->set_field_upload('file_kepemilikan_alat', 'assets/uploads/file_kepemilikan_peralatan');
		
        $c->set_crud_url_path(base_url(strtolower(__CLASS__ . "/" . __FUNCTION__)), base_url(strtolower(__CLASS__ . "/peralatan")));
		
        $c->field_type('id_perusahaan', 'hidden', $this->session->userdata('id_perusahaan'));
        $c->field_type('id_permohonan', 'hidden', $this->session->userdata('id_permohonan'));
        $c->field_type('golongan_alat', 'hidden', 'Peralatan Utama');

        $c->display_as('tipe_alat', 'Tipe/Kapasitas');
        // set relation
        $c->set_relation('lokasi', 'ref_kota', 'kota', null, 'id_kota');

        $output = $c->render();
        $this->logs();

        if ($c->getState() != 'list') {
            $this->pelatihan_tenaga_kerja($output);
        } else {
            return $output;
        }
    }
	
    public function peralatan_pendukung(){
        $this->config->load('grocery_crud');
        $this->config->set_item('grocery_crud_dialog_forms', true);
        $this->config->set_item('grocery_crud_default_per_page', 10);
        $this->config->set_item('grocery_crud_file_upload_allow_file_types', 'pdf');
        $c = new grocery_crud();
        $c->set_table('peralatan');

        $c->where('id_perusahaan', $this->session->userdata('id_perusahaan'));
        $c->where('golongan_alat', 'Peralatan Pendukung');

        // unset field
        $c->unset_fields('catatan_petugas', 'status_pemakaian', 'file_kepemilikan_alat');

        $c->unset_columns('id_perusahaan', 'catatan_petugas', 'id_permohonan', 'golongan_alat', 'status_pemakaian', 'file_kepemilikan_alat');
        $c->required_fields('nama_alat', 'tipe_alat', 'jumlah', 'lokasi', 'status_kepemilikan');

        $c->field_type('catatan', 'text');
        $c->field_type('status_kepemilikan', 'enum', array('Milik Sendiri', 'Sewa'));
        //$c->set_field_upload('file_kepemilikan_alat', 'assets/uploads/file_kepemilikan_peralatan');
		
        $c->set_crud_url_path(base_url(strtolower(__CLASS__ . "/" . __FUNCTION__)), base_url(strtolower(__CLASS__ . "/peralatan")));
		
        $c->field_type('id_perusahaan', 'hidden', $this->session->userdata('id_perusahaan'));
        $c->field_type('id_permohonan', 'hidden', $this->session->userdata('id_permohonan'));
        $c->field_type('golongan_alat', 'hidden', 'Peralatan Pendukung');

        $c->display_as('tipe_alat', 'Tipe/Kapasitas');
        // set relation
        $c->set_relation('lokasi', 'ref_kota', 'kota', null, 'id_kota');

        $output = $c->render();
        $this->logs();

        if ($c->getState() != 'list') {
            $this->pelatihan_tenaga_kerja($output);
        } else {
            return $output;
        }
    }
	
    public function peralatan_keselamatan(){
        $this->config->load('grocery_crud');
        $this->config->set_item('grocery_crud_dialog_forms', true);
        $this->config->set_item('grocery_crud_default_per_page', 10);
        $this->config->set_item('grocery_crud_file_upload_allow_file_types', 'pdf');
        $c = new grocery_crud();
        $c->set_table('peralatan');

        $c->where('id_perusahaan', $this->session->userdata('id_perusahaan'));
        $c->where('golongan_alat', 'Peralatan Keselamatan dan Kesehatan Kerja');

        // unset field
        $c->unset_fields('catatan_petugas', 'status_pemakaian', 'file_kepemilikan_alat');

        $c->unset_columns('id_perusahaan', 'catatan_petugas', 'id_permohonan', 'golongan_alat', 'status_pemakaian', 'file_kepemilikan_alat');
        $c->required_fields('nama_alat', 'tipe_alat', 'jumlah', 'lokasi', 'status_kepemilikan');

        $c->field_type('catatan', 'text');
        $c->field_type('status_kepemilikan', 'enum', array('Milik Sendiri', 'Sewa'));
        //$c->set_field_upload('file_kepemilikan_alat', 'assets/uploads/file_kepemilikan_peralatan');
		
        $c->set_crud_url_path(base_url(strtolower(__CLASS__ . "/" . __FUNCTION__)), base_url(strtolower(__CLASS__ . "/peralatan")));
		
        $c->field_type('id_perusahaan', 'hidden', $this->session->userdata('id_perusahaan'));
        $c->field_type('id_permohonan', 'hidden', $this->session->userdata('id_permohonan'));
        $c->field_type('golongan_alat', 'hidden', 'Peralatan Keselamatan dan Kesehatan Kerja');

        $c->display_as('tipe_alat', 'Tipe/Kapasitas');
        // set relation
        $c->set_relation('lokasi', 'ref_kota', 'kota', null, 'id_kota');

        $output = $c->render();
        $this->logs();

        if ($c->getState() != 'list') {
            $this->pelatihan_tenaga_kerja($output);
        } else {
            return $output;
        }
    }

//***************************************************************************************************************************
// 5.15 FUNGSI PERALATAN

    function peralatan(){ //multigrid
        $this->config->load('grocery_crud');
        $this->config->set_item('grocery_crud_dialog_forms', true);
        $this->config->set_item('grocery_crud_default_per_page', 10);

        $output1 = $this->peralatan_utama();
        $output2 = $this->peralatan_pendukung();
        $output3 = $this->peralatan_keselamatan();

        $js_files = $output1->js_files + $output2->js_files + $output3->js_files;
        $css_files = $output1->css_files + $output2->css_files + $output3->css_files;
        $output = "<h2>1. Peralatan Utama </h2>" . $output1->output . "<br/><hr/><h2>2. Peralatan Pendukung</h2>" . $output2->output. "<br/><hr/><h2>3. Peralatan Keselamatan Kerja</h2>" . $output3->output;

        $level = $this->session->userdata('level');
        if ($level != NULL) {
            if ($level == 1) {
                $this->load->view('level1/skt_tabel', (object)array(
                    'js_files' => $js_files,
                    'css_files' => $css_files,
                    'output' => $output
                ));
            } elseif ($level == NULL) {
                redirect('all_users/dashboard');
            }
        } elseif ($level == NULL) {
            redirect('umum/logout');
        }
    }

//***************************************************************************************************************************
// 5.17 FUNGSI NILAI_INVESTASI

    public function nilai_investasi()
    {
        $this->config->load('grocery_crud');
        $this->config->set_item('grocery_crud_file_upload_allow_file_types', 'pdf');

        $c = new grocery_crud();
        $c->set_table('nilai_investasi');;
        $c->where('id_perusahaan', $this->session->userdata('id_perusahaan'));

        // unset field
        $c->unset_fields('catatan_petugas', 'status_kepemilikan', 'file_nilai_investasi');

        // unset columtn
        $c->unset_columns('id_perusahaan', 'catatan_petugas', 'status_pemakaian', 'file_nilai_investasi');

        $c->field_type('id_perusahaan', 'hidden', $this->session->userdata('id_perusahaan'));
        $c->required_fields('nama_investor', 'negara_asal', 'nominal_investasi', 'persentase');
        //$c->set_field_upload('file_nilai_investasi', 'assets/uploads/file_nilai_investasi');

        $c->display_as('persentase', 'Persentase %');

        //$c->unset_delete();
        $output = $c->render();

        $level = $this->session->userdata('level');
        if ($level != NULL) {
            if ($level == 1) {
                $this->load->view('level1/skt_tabel', $output);
            } else {
                redirect('all_users/dashboard');
            }
        } elseif ($level == NULL) {
            redirect('umum/logout');
        }
    }


//***************************************************************************************************************************
// 5.18 FUNGSI PENGALAMAN_KERJA

    function pengalaman_kerja()
    { //multigrid
        $c = new grocery_crud();
        $c->set_table('daftar_pekerjaan');
        $c->where('id_perusahaan', $this->session->userdata('id_perusahaan'));
        $this->load->config('grocery_crud');
        //$c->unset_delete();
        $c->required_fields('nama_pekerjaan', 'tujuan_pelaksanaan', 'pemberi_kerja', 'lokasi_kerja', 'nilai_kontrak');
        $c->set_relation('lokasi_kerja', 'ref_kota', 'kota', null, 'id_kota');
        //field type
        $c->field_type('id_perusahaan', 'hidden', $this->session->userdata('id_perusahaan'));
        $c->field_type('id_permohonan', 'hidden', $this->session->userdata('id_permohonan'));

        // display as
        $c->display_as('id_daftar_pekerjaan', 'Nama Pekerjaan');
        $c->display_as('k3l', 'K3L');
        $c->display_as('iso', 'ISO');

        // unset columns
        $c->unset_columns('id_perusahaan', 'id_permohonan', 'catatan_petugas', 'status_pemakaian');
        $c->unset_fields('catatan_petugas', 'status_pemakaian');

        $output = $c->render();
        $this->logs();

        $level = $this->session->userdata('level');
        if ($level != NULL) {
            if ($level == 1) {
                $this->load->view('level1/skt_tabel', $output);
            } else {
                redirect('all_users/dashboard');
            }
        } elseif ($level == NULL) {
            redirect('umum/logout');
        }
    }

//***************************************************************************************************************************
// 5.19 FUNGSI SOP

    public function sop()
    {


        $c = new grocery_crud();
        $c->set_table('sop');

        $this->config->load('grocery_crud');
        $this->config->set_item('grocery_crud_file_upload_allow_file_types', 'pdf');

        $c->where('id_perusahaan', $this->session->userdata('id_perusahaan'));
        
        $c->required_fields('prosedur', 'deskripsi', 'file_manajemen_prosedur_kerja');

        $c->field_type('id_perusahaan', 'hidden', $this->session->userdata('id_perusahaan'));
        $c->field_type('id_permohonan', 'hidden', $this->session->userdata('id_permohonan'));

        $c->unset_fields('catatan_petugas','id_permohonan', 'status_pemakaian');
        $c->unset_columns('id_perusahaan','catatan_petugas','id_permohonan', 'status_pemakaian');
        //$c->unset_delete();
        $c->field_type('deskripsi', 'text');
        $c->display_as('prosedur', 'Prosedur Yang Digunakan');
        $c->display_as('deskripsi', 'Deskripsi Singkat Penerapan Prosedur');

        $c->set_field_upload('file_manajemen_prosedur_kerja', 'assets/uploads/file_sistem_manajemen_dan_prosedur_kerja_teknis');

        $output = $c->render();
        $this->logs();

        $level = $this->session->userdata('level');
        if ($level != NULL) {
            if ($level == 1) {
                $this->load->view('level1/skt_tabel', $output);
            } else {
                redirect('all_users/dashboard');
            }
        } elseif ($level == NULL) {
            redirect('umum/logout');
        }
    }

//***************************************************************************************************************************
// 5.20 FUNGSI CSR

    public function csr()
    {
        $c = new grocery_crud();
        $c->set_table('csr');

        $c->where('id_perusahaan', $this->session->userdata('id_perusahaan'));
        
        $c->required_fields('kegiatan', 'waktu', 'lokasi');
        $c->set_relation('lokasi', 'ref_kota', 'kota', null, 'id_kota');

        $c->field_type('id_perusahaan', 'hidden', $this->session->userdata('id_perusahaan'));
        $c->set_field_upload('file_csr', 'assets/uploads/file_csr');

        $c->unset_fields('catatan_petugas', 'status_pemakaian');
        $c->unset_columns('id_perusahaan', 'catatan_petugas', 'status_pemakaian');
        $c->display_as('file_csr', 'File CSR');
        //$c->unset_delete();

        $output = $c->render();
        $this->logs();

        $level = $this->session->userdata('level');
        if ($level != NULL) {
            if ($level == 1) {
                $this->load->view('level1/skt_tabel', $output);
            } else {
                redirect('all_users/dashboard');
            }
        } elseif ($level == NULL) {
            redirect('umum/logout');
        }
    }

//***************************************************************************************************************************
// 5.21 FUNGSI JENIS_PERMOHONAN_BIDANG_USAHA

    public function jenis_permohonan_bidang_usaha()
    {

        if ($this->input->post('bidang_usaha', TRUE) == '01') {
            $bidang_usaha = 'Jasa Konstruksi';
        } elseif ($this->input->post('bidang_usaha', TRUE) == '02') {
            $bidang_usaha = 'Jasa Non Konstruksi';
        } else {
            $bidang_usaha = 'Industri Penunjang';
        }

        $sub_bidang = $this->model->select('*', 'ref_sub_bidang', array('id_sub_bidang' => $this->input->post('sub_bidang', TRUE)));
        $bagian_sub_bidang = implode(", ", $this->input->post('bagian_sub_bidang', TRUE));
        $sub_bagian_sub_bidang = implode(", ", $this->input->post('sub_bagian_sub_bidang', TRUE));
        $data = array(
            'id_perusahaan' => $this->session->userdata('id_perusahaan'),
            'jenis_permohonan' => $this->input->post('jenis_permohonan', TRUE),
            'bidang_sub_bidang' => $bidang_usaha . '/' . $sub_bidang->sub_bidang,
            'bagian_sub_bidang' => $bagian_sub_bidang,
            'sub_bagian_sub_bidang' => $sub_bagian_sub_bidang,
        );
        $this->model->insert('permohonan', $data);

        $this->session->set_userdata('id_permohonan', $this->db->insert_id());
        $this->logs();
        if ($this->db->affected_rows() > 0) {
            redirect(base_url('perusahaan/data_pemohon'));
        }

    }

//***************************************************************************************************************************
// 5.22 FUNGSI STATUS_PROGRESS

    public function status_progress()
    {
        $id = $this->model->select('*', 'biodata_perusahaan', array('id_perusahaan' => $this->session->userdata('id_perusahaan')));
        $stat = $this->model->select('*', 'ref_status_progres', array('key_status' => $id->status_progress));
        $disposisi = $this->model->select('*', 'disposisi', array('id_perusahaan' => $this->session->userdata('id_perusahaan'), 'status_progress' => $id->status_progress));
        $dis = $this->model->selects('*', 'view_disposisi_permohonan', array('id_perusahaan' => $this->session->userdata('id_perusahaan'), 'status_progress' => 2));        

        $output = array('status' => $dis);

        // echo var_dump($output);

        $level = $this->session->userdata('level');
        if ($level != NULL) {
            if ($level == 1) {
                $this->load->view('level1/skt_tabel', $output);
            } else {
                redirect('all_users/dashboard');
            }
        } elseif ($level == NULL) {
            redirect('umum/logout');
        }

    }

 public function disposisi_user_to_admin($id_perusahaan)
    {

        $admin = $this->model->select('id_user', 'users', array('level' => 2, 'status' => 1));
        $disposisi = $this->model->select('*', 'disposisi', array('id_perusahaan' => $this->session->userdata('id_perusahaan')));
        // if ($disposisi->status_progress == 1) {
        //     $status_progress = 2;
        // } elseif ($disposisi->status_progress == 3) {
        //     $status_progress = 32;
        // } elseif ($disposisi->status_progress == 10) {
        //     $status_progress = 102;
        // } elseif ($disposisi->status_progress == 32) {
        //     $status_progress = 32;
        // } elseif ($disposisi->status_progress == 102) {
        //     $status_progress = 102;
        // }
        $data_disposisi = array(
            'id_perusahaan' => $id_perusahaan,
            'user_asal' => $this->session->userdata('id_user'),
            'user_tujuan' => $admin->id_user,
            'catatan' => $this->input->post('catatan', TRUE),
            'status_progress' => 2,
            'id_permohonan' => $this->session->userdata('id_permohonan')
        );

        $this->model->insert('disposisi', $data_disposisi);
        $this->model->update('biodata_perusahaan', array('status_progress' => 2), array('id_perusahaan' => $id_perusahaan));
        $this->model->update('permohonan', array('selesai' => 1), array('id_perusahaan' => $id_perusahaan));
        echo "<script>alert('Terima kasih telah melengkapi proses registrasi, data Anda segera akan kami proses')</script>";
        // echo "<script>window.history.back()</script>";
        $this->session->set_flashdata('message', 'Registrasi anda berhasil!');
        redirect(base_url('all_users/dashboard'));
    }

	//***************************************************************************************************************************
// 5.2 FUNGSI SUB_BIDANG

    public function sub_bidang_skp()
    {
        $sub_bidang = $this->input->post('sub_bidang', TRUE);
        $bagian_sub_bidang = $this->model->selects('*', 'ref_bagian_sub_bidang', array('id_sub_bidang' => $sub_bidang));

        $bagian_sub_bidang_obj = '<label>Bagian Sub Bidang<br/>& Sub Bagian Sub Bidang</label>: <br/>';
        // $sub_bidang_obj .= '<option value="">-Pilih Sub Bidang-</option>';

        foreach ($bagian_sub_bidang as $key => $sbdg) {
            $bagian_sub_bidang_obj .= '<input class="checkbox-bsb" type="checkbox" name="bagian_sub_bidang[]" id="bsb-' . $sbdg->id_bagian_sub_bidang . '" onchange="bagian_sub_bidang(' . $sbdg->id_bagian_sub_bidang . ')" value="' . $sbdg->bagian_sub_bidang . '"> ' . $sbdg->bagian_sub_bidang . '<div class="chk-sbsb" id="sub-bagian-sub-bidang-' . $sbdg->id_bagian_sub_bidang . '"></div>';

        }
        $json['subbidang'] = $bagian_sub_bidang_obj;
        //$json['menu'] = '';
        echo json_encode($json);
    }

//***************************************************************************************************************************
// 5.3 FUNGSI BAGIAN_SUB_BIDANG

    public function bagian_sub_bidang_skp()
    {
        $bagian_sub_bidang = $this->input->post('bagian_sub_bidang', TRUE);
        $sub_bagian_sub_bidang = $this->model->selects('*', 'ref_sub_bagian_sub_bidang', array('id_bagian_sub_bidang' => $bagian_sub_bidang));
        $sub_bagian_sub_bidang_obj = '';
        foreach ($sub_bagian_sub_bidang as $key => $sbsbdg) {
            $sub_bagian_sub_bidang_obj .= '<input class="checkbox-sbsb" type="checkbox" id="sbsb-' . $sbsbdg->id_sub_bagian_sub_bidang . '" name="sub_bagian_sub_bidang[]" onchange="sub_bagian_sub_bidang(' . $sbsbdg->id_sub_bagian_sub_bidang . ')" value="' . $sbsbdg->sub_bagian_sub_bidang . '"> ' . $sbsbdg->sub_bagian_sub_bidang . '<br/>';
        }
        $sub_bagian_sub_bidang_obj .= '<hr/>';

        $json['bagiansubbidang'] = $sub_bagian_sub_bidang_obj;
        //$json['menu'] = '';
        echo json_encode($json);
    }

//***************************************************************************************************************************
// 5.4 FUNGSI PENGAJUAN

    public function pengajuan_skp()
    {
        $level = $this->session->userdata('level');

        if ($level != NULL) {
            if ($level == 1) {
                $this->load->view('level1/skp_tabel');
            } else {
                redirect('all_users/dashboard');
            }
        } elseif ($level == NULL) {
            redirect('umum/logout');
        }
    }	

	//***************************************************************************************************************************
// 5.21 FUNGSI JENIS_PERMOHONAN_BIDANG_USAHA

    public function jenis_permohonan_bidang_usaha_skp()
    {

        if ($this->input->post('bidang_usaha', TRUE) == '01') {
            $bidang_usaha = 'Jasa Konstruksi';
        } elseif ($this->input->post('bidang_usaha', TRUE) == '02') {
            $bidang_usaha = 'Jasa Non Konstruksi';
        } else {
            $bidang_usaha = 'Industri Penunjang';
        }

        $sub_bidang = $this->model->select('*', 'ref_sub_bidang', array('id_sub_bidang' => $this->input->post('sub_bidang', TRUE)));
        $bagian_sub_bidang = implode(", ", $this->input->post('bagian_sub_bidang', TRUE));
        $sub_bagian_sub_bidang = implode(", ", $this->input->post('sub_bagian_sub_bidang', TRUE));
        $data = array(
            'id_perusahaan' => $this->session->userdata('id_perusahaan'),
            'jenis_permohonan' => $this->input->post('jenis_permohonan', TRUE),
            'bidang_sub_bidang' => $bidang_usaha . '/' . $sub_bidang->sub_bidang,
            'bagian_sub_bidang' => $bagian_sub_bidang,
            'sub_bagian_sub_bidang' => $sub_bagian_sub_bidang,
        );
        $this->model->insert('permohonan', $data);

        $this->session->set_userdata('id_permohonan', $this->db->insert_id());
        $this->logs();
        if ($this->db->affected_rows() > 0) {
            redirect(base_url('hal/data_pemohon'));
        }

    }
	

//***************************************************************************************************************************
// 2.4 FUNGSI LOGS

    public function logs($user_data = NULL)
    {
        $this->load->library('user_agent');
        $this->load->model('model');
        $this->load->helper('url');

        $logData = array(
            'ip_address' => $this->getUserIP(),
            'user_agent' => $this->agent->agent_string(),
            'url' => $this->uri->uri_string(),
            'who' => $this->session->userdata('id_user'),
            'user_data' => $user_data
        );

        $this->model->insert('logs', $logData);

    }

//***************************************************************************************************************************
// 2.5 FUNGSI GETUSERIP

    public function getUserIP()
    {
        $client = @$_SERVER['HTTP_CLIENT_IP'];
        // $forward = @$_SERVER['HTTP_X_FORWARDED_FOR'];
        $forward = @$_SERVER['HTTP_CLIENT_IP'];
        $remote = $_SERVER['REMOTE_ADDR'];

        if (filter_var($client, FILTER_VALIDATE_IP)) {
            $ip = $client;
        } elseif (filter_var($forward, FILTER_VALIDATE_IP)) {
            $ip = $forward;
        } else {
            $ip = $remote;
        }
        return $ip;
    }
	
}