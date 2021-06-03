<?php
include 'koneksiaya.php';

function tulis_log($msg)
{
        $file_log="./log/komcadhelpdesk-";
        $fp=fopen($file_log.date("Ymd").".log","a");
        fwrite($fp,date("Y-m-d H:i:s")." >> [".getmypid()."] >> ".$msg."\n");
        fclose($fp);
}

$dodol = json_decode($_POST["data"],true);
$posdata=print_r($_POST,true);
tulis_log($posdata);
$didil= $dodol['text'];
tulis_log("ini dari rapiwha textnya = " . $didil);
$phoneno= $dodol['from'];
tulis_log("ini nomer yang di catat = " . $phoneno);
$datareply=array();
$datareply['autoreply']="Selamat datang di Layanan Bantuan Komcad. Silakan pilih menu layanan:\n1. Pengaduan\n2. Status nomor pengaduan";
$sukses=1;
$tstamp=date("U");
$waktu = date("Y-m-d H:i:s");

function isi_intent($isinya,$phoneno,$tstamp,$waktu)
{
        include 'koneksiaya.php';
        $sql2 = "INSERT into hitung_intent (nama_intent, tstamp,timestamp,session) values ('{$isinya}','{$tstamp}','{$waktu}','{$phoneno}')";
        $result = mysqli_query($con, $sql2);
        if (!$result) {
          tulis_log("gagal insert {$sql2}: " . mysqli_error($con));  
        }
        mysqli_close($con);

}

