<!doctype html>
<html>
<head>
	<title>Sistem Basis Data Internal Penunjang Migas</title>
	<link rel="shortcut icon" href="<?php echo base_url('assets/images') ?>/esdmlogo.png">
	<link rel="stylesheet" type="text/css" href="<?php echo base_url('assets/styles') ?>/layout.css">
	<link rel="stylesheet" type="text/css" href="<?php echo base_url('assets/styles') ?>/bootstrap.min.css">
	<link rel="stylesheet" type="text/css" href="<?php echo base_url('assets/styles') ?>/forms.css">
	<script src="<?php echo base_url('assets/scripts') ?>/jquery-1.11.1.min.js"></script>
	
</head>
<body>
		<?php if(isset($msg)){
			echo $msg;
		}?>
		
	<div class="header">
		<div class="mainhead">
			<div class="rowhead">
				<div class="logo">
					<?php if($this->session->userdata('level') != NULL){
						$link_logo = base_url('all_users/dashboard');
					}else{
						$link_logo= base_url();
					}

					?>
					<a href="<?= $link_logo; ?>"><img src="<?php echo base_url('assets/images') ?>/esdmlogo.png"></a>
					
					<div class="logotitle">
						<div class="logotitle1">Kementerian Energi dan Sumberdaya Mineral</div>
						<div class="logotitle2"><b>Direktorat Teknik dan Lingkungan Migas</b></div>
						<div class="logotitle3">Sistem Basis Data Internal Usaha Penunjang Migas</div>
						<div class="logotitle4">Surat Keterangan Terdaftar (SKT) Migas Online</div>
					</div>
				</div>
			</div>
			
			<div class="navigation">
				<div id="navigation" >				    
					<div class="runningtext"><h2><?= $this->session->userdata('nama_user_online'); ?> <a href="<?= base_url('umum/logout')?>" >Logout</a></h2></div>						
				</div>
			</div>
		</div>	
	</div>
	
	<script>
		$( document ).ready(function() {		
			<?php if(isset($popup)){
				echo 'alert("'.$popup.'");';
				} ?>
				
			<?php if($this->session->flashdata('message')){
				echo 'alert("'.$this->session->flashdata('message').'");';
				} ?>			
		});
	</script>
	<!-- Load grocerycrud css and js -->
	
	<div class="content">
	<?php if(isset($output)){ ?>
	<?php foreach($css_files as $file): ?>
	<link type="text/css" rel="stylesheet" href="<?php echo $file; ?>" />
	<?php endforeach; ?>
	<?php foreach($js_files as $file): ?>
	<script src="<?php echo $file; ?>"></script>
	<?php endforeach; }?>
	

		
		<div class="columnfull">
			<div class="borderbottomdotted">
				<legend>Detail Perusahaan</legend>
				<?php if($param == "pengajuan_skt"){
						$link = "daftar_pengajuan_skt";
					}elseif($param == "revisi_admin"){
						$link ="daftar_revisi_admin";
					}elseif($param == "pengajuan_skp"){
						$link ="daftar_pengajuan_skp";
					}elseif($param == "laporan_berkala_kasie" || $param == "laporan_berkala_evaluator"){
						$link = $param;
					}else{
						$link ="";
					}?>
					
					<div class="form-group grup-tombol-detail-kanan">
					  <div class="col-lg-10 col-lg-offset-2">
						<a href="<?php echo site_url('all_admin/'.$link); ?>"><button class="btn btn-primary detail" type="button">Kembali </button></a>
					  </div>
					</div>							
						  
			</div>
			<div class="formcolumn">
				<?= form_open(base_url('admin/catatan_petugas/biodata_perusahaan/'.$this->uri->segment(4)),array("class"=>"pure-form pure-form-aligned")); echo validation_errors(); ?> 
				    <fieldset>
				        <div class="pure-control-group">
				            <label for="name">Nama Perusahaan</label>
				            : <b><?= $biodata_perusahaan->nama_perusahaan; ?></b>
				       <div class="jarak"></div>
				            <label for="name">Direktur Utama</label>
				            : <b><?= $biodata_perusahaan->direktur_utama; ?></b>
				        </div>
				        <div class="pure-control-group">
				            <label for="name">Contact Person</label>
				            : <b><?= $biodata_perusahaan->contact_person; ?></b>
				        </div>
				        <div class="pure-control-group">
				            <label for="name">Email Perusahaan</label>
				            : <b><?= $biodata_perusahaan->email; ?></b>
				        </div>
				        <div class="pure-control-group">
				            <label for="name">Alamat</label>
				            : <b><?= $biodata_perusahaan->alamat.', Kota '.$biodata_perusahaan->kota.', Provinsi '.$biodata_perusahaan->provinsi; ?></b>
				        </div>
				        <div class="pure-control-group">
				            <label for="name">Website</label>
				            : <b><?= $biodata_perusahaan->website; ?></b>
				        </div>
				        <div class="pure-control-group">
				            <label for="name">Deskripsi Perusahaan</label>
				            : <b><?= $biodata_perusahaan->deskripsi_perusahaan; ?></b>
				        </div>
				    </fieldset>
				</form>
			</div>
		</div>
