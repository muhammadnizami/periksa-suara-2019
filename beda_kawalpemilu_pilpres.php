<?php
	// define the path and name of cached file
	$cachefile = 'cached-files-beda_kawalpemilu_pilpres.php';
	// define how long we want to keep the file in seconds.
	$cachetime = 20;
	// Check if the cached file is still fresh. If it is, serve it up and exit.
	if (file_exists($cachefile)){
		if (time() - $cachetime < filemtime($cachefile)) {
	   		include($cachefile);
	    	exit;
    	}else{ignore_user_abort(true);
			set_time_limit(0);

			ob_start();
			// do initial processing here
			include($cachefile); // send the response
			header('Connection: close');
			header('Content-Length: '.ob_get_length());
			ob_end_flush();
			ob_flush();
			flush();

    	}
	}
	// if there is either no file OR the file to too old, render the page and capture the HTML.
	ob_start();
?>

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
			/* Popup box BEGIN */
			.hover_bkgr_fricc{
			    background:rgba(0,0,0,.4);
			    cursor:pointer;
			    display:none;
			    height:100%;
			    position:fixed;
			    text-align:center;
			    top:0;
			    width:100%;
			    z-index:10000;
			}
			.hover_bkgr_fricc .helper{
			    display:inline-block;
			    height:100%;
			    vertical-align:middle;
			}
			.hover_bkgr_fricc > div {
			    background-color: #fff;
			    box-shadow: 10px 10px 60px #555;
			    display: inline-block;
			    height: auto;
			    max-width: 551px;
			    min-height: 100px;
			    vertical-align: middle;
			    width: 60%;
			    position: relative;
			    border-radius: 8px;
			    padding: 15px 5%;
			}
			.popupCloseButton {
			    background-color: #fff;
			    border: 3px solid #999;
			    border-radius: 50px;
			    cursor: pointer;
			    display: inline-block;
			    font-family: arial;
			    font-weight: bold;
			    position: absolute;
			    top: -20px;
			    right: -20px;
			    font-size: 25px;
			    line-height: 30px;
			    width: 30px;
			    height: 30px;
			    text-align: center;
			}
			.popupCloseButton:hover {
			    background-color: #ccc;
			}
			.trigger_popup_fricc {
			    cursor: pointer;
			    font-size: 20px;
			    margin: 20px;
			    display: inline-block;
			    font-weight: bold;
			}
			/* Popup box END */

			/* other styles */
			em{
				text-decoration: underline;
			}
		</style>
		<script>
			$(window).on('load', function () {
			    $(".trigger_popup_fricc").click(function(){
			    	hover_id = $(this).attr('id').replace('trigger','hover');
			    	console.log(hover_id);
			       $('.hover_bkgr_fricc#'+hover_id).show();
			    });
			    $('.hover_bkgr_fricc').click(function(){
			    	hover_id = $(this).attr('id');
			       $('.hover_bkgr_fricc#'+hover_id).hide();
			    });
			    $('.popupCloseButton').click(function(){
			    	hover_id = $(this).attr('id').replace('closebutton','hover');
			       $('.hover_bkgr_fricc#'+hover_id).hide();
			    });
			});
		</script>
	</head>
    <body>
        <h1>PeriksaSuara2019 | Perbedaan Situng KPU dengan KawalPemilu | Pilpres</h1>
        <a class="trigger_popup_fricc" id="popup-trigger-1">PENTING! HARAP BACA CATATAN DAN DISCLAIMER. klik di sini untuk lihat disclaimer</a>

		<div class="hover_bkgr_fricc" id="popup-hover-1">
		    <span class="helper"></span>
		    <div>
		        <div class="popupCloseButton" id="popup-closebutton-1">X</div>
			        <h2>CATATAN</h2>
			        <p> Ini dibuat sebagai alat bantu saja untuk memeriksa kembali data. Hasil yang muncul di situs ini bukanlah sebuah pernyataan ataupun publikasi resmi dari organisasi ataupun pihak apapun.
			        </p>
			        <p> Data tidak selalu data terbaru dan tidak lengkap. Data hanya diupdate kadang-kadang dan hanya sebagian saja. </p>
			        <p> Data yang muncul di bawah ini bisa saja salah. Harap crosscheck dengan sumber aslinya. Data di situs ini hanya untuk membantu mendeteksi dan tidak dapat dijadikan bukti apapun. </p>
			        <p> ***Untuk memahami rangkuman, harap perhatikan macam-macam penyebab kenapa data berbeda. Arti dari rangkuman ini harus dilihat dari sumber kesalahannya. Sumber kesalahan bisa dari mana saja, termasuk dari pengumpulan data di situs ini. Silakan lihat data yang lebih detil di bagian bawah </p>
		    </div>
		</div>
        <h2> Rangkuman***</h2>
		<p> Jumlah TPS yang telah dicocokkan: <?php
			$sql = "SELECT COUNT(*) as jumlah_tps FROM (suara_situngkpu_pilpres NATURAL JOIN tps) JOIN suara_kawalpemilu_pilpres USING (id_provinsi, id_kotakab, id_kecamatan, id_kelurahan, no_tps);";
			$result = $dbconn->query($sql)->fetch_assoc();
			echo $result['jumlah_tps'];
		?></p>
		<p> Jumlah TPS dengan data berbeda: <?php
			$sql = "SELECT COUNT(*) as jumlah_tps FROM (suara_situngkpu_pilpres NATURAL JOIN tps) JOIN suara_kawalpemilu_pilpres USING (id_provinsi, id_kotakab, id_kecamatan, id_kelurahan, no_tps) WHERE suara_situngkpu_pilpres.pas1 <> suara_kawalpemilu_pilpres.pas1 OR suara_situngkpu_pilpres.pas2 <> suara_kawalpemilu_pilpres.pas2 OR suara_situngkpu_pilpres.tSah <> suara_kawalpemilu_pilpres.tSah OR suara_situngkpu_pilpres.sah <> suara_kawalpemilu_pilpres.sah;";
			$result = $dbconn->query($sql)->fetch_assoc();
			echo $result['jumlah_tps'];
		?></p>
        <button class="trigger_popup_fricc" id="popup-trigger-2"> Lihat lebih banyak rangkuman </button>
