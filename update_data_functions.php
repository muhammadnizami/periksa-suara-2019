<?php


include('dbconfig.php');

define('PROTOKOL_KPU',"https");
define('PROTOKOL_KAWALPEMILU',"https");
$server_kpu = "pemilu2019.kpu.go.id";
$server_kawalpemilu = "kawal-c1.appspot.com";

$waktu_coba_lagi = 5;
$max_waktu_coba_lagi = 300;
$max_percobaan=10;

function file_get_contents_no_verify_continue_try($url){
	$waktu_coba_lagi = $GLOBALS['waktu_coba_lagi'];
	$max_waktu_coba_lagi = $GLOBALS['max_waktu_coba_lagi'];
	$percobaan=0;
	$result = false;
	do{

		$arrContextOptions=array(
		    "ssl"=>array(
		        "verify_peer"=>false,
		        "verify_peer_name"=>false,
		    ),
		);  
		$result= file_get_contents($url, false, stream_context_create($arrContextOptions));
		sleep(min($max_waktu_coba_lagi,$waktu_coba_lagi*$percobaan));
		$percobaan = $percobaan + 1;
	}while ($result==NULL and $percobaan < $max_percobaan);

	return $result;
}

function updateDaftarTPSKPU(){

	// Create connection
	$dbconn = new mysqli(DBSERVERNAME, DBUSERNAME, DBPASSWORD);
	$server_kpu = $GLOBALS['server_kpu'];

	// Check connection
	if ($dbconn->connect_error) {
	    printf("Local DB connection failed: " . $dbconn->connect_error . "\n");
	    return false;
	} 
	printf("Local DB connected successfully\n");

	$sql = sprintf("USE %s",DBNAME);
	if ($dbconn->query($sql) === TRUE) {
	    printf("USE %s succeeded", DBNAME);
	} else {
	    printf("Error: " . $sql . "\n" . $dbconn->error);
	}

	$url_nasional= sprintf(PROTOKOL_KPU."://%s/static/json/wilayah/0.json", $server_kpu);
	$json_nasional = file_get_contents_no_verify_continue_try($url_nasional);

	if ($json_nasional==NULL){
		return false;
	}
	$daftar_provinsi = json_decode($json_nasional,true);
	foreach($daftar_provinsi as $id_provinsi => $detail_provinsi) {
		$nama_provinsi=$detail_provinsi["nama"];

		$sql = sprintf("INSERT INTO provinsi (id_provinsi, nama_provinsi) VALUES (%d, '%s') ON DUPLICATE KEY UPDATE nama_provinsi='%s'", $id_provinsi, str_replace("'","\\'",$nama_provinsi), str_replace("'","\\'",$nama_provinsi));
		if ($dbconn->query($sql) === TRUE) {
		    printf("New record %s created successfully\n", $nama_provinsi);
		} else {
		    printf("Error: " . $sql . "\n" . $dbconn->error);
		}


		$url_provinsi=sprintf(PROTOKOL_KPU."://%s/static/json/wilayah/%d.json", $server_kpu, $id_provinsi);
		$json_provinsi = file_get_contents_no_verify_continue_try($url_provinsi);
		if ($json_provinsi==NULL){
			return false;
		}
		$daftar_kotakab[$id_provinsi]=json_decode($json_provinsi,true);
		foreach ($daftar_kotakab[$id_provinsi] as $id_kotakab => $detail_kotakab){
			$nama_kotakab=$detail_kotakab["nama"];

			$sql = sprintf("INSERT INTO kotakab (id_provinsi, id_kotakab, nama_kotakab)	VALUES (%d, %d, '%s') ON DUPLICATE KEY UPDATE nama_kotakab='%s'", $id_provinsi, $id_kotakab, $id_kotakab, str_replace("'","\\'",$nama_kotakab), str_replace("'","\\'",$nama_kotakab));
			if ($dbconn->query($sql) === TRUE) {
			    printf("New record %s created successfully\n", $nama_kotakab);
			} else {
			    printf("Error: " . $sql . "\n" . $dbconn->error);
			}

			$url_kotakab=sprintf(PROTOKOL_KPU."://%s/static/json/wilayah/%d/%d.json",$server_kpu,$id_provinsi,$id_kotakab);
			$json_kotakab = file_get_contents_no_verify_continue_try($url_kotakab);
			if ($json_kotakab==NULL){
				return false;
			}
			$daftar_kecamatan[$id_provinsi][$id_kotakab]=json_decode($json_kotakab,true);
			foreach ($daftar_kecamatan[$id_provinsi][$id_kotakab] as $id_kecamatan => $detail_kecamatan){
				$nama_kecamatan=$detail_kecamatan["nama"];

				$sql = sprintf("INSERT INTO kecamatan (id_provinsi, id_kotakab, id_kecamatan, nama_kecamatan) VALUES (%d, %d, %d, '%s') ON DUPLICATE KEY UPDATE nama_kecamatan='%s'", $id_provinsi, $id_kotakab, $id_kecamatan, str_replace("'","\\'",$nama_kecamatan), str_replace("'","\\'",$nama_kecamatan));
				if ($dbconn->query($sql) === TRUE) {
				    printf("New record %s created successfully\n", $nama_kecamatan);
				} else {
				    printf("Error: " . $sql . "\n" . $dbconn->error);
				}


				$url_kecamatan=sprintf(PROTOKOL_KPU."://%s/static/json/wilayah/%d/%d/%d.json",$server_kpu,$id_provinsi,$id_kotakab,$id_kecamatan);
				$json_kecamatan = file_get_contents_no_verify_continue_try($url_kecamatan);
				if ($json_kecamatan==NULL){
					return false;
				}
				$daftar_kelurahan[$id_provinsi][$id_kotakab][$id_kecamatan]=json_decode($json_kecamatan,true);
				foreach ($daftar_kelurahan[$id_provinsi][$id_kotakab][$id_kecamatan] as $id_kelurahan => $detail_kelurahan){
					$nama_kelurahan=$detail_kelurahan["nama"];

					$sql = sprintf("INSERT INTO kelurahan (id_provinsi, id_kotakab, id_kecamatan, id_kelurahan,  nama_kelurahan) VALUES (%d, %d, %d, %d, '%s') ON DUPLICATE KEY UPDATE nama_kelurahan='%s'", $id_provinsi, $id_kotakab, $id_kecamatan, $id_kelurahan, str_replace("'","\\'",$nama_kelurahan), str_replace("'","\\'",$nama_kelurahan));
					if ($dbconn->query($sql) === TRUE) {
					    printf("New record %s created successfully\n", $nama_kelurahan);
					} else {
					    printf("Error: " . $sql . "\n" . $dbconn->error);
					}


					$url_kelurahan=sprintf(PROTOKOL_KPU."://%s/static/json/wilayah/%d/%d/%d/%d.json",$server_kpu,$id_provinsi,$id_kotakab,$id_kecamatan,$id_kelurahan);
					$json_kelurahan = file_get_contents_no_verify_continue_try($url_kelurahan);
					if ($json_kelurahan==NULL){
						return false;
					}
					$daftar_tps[$id_provinsi][$id_kotakab][$id_kecamatan][$id_kelurahan]=json_decode($json_kelurahan,true);
					foreach($daftar_tps[$id_provinsi][$id_kotakab][$id_kecamatan][$id_kelurahan] as $id_tps => $detail_tps){
						$nama_tps=$detail_tps["nama"];

						$sql = sprintf("INSERT INTO tps (id_provinsi, id_kotakab, id_kecamatan, id_kelurahan, id_tps, nama_tps)	VALUES (%d, %d, %d, %d, %d, '%s') ON DUPLICATE KEY UPDATE nama_tps='%s'", $id_provinsi, $id_kotakab, $id_kecamatan, $id_kelurahan, $id_tps, str_replace("'","\\'",$nama_tps), str_replace("'","\\'",$nama_tps));
						if ($dbconn->query($sql) === TRUE) {
						    printf("New record %s created successfully\n", $nama_tps);
						} else {
						    printf("Error: " . $sql . "\n" . $dbconn->error);
						}
					}
				}
			}
		}
	}
	$dbconn->close();
}

