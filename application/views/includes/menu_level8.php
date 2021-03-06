		<script>
		$( document ).ready(function() {
			
			var pathname = window.location.pathname;
			 pathname = pathname.split("/");
			 if(pathname[3] == 'dashboard' || pathname[3] == 'login'){
				$('#dashboard').addClass('active');	
				$('#judul').text('*Selamat Datang, Admin <?= $this->session->userdata('nama_user_online'); ?>');	 
			 }
			 
			 else if(pathname[4] == 'pengajuan_skt' || pathname[4] == 'pengajuan_skp'){
				$('#pengajuan_skt').addClass('active');
			 }
			 
			 else if(pathname[3] == 'pengajuan_skt_diterima' || pathname[3] == 'pengajuan_skp_diterima' || pathname[3] == 'pengajuan_skt_diterima_naik' || pathname[3] == 'pengajuan_skp_diterima_naik'){
				$('#pengajuan_skt').addClass('active');
				$('#judul').text('*Disposisi Pengajuan');	
			 }
			 
			 else if(pathname[3] == 'daftar_register'){
				$('#regis_baru').addClass('active');
				$('#judul').text('*Calon Member Perusahaan');
			 }
			 	
			else if(pathname[3] == 'daftar_revisi_admin' || pathname[4] == 'revisi_skt' || pathname[4] == 'revisi_skp' || pathname[3] == 'pengajuan_revisi_diterima'){
				$('#revisi_skt').addClass('active');	
				$('#judul').text('*Daftar Pengajuan Direvisi');	 	 
			 }	 
			 
			 else if(pathname[3] == 'kotak_masuk' || pathname[3] == 'kotak_keluar'){
				$('#kotak_pesan').addClass('active');
			 }	
			 
			 else if(pathname[3] == 'menej_pengumuman' || pathname[3] == 'menej_ref'){
				$('#kelola').addClass('active');
				$('#judul').text('Manage Pengumuman');	 
			 }			 
			 
			 else if(pathname[3] == 'pengaturan'){
				$('#pengaturan').addClass('active');	
				$('#judul').text('');	 	 
			 }		 
			 
			 else if(pathname[3] == 'cek_status_admin'){
				$('#cek_status').addClass('active');		 	 
			 }	
		});
	</script>
	
		<div class="dashboardmenu">
			<ul class="nav nav-tabs" role="tablist">
			  <li role="presentation" id="dashboard"><a href="<?php echo base_url('all_users/dashboard/');  ?>" role="tab" data-toggle="tab"><img src="<?php echo base_url('assets/images') ?>/icon/dashboard-icon.png" class="tab-icon"><br/>Home</a></li>
			  <li role="presentation" id="kelola"><a href="<?php echo base_url() ?>admin/kelola_sistem" role="tab" data-toggle="tab"><img src="<?php echo base_url('assets/images') ?>/icon/manage-icon.png" class="tab-icon"><br/>Kelola<br/>Sistem</a></li>
			  <li role="presentation" id="pengaturan"><a href="<?php echo base_url() ?>all_users/pengaturan" role="tab" data-toggle="tab"><img src="<?php echo base_url('assets/images') ?>/icon/pengaturan-icon.png" class="tab-icon"><br/>Pengaturan</a></li>
			</ul>
		</div>