<!--********************************	Data Pemohon	**************************************-->		
		<div class="columnfull">
			<div class="borderbottomdotted">
				<legend>A. Data Pemohon</legend>
			</div>
			<div class="tablesection">
			<h2>1. Data Umum</h2>
				<table class="table table-bordered table-striped">
					<thead>
						<tr>
							<th rowspan="2">No</th>
							<th rowspan="2">Dokumen</th>
							<th rowspan="2">Nomor</th>
							<th rowspan="2">Penerbit</th>
							<th colspan="2">Masa Berlaku</th>
							<th rowspan="2">File Dokumen</th>
						</tr>
						<tr>
							<th>Tanggal Terbit</th>
							<th>Tanggal Berakhir</th>
						</tr>
					</thead>
					<tbody>
						<?php if($data_umum != NULL){						
						$i=1; foreach($data_umum as $dt_umum):?>
						<?php $ref_jenis_dokumen = $this->model->select('*', 'ref_jenis_dokumen', array('id_jenis_dokumen' => $dt_umum->jenis_dokumen)); ?>
						<tr>						
							<td><?= $i++; ?></td>
							<td><?= $ref_jenis_dokumen->jenis_dokumen; ?></td>
							<td><?= $dt_umum->nomor; ?></td>
							<td><?= $dt_umum->penerbit; ?></td>
							<td><?= $dt_umum->tanggal_terbit; ?></td>
							<td><?= $dt_umum->akhir_masa_berlaku; ?></td>
							<td><?= anchor('assets/uploads/files/'.$dt_umum->file_dokumen, 'Dokumen'); ?></td>
						</tr>
						<?php endforeach; }else{ ?><tr><td colspan="7">Tidak ada record</td></tr><?php } ?>
					</tbody>
				</table>
			</div>
			<hr/>
			<div class="tablesection">
			<h2>2. Data Khusus</h2>
				<table class="table table-bordered table-striped">
					<thead>
						<tr>
							<th rowspan="2">No</th>
							<th rowspan="2">Dokumen</th>
							<th rowspan="2">Nomor</th>
							<th rowspan="2">Penerbit</th>
							<th colspan="2">Masa Berlaku</th>
							<th rowspan="2">File Dokumen</th>
						</tr>
						<tr>
							<th>Tanggal Terbit</th>
							<th>Tanggal Berakhir</th>
						</tr>
					</thead>
					<tbody>
						<?php $ada = NULL;
						if($data_khusus != NULL){
						$i=1; foreach($data_khusus as $dt_khusus):
						if($dt_khusus->status_pemakaian != NULL){
							$temp = explode(',', $dt_khusus->status_pemakaian);
						
							foreach ($temp as $key => $status_pemakaian) {
								if($status_pemakaian == $this->uri->segment(4)){ ?>
								<tr>
									<td><?= $i++; ?></td>
									<td><?= $dt_khusus->jenis_dokumen; ?></td>
									<td><?= $dt_khusus->nomor; ?></td>
									<td><?= $dt_khusus->penerbit; ?></td>
									<td><?= $dt_khusus->tanggal_terbit; ?></td>
									<td><?= $dt_khusus->akhir_masa_berlaku; ?></td>
									<td>
										<?= anchor('assets/uploads/files/'.$dt_khusus->file_dokumen, 'Dokumen'); ?>
									</td>
								</tr><?php	$ada = 'ada';
								}elseif($status_pemakaian == ''){
								   unset( $temp[$key] );
								}
							}
						}
						 endforeach; }
						 if($ada == NULL){
						 ?><tr><td colspan="7">Tidak ada record</td></tr><?php } ?>
					</tbody>
				</table>
			</div>
		</div>