function updateSuaraKawalPemilu($callback=0){
	// Create connection
	$dbconn = new mysqli(DBSERVERNAME, DBUSERNAME, DBPASSWORD);
	$server_kawalpemilu = $GLOBALS['server_kawalpemilu'];

	$sql = sprintf("USE %s",DBNAME);
	if ($dbconn->query($sql) === TRUE) {
	    printf("USE %s succeeded", DBNAME);
	} else {
	    printf("Error: " . $sql . "\n" . $dbconn->error);
	}

	$url_nasional = sprintf(PROTOKOL_KAWALPEMILU."://%s/api/c/0",$server_kawalpemilu);
	$json_nasional = file_get_contents_no_verify_continue_try($url_nasional);
	$daftar_provinsi = json_decode($json_nasional,true);
	if ($json_nasional==NULL){
		return false;
	}
	foreach($daftar_provinsi["data"] as $id_provinsi => $detail_provinsi){
		if (array_key_exists("cakupan",$detail_provinsi["sum"]) and $detail_provinsi["sum"]["cakupan"] > 0){
			$url_provinsi = sprintf(PROTOKOL_KAWALPEMILU."://%s/api/c/%d",$server_kawalpemilu, $id_provinsi);
			$json_provinsi = file_get_contents_no_verify_continue_try($url_provinsi);
			if ($json_provinsi==NULL){
				continue;
			}
			$daftar_kotakab = json_decode($json_provinsi,true);
			foreach($daftar_kotakab["data"] as $id_kotakab => $detail_kotakab){
				if (array_key_exists("cakupan",$detail_kotakab["sum"]) and $detail_kotakab["sum"]["cakupan"] > 0){
					$url_kotakab = sprintf(PROTOKOL_KAWALPEMILU."://%s/api/c/%d",$server_kawalpemilu, $id_kotakab);
					$json_kotakab = file_get_contents_no_verify_continue_try($url_kotakab);
					if ($json_kotakab==NULL){
						continue;
					}
					$daftar_kecamatan = json_decode($json_kotakab,true);
					foreach($daftar_kecamatan["data"] as $id_kecamatan => $detail_kecamatan){
						if (array_key_exists("cakupan",$detail_kecamatan["sum"]) and $detail_kecamatan["sum"]["cakupan"] > 0){
							$url_kecamatan = sprintf(PROTOKOL_KAWALPEMILU."://%s/api/c/%d",$server_kawalpemilu, $id_kecamatan);
							$json_kecamatan = file_get_contents_no_verify_continue_try($url_kecamatan);
							if ($json_kecamatan==NULL){
								continue;
							}
							$daftar_kelurahan = json_decode($json_kecamatan,true);
							foreach($daftar_kelurahan["data"] as $id_kelurahan => $detail_kelurahan){
								if (array_key_exists("cakupan",$detail_kelurahan["sum"]) and $detail_kelurahan["sum"]["cakupan"] > 0){
									$url_kelurahan = sprintf(PROTOKOL_KAWALPEMILU."://%s/api/c/%d",$server_kawalpemilu, $id_kelurahan);
									$json_kelurahan = file_get_contents_no_verify_continue_try($url_kelurahan);
									if ($json_kelurahan==NULL){
										continue;
									}
									$daftar_tps = json_decode($json_kelurahan,true);
									foreach($daftar_tps["data"] as $no_tps => $detail_tps){
										$sum = $detail_tps["sum"];
										if (sizeof($sum)>0 and array_key_exists("pas1",$sum) and array_key_exists("pas2",$sum) and array_key_exists("tSah",$sum) and array_key_exists("sah",$sum)){
											$date = date('Y-m-d H:i:s');
											$pas1 = $sum['pas1'];
											$pas2 = $sum['pas2'];
											$tSah = $sum['tSah'];
											$sah = $sum['sah'];
											$photos_list=$detail_tps['photos'];
											$photo=NULL;
											$nama_tps = sprintf('TPS %0'.strval(floor(log10(sizeof($daftar_tps["data"]))+1)).'d',$no_tps);
											foreach($photos_list as $photo_url => $detail_photo){
												$sum = $detail_photo["sum"];
												if (array_key_exists("pas1",$sum) and array_key_exists("pas2",$sum) and array_key_exists("tSah",$sum) and array_key_exists("sah",$sum)){
													$pas1 = $sum['pas1'];
													$pas2 = $sum['pas2'];
													$tSah = $sum['tSah'];
													$sah = $sum['sah'];
													$photo=$photo_url;
													$tipe_form = $detail_photo['c1']['plano']==1?'c1-plano':'c1'; 
													$sql = sprintf("INSERT INTO suara_kawalpemilu_pilpres (id_provinsi,id_kotakab,id_kecamatan,id_kelurahan,nama_tps,tanggal_update_suara_kawalpemilu_pilpres, pas1, pas2, tSah, sah, photo, tipe_form) VALUES (%s,%s,%s,%s,'%s','%s', %s, %s, %s, %s,'%s', '%s') ON DUPLICATE KEY UPDATE tanggal_update_suara_kawalpemilu_pilpres='%s', pas1=%s, pas2=%s, tSah=%s, sah=%s, photo='%s'", $id_provinsi,$id_kotakab,$id_kecamatan,$id_kelurahan,$nama_tps,$date, $pas1, $pas2, $tSah, $sah,$photo,$tipe_form,$date, $pas1, $pas2, $tSah, $sah,$photo); //TODO ADD PHOTO
													if ($dbconn->query($sql) === TRUE) {
													    printf("New record %s with tipe_form %s created successfully\n", $nama_tps,$tipe_form);
													} else {
													    printf("Error: " . $sql . "\n" . $dbconn->error);
													}

												}
											}
											if ($photo == NULL){
												$sql = sprintf("INSERT INTO suara_kawalpemilu_pilpres (id_provinsi,id_kotakab,id_kecamatan,id_kelurahan,nama_tps,tanggal_update_suara_kawalpemilu_pilpres, pas1, pas2, tSah, sah, photo, tipe_form) VALUES (%s,%s,%s,%s,'%s','%s', %s, %s, %s, %s,'%s', 'unknown') ON DUPLICATE KEY UPDATE tanggal_update_suara_kawalpemilu_pilpres='%s', pas1=%s, pas2=%s, tSah=%s, sah=%s, photo='%s'", $id_provinsi,$id_kotakab,$id_kecamatan,$id_kelurahan,$nama_tps,$date, $pas1, $pas2, $tSah, $sah,$photo,$date, $pas1, $pas2, $tSah, $sah,$photo); //TODO ADD PHOTO
												if ($dbconn->query($sql) === TRUE) {
												    printf("New record %s created successfully\n", $nama_tps);
												} else {
												    printf("Error: " . $sql . "\n" . $dbconn->error);
												}
											}else{
												$sql = sprintf("DELETE FROM suara_kawalpemilu_pilpres WHERE id_provinsi=%s AND id_kotakab = %s AND id_kecamatan = %s AND id_kelurahan = %s AND nama_tps = '%s' AND tipe_form = 'unknown'", $id_provinsi, $id_kotakab, $id_kecamatan, $id_kelurahan, $nama_tps);
												if ($dbconn->query($sql) === TRUE) {
												    printf("Old record %s deleted successfully\n", $nama_tps);
												} else {
												    printf("Error: " . $sql . "\n" . $dbconn->error);
												}
											}
											if ($callback){
												$callback($id_provinsi, $id_kotakab, $id_kecamatan, $id_kelurahan, $nama_tps, $dbconn);
											}
										}
									}
								}
							}
						}
					}
				}
			}
		}
	}
	$dbconn->close();
}
function myHash($array){
   return implode('|',$array);
}
function updateSuaraKPUBerdasarkanKawalPemiluTerbaru(){
	// Create connection
	$dbconn = new mysqli(DBSERVERNAME, DBUSERNAME, DBPASSWORD);
	$server_kawalpemilu = $GLOBALS['server_kawalpemilu'];
	$server_kpu = $GLOBALS['server_kpu'];

	$sql = sprintf("USE %s",DBNAME);
	if ($dbconn->query($sql) === TRUE) {
	    printf("USE %s succeeded", DBNAME);
	} else {
	    printf("Error: " . $sql . "\n" . $dbconn->error);
	}

	$sql = "SELECT id_provinsi, id_kotakab, id_kecamatan, id_kelurahan, id_tps, tps.nama_tps, no_tps FROM suara_kawalpemilu_pilpres LEFT JOIN tps USING (id_provinsi,id_kotakab,id_kecamatan,id_kelurahan,no_tps) LEFT JOIN suara_situngkpu_pilpres USING (id_provinsi,id_kotakab, id_kecamatan, id_kelurahan, id_tps) WHERE tanggal_update_suara_situngkpu_pilpres IS NULL OR tanggal_update_suara_situngkpu_pilpres<tanggal_update_suara_kawalpemilu_pilpres ORDER BY tanggal_update_suara_situngkpu_pilpres ASC";
	$antrian_update_tps=$dbconn->query($sql);
	update_tps($antrian_update_tps, $dbconn);
	$dbconn->close();
}
function update_tps($antrian_update_tps, $dbconn){
	$server_kawalpemilu = $GLOBALS['server_kawalpemilu'];
	$server_kpu = $GLOBALS['server_kpu'];
	if ($antrian_update_tps->num_rows > 0) {
		// database update caching
		$antrian_tps_baru = [];
		$id_kelurahan_sekarang_antrian_tps_baru = null;

    	// update data of each row
    	$id_provinsi_terupdate=array();
    	$id_kotakab_terupdate=array();
    	$id_kecamatan_terupdate=array();
    	$id_kelurahan_terupdate=array();
    	$id_tps_terupdate=array();
	    while($row = $antrian_update_tps->fetch_assoc()) {
	    	$id_provinsi=$row['id_provinsi'];
	    	$id_kotakab=$row['id_kotakab'];
	    	$id_kecamatan=$row['id_kecamatan'];
	    	$id_kelurahan=$row['id_kelurahan'];
	    	$id_tps=$row['id_tps'];
	    	$nama_tps=$row['nama_tps'];
	    	$no_tps=$row['no_tps'];

	    	if ($id_kelurahan_sekarang_antrian_tps_baru != $id_kelurahan and sizeof($antrian_tps_baru)>0){
	    		$sql = "INSERT INTO suara_situngkpu_pilpres (id_provinsi,id_kotakab,id_kecamatan,id_kelurahan,id_tps,tanggal_update_suara_situngkpu_pilpres, pas1, pas2, tSah, sah, photo) VALUES ".implode(',',$antrian_tps_baru)."  ON DUPLICATE KEY UPDATE tanggal_update_suara_situngkpu_pilpres=VALUES(tanggal_update_suara_situngkpu_pilpres), pas1=VALUES(pas1), pas2=VALUES(pas2), tSah=VALUES(tSah), sah=VALUES(sah), photo=VALUES(photo)";
	    		$id_kelurahan_sekarang_antrian_tps_baru = $id_kelurahan;
				if ($dbconn->query($sql) === TRUE) {
			    	printf("Suara situng kpu TPS-TPS di kelurahan %d diupdate\n", $id_kelurahan);
				    $antrian_tps_baru = [];
				} else {
				    printf("Error: " . $sql . "\n" . $dbconn->error);
				}
	    	}

	    	//updating details
	        if (! array_key_exists($id_provinsi,$id_provinsi_terupdate)){
	        	$sql = sprintf("SELECT * FROM provinsi WHERE id_provinsi=%d",$id_provinsi);
	        	$provinsi = $dbconn->query($sql);
	        	if ($provinsi->num_rows == 0){
					$url_nasional= sprintf(PROTOKOL_KPU."://%s/static/json/wilayah/0.json", $server_kpu);
					$json_nasional = file_get_contents_no_verify_continue_try($url_nasional);
					if ($json_nasional==NULL){
						continue;
					}
					$daftar_provinsi = json_decode($json_nasional,true);
					$daftar_provinsi_baru_sql = array();
					$daftar_id_provinsi_baru = array();
					foreach($daftar_provinsi as $id_provinsi_baru => $detail_provinsi) {
						if (! array_key_exists($id_provinsi_baru,$id_provinsi_terupdate)){
							$nama_provinsi=$detail_provinsi["nama"];

							array_push($daftar_provinsi_baru_sql,sprintf("(%d,'%s')",$id_provinsi_baru, str_replace("'","\\'",$nama_provinsi)));
							array_push($daftar_id_provinsi_baru,$id_provinsi_baru);
						}
					}
					$sql = sprintf("INSERT IGNORE INTO provinsi (id_provinsi, nama_provinsi) VALUES ".implode(',',$daftar_provinsi_baru_sql));
					if ($dbconn->query($sql) === TRUE) {
					    printf("provinsi baru %s terupdate\n", implode(',',$daftar_provinsi_baru_sql));
					    foreach ($daftar_id_provinsi_baru as $id_provinsi_baru){
							$id_provinsi_terupdate[$id_provinsi_baru]=true;
						}
					} else {
					    printf("Error: " . $sql . "\n" . $dbconn->error);
					}
	        	}else{
        			$id_provinsi_terupdate[$id_provinsi]=true;
	        	}
	        }
	        if (! array_key_exists(myHash([$id_provinsi,$id_kotakab]),$id_kecamatan_terupdate)){
	        	$sql = sprintf("SELECT * FROM kotakab WHERE id_provinsi=%d AND id_kotakab=%d",$id_provinsi,$id_kotakab);
	        	$kotakab = $dbconn->query($sql);
	        	if ($kotakab->num_rows == 0){
					$url_provinsi= sprintf(PROTOKOL_KPU."://%s/static/json/wilayah/%d.json", $server_kpu,$id_provinsi);
					$json_provinsi = file_get_contents_no_verify_continue_try($url_provinsi);
					if ($json_provinsi==NULL){
						continue;
					}
					$daftar_kotakab = json_decode($json_provinsi,true);
					$daftar_kotakab_baru_sql = array();
					$daftar_id_kotakab_baru = array();
					foreach($daftar_kotakab as $id_kotakab_baru => $detail_kotakab) {
						if (! array_key_exists(myHash([$id_provinsi,$id_kotakab_baru]),$id_kotakab_terupdate)){
							$nama_kotakab=$detail_kotakab["nama"];

							array_push($daftar_kotakab_baru_sql,sprintf("(%d,%d,'%s')",$id_provinsi,$id_kotakab_baru, str_replace("'","\\'",$nama_kotakab)));
							array_push($daftar_id_kotakab_baru,myHash([$id_provinsi,$id_kotakab_baru]));
						}
					}
					$sql = sprintf("INSERT IGNORE INTO kotakab (id_provinsi, id_kotakab, nama_kotakab) VALUES ".implode(',',$daftar_kotakab_baru_sql));
					if ($dbconn->query($sql) === TRUE) {
					    printf("kotakab baru %s terupdate\n", implode(',',$daftar_kotakab_baru_sql));
					    foreach ($daftar_id_kotakab_baru as $id_kotakab_baru){
							$id_kotakab_terupdate[$id_kotakab_baru]=true;
						}
					} else {
					    printf("Error: " . $sql . "\n" . $dbconn->error);
					}
	        	}else{
        			$id_kotakab_terupdate[myHash([$id_provinsi,$id_kotakab])]=true;
	        	}
	        }
	        if (! array_key_exists(myHash([$id_provinsi,$id_kotakab,$id_kecamatan]),$id_kecamatan_terupdate)){
	        	$sql = sprintf("SELECT * FROM kecamatan WHERE id_provinsi=%d AND id_kotakab=%d AND id_kecamatan = %d",$id_provinsi,$id_kotakab,$id_kecamatan);
	        	$kecamatan = $dbconn->query($sql);
	        	if ($kecamatan->num_rows == 0){
					$url_kotakab= sprintf(PROTOKOL_KPU."://%s/static/json/wilayah/%d/%d.json", $server_kpu,$id_provinsi,$id_kotakab);
					$json_kotakab = file_get_contents_no_verify_continue_try($url_kotakab);
					if ($json_kotakab==NULL){
						continue;
					}
					$daftar_kecamatan = json_decode($json_kotakab,true);
					$daftar_kecamatan_baru_sql = array();
					$daftar_id_kecamatan_baru = array();
					foreach($daftar_kecamatan as $id_kecamatan_baru => $detail_kecamatan) {
						if (! array_key_exists(myHash([$id_provinsi,$id_kotakab,$id_kecamatan_baru]),$id_kecamatan_terupdate)){
							$nama_kecamatan=$detail_kecamatan["nama"];

							array_push($daftar_kecamatan_baru_sql,sprintf("(%d,%d,%d,'%s')",$id_provinsi,$id_kotakab,$id_kecamatan_baru, str_replace("'","\\'",$nama_kecamatan)));
							array_push($daftar_id_kecamatan_baru,myHash([$id_provinsi,$id_kotakab,$id_kecamatan_baru]));
						}
					}
					$sql = sprintf("INSERT IGNORE INTO kecamatan (id_provinsi, id_kotakab, id_kecamatan, nama_kecamatan) VALUES ".implode(',',$daftar_kecamatan_baru_sql));
					if ($dbconn->query($sql) === TRUE) {
					    printf("kecamatan baru %s terupdate\n", implode(',',$daftar_kecamatan_baru_sql));
					    foreach ($daftar_id_kecamatan_baru as $id_kecamatan_baru){
							$id_kecamatan_terupdate[$id_kecamatan_baru]=true;
						}
					} else {
					    printf("Error: " . $sql . "\n" . $dbconn->error);
					}
	        	}else{
        			$id_kecamatan_terupdate[myHash([$id_provinsi,$id_kotakab,$id_kecamatan])]=true;
	        	}
	        }
	        if (! array_key_exists(myHash([$id_provinsi,$id_kotakab,$id_kecamatan,$id_kelurahan]),$id_kelurahan_terupdate)){
	        	$sql = sprintf("SELECT * FROM kelurahan WHERE id_provinsi=%d AND id_kotakab=%d AND  id_kecamatan=%d AND id_kelurahan = %d ",$id_provinsi,$id_kotakab,$id_kecamatan,$id_kelurahan);
	        	$kelurahan = $dbconn->query($sql);
	        	if ($kelurahan->num_rows == 0){
					$url_kecamatan= sprintf(PROTOKOL_KPU."://%s/static/json/wilayah/%d/%d/%d.json", $server_kpu,$id_provinsi,$id_kotakab,$id_kecamatan);
					$json_kecamatan = file_get_contents_no_verify_continue_try($url_kecamatan);
					if ($json_kecamatan==NULL){
						continue;
					}
					$daftar_kelurahan = json_decode($json_kecamatan,true);
					$daftar_kelurahan_baru_sql = array();
					$daftar_id_kelurahan_baru = array();
					foreach($daftar_kelurahan as $id_kelurahan_baru => $detail_kelurahan) {
						if (! array_key_exists(myHash([$id_provinsi,$id_kotakab,$id_kecamatan,$id_kelurahan_baru]),$id_kelurahan_terupdate)){
							$nama_kelurahan=$detail_kelurahan["nama"];

							array_push($daftar_kelurahan_baru_sql,sprintf("(%d,%d,%d,%d,'%s')",$id_provinsi,$id_kotakab,$id_kecamatan,$id_kelurahan_baru, str_replace("'","\\'",$nama_kelurahan)));
							array_push($daftar_id_kelurahan_baru,myHash([$id_provinsi,$id_kotakab,$id_kecamatan,$id_kelurahan_baru]));
						}
					}
					$sql = sprintf("INSERT IGNORE INTO kelurahan (id_provinsi, id_kotakab, id_kecamatan, id_kelurahan, nama_kelurahan) VALUES ".implode(',',$daftar_kelurahan_baru_sql));
					if ($dbconn->query($sql) === TRUE) {
					    printf("kelurahan baru %s terupdate\n", implode(',',$daftar_kelurahan_baru_sql));
					    foreach ($daftar_id_kelurahan_baru as $id_kelurahan_baru){
							$id_kelurahan_terupdate[$id_kelurahan_baru]=true;
						}
					} else {
					    printf("Error: " . $sql . "\n" . $dbconn->error);
					}
	        	}else{
        			$id_kelurahan_terupdate[myHash([$id_provinsi,$id_kotakab,$id_kecamatan,$id_kelurahan])]=true;
	        	}
	        }
	        if (! array_key_exists(myHash([$id_provinsi,$id_kotakab,$id_kecamatan,$id_kelurahan,$no_tps]),$id_tps_terupdate)){
	        	$sql = sprintf("SELECT * FROM tps WHERE id_provinsi=%d AND id_kotakab=%d AND id_kecamatan=%d AND id_kelurahan = %d AND no_tps = %d",$id_provinsi,$id_kotakab,$id_kecamatan,$id_kelurahan,$no_tps);
	        	$tps = $dbconn->query($sql);
	        	if ($tps->num_rows == 0){
					$url_kelurahan= sprintf(PROTOKOL_KPU."://%s/static/json/wilayah/%d/%d/%d/%d.json", $server_kpu,$id_provinsi,$id_kotakab,$id_kecamatan,$id_kelurahan);
					$json_kelurahan = file_get_contents_no_verify_continue_try($url_kelurahan);
					if ($json_kelurahan==NULL){
						continue;
					}
					$daftar_tps = json_decode($json_kelurahan,true);
					$daftar_tps_baru_sql = array();
					$daftar_no_tps_baru = array();
					foreach($daftar_tps as $id_tps_baru => $detail_tps) {
						$nama_tps_baru = $detail_tps["nama"];
						$no_tps_baru = (int)substr($nama_tps_baru,4);
						if (! array_key_exists(myHash([$id_provinsi,$id_kotakab,$id_kecamatan,$id_kelurahan,$no_tps_baru]),$id_tps_terupdate)){
							array_push($daftar_tps_baru_sql,sprintf("(%d,%d,%d,%d,%d,'%s')",$id_provinsi,$id_kotakab,$id_kecamatan,$id_kelurahan,$id_tps_baru, str_replace("'","\\'",$nama_tps_baru)));
							array_push($daftar_no_tps_baru,myHash([$id_provinsi,$id_kotakab,$id_kecamatan,$id_kelurahan,$no_tps_baru]));
							$map_id_tps_baru[myHash([$id_provinsi,$id_kotakab,$id_kecamatan,$id_kelurahan,$no_tps_baru])]=$id_tps_baru;
						}
					}
					$sql = sprintf("INSERT IGNORE INTO tps (id_provinsi, id_kotakab, id_kecamatan, id_kelurahan, id_tps, nama_tps) VALUES ".implode(',',$daftar_tps_baru_sql));
					if ($dbconn->query($sql) === TRUE) {
					    printf("tps baru %s terupdate\n", implode(',',$daftar_tps_baru_sql));
					    foreach ($daftar_no_tps_baru as $id_tps_baru){
							$id_tps_terupdate[$id_tps_baru]=true;
						}
					} else {
						print('-->'.implode(',',$daftar_tps_baru_sql)."<<--\n");
					    printf("Error: " . $sql . "\n" . $dbconn->error);
					}
	        	}else{
        			$id_tps_terupdate[myHash([$id_provinsi,$id_kotakab,$id_kecamatan,$id_kelurahan,$no_tps])]=true;
        			$tps_row = $tps->fetch_assoc();
        			$id_tps_baru = $tps_row['id_tps'];
        			$map_id_tps_baru[myHash([$id_provinsi,$id_kotakab,$id_kecamatan,$id_kelurahan,$no_tps])]=$id_tps_baru;
	        	}
	        }
	        if (! $id_tps){
	        	$id_tps=$map_id_tps_baru[myHash([$id_provinsi,$id_kotakab,$id_kecamatan,$id_kelurahan,$no_tps])];
	        }
	    	//updating suara
	    	$url_suara = sprintf(PROTOKOL_KPU."://".$server_kpu."/static/json/hhcw/ppwp/%d/%d/%d/%d/%d.json",
	    		$id_provinsi, $id_kotakab, $id_kecamatan, $id_kelurahan, $id_tps);
	    	$json_suara = file_get_contents_no_verify_continue_try($url_suara);
			if ($json_suara==NULL){
				continue;
			}
	    	$suara = json_decode($json_suara,true);
	    	if (array_key_exists('chart',$suara)){
	    		$pas1 = $suara['chart'][21];
	    		$pas2 = $suara['chart'][22];
	    		$tSah = $suara['suara_tidak_sah'];
	    		$sah = $suara['suara_sah'];
				$date = date('Y-m-d H:i:s');
				$photo = implode(';',$suara['images']);
	    		$tps_baru = sprintf( "(%s,%s,%s,%s,%s,'%s', %s, %s, %s, %s,'%s')", $id_provinsi,$id_kotakab,$id_kecamatan,$id_kelurahan,$id_tps,$date, $pas1, $pas2, $tSah, $sah,$photo);
	    		array_push($antrian_tps_baru,$tps_baru);

	    	}
	    }
    	if (sizeof($antrian_tps_baru)>0){
    		$sql = "INSERT INTO suara_situngkpu_pilpres (id_provinsi,id_kotakab,id_kecamatan,id_kelurahan,id_tps,tanggal_update_suara_situngkpu_pilpres, pas1, pas2, tSah, sah, photo) VALUES ".implode(',',$antrian_tps_baru)."  ON DUPLICATE KEY UPDATE tanggal_update_suara_situngkpu_pilpres=VALUES(tanggal_update_suara_situngkpu_pilpres), pas1=VALUES(pas1), pas2=VALUES(pas2), tSah=VALUES(tSah), sah=VALUES(sah), photo=VALUES(photo)";
			if ($dbconn->query($sql) === TRUE) {
			    printf("Suara situng kpu TPS-TPS di kelurahan %d diupdate\n", $id_kelurahan);
			    $antrian_tps_baru = [];
			} else {
			    printf("Error: " . $sql . "\n" . $dbconn->error);
			}
    	}
	} else {
	    printf("0 results");
	}
}
function after_kawalpemilu_situng_kpu_callback($id_provinsi, $id_kotakab, $id_kecamatan, $id_kelurahan, $nama_tps, $dbconn){
	$no_tps = (int)substr($nama_tps,4);
	$sql = sprintf("SELECT * FROM suara_kawalpemilu_pilpres LEFT JOIN tps USING (id_provinsi,id_kotakab,id_kecamatan,id_kelurahan,nama_tps) LEFT JOIN suara_situngkpu_pilpres USING (id_provinsi,id_kotakab, id_kecamatan, id_kelurahan, id_tps) WHERE id_provinsi=%d AND id_kotakab=%d AND id_kecamatan=%d AND id_kelurahan=%d AND no_tps=%d",$id_provinsi, $id_kotakab, $id_kecamatan, $id_kelurahan, $no_tps);
	$antrian_update_tps = $dbconn->query($sql);
	update_tps($antrian_update_tps,$dbconn);
}
function update_both(){
	updateSuaraKawalPemilu('after_kawalpemilu_situng_kpu_callback');
}
?>