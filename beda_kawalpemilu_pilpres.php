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
    </head>
    <body>
        <h1>PeriksaSuara2019 | Perbedaan Situng KPU dengan KawalPemilu | Pilpres</h1>
        <h2>CATATAN</h2>
        <p> Ini dibuat sebagai alat bantu saja untuk memeriksa kembali data. Hasil yang muncul di situs ini bukanlah sebuah pernyataan ataupun publikasi resmi dari organisasi ataupun pihak apapun.
        </p>
        <p> Data tidak selalu data terbaru dan tidak lengkap. Data hanya diupdate kadang-kadang dan hanya sebagian saja. </p>
        <p> Data yang muncul di bawah ini bisa saja salah. Harap crosscheck dengan sumber aslinya </p>
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
    		<p> Jumlah TPS diperiksa: <?php
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
		<form>
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
			<button type="submit" formmethod="get">Lihat</button>
		</form>

		<?php
			if (isset($_GET['pas1_kpu_gt_kawalpemilu']) or isset($_GET['pas1_kpu_lt_kawalpemilu']) or isset($_GET['pas2_kpu_gt_kawalpemilu']) or isset($_GET['pas2_kpu_lt_kawalpemilu']) or isset($_GET['tSah_kpu_gt_kawalpemilu']) or isset($_GET['tSah_kpu_lt_kawalpemilu']) or isset($_GET['sah_kpu_gt_kawalpemilu']) or isset($_GET['sah_kpu_lt_kawalpemilu'])){
				$conditions=[];
				if (isset($_GET['pas1_kpu_gt_kawalpemilu']) or isset($_GET['pas1_kpu_lt_kawalpemilu'])){
					$operator='';
					if (isset($_GET['pas1_kpu_lt_kawalpemilu'])){
						$operator=$operator.'<';
					}
					if (isset($_GET['pas1_kpu_gt_kawalpemilu'])){
						$operator=$operator.'>';
					}
					array_push($conditions,'suara_situngkpu_pilpres.pas1'.$operator.'suara_kawalpemilu_pilpres.pas1');
				}
				if (isset($_GET['pas2_kpu_gt_kawalpemilu']) or isset($_GET['pas2_kpu_lt_kawalpemilu'])){
					$operator='';
					if (isset($_GET['pas2_kpu_lt_kawalpemilu'])){
						$operator=$operator.'<';
					}
					if (isset($_GET['pas2_kpu_gt_kawalpemilu'])){
						$operator=$operator.'>';
					}
					array_push($conditions,'suara_situngkpu_pilpres.pas2'.$operator.'suara_kawalpemilu_pilpres.pas2');
				}
				if (isset($_GET['tSah_kpu_gt_kawalpemilu']) or isset($_GET['tSah_kpu_lt_kawalpemilu'])){
					$operator='';
					if (isset($_GET['tSah_kpu_lt_kawalpemilu'])){
						$operator=$operator.'<';
					}
					if (isset($_GET['tSah_kpu_gt_kawalpemilu'])){
						$operator=$operator.'>';
					}
					array_push($conditions,'suara_situngkpu_pilpres.tSah'.$operator.'suara_kawalpemilu_pilpres.tSah');
				}
				if (isset($_GET['sah_kpu_gt_kawalpemilu']) or isset($_GET['sah_kpu_lt_kawalpemilu'])){
					$operator='';
					if (isset($_GET['sah_kpu_lt_kawalpemilu'])){
						$operator=$operator.'<';
					}
					if (isset($_GET['sah_kpu_gt_kawalpemilu'])){
						$operator=$operator.'>';
					}
					array_push($conditions,'suara_situngkpu_pilpres.sah'.$operator.'suara_kawalpemilu_pilpres.sah');
				}

				$sql = "SELECT id_tps, tanggal_update_suara_situngkpu_pilpres, tanggal_update_suara_kawalpemilu_pilpres,  suara_situngkpu_pilpres.pas1 as situngkpupas1, suara_situngkpu_pilpres.pas2 as situngkpupas2, suara_situngkpu_pilpres.tSah as situngkputSah, suara_situngkpu_pilpres.sah as situngkpusah,  suara_kawalpemilu_pilpres.pas1 as kawalpemilupas1, suara_kawalpemilu_pilpres.pas2 as kawalpemilupas2, suara_kawalpemilu_pilpres.tSah as kawalpemilutSah, suara_kawalpemilu_pilpres.sah as kawalpemilusah, nama_provinsi, nama_kotakab, nama_kecamatan, nama_kelurahan, nama_tps, suara_situngkpu_pilpres.photo as situngkpuphoto, suara_kawalpemilu_pilpres.photo as kawalpemiluphoto FROM (suara_situngkpu_pilpres NATURAL JOIN tps) JOIN suara_kawalpemilu_pilpres USING (id_provinsi, id_kotakab, id_kecamatan, id_kelurahan, nama_tps) NATURAL JOIN provinsi NATURAL JOIN kotakab NATURAL JOIN kecamatan NATURAL JOIN kelurahan WHERE ".implode(' OR ',$conditions);
    			$result = $dbconn->query($sql);

    			echo "<p>".$result->num_rows." TPS </p>";
				if ($result->num_rows > 0) {
					echo '<table border=1 width="100%"><tr><th>Waktu update</th><th>TPS</th><th>Data Situng KPU</th><th width="30%">Foto Situng KPU</th><th>Data KawalPemilu</th><th width="30%">Foto KawalPemilu</th></tr>';
		    		while($row = $result->fetch_assoc()) {
		    			echo '<tr>';

		    			echo '<td>';
		    			echo 'situngkpu: <br> '.$row['tanggal_update_suara_situngkpu_pilpres'].'<br>';
		    			echo 'kawalpemilu: <br> '.$row['tanggal_update_suara_kawalpemilu_pilpres'].'<br>';
		    			echo '</td>';

		    			echo '<td>';
		    			echo 'Provinsi: '.$row['nama_provinsi'].'<br>';
		    			echo 'Kabupaten/Kota: '.$row['nama_kotakab'].'<br>';
		    			echo 'Kecamatan: '.$row['nama_kecamatan'].'<br>';
		    			echo 'Kelurahan: '.$row['nama_kelurahan'].'<br>';
		    			echo $row['nama_tps'].'<br>';
		    			echo '</td>';

		    			echo '<td>';
		    			echo 'pas1: '.$row['situngkpupas1'].'<br>';
		    			echo 'pas2: '.$row['situngkpupas2'].'<br>';
		    			echo 'tidak Sah: '.$row['situngkputSah'].'<br>';
		    			echo 'sah: '.$row['situngkpusah'].'<br>';
		    			echo '</td>';

		    			echo '<td>';
		    			$id_tps_str = strval($row['id_tps']);
		    			$situngkpuphoto = 'https://pemilu2019.kpu.go.id/img/c/'.substr($id_tps_str,0,3).'/'.substr($id_tps_str,3,3).'/'.$id_tps_str.'/'.explode(';',$row['situngkpuphoto'])[1];
		    			echo '<img src='.$situngkpuphoto.' style="width:100%;" />';
		    			echo ' </td>';

		    			echo '<td>';
		    			echo 'pas1: '.$row['kawalpemilupas1'].'<br>';
		    			echo 'pas2: '.$row['kawalpemilupas2'].'<br>';
		    			echo 'tidak Sah: '.$row['kawalpemilutSah'].'<br>';
		    			echo 'sah: '.$row['kawalpemilusah'].'<br>';
		    			echo '</td>';

		    			echo '<td>';
		    			echo '<img src='.$row['kawalpemiluphoto'].' style="width:100%;" />';
		    			echo ' </td>';
		    		}
		    		echo '</table>';
	    		}
			}
		?>
    </body>
</html>