<!--********************************	Keanggotaan Asosiasi	**************************************-->
		<div class="columnfull">
			<div class="borderbottomdotted">
				<legend>B. Keanggotaan Asosiasi Perusahaan</legend>
			</div>
			<div class="tablesection">
				<table class="table table-bordered table-striped">
					<thead>
						<tr>
							<th>No</th>
							<th>Asosiasi</th>
							<th>Nomor Anggota</th>
							<th>Berlaku Hingga</th>
						</tr>
					</thead>
					<tbody>
						<?php $ada = NULL;
						if($keanggotaan_asosiasi != NULL){		
						$i=1; foreach($keanggotaan_asosiasi as $asosiasi):
						if($asosiasi->status_pemakaian != NULL){
							$temp = explode(',', $asosiasi->status_pemakaian);
						
							foreach ($temp as $key => $status_pemakaian) {
								if($status_pemakaian == $this->uri->segment(4)){ ?>
						<tr>
							<td><?= $i++; ?></td>
							<td><?= $asosiasi->asosiasi; ?></td>
							<td><?= $asosiasi->nomor_anggota; ?></td>
							<td><?= date("d/m/Y",strtotime($asosiasi->berlaku_hingga)); ?></td>
						</tr><?php	$ada = 'ada';
								}elseif($status_pemakaian == ''){
								   unset( $temp[$key] );
								}
							}
						}
						 endforeach; }
						 if($ada == NULL){
						 ?><tr><td colspan="4">Tidak ada record</td></tr><?php } ?>
					</tbody>
				</table>
			</div>
		</div>