if (($didil=="1") or ($didil=="pengaduan") or ($didil=="Pengaduan") or ($didil=="PENGADUAN") or ($didil=="1. Pengaduan")){
	$datareply['autoreply']="Silakan pilih kategori pengaduan :\nA. Personal Komcad\nB. Panitia Komcad\nKetik menu untuk kembali ke menu utama.";
	tulis_log("mulai hitung intent pengaduan");
	$isinya=("Pengaduan Komcad");
	isi_intent($isinya,$phoneno,$tstamp,$waktu);

}
elseif (($didil=="A") or ($didil=="a") or ($didil=="Personal") or ($didil=="personal") or ($didil=="PERSONAL") or ($didil=="personal komcad") or ($didil=="Personal Komcad") or ($didil=="Personal komcad") or ($didil=="personal Komcad") or ($didil=="PERSONAL KOMCAD") or ($didil=="A. Personal Komcad")) {
	$nohp=$dodol["from"];
	$a=date("U");
	$idform=rand(10,99).$a;
	$category='Komcad';
	$status=0;
	$timestamp=$a;
	
	tulis_log ("coba komcad no hp = $nohp");
	tulis_log ("id form = $idform");
	tulis_log ("category = $category");
	tulis_log ("status = $status");
	tulis_log ("timestamp = $timestamp");

	$sqlinsert="INSERT INTO form_id_pelaporan (`nohp`,`idform`,`category`,`status`,`timestamp`) values('{$nohp}','{$idform}','{$category}','{$status}','{$timestamp}')";
	$querysqlinsert=mysqli_query($con,$sqlinsert);

	if(!$querysqlinsert) {
		tulis_log("gagal insert {$sqlinsert}: ". mysqli_error($con));
	}
	else{
		$datareply['autoreply']="Silakan klik tautan berikut untuk menuliskan pengaduan anggota Komcad: https://komcad.kemhan.go.id/peta/index.html?data_pengaduan=$idform \nKetik menu untuk kembali ke menu utama.";
	}

	tulis_log("mulai hitung intent pengaduan personal");
	$isinya= ("Pengaduan Personal Komcad");
    isi_intent($isinya,$phoneno,$tstamp,$waktu);

}
elseif (($didil=="B") or ($didil=="b") Or ($didil=="panitia") or ($didil=="Panitia") or ($didil=="PANITIA") or ($didil=="panitia komcad") or ($didil=="Panitia Komcad") or ($didil=="Panitia komcad") or ($didil=="panitia Komcad") or ($didil=="B. Panitia Komcad")) {

	$a=date("U");
	$idform=rand(10,99).$a;
	$category='Panitia';
	$status=0;
	$timestamp=$a;
	
	tulis_log ("coba panitia no hp = $nohp");
	tulis_log ("id form = $idform");
	tulis_log ("category = $category");
	tulis_log ("status = $status");
	tulis_log ("timestamp = $timestamp");

	$sqlinsert="INSERT INTO form_id_pelaporan (`nohp`,`idform`,`category`,`status`,`timestamp`) values ('{$nohp}','{$idform}','{$category}','{$status}','{$timestamp}')";

	$querysqlinsert=mysqli_query($con, $sqlinsert);

	if(!$querysqlinsert) {
		tulis_log("gagal insert {$sqlinsert}: ". mysqli_error($con));
	}
	else{
		$datareply['autoreply']="Silakan klik tautan berikut untuk menuliskan pelaporan Panitia Komcad: https://komcad.kemhan.go.id/peta/index.html?data_pelaporan=$idform \nKetik menu untuk kembali ke menu utama.";

	}

	tulis_log("mulai hitung intent pengaduan panitia");
	$isinya=("Pengaduan Panitia Komcad");
	isi_intent($isinya,$phoneno,$tstamp,$waktu);

}
elseif (($didil=="2") or ($didil=="status") or ($didil=="status pengaduan") or ($didil=="status nomer pengaduan") or ($didil=="status nomor pengaduan") or ($didil=="Status nomer pengaduan") or ($didil=="Status nomor pengaduan") or ($didil=="status nomer") or ($didil=="status nomor") or ($didil=="2. Status nomor pengaduan")){
	$datareply['autoreply']="Silakan tuliskan nomor pengaduan Anda diawali dengan #";
	tulis_log("mulai hitung intent pengaduan panitia");
	$isinya=("Status Pengaduan Komcad");
	isi_intent($isinya,$phoneno,$tstamp,$waktu);
}
elseif ($didil[0]=="#") {
//	$nopengaduan=explode("#",$didil);
//	tulis_log("nomer pengaduannya = $nopengaduan");

	$pisahnopeng=explode("#", $didil);
	$nopengaduan=$pisahnopeng[1];
	tulis_log("nomer pengaduannya = " . $nopengaduan);
	$con2 = mysqli_connect($dhostcrm,$dusercrm,$dpasscrm,$dnamecrm,$dportcrm);

	if (!$con2) {
		tulis_log("Failed to connect to MySQL2: " . mysqli_connect_error());
		//$sukses=0;
	}

	$sql = "SELECT status,solution from teleasy_troubletickets where ticketid='{$nopengaduan}'";
	$result = mysqli_query($con2, $sql);
	if (!$result) {
	  tulis_log("gagal select {$sql}: " . mysqli_error($con2));
	  $sukses=0;
	}
	
	$row = mysqli_fetch_array($result, MYSQLI_ASSOC);
	$stdb = $row['status'];
	$solusi = $row['solution'];
	
	$rowcount=mysqli_num_rows($result);
	
	if($rowcount>=1) {
	
		switch ($stdb) {
			case "Belum diproses":
				$statusnya="Pelaporan terkirim";
				break;
			case "Dalam proses":
				$statusnya="Pelaporan sedang dalam proses";
				break;
			case "Selesai diproses":
				$statusnya="Pelaporan sudah selesai diproses";
				$tambahsolusi = 1;
				break;
			default:
			$statusnya="Pelaporan terkirim";
		}
	}

	if ($sukses==1) {
		if ($tambahsolusi == 1) {
			$isisolusi = "Tindakan yang dilakukan adalah:\r\n\r\n{$solusi}\r\n\r\n";
		}		
		$datareply['autoreply'] = "Status nomor pelaporan Anda adalah:\r\n\r\n{$statusnya}\r\n\r\n{$isisolusi}Ada lagi yang bisa kami bantu?\r\nKetik menu untuk kembali ke menu utama.";
	}
	
	if($rowcount==0) {
		$datareply['autoreply'] = "Maaf nomor pelaporan yang Anda masukkan tidak terdaftar, Silakan masukkan nomor pengaduan yang benar atau ketik Menu untuk kembali ke menu utama.";
	}

}

