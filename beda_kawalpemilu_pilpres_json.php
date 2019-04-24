<?php
	// define the path and name of cached file
	$cachefile = 'cached-files-beda_kawalpemilu_pilpres_json.php'.isset($_GET['pas1_kpu_gt_kawalpemilu']) . isset($_GET['pas1_kpu_lt_kawalpemilu']) . isset($_GET['pas2_kpu_gt_kawalpemilu']) . isset($_GET['pas2_kpu_lt_kawalpemilu']) . isset($_GET['tSah_kpu_gt_kawalpemilu']) . isset($_GET['tSah_kpu_lt_kawalpemilu']) . isset($_GET['sah_kpu_gt_kawalpemilu']) . isset($_GET['sah_kpu_lt_kawalpemilu']);
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

		$entries=[];
		if ($result->num_rows > 0) {

    		while($row = $result->fetch_assoc()) {
    			array_push($entries, $row);
    		}
    	}

		echo json_encode($entries);
    }
    else{
    	echo "null";
    }

?>
<?php
	// We're done! Save the cached content to a file
	$fp = fopen($cachefile, 'w');
	fwrite($fp, ob_get_contents());
	fclose($fp);
	// finally send browser output
	ob_end_flush();
?>