<!--********************************	Data Tenaga Kerja		**************************************-->			
		<div class="columnfull">
			<div class="borderbottomdotted">
				<legend>D. Data Tenaga Kerja</legend>
			</div>
			<div class="tablesection">
			<h2>1. Daftar Tenaga Kerja Ahli Sesuai Bidang Usaha</h2>
				<table class="table table-bordered table-striped">
					<thead>
						<tr>
							<th rowspan="2">No</th>
							<th rowspan="2">Nama Lengkap</th>
							<th rowspan="2">Status</th>
							<th rowspan="2">Posisi/<br/>Jabatan</th>
							<th colspan="2">Pendidikan Terakhir</th>
							<th rowspan="2">Pelatihan<br/>Eksternal</th>
						</tr>
						<tr>
							<th>Jenjang</th>
							<th>Jurusan</th>
						</tr>
					</thead>
					<tbody>
						<?php $ada = NULL;
						if($tenaga_kerja != NULL){		
						$i=1; foreach($tenaga_kerja as $pekerja):
						if($pekerja->status_pemakaian != NULL){
							$temp = explode(',', $pekerja->status_pemakaian);
						
							foreach ($temp as $key => $status_pemakaian) {
								if($status_pemakaian == $this->uri->segment(4)){ ?>
						<tr>
							<td><?= $i++; ?></td>
							<td><?= $pekerja->nama_lengkap; ?></td>
							<td><?= $pekerja->status; ?></td>
							<td><?= $pekerja->jabatan; ?></td>
							<td><?= $pekerja->jenjang_pendidikan; ?></td>
							<td><?= $pekerja->jurusan_pendidikan; ?></td>
							<td><a>lihat detil</a></td>
						</tr><?php	$ada = 'ada';
								}elseif($status_pemakaian == ''){
								   unset( $temp[$key] );
								}
							}
						}
						 endforeach; }
						 if($ada == NULL){
						 ?><tr><td colspan="4">Tidak ada record</td></tr><?php } ?>
					</tbody>
				</table>
			</div>

			<hr/>

			<div class="tablesection">
			<h2>2. Jumlah Tenaga Kerja</h2>
				<table class="table table-bordered table-striped">
					<thead>
						<tr>
							<th rowspan="2">No</th>
							<th rowspan="2">Tenaga Kerja</th>
							<th colspan="7">Jumlah Tenaga Kerja Berdasarkan Jenjang Pendidikan Terakhir</th>
						</tr>
						<tr>
							<th>Sekolah Dasar<br/>(SD)</th>
							<th>Sekolah Menengah Pertama<br/>(SMP)</th>
							<th>Sekolah Menengah Atas<br/>(SMA)</th>
							<th>Diploma</th>
							<th>Sarjana</th>
							<th>Paska Sarjana</th>
							<th>Doktor</th>
						</tr>
					</thead>
					<tbody>
						<?php if($jumlah_tenaga_kerja != NULL){						
						$i=1; foreach($jumlah_tenaga_kerja as $jml_pekerja):?>
						<tr>
							<td><?= $i++; ?></td>
							<td><?= $jml_pekerja->tipe_tenaga_kerja; ?></td>
							<td><?= $jml_pekerja->sd; ?></td>
							<td><?= $jml_pekerja->smp; ?></td>
							<td><?= $jml_pekerja->sma; ?></td>
							<td><?= $jml_pekerja->diploma; ?></td>
							<td><?= $jml_pekerja->sarjana; ?></td>
							<td><?= $jml_pekerja->paska_sarjana; ?></td>
							<td><?= $jml_pekerja->doktor; ?></td>
						</tr>
						<?php endforeach; }else{ ?><tr><td colspan="9">Tidak ada record</td></tr><?php } ?>
					</tbody>
				</table>
			</div>
		</div>
<!--********************************	Pelatihan Tenaga Kerja Internal		**************************************-->
		<div class="columnfull">
			<div class="borderbottomdotted">
				<legend>E. Pelatihan Tenaga Kerja</legend>
			</div>
			<div class="tablesection">
			<h2>1. Tabel Pelatihan Tenaga Kerja inhouse</h2>
				<table class="table table-bordered table-striped">
					<thead>
						<tr>
							<th>No</th>
							<th>Jenis Pelatihan</th>
							<th>Keterangan</th>
						</tr>
					</thead>
					<tbody>
						<?php $ada = NULL;
						if($pelatihan_tenaga_kerja_internal != NULL){								
						$i=1; foreach($pelatihan_tenaga_kerja_internal as $pelatihan_internal):
						if($pelatihan_internal->status_pemakaian != NULL){
							$temp = explode(',', $pelatihan_internal->status_pemakaian);
						
							foreach ($temp as $key => $status_pemakaian) {
								if($status_pemakaian == $this->uri->segment(4)){ ?>
						<tr>
							<td><?= $i++; ?></td>
							<td><?= $pelatihan_internal->jenis_pelatihan; ?></td>
							<td><?= $pelatihan_internal->keterangan; ?></td>
						</tr><?php	$ada = 'ada';
								}elseif($status_pemakaian == ''){
								   unset( $temp[$key] );
								}
							}
						}
						 endforeach; }
						 if($ada == NULL){
						 ?><tr><td colspan="3">Tidak ada record</td></tr><?php } ?>
					</tbody>
				</table>
			</div>

			<hr/>
			<div class="tablesection">
			<h2>2. Tabel Program Pelatihan Tenaga Kerja eksternal</h2>
				<table class="table table-bordered table-striped">
					<thead>
						<tr>
							<th>No</th>
							<th>Nama Tenaga Kerja</th>
							<th>Jenis Pelatihan</th>
						</tr>
					</thead>
					<tbody>
						<?php $ada = NULL;
						if($pelatihan_tenaga_kerja_eksternal != NULL){						
						$i=1; foreach($pelatihan_tenaga_kerja_eksternal as $pelatihan_eksternal):?>
						<?php if($tenaga_kerja != NULL){		
						$i=1; foreach($tenaga_kerja as $pekerja):
						if($pelatihan_eksternal->status_pemakaian != NULL){
							$temp = explode(',', $pelatihan_eksternal->status_pemakaian);
						
							foreach ($temp as $key => $status_pemakaian) {
								if($status_pemakaian == $this->uri->segment(4)){ ?>
						<tr>
							<td><?= $i++; ?></td>
							<td><?= $pekerja->nama_lengkap; ?></td>
							<td><?= $pelatihan_eksternal->jenis_pelatihan; ?></td>
						</tr><?php	$ada = 'ada';
								}elseif($status_pemakaian == ''){
								   unset( $temp[$key] );
								}
							}
						}
						 endforeach; }
						 endforeach; }
						 if($ada == NULL){
						 ?><tr><td colspan="3">Tidak ada record</td></tr><?php } ?>
					</tbody>
				</table>
			</div>

		</div>	
