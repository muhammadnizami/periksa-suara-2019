<?php

	include('dbconfig.php');

	// Create connection
	$dbconn = new mysqli(DBSERVERNAME, DBUSERNAME, DBPASSWORD);

	// Check connection
	if ($dbconn->connect_error) {
	    printf("Local DB connection failed: " . $dbconn->connect_error . "\n");
	    return false;
	} 

	$sql = sprintf("USE %s",DBNAME);
	if ($dbconn->query($sql) === TRUE) {
	    
	} else {
	    printf("Error: " . $sql . "\n" . $dbconn->error);
	}

?>
<!DOCTYPE html>
<html>
    <head>
        <title>PeriksaSuara2019 | Perbedaan Situng KPU dengan KawalPemilu | Pilpres</title>
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.0/jquery.min.js"></script>
		<style>
			body {
			    padding-bottom: 200px;
			}
		</style>
	</head>
    <body>
        <h1>PeriksaSuara2019 | Perbedaan Situng KPU dengan KawalPemilu | Pilpres</h1>
        <h2>CATATAN</h2>
        <p> Ini dibuat sebagai alat bantu saja untuk memeriksa kembali data. Hasil yang muncul di situs ini bukanlah sebuah pernyataan ataupun publikasi resmi dari organisasi ataupun pihak apapun.
        </p>
        <p> Data tidak selalu data terbaru dan tidak lengkap. Data hanya diupdate kadang-kadang dan hanya sebagian saja. </p>
        <p> Data yang muncul di bawah ini bisa saja salah. Harap crosscheck dengan sumber aslinya. Data di situs ini hanya untuk membantu mendeteksi dan tidak dapat dijadikan bukti apapun. </p>
        <p> ***Untuk memahami rangkuman, harap perhatikan macam-macam penyebab kenapa data berbeda. Arti dari rangkuman ini harus dilihat dari sumber kesalahannya. Sumber kesalahan bisa dari mana saja, termasuk dari pengumpulan data di situs ini. Silakan lihat data yang lebih detil di bagian bawah </p>
        <h2>Rangkuman***</h2>