else {
	$datareply['autoreply']="Selamat datang di Layanan Bantuan Komcad. Silakan pilih menu layanan:\n1. Pengaduan\n2. Status nomor pengaduan";
	$batas= 1;
	$sukses=0;
	$x=1;
	$pengecualian= "Selesai diproses";
	$sql = "SELECT a.status, b.ticketid FROM teleasy_troubletickets a LEFT JOIN teleasy_ticketcf b ON a.ticketid=b.ticketid WHERE b.cf_458 = '{$phoneno}' AND a.status <> '$pengecualian'";
		if ($result = mysqli_query($con2, $sql)) {
			while ($row = mysqli_fetch_array($result)) {
    		$inistatus= $row['status'];
    		$ininotiket= $row['ticketid'];
			$ditulis .= "{$x}. Nomer Tiket = {$ininotiket} Status Tiket = {$inistatus}\r\n";
			$x++;
			$sukses=1;
  		}
/*		$row = mysqli_num_rows($result);
		tulis_log("jumlah rowwww: ".$row);
		for ($x = 0; $x <= $row; $x++) {
 		$nomernya .= "$x. "
		}*/
	}
	if (!$result) {
	  tulis_log("gagal select {$sql}: " . mysqli_error($con2));
	  $sukses=0;
	}

	if ($sukses==1) {	
		$datareply['autoreply'] = "Status nomor pelaporan Anda adalah:\r\n\r\n{$ditulis}\nSelamat datang di Layanan Bantuan Komcad. Silakan pilih menu layanan:\n1. Pengaduan\n2. Status nomor pengaduan.";
	}
	if ($sukses==0) {
	$datareply['autoreply']="Selamat datang di Layanan Bantuan Komcad. Silakan pilih menu layanan:\n1. Pengaduan\n2. Status nomor pengaduan";
	}
	//$datareply['autoreply']="Selamat datang di Layanan Bantuan Komcad. Silakan pilih menu layanan:\n1. Pengaduan\n2. Status nomor pengaduan";
	tulis_log("masuk bagian hitung session ya");
	$bales=0;
	$sukses=0;
	$adasesi=0;
	$waktu = date("Y-m-d H:i:s");
	
	$sql = "SELECT * FROM hitung_session where `session` = '{$phoneno}' and status=1";
	$result = mysqli_query($con, $sql);
	if(!$result) {
		tulis_log("gagal select {$sql}: ". mysqli_error($con));
	}

	$row = mysqli_num_rows($result);
	tulis_log("jumlah row: ".$row);
	if($row>0) {
		$adasesi=1;
	}

	if($adasesi==1) {
		tulis_log("ada sesi");
		$isi = mysqli_fetch_array($result);
		$sesinya=$isi['session'];
		$idtab=$isi['id'];
	}

	if($adasesi==0) {
		tulis_log("ga ada sesi");
		$waktu=date("Y-m-d H:i:s");
		$sqlinsert="INSERT INTO hitung_session (`session`,`tstamp`,`timestamp`,`status`) values('{$phoneno}','{$tstamp}','{$waktu}',1)";
		$querysqlinsert=mysqli_query($con, $sqlinsert);
		if(!$querysqlinsert) {
			tulis_log("gagal insert {$sqlinsert}: ". mysqli_error($con));
		}
	}
	elseif (($didil=="Tidak") or ($didil=="tidak") or ($didil=="tidak ada") or ($didil=="Sudah") or ($didil=="Ga") or ($didil=="Ga ada") or ($didil=="terimakasih") or ($didil=="Ok") or ($didil=="Oke") or ($didil=="Baik") or ($didil=="Makasih") or ($didil=="terima kasih") or ($didil=="Terimakasih") or ($didil=="Terima Kasih") or ($didil=="siap")) {
	$datareply['autoreply']="Terima kasih sudah menggunakan Layanan Bantuan Komcad";
	tulis_log("intent penutup");
	tulis_log("selesai disini untuk satu session, kalo sampe sini udah berhasil berarti gw ganteng");
	$sqlupdate="UPDATE hitung_session set status=0 where id={$idtab}";
	$querysqlupdate=mysqli_query($con, $sqlupdate);
	if(!$querysqlupdate) {
	tulis_log("gagal update {$sqlupdate}: ". mysqli_error($con));	
		}


	}
}

mysqli_close($con);
tulis_log(json_encode($datareply));
echo json_encode($datareply);

?>