<!--********************************	Peralatan		**************************************-->		
		<div class="columnfull">
			<div class="borderbottomdotted">
				<legend>F. Data Peralatan</legend>
			</div>
			<div class="tablesection">
			<h2>1. Peralatan Utama</h2>
				<table class="table table-bordered table-striped">
					<thead>
						<tr>
							<th>No</th>
							<th>Nama Peralatan</th>
							<th>Tipe/Kapasitas</th>
							<th>Jumlah</th>
							<th>Lokasi</th>
							<th>Status Kepemilikan</th>
						</tr>
					</thead>
					<tbody>
						<?php $ada = NULL;
						if($peralatan != NULL){
						$i=1; foreach($peralatan as $alat):
						if($alat->golongan_alat == 'Peralatan Utama'){
						if($alat->status_pemakaian != NULL){
							$temp = explode(',', $alat->status_pemakaian);
						
							foreach ($temp as $key => $status_pemakaian) {
								if($status_pemakaian == $this->uri->segment(4)){ ?>
						
						<tr>
							<td><?= $i++; ?></td>
							<td><?= $alat->nama_alat; ?></td>
							<td><?= $alat->tipe_alat; ?></td>
							<td><?= $alat->jumlah; ?></td>
							<td><?= $alat->lokasi; ?></td>
							<td><?= $alat->status_kepemilikan; ?></td>
						</tr><?php	$ada = 'ada';
								}elseif($status_pemakaian == ''){
								   unset( $temp[$key] );
								}
							}
						}
						}
						 endforeach; }
						 if($ada == NULL){
						 ?><tr><td colspan="6">Tidak ada record</td></tr><?php } ?>
					</tbody>
				</table>
			</div>
			<hr/>
			
			<div class="tablesection">
			<h2>2. Peralatan Pendukung</h2>
				<table class="table table-bordered table-striped">
					<thead>
						<tr>
							<th>No</th>
							<th>Nama Peralatan</th>
							<th>Tipe/Kapasitas</th>
							<th>Jumlah</th>
							<th>Lokasi</th>
							<th>Status Kepemilikan</th>
						</tr>
					</thead>
					<tbody>
						<?php $ada = NULL;
						if($peralatan != NULL){
						$i=1; foreach($peralatan as $alat):
						if($alat->golongan_alat == 'Peralatan Pendukung'){
						if($alat->status_pemakaian != NULL){
							$temp = explode(',', $alat->status_pemakaian);
						
							foreach ($temp as $key => $status_pemakaian) {
								if($status_pemakaian == $this->uri->segment(4)){ ?>
						
						<tr>
							<td><?= $i++; ?></td>
							<td><?= $alat->nama_alat; ?></td>
							<td><?= $alat->tipe_alat; ?></td>
							<td><?= $alat->jumlah; ?></td>
							<td><?= $alat->lokasi; ?></td>
							<td><?= $alat->status_kepemilikan; ?></td>
						</tr><?php	$ada = 'ada';
								}elseif($status_pemakaian == ''){
								   unset( $temp[$key] );
								}
							}
						}
						}
						 endforeach; }
						 if($ada == NULL){
						 ?><tr><td colspan="6">Tidak ada record</td></tr><?php } ?>
					</tbody>
				</table>
			</div>
			<hr/>
			
			<div class="tablesection">
			<h2>3. Peralatan Keselamatan Dan Kesehatan Kerja</h2>
				<table class="table table-bordered table-striped">
					<thead>
						<tr>
							<th>No</th>
							<th>Nama Peralatan</th>
							<th>Tipe/Kapasitas</th>
							<th>Jumlah</th>
							<th>Lokasi</th>
							<th>Status Kepemilikan</th>
						</tr>
					</thead>
					<tbody>
						<?php $ada = NULL;
						if($peralatan != NULL){
						$i=1; foreach($peralatan as $alat):
						if($alat->golongan_alat == 'Peralatan Keselamatan dan Kesehatan Kerja'){
						if($alat->status_pemakaian != NULL){
							$temp = explode(',', $alat->status_pemakaian);
						
							foreach ($temp as $key => $status_pemakaian) {
								if($status_pemakaian == $this->uri->segment(4)){ ?>
						
						<tr>
							<td><?= $i++; ?></td>
							<td><?= $alat->nama_alat; ?></td>
							<td><?= $alat->tipe_alat; ?></td>
							<td><?= $alat->jumlah; ?></td>
							<td><?= $alat->lokasi; ?></td>
							<td><?= $alat->status_kepemilikan; ?></td>
						</tr><?php	$ada = 'ada';
								}elseif($status_pemakaian == ''){
								   unset( $temp[$key] );
								}
							}
						}
						}
						 endforeach; }
						 if($ada == NULL){
						 ?><tr><td colspan="6">Tidak ada record</td></tr><?php } ?>
					</tbody>
				</table>
			</div>
		</div>