<!-- APAKAH PERLU TAMPILAN TABEL RANGKUMAN? pertimbangannya adalah tidak ada artinya rangkuman dari data yang belum diperiksa dan diinterpretasi. Boleh dibilang, sebelum diperiksa dan diinterpretasi, ini data sampah, jadi rangkumannya juga sampah.-->
         <table border=1>
	        <tr>
	        	<th>Perbedaan</th>
	        	<th>Jumlah Suara</th>
	        	<th>Jumlah TPS</th>
	    	</tr>
	    	<tr>
	    		<td> Paslon 01 Situng KPU > Paslon 01 KawalPemilu </td>
	    		<?php
	    			$sql = "SELECT SUM(suara_situngkpu_pilpres.pas1 - suara_kawalpemilu_pilpres.pas1) as jumlah_suara ,COUNT(*) as jumlah_tps FROM (suara_situngkpu_pilpres NATURAL JOIN tps) JOIN suara_kawalpemilu_pilpres USING (id_provinsi, id_kotakab, id_kecamatan, id_kelurahan, nama_tps) WHERE suara_situngkpu_pilpres.pas1 > suara_kawalpemilu_pilpres.pas1";
	    			$result = $dbconn->query($sql)->fetch_assoc();
    			?>
	    		<th><?php echo $result['jumlah_suara'] ?></th>
	    		<th><?php echo $result['jumlah_tps'] ?></th>
    		</tr>
	    	<tr>
	    		<td> Paslon 01 Situng KPU < Paslon 01 KawalPemilu </td>
	    		<?php
	    			$sql = "SELECT SUM(0 - suara_situngkpu_pilpres.pas1 + suara_kawalpemilu_pilpres.pas1) as jumlah_suara ,COUNT(*) as jumlah_tps FROM (suara_situngkpu_pilpres NATURAL JOIN tps) JOIN suara_kawalpemilu_pilpres USING (id_provinsi, id_kotakab, id_kecamatan, id_kelurahan, nama_tps) WHERE suara_situngkpu_pilpres.pas1 < suara_kawalpemilu_pilpres.pas1";
	    			$result = $dbconn->query($sql)->fetch_assoc();
    			?>
	    		<th><?php echo $result['jumlah_suara'] ?></th>
	    		<th><?php echo $result['jumlah_tps'] ?></th>
    		</tr>
	    	<tr>
	    		<td> Paslon 02 Situng KPU > Paslon 02 KawalPemilu </td>
	    		<?php
	    			$sql = "SELECT SUM(suara_situngkpu_pilpres.pas2 - suara_kawalpemilu_pilpres.pas2) as jumlah_suara ,COUNT(*) as jumlah_tps FROM (suara_situngkpu_pilpres NATURAL JOIN tps) JOIN suara_kawalpemilu_pilpres USING (id_provinsi, id_kotakab, id_kecamatan, id_kelurahan, nama_tps) WHERE suara_situngkpu_pilpres.pas2 > suara_kawalpemilu_pilpres.pas2";
	    			$result = $dbconn->query($sql)->fetch_assoc();
    			?>
	    		<th><?php echo $result['jumlah_suara'] ?></th>
	    		<th><?php echo $result['jumlah_tps'] ?></th>
    		</tr>
	    	<tr>
	    		<td> Paslon 02 Situng KPU < Paslon 02 KawalPemilu </td>
	    		<?php
	    			$sql = "SELECT SUM(0 - suara_situngkpu_pilpres.pas2 + suara_kawalpemilu_pilpres.pas2) as jumlah_suara ,COUNT(*) as jumlah_tps FROM (suara_situngkpu_pilpres NATURAL JOIN tps) JOIN suara_kawalpemilu_pilpres USING (id_provinsi, id_kotakab, id_kecamatan, id_kelurahan, nama_tps) WHERE suara_situngkpu_pilpres.pas2 < suara_kawalpemilu_pilpres.pas2";
	    			$result = $dbconn->query($sql)->fetch_assoc();
    			?>
	    		<th><?php echo $result['jumlah_suara'] ?></th>
	    		<th><?php echo $result['jumlah_tps'] ?></th>
    		</tr>
	    	<tr>
	    		<td> Suara Tidak Sah Situng KPU > Suara Tidak Sah KawalPemilu </td>
	    		<?php
	    			$sql = "SELECT SUM(suara_situngkpu_pilpres.tSah - suara_kawalpemilu_pilpres.tSah) as jumlah_suara ,COUNT(*) as jumlah_tps FROM (suara_situngkpu_pilpres NATURAL JOIN tps) JOIN suara_kawalpemilu_pilpres USING (id_provinsi, id_kotakab, id_kecamatan, id_kelurahan, nama_tps) WHERE suara_situngkpu_pilpres.tSah > suara_kawalpemilu_pilpres.tSah";
	    			$result = $dbconn->query($sql)->fetch_assoc();
    			?>
	    		<th><?php echo $result['jumlah_suara'] ?></th>
	    		<th><?php echo $result['jumlah_tps'] ?></th>
    		</tr>
	    	<tr>
	    		<td> Suara Tidak Sah Situng KPU < Suara Tidak Sah KawalPemilu </td>
	    		<?php
	    			$sql = "SELECT SUM(0 - suara_situngkpu_pilpres.tSah + suara_kawalpemilu_pilpres.tSah) as jumlah_suara ,COUNT(*) as jumlah_tps FROM (suara_situngkpu_pilpres NATURAL JOIN tps) JOIN suara_kawalpemilu_pilpres USING (id_provinsi, id_kotakab, id_kecamatan, id_kelurahan, nama_tps) WHERE suara_situngkpu_pilpres.tSah < suara_kawalpemilu_pilpres.tSah";
	    			$result = $dbconn->query($sql)->fetch_assoc();
    			?>
	    		<th><?php echo $result['jumlah_suara'] ?></th>
	    		<th><?php echo $result['jumlah_tps'] ?></th>
    		</tr>
	    	<tr>
	    		<td> Suara Sah Situng KPU > Suara Sah KawalPemilu </td>
	    		<?php
	    			$sql = "SELECT SUM(suara_situngkpu_pilpres.sah - suara_kawalpemilu_pilpres.sah) as jumlah_suara ,COUNT(*) as jumlah_tps FROM (suara_situngkpu_pilpres NATURAL JOIN tps) JOIN suara_kawalpemilu_pilpres USING (id_provinsi, id_kotakab, id_kecamatan, id_kelurahan, nama_tps) WHERE suara_situngkpu_pilpres.sah > suara_kawalpemilu_pilpres.sah";
	    			$result = $dbconn->query($sql)->fetch_assoc();
    			?>
	    		<th><?php echo $result['jumlah_suara'] ?></th>
	    		<th><?php echo $result['jumlah_tps'] ?></th>
    		</tr>
	    	<tr>
	    		<td> Suara Sah Situng KPU < Suara Sah KawalPemilu </td>
	    		<?php
	    			$sql = "SELECT SUM(0 - suara_situngkpu_pilpres.sah + suara_kawalpemilu_pilpres.sah) as jumlah_suara ,COUNT(*) as jumlah_tps FROM (suara_situngkpu_pilpres NATURAL JOIN tps) JOIN suara_kawalpemilu_pilpres USING (id_provinsi, id_kotakab, id_kecamatan, id_kelurahan, nama_tps) WHERE suara_situngkpu_pilpres.sah < suara_kawalpemilu_pilpres.sah";
	    			$result = $dbconn->query($sql)->fetch_assoc();
    			?>
	    		<th><?php echo $result['jumlah_suara'] ?></th>
	    		<th><?php echo $result['jumlah_tps'] ?></th>
    		</tr>
	    </table>
    		<p> Jumlah TPS yang telah dicocokkan: <?php
    			$sql = "SELECT COUNT(*) as jumlah_tps FROM (suara_situngkpu_pilpres NATURAL JOIN tps) JOIN suara_kawalpemilu_pilpres USING (id_provinsi, id_kotakab, id_kecamatan, id_kelurahan, nama_tps);";
    			$result = $dbconn->query($sql)->fetch_assoc();
    			echo $result['jumlah_tps'];
			?></p>
    		<p> Jumlah TPS dengan data berbeda: <?php
    			$sql = "SELECT COUNT(*) as jumlah_tps FROM (suara_situngkpu_pilpres NATURAL JOIN tps) JOIN suara_kawalpemilu_pilpres USING (id_provinsi, id_kotakab, id_kecamatan, id_kelurahan, nama_tps) WHERE suara_situngkpu_pilpres.pas1 <> suara_kawalpemilu_pilpres.pas1 OR suara_situngkpu_pilpres.pas2 <> suara_kawalpemilu_pilpres.pas2 OR suara_situngkpu_pilpres.tSah <> suara_kawalpemilu_pilpres.tSah OR suara_situngkpu_pilpres.sah <> suara_kawalpemilu_pilpres.sah;";
    			$result = $dbconn->query($sql)->fetch_assoc();
    			echo $result['jumlah_tps'];
			?></p>
        <p> ***Untuk memahami rangkuman, harap perhatikan macam-macam penyebab kenapa data berbeda. Arti dari rangkuman ini harus dilihat dari sumber kesalahannya. Sumber kesalahan bisa dari mana saja, termasuk dari pengumpulan data di situs ini. Silakan lihat data yang lebih detil di bagian bawah </p>

		<h2>Lihat data yang berbeda</h2>
		<form id="filters" onsubmit="onSubmit(); return false;">
			<script>
				function onSubmit(){


					$.ajax({
						type: 'get',
						url: 'beda_kawalpemilu_pilpres_json.php',
						data: $('#filters').serialize(),
						success: function (response) {
						  entries = JSON.parse(response);
						  console.log('success, response: ' + entries);
						  reset_page_num();
						  display();
						}

					});

				};
			</script>
			<label> Pilih jenis beda data: </label><br>
			<?php
			 $check_all= (!(isset($_GET['pas1_kpu_gt_kawalpemilu']) or isset($_GET['pas1_kpu_lt_kawalpemilu']) or isset($_GET['pas2_kpu_gt_kawalpemilu']) or isset($_GET['pas2_kpu_lt_kawalpemilu']) or isset($_GET['tSah_kpu_gt_kawalpemilu']) or isset($_GET['tSah_kpu_lt_kawalpemilu']) or isset($_GET['sah_kpu_gt_kawalpemilu']) or isset($_GET['sah_kpu_lt_kawalpemilu'])));
			 ?>

			<input type="checkbox" name="pas1_kpu_gt_kawalpemilu" <?php if ($check_all or isset($_GET['pas1_kpu_gt_kawalpemilu'])){ echo 'checked';} ?>> Paslon 01 Situng KPU > Paslon 01 KawalPemilu<br>
			<input type="checkbox" name="pas1_kpu_lt_kawalpemilu" <?php if ($check_all or isset($_GET['pas1_kpu_lt_kawalpemilu'])){ echo 'checked';} ?>> Paslon 01 Situng KPU < Paslon 01 KawalPemilu<br>
			<input type="checkbox" name="pas2_kpu_gt_kawalpemilu" <?php if ($check_all or isset($_GET['pas2_kpu_gt_kawalpemilu'])){ echo 'checked';} ?>> Paslon 02 Situng KPU > Paslon 02 KawalPemilu<br>
			<input type="checkbox" name="pas2_kpu_lt_kawalpemilu" <?php if ($check_all or isset($_GET['pas2_kpu_lt_kawalpemilu'])){ echo 'checked';} ?>> Paslon 02 Situng KPU < Paslon 02 KawalPemilu<br>
			<input type="checkbox" name="tSah_kpu_gt_kawalpemilu" <?php if ($check_all or isset($_GET['tSah_kpu_gt_kawalpemilu'])){ echo 'checked';} ?>> Suara Tidak Sah Situng KPU > Suara Tidak Sah KawalPemilu<br>
			<input type="checkbox" name="tSah_kpu_lt_kawalpemilu" <?php if ($check_all or isset($_GET['tSah_kpu_lt_kawalpemilu'])){ echo 'checked';} ?>> Suara Tidak Sah Situng KPU < Suara Tidak Sah KawalPemilu<br>
			<input type="checkbox" name="sah_kpu_gt_kawalpemilu" <?php if ($check_all or isset($_GET['sah_kpu_gt_kawalpemilu'])){ echo 'checked';} ?>> Suara Sah Situng KPU > Suara Sah KawalPemilu<br>
			<input type="checkbox" name="sah_kpu_lt_kawalpemilu" <?php if ($check_all or isset($_GET['sah_kpu_lt_kawalpemilu'])){ echo 'checked';} ?>> Suara Sah Situng KPU < Suara Sah KawalPemilu<br>
			<button type="submit">Lihat</button>
		</form>
		<div id="display_div" hidden>
			<p id="num_rows"></p>
			<button type="button" onclick="prev_page()">Sebelumnya</button>
			 Halaman <span id="page_num">1</span> / <span id="page_count"></span> 
			 					<button type="button" onclick="next_page()">Selanjutnya</button>
								<br> <span id="num_entries_per_page"></span> entri per halaman<br>
								<table id="datatable" border=1 width="100%"><thead id="datatable_head"><tr><th>Waktu update</th><th>TPS</th><th>Data Situng KPU</th><th width="30%">Foto Situng KPU</th><th>Data KawalPemilu</th><th width="30%">Foto KawalPemilu</th></thead></tr>
								<tbody id="datatable_body"></tbody>
					    		</table>
			<button type="button" onclick="prev_page()">Sebelumnya</button>
			 Halaman <span id="page_num_2">1</span> / <span id="page_count_2"></span> 
			<button type="button" onclick="next_page()">Selanjutnya</button>
    	</div>

		<script>
			var page_num=1;
			var num_entries_per_page = 10;
			var page_count=0;
			var entries=null;
			function reset_page_num(){
				page_num=1;
			}
			function display(){
				if (entries){
					page_count=Math.ceil(entries.length/num_entries_per_page);
					document.getElementById("page_num").innerHTML=page_num;
					document.getElementById("page_count").innerHTML=page_count;
					document.getElementById("page_num_2").innerHTML=page_num;
					document.getElementById("page_count_2").innerHTML=page_count;
					document.getElementById("num_entries_per_page").innerHTML=num_entries_per_page;
					document.getElementById("num_rows").innerHTML=""+entries.length+" TPS";
					var tbody = document.getElementById("datatable_body");
					tbody.innerHTML="";
					for (var i=(page_num-1)*num_entries_per_page; i < (page_num)*num_entries_per_page && i < entries.length; i++){
						entry = entries[i];
						row = tbody.insertRow();
						row.insertCell().innerHTML="dari situngkpu: <br> " + entry["tanggal_update_suara_situngkpu_pilpres"] + "<br> dari kawalpemilu: <br> " + entry["tanggal_update_suara_kawalpemilu_pilpres"] + "<br>";
						row.insertCell().innerHTML="Provinsi: "+ entry["nama_provinsi"] + "<br>Kabupaten/Kota: "+ entry["nama_kotakab"] + "<br>	Kecamatan: "+ entry["nama_kecamatan"] + "<br>Kelurahan: "+ entry["nama_kelurahan"] + "<br>";
						row.insertCell().innerHTML=" pas1: " + entry["situngkpupas1"] + "<br> pas2: " + entry["situngkpupas2"] + "<br> tidak Sah: " + entry["situngkputSah"] + "<br> sah: " + entry["situngkpusah"] + "<br>";
						id_tps = entry["id_tps"];
						id_tps_str = id_tps.toString();
						var img = document.createElement("img");
						img.src="https://pemilu2019.kpu.go.id/img/c/" + id_tps_str.substring(0,3) + "/" + id_tps.substring(3,6) + "/" + id_tps_str + "/" + entry["situngkpuphoto"].split(";")[1];
						img.style="width:100%;";
						row.insertCell().appendChild(img);
						row.insertCell().innerHTML=" pas1: " + entry["kawalpemilupas1"] + "<br> pas2: " + entry["kawalpemilupas2"] + "<br> tidak Sah: " + entry["kawalpemilutSah"] + "<br> sah: " + entry["kawalpemilusah"] + "<br> ";
						var img2 = document.createElement("img");
						img2.src = entry["kawalpemiluphoto"];
						img2.style="width:100%;";
						row.insertCell().appendChild(img2);
					}
					document.getElementById("display_div").removeAttribute("hidden");
				}else{
					document.getElementById("display_div").addAttribute("hidden");
				}
			}

			function prev_page(){
				if (page_num > 1){
					page_num--;
					display();
				}
			}

			function next_page(){
				if (page_num < page_count){
					page_num++;
					display();
				}
			}
		</script>
    </body>
</html>