<!-- APAKAH PERLU TAMPILAN TABEL RANGKUMAN? pertimbangannya adalah tidak ada artinya rangkuman dari data yang belum diperiksa dan diinterpretasi. Boleh dibilang, sebelum diperiksa dan diinterpretasi, ini data sampah, jadi rangkumannya juga sampah.-->

		<div class="hover_bkgr_fricc" id="popup-hover-2">
		    <span class="helper"></span>
		    <div>
		        <div class="popupCloseButton" id="popup-closebutton-2">X</div>
			         <table border=1>
				        <tr>
				        	<th>Perbedaan</th>
				        	<th>Jumlah Suara</th>
				        	<th>Jumlah TPS</th>
				    	</tr>
				    	<tr>
				    		<td> Paslon 01 Situng KPU > Paslon 01 KawalPemilu </td>
				    		<?php
				    			$sql = "SELECT SUM(suara_situngkpu_pilpres.pas1 - suara_kawalpemilu_pilpres.pas1) as jumlah_suara ,COUNT(*) as jumlah_tps FROM (suara_situngkpu_pilpres NATURAL JOIN tps) JOIN suara_kawalpemilu_pilpres USING (id_provinsi, id_kotakab, id_kecamatan, id_kelurahan, no_tps) WHERE suara_situngkpu_pilpres.pas1 > suara_kawalpemilu_pilpres.pas1";
				    			$result = $dbconn->query($sql)->fetch_assoc();
			    			?>
				    		<th><?php echo $result['jumlah_suara'] ?></th>
				    		<th><?php echo $result['jumlah_tps'] ?></th>
			    		</tr>
				    	<tr>
				    		<td> Paslon 01 Situng KPU < Paslon 01 KawalPemilu </td>
				    		<?php
				    			$sql = "SELECT SUM(0 - suara_situngkpu_pilpres.pas1 + suara_kawalpemilu_pilpres.pas1) as jumlah_suara ,COUNT(*) as jumlah_tps FROM (suara_situngkpu_pilpres NATURAL JOIN tps) JOIN suara_kawalpemilu_pilpres USING (id_provinsi, id_kotakab, id_kecamatan, id_kelurahan, no_tps) WHERE suara_situngkpu_pilpres.pas1 < suara_kawalpemilu_pilpres.pas1";
				    			$result = $dbconn->query($sql)->fetch_assoc();
			    			?>
				    		<th><?php echo $result['jumlah_suara'] ?></th>
				    		<th><?php echo $result['jumlah_tps'] ?></th>
			    		</tr>
				    	<tr>
				    		<td> Paslon 02 Situng KPU > Paslon 02 KawalPemilu </td>
				    		<?php
				    			$sql = "SELECT SUM(suara_situngkpu_pilpres.pas2 - suara_kawalpemilu_pilpres.pas2) as jumlah_suara ,COUNT(*) as jumlah_tps FROM (suara_situngkpu_pilpres NATURAL JOIN tps) JOIN suara_kawalpemilu_pilpres USING (id_provinsi, id_kotakab, id_kecamatan, id_kelurahan, no_tps) WHERE suara_situngkpu_pilpres.pas2 > suara_kawalpemilu_pilpres.pas2";
				    			$result = $dbconn->query($sql)->fetch_assoc();
			    			?>
				    		<th><?php echo $result['jumlah_suara'] ?></th>
				    		<th><?php echo $result['jumlah_tps'] ?></th>
			    		</tr>
				    	<tr>
				    		<td> Paslon 02 Situng KPU < Paslon 02 KawalPemilu </td>
				    		<?php
				    			$sql = "SELECT SUM(0 - suara_situngkpu_pilpres.pas2 + suara_kawalpemilu_pilpres.pas2) as jumlah_suara ,COUNT(*) as jumlah_tps FROM (suara_situngkpu_pilpres NATURAL JOIN tps) JOIN suara_kawalpemilu_pilpres USING (id_provinsi, id_kotakab, id_kecamatan, id_kelurahan, no_tps) WHERE suara_situngkpu_pilpres.pas2 < suara_kawalpemilu_pilpres.pas2";
				    			$result = $dbconn->query($sql)->fetch_assoc();
			    			?>
				    		<th><?php echo $result['jumlah_suara'] ?></th>
				    		<th><?php echo $result['jumlah_tps'] ?></th>
			    		</tr>
				    	<tr>
				    		<td> Suara Tidak Sah Situng KPU > Suara Tidak Sah KawalPemilu </td>
				    		<?php
				    			$sql = "SELECT SUM(suara_situngkpu_pilpres.tSah - suara_kawalpemilu_pilpres.tSah) as jumlah_suara ,COUNT(*) as jumlah_tps FROM (suara_situngkpu_pilpres NATURAL JOIN tps) JOIN suara_kawalpemilu_pilpres USING (id_provinsi, id_kotakab, id_kecamatan, id_kelurahan, no_tps) WHERE suara_situngkpu_pilpres.tSah > suara_kawalpemilu_pilpres.tSah";
				    			$result = $dbconn->query($sql)->fetch_assoc();
			    			?>
				    		<th><?php echo $result['jumlah_suara'] ?></th>
				    		<th><?php echo $result['jumlah_tps'] ?></th>
			    		</tr>
				    	<tr>
				    		<td> Suara Tidak Sah Situng KPU < Suara Tidak Sah KawalPemilu </td>
				    		<?php
				    			$sql = "SELECT SUM(0 - suara_situngkpu_pilpres.tSah + suara_kawalpemilu_pilpres.tSah) as jumlah_suara ,COUNT(*) as jumlah_tps FROM (suara_situngkpu_pilpres NATURAL JOIN tps) JOIN suara_kawalpemilu_pilpres USING (id_provinsi, id_kotakab, id_kecamatan, id_kelurahan, no_tps) WHERE suara_situngkpu_pilpres.tSah < suara_kawalpemilu_pilpres.tSah";
				    			$result = $dbconn->query($sql)->fetch_assoc();
			    			?>
				    		<th><?php echo $result['jumlah_suara'] ?></th>
				    		<th><?php echo $result['jumlah_tps'] ?></th>
			    		</tr>
				    	<tr>
				    		<td> Suara Sah Situng KPU > Suara Sah KawalPemilu </td>
				    		<?php
				    			$sql = "SELECT SUM(suara_situngkpu_pilpres.sah - suara_kawalpemilu_pilpres.sah) as jumlah_suara ,COUNT(*) as jumlah_tps FROM (suara_situngkpu_pilpres NATURAL JOIN tps) JOIN suara_kawalpemilu_pilpres USING (id_provinsi, id_kotakab, id_kecamatan, id_kelurahan, no_tps) WHERE suara_situngkpu_pilpres.sah > suara_kawalpemilu_pilpres.sah";
				    			$result = $dbconn->query($sql)->fetch_assoc();
			    			?>
				    		<th><?php echo $result['jumlah_suara'] ?></th>
				    		<th><?php echo $result['jumlah_tps'] ?></th>
			    		</tr>
				    	<tr>
				    		<td> Suara Sah Situng KPU < Suara Sah KawalPemilu </td>
				    		<?php
				    			$sql = "SELECT SUM(0 - suara_situngkpu_pilpres.sah + suara_kawalpemilu_pilpres.sah) as jumlah_suara ,COUNT(*) as jumlah_tps FROM (suara_situngkpu_pilpres NATURAL JOIN tps) JOIN suara_kawalpemilu_pilpres USING (id_provinsi, id_kotakab, id_kecamatan, id_kelurahan, no_tps) WHERE suara_situngkpu_pilpres.sah < suara_kawalpemilu_pilpres.sah";
				    			$result = $dbconn->query($sql)->fetch_assoc();
			    			?>
				    		<th><?php echo $result['jumlah_suara'] ?></th>
				    		<th><?php echo $result['jumlah_tps'] ?></th>
			    		</tr>
				    </table>
			        <p> ***Untuk memahami rangkuman, harap perhatikan macam-macam penyebab kenapa data berbeda. Arti dari rangkuman ini harus dilihat dari sumber kesalahannya. Sumber kesalahan bisa dari mana saja, termasuk dari pengumpulan data di situs ini. Silakan lihat data yang lebih detil di bagian bawah </p>
		    </div>
		</div>

		<h2>Lihat data yang berbeda</h2>
		<form id="filters" onsubmit="onSubmit(); return false;">
			<script>
				function onSubmit(){

					document.getElementById("submitbutton").disabled = true;
					document.getElementById("submitbutton").innerHTML = "Harap tunggu...";

					$.ajax({
						type: 'get',
						url: 'beda_kawalpemilu_pilpres_json.php',
						data: $('#filters').serialize(),
						success: function (response) {
						  entries = JSON.parse(response);
						  console.log('success, response: ' + entries);
						  reset_page_num();
						  update_page_count();
						  display();
						  document.getElementById("submitbutton").disabled = false;
					      document.getElementById("submitbutton").innerHTML = "Lihat";
						}

					});

				};
			</script>
			<label> Pilih jenis beda data: </label><br>
			<?php
			 $check_all= (!(isset($_GET['pas1_kpu_gt_kawalpemilu']) or isset($_GET['pas1_kpu_lt_kawalpemilu']) or isset($_GET['pas2_kpu_gt_kawalpemilu']) or isset($_GET['pas2_kpu_lt_kawalpemilu']) or isset($_GET['tSah_kpu_gt_kawalpemilu']) or isset($_GET['tSah_kpu_lt_kawalpemilu']) or isset($_GET['sah_kpu_gt_kawalpemilu']) or isset($_GET['sah_kpu_lt_kawalpemilu'])));
			 ?>

			<style>
				@media screen and (min-width: 720px) {
					.column {
					  float: left;
					  width: 50%;
					}
				}
				@media screen and (max-width: 720px) {
					.column:after {
					  content: "";
					  display: table;
					  clear: both;
					}
				}

				/* Clear floats after the columns */
				.row:after {
				  content: "";
				  display: table;
				  clear: both;
				}
			</style>
			<div class="row">
			<div class="column"><input type="checkbox" name="pas1_kpu_gt_kawalpemilu" <?php if ($check_all or isset($_GET['pas1_kpu_gt_kawalpemilu'])){ echo 'checked';} ?>> Paslon 01 Situng KPU > Paslon 01 KawalPemilu</div>
			<div class="column"><input type="checkbox" name="pas1_kpu_lt_kawalpemilu" <?php if ($check_all or isset($_GET['pas1_kpu_lt_kawalpemilu'])){ echo 'checked';} ?>> Paslon 01 Situng KPU < Paslon 01 KawalPemilu</div>
			</div>
			<div class="row">
			<div class="column"><input type="checkbox" name="pas2_kpu_gt_kawalpemilu" <?php if ($check_all or isset($_GET['pas2_kpu_gt_kawalpemilu'])){ echo 'checked';} ?>> Paslon 02 Situng KPU > Paslon 02 KawalPemilu</div>
			<div class="column"><input type="checkbox" name="pas2_kpu_lt_kawalpemilu" <?php if ($check_all or isset($_GET['pas2_kpu_lt_kawalpemilu'])){ echo 'checked';} ?>> Paslon 02 Situng KPU < Paslon 02 KawalPemilu</div>
			</div>
			<div class="row">
			<div class="column"><input type="checkbox" name="tSah_kpu_gt_kawalpemilu" <?php if ($check_all or isset($_GET['tSah_kpu_gt_kawalpemilu'])){ echo 'checked';} ?>> Suara Tidak Sah Situng KPU > Suara Tidak Sah KawalPemilu</div>
			<div class="column"><input type="checkbox" name="tSah_kpu_lt_kawalpemilu" <?php if ($check_all or isset($_GET['tSah_kpu_lt_kawalpemilu'])){ echo 'checked';} ?>> Suara Tidak Sah Situng KPU < Suara Tidak Sah KawalPemilu</div>
			</div>
			<div class="row">
			<div class="column"><input type="checkbox" name="sah_kpu_gt_kawalpemilu" <?php if ($check_all or isset($_GET['sah_kpu_gt_kawalpemilu'])){ echo 'checked';} ?>> Suara Sah Situng KPU > Suara Sah KawalPemilu</div>
			<div class="column"><input type="checkbox" name="sah_kpu_lt_kawalpemilu" <?php if ($check_all or isset($_GET['sah_kpu_lt_kawalpemilu'])){ echo 'checked';} ?>> Suara Sah Situng KPU < Suara Sah KawalPemilu</div>
			</div>
			<button type="submit" id="submitbutton">Lihat</button>
		</form>
		<div id="display_div" hidden>
			<p id="num_rows"></p>
			<button type="button" onclick="prev_page()">Sebelumnya</button>
			 Halaman <select id="page_num" onchange="select_page_num(this)">1</select> / <span id="page_count"></span> 
			 					<button type="button" onclick="next_page()">Selanjutnya</button>
								<br> <span id="num_entries_per_page"></span> entri per halaman<br>
								<table id="datatable" border=1 style="max-width: 100vw;"><thead id="datatable_head"><tr><th>Waktu update</th><th>TPS</th><th>Data SitungKPU</th><th style="width: 28%;">Foto Situng KPU</th><th>Data KawalPemilu</th><th style="width: 28%;">Foto KawalPemilu</th></thead></tr>
								<tbody id="datatable_body"></tbody>
					    		</table>
			<button type="button" onclick="prev_page();document.getElementById('num_rows').scrollIntoView()">Sebelumnya</button>
			 Halaman <select id="page_num_2" onchange="select_page_num(this)">1</select> / <span id="page_count_2"></span> 
			<button type="button" onclick="next_page();document.getElementById('num_rows').scrollIntoView()">Selanjutnya</button>
			<p> data mungkin tidak konsisten dengan rangkuman. Hal ini karena kami menggunakan cache dan bisa saja kedua cache tidak terupdate secara bersamaan </p>
    	</div>

		<script>
			var page_num=1;
			var num_entries_per_page = 10;
			var page_count=0;
			var entries=null;
			function reset_page_num(){
				page_num=1;
			}
			function update_page_count(){
				page_count=Math.ceil(entries.length/num_entries_per_page);
				document.getElementById("page_count").innerHTML=page_count;
				document.getElementById("page_count_2").innerHTML=page_count;

				var select = document.getElementById('page_num');    
				select.innerHTML="";

				for (var i = 1; i<= page_count; i++){
				    var option = document.createElement('option');
				    option.value = i;
				    option.innerHTML = i;
				    select.options.add(option);
				}
				document.getElementById('page_num_2').innerHTML=select.innerHTML;
			}
			function display(){
				if (entries){
					document.getElementById("page_num").selectedIndex=page_num-1;
					document.getElementById("page_num_2").selectedIndex=page_num-1;
					document.getElementById("num_entries_per_page").innerHTML=num_entries_per_page;
					document.getElementById("num_rows").innerHTML=""+entries.length+" TPS";
					var tbody = document.getElementById("datatable_body");
					tbody.innerHTML="";
					for (var i=(page_num-1)*num_entries_per_page; i < (page_num)*num_entries_per_page && i < entries.length; i++){
						entry = entries[i];
						row = tbody.insertRow();
						row.insertCell().innerHTML="dari situngkpu: <br> " + entry["tanggal_update_suara_situngkpu_pilpres"] + "<br> dari kawalpemilu: <br> " + entry["tanggal_update_suara_kawalpemilu_pilpres"] + "<br>";
						row.insertCell().innerHTML="Provinsi: "+ entry["nama_provinsi"] + "<br>Kabupaten/Kota: "+ entry["nama_kotakab"] + "<br>	Kecamatan: "+ entry["nama_kecamatan"] + "<br>Kelurahan: "+ entry["nama_kelurahan"] + "<br>" + entry["nama_tps"] + "<br>";
						pas1 = entry["situngkpupas1"];
						if (entry["kawalpemilupas1"] != entry["situngkpupas1"]){
							pas1 = "<em>"+pas1+"</em>";
						}
						pas2 = entry["situngkpupas2"];
						if (entry["kawalpemilupas2"] != entry["situngkpupas2"]){
							pas2 = "<em>"+pas2+"</em>";
						}
						tSah = entry["situngkputSah"];
						if (entry["kawalpemilutSah"] != entry["situngkputSah"]){
							tSah = "<em>"+tSah+"</em>";
						}
						sah = entry["situngkpusah"];
						if (entry["kawalpemilusah"] != entry["situngkpusah"]){
							sah = "<em>"+sah+"</em>";
						}
						row.insertCell().innerHTML=" pas1: " + pas1 + "<br> pas2: " + pas2 + "<br> tidak Sah: " + tSah + "<br> sah: " + sah + "<br> ";
						id_tps = entry["id_tps"];
						id_tps_str = id_tps.toString();
						var img = document.createElement("img");
						img.src="https://pemilu2019.kpu.go.id/img/c/" + id_tps_str.substring(0,3) + "/" + id_tps.substring(3,6) + "/" + id_tps_str + "/" + entry["situngkpuphoto"].split(";")[1];
						img.style="width:100%;";
						row.insertCell().appendChild(img);
						pas1 = entry["kawalpemilupas1"];
						if (entry["kawalpemilupas1"] != entry["situngkpupas1"]){
							pas1 = "<em>"+pas1+"</em>";
						}
						pas2 = entry["kawalpemilupas2"];
						if (entry["kawalpemilupas2"] != entry["situngkpupas2"]){
							pas2 = "<em>"+pas2+"</em>";
						}
						tSah = entry["kawalpemilutSah"];
						if (entry["kawalpemilutSah"] != entry["situngkputSah"]){
							tSah = "<em>"+tSah+"</em>";
						}
						sah = entry["kawalpemilusah"];
						if (entry["kawalpemilusah"] != entry["situngkpusah"]){
							sah = "<em>"+sah+"</em>";
						}
						row.insertCell().innerHTML=" pas1: " + pas1 + "<br> pas2: " + pas2 + "<br> tidak Sah: " + tSah + "<br> sah: " + sah + "<br> ";
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

			function select_page_num(el){
				page_num = el.selectedIndex;
				display();
			}
		</script>
    </body>
</html>



<?php
	// We're done! Save the cached content to a file
	$fp = fopen($cachefile, 'w');
	fwrite($fp, ob_get_contents());
	fclose($fp);
	// finally send browser output
	ob_end_flush();
?>