<!--********************************	Nilai Investasi	**************************************-->
		<div class="columnfull">
			<div class="borderbottomdotted">
				<legend>G. Nilai Investasi</legend>
			</div>
			<div class="tablesection">
				<table class="table table-bordered table-striped">
					<thead>
						<tr>
							<th>No</th>
							<th>Nama Investor</th>
							<th>Negara Asal</th>
							<th>Nominal Investasi</th>
							<th>Persentase</th>
						</tr>
					</thead>
					<tbody>
						<?php if($nilai_investasi != NULL){						
						$i=1; foreach($nilai_investasi as $invest):?>
						<tr>
							<td><?= $i++; ?></td>
							<td><?= $invest->nama_investor; ?></td>
							<td><?= $invest->negara_asal; ?></td>
							<td><?= $invest->nominal_investasi; ?></td>
							<td><?= $invest->persentase; ?></td>
						</tr>
						<?php endforeach; }else{ ?><tr><td colspan="5">Tidak ada record</td></tr><?php } ?>
					</tbody>
				</table>
			</div>
		</div>
<!--********************************	Pengalaman Kerja	**************************************-->
		<div class="columnfull">
			<div class="borderbottomdotted">
				<legend>H. Pengalaman Kerja / Performance</legend>
			</div>
			<div class="tablesection">
				<table class="table table-bordered table-striped">
					<thead>
						<tr>
							<th>No</th>
							<th>Nama Pekerjaan</th>
							<th>Pemberi Kerja</th>
							<th>Lokasi</th>
							<th>Tujuan Pelaksanaan</th>
							<th>Nilai Kontrak</th>
						</tr>
					</thead>
					<tbody>
						<?php $ada = NULL;
						if($daftar_pekerjaan != NULL){		
						$i=1; foreach($daftar_pekerjaan as $pekerjaan):
						if($pekerjaan->status_pemakaian != NULL){
							$temp = explode(',', $pekerjaan->status_pemakaian);
						
							foreach ($temp as $key => $status_pemakaian) {
								if($status_pemakaian == $this->uri->segment(4)){ ?>
						<tr>
							<td><?= $i++; ?></td>
							<td><?= $pekerjaan->nama_pekerjaan; ?></td>
							<td><?= $pekerjaan->pemberi_kerja; ?></td>
							<td><?= $pekerjaan->lokasi_kerja; ?></td>
							<td><?= $pekerjaan->tujuan_pelaksanaan; ?></td>
							<td><?= $pekerjaan->nilai_kontrak; ?></td>
						</tr><?php	$ada = 'ada';
								}elseif($status_pemakaian == ''){
								   unset( $temp[$key] );
								}
							}
						}
						 endforeach; }
						 if($ada == NULL){
						 ?><tr><td colspan="6">Tidak ada record</td></tr><?php } ?>
					</tbody>
				</table>
			</div>
		</div>


		<div class="columnfull">
			<div class="borderbottomdotted">
				<legend>I. Data Sistem Manajemen Dan Prosedur Kerja Teknis (SOP)</legend>
			</div>
			<div class="tablesection">
				<table class="table table-bordered table-striped">
					<thead>
						<tr>
							<th>No</th>
							<th>Prosedur</th>
							<th>Dokumen Prosedur </th>
							<th>Deskripsi</th>
						</tr>
					</thead>
					<tbody>
						<?php $ada = NULL;
						if($sop != NULL){		
						$i=1; foreach($sop as $sopx): 
						if($sopx->status_pemakaian != NULL){
							$temp = explode(',', $sopx->status_pemakaian);
						
							foreach ($temp as $key => $status_pemakaian) {
								if($status_pemakaian == $this->uri->segment(4)){ ?>
						<tr>
							<td><?= $i++; ?></td>
							<td><?= $sopx->prosedur; ?></td>
							<td><?= anchor('assets/uploads/files/'.$sopx->file_manajemen_prosedur_kerja, 'Dokumen'); ?>

							</td>
							<td><?= $sopx->deskripsi; ?></td>
						</tr><?php	$ada = 'ada';
								}elseif($status_pemakaian == ''){
								   unset( $temp[$key] );
								}
							}
						}
						 endforeach; }
						 if($ada == NULL){
						 ?><tr><td colspan="4">Tidak ada record</td></tr><?php } ?>
					</tbody>
				</table>
			</div>
		</div>


		<div class="columnfull">
			<div class="borderbottomdotted">
				<legend>J. Data Corporate Social Responsibility (CSR) Dan Community Development</legend>
			</div>
			<div class="tablesection">
				<table class="table table-bordered table-striped">
					<thead>
						<tr>
							<th>No</th>
							<th>Waktu</th>
							<th>Kegiatan</th>
							<th>Lokasi</th>
						</tr>
					</thead>
					<tbody>
						<?php if($csr != NULL){
						$ada = NULL;
						$i=1; foreach($csr as $csrx): 
						if($csrx->status_pemakaian != NULL){
							$temp = explode(',', $csrx->status_pemakaian);
						
							foreach ($temp as $key => $status_pemakaian) {
								if($status_pemakaian == $this->uri->segment(4)){ ?>
						<tr>
							<td><?= $i++; ?></td>
							<td><?= $csrx->waktu; ?></td>
							<td><?= $csrx->kegiatan; ?></td>
							<td><?= $csrx->lokasi; ?></td>
						</tr><?php	$ada = 'ada';
								}elseif($status_pemakaian == ''){
								   unset( $temp[$key] );
								}
							}
						}
						 endforeach; }
						 if($ada == NULL){
						 ?><tr><td colspan="4">Tidak ada record</td></tr><?php } ?>
					</tbody>
				</table>
			</div>
			<div class="form-group grup-tombol-detail-kanan">
			  <div class="col-lg-10 col-lg-offset-2">
				<a href="<?php echo site_url('all_admin/'.$link); ?>"><button class="btn btn-primary detail" type="button">Kembali </button></a>
			  </div>
			</div>
			
		</div>		
		
<?php $this->load->view('includes/footer') ?>

</body>
</html>