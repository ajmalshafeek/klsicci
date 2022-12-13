<?php
	$config = parse_ini_file(__DIR__ . "./jsheetconfig.ini");



	if (!isset($_SESSION)) {

		session_name($config['sessionName']);

		session_start();

	}

function checkData($data){
	if($data==0){
		return "";
	}else{
		return $data;}
}
	require_once $_SERVER['DOCUMENT_ROOT'] . $config['appRoot'] . "/phpfunctions/isLogin.php";
	require_once $_SERVER['DOCUMENT_ROOT'] . $config['appRoot'] . "/query/connect.php";
	require_once $_SERVER['DOCUMENT_ROOT'] . $config['appRoot'] . "/query/clientComplaint.php";
	require_once $_SERVER['DOCUMENT_ROOT'] . $config['appRoot'] . "/query/eaform.php";
	$id=2;
	$con=connectDb();
	$data=fetchEAForm($con,$id);
	print_r($data);


    $table='<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>EA Table</title>
    <style>
        @font-face {
   font-family: arial;
   src: url(ARIAL.woff);
        }

* {
   font-family: arial;
}
        body{
              -webkit-print-color-adjust:exact;
        }
        .fontsize{font-size: 8pt}
    .fontsize20 {font-size: 20pt;font-weight: 800; width: 50px;}
        .fontsize10 {font-size: 10pt;font-weight: 800}
        .dark{color:#ffffff;background-color: #000000;text-align: center;}
        td{padding:3px;
        }
        .small{width:17px}
        .insert{background-color:#ddebf7;font-weight: 600;border: 1px #000000 solid !important;}
        .td1{width:70px}
        .td2{width:90px}
        .td3{width:90px}
        .td4{width:60px}
    </style>
</head>

<body>
<table width="900" border="0" class="fontsize" cellpadding="0" cellspacing="0">
  <tbody>
    <tr>
      <td colspan="4">&nbsp;(C.P.8A - Pin. 2017)</td>
      <td class="td1">&nbsp;</td>
      <td class="td2">&nbsp;</td>
      <td colspan="6" align="center">MALAYSIA</td>
      <td colspan="3" style="padding: 3px" class="dark">Penyata Gaji Pekerja SWASTA</td>
      <td width="59" rowspan="2" class="fontsize20">&nbsp;EA</td>
    </tr>
    <tr>
      <td width="23" class="small">&nbsp;</td>
        <td width="23" class="small">&nbsp;</td>
        <td width="23" class="small">&nbsp;</td>
        <td class="td1">&nbsp;</td>
        <td class="td1">&nbsp;</td>
      <td>&nbsp;</td>
        <td colspan="6" align="center" style="font-size: 10pt"><strong>CUKAI PENDAPATAN</strong></td>
      <td colspan="3" align="center">No. Cukai Pendapatan Pekerja</td>
    </tr>
    <tr>
      <td colspan="3">No. Siri</td>
      <td>&nbsp;</td>
      <td colspan="2" class="insert"></td>
      <td colspan="6" align="center">PENYATA SARAAN DARIPADA PENGGAJIAN</td>
      <td colspan="4" class="insert"></td>
    </tr>
    <tr>
      <td colspan="4">No. Majikan E</td>
      <td colspan="2" class="insert"></td>
      <td colspan="5" align="center">BAGI TAHUN BERAKHIR 31 DISEMBER</td>
        <td class="insert">'.$data['year'].'</td>
      <td colspan="2">Cawangan LHDNM</td>
      <td colspan="2" class="insert"></td>
    </tr>
    <tr>
      <td colspan="16" class="dark" style="font-size: 10pt"><strong>BORANG EA INI PERLU DISEDIAKAN UNTUK DISERAHKAN KEPADA PEKERJA BAGI TUJUAN CUKAI PENDAPATAN</strong></td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td class="td3">&nbsp;</td>
      <td class="td4">&nbsp;</td>
      <td width="23"  class="small">&nbsp;</td>
      <td width="23"  class="small">&nbsp;</td>
      <td width="23" class="small">&nbsp;</td>
      <td width="112">&nbsp;</td>
      <td width="48">&nbsp;</td>
      <td width="69">&nbsp;</td>
      <td width="107">&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td class="dark"><strong>A</strong></td>
        <td colspan="5"><strong>BUTIRAN PEKERJA</strong></td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td>1.</td>
      <td colspan="4">Nama Penuh Pekerja/Pesara (En./Cik/Puan)
</td>
      <td colspan="10" class="insert">'.$data['staffName'].'</td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td colspan="2">&nbsp;</td>
      <td colspan="4">&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td colspan="3">&nbsp;</td>
      <td colspan="3">&nbsp;</td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td>2.</td>
      <td colspan="2">Jawatan</td>
      <td colspan="4" class="insert">'.$data['position'].'</td>
       <td>&nbsp;</td>
        <td>3.</td>
      <td colspan="3">No. Kakitangan/No. Gaji</td>
      <td colspan="3" class="insert">'.$data['staffNum'].'</td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td>4.</td>
      <td colspan="2">No. K.P. Baru </td>
      <td colspan="3" class="insert">'.$data['icNum'].'</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>5.
</td>
      <td colspan="3">No. Pasport</td>
      <td colspan="3" class="insert"></td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td>6.</td>
      <td colspan="2">No. KWSP</td>
      <td colspan="3" class="insert">'.$data['kwspNum'].'</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>7.</td>
      <td colspan="2">No. PERKESO</td>
      <td>&nbsp;</td>
      <td colspan="3" class="insert">'.$data['perkesoNum'].'</td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td>8.</td>
      <td colspan="6">Bilangan Anak Yang Layak</td>
      <td>&nbsp;</td>
      <td>9.</td>
      <td colspan="6">Jika bekerja tidak genap setahun, nyatakan:</td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td colspan="3">Untuk Pelepasan Cukai</td>
      <td colspan="2" class="insert">'.$data['childrenNum'].'</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>(a)</td>
      <td colspan="2">Tarikh mula bekerja</td>
      <td colspan="3" class="insert">'.checkData($data['startDate']).'</td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>(b)</td>
      <td colspan="2">Tarikh berhenti kerja</td>
      <td colspan="3" class="insert">'.checkData($data['endDate']).'</td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
    <tr>
        <td rowspan="2" class="dark"><strong>B</strong></td>
      <td colspan="12"><strong>PENDAPATAN PENGGAJIAN, MANFAAT DAN TEMPAT KEDIAMAN</strong></td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
    </tr>
    <tr>
      <td colspan="12"><strong>(Tidak Termasuk Elaun/Perkuisit/Pemberian/Manfaat Yang Dikecualikan Cukai)</strong></td>
        <td>&nbsp;</td>
        <td align="center"><strong>RM</strong></td>
        <td>&nbsp;</td>
    </tr>
    <tr>
      <td colspan="16">&nbsp;</td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td>1.</td>
      <td>(a)</td>
      <td colspan="5">Gaji kasar, upah atau gaji cuti (termasuk gaji lebih masa)</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td colspan="2" class="insert">'.checkData($data['grossSalary']).'</td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>(b)</td>
      <td colspan="5">Fi (termasuk fi pengarah), komisen atau bonus </td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td colspan="2" class="insert">'.checkData($data['fiBonus']).'</td>

    </tr>
    <tr>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>(c)</td>
      <td colspan="8">Tip kasar, perkuisit, penerimaan sagu hati atau elaun-elaun lain (Perihal pembayaran:</td>
      <td colspan="2" class="insert">'.$data['payFor'].'</td>
      <td>)</td>
      <td colspan="2" class="insert">'.checkData($data['payAmount']).'</td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>(d)</td>
      <td colspan="5">Cukai Pendapatan yang dibayar oleh Majikan bagi pihak Pekerja</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td colspan="2" class="insert">'.checkData($data['taxByEmployer']).'</td>
    </tr>
    <tr>
      <td height="29">&nbsp;</td>
      <td>&nbsp;</td>
      <td>(e)</td>
      <td colspan="5">Manfaat Skim Opsyen Saham Pekerja (ESOS)</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td colspan="2" class="insert">'.checkData($data['esos']).'</td>
    </tr>

    <tr>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>(f)</td>
      <td colspan="2">Ganjaran bagi tempoh dari</td>
      <td colspan="2"  class="insert">'.$data['rewardFrom'].'</td>
      <td>&nbsp;</td>
      <td colspan="3">hingga</td>
      <td colspan="2"  class="insert">'.$data['rewardTo'].'</td>
      <td>&nbsp;</td>
      <td colspan="2" class="insert">&nbsp;</td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td>2.</td>
      <td colspan="12">Butiran bayaran tunggakan dan lain-lain bagi tahun-tahun terdahulu dalam tahun semasa</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td colspan="3">Jenis pendapatan </td>
      <td align="right">(a)</td>
      <td colspan="6" class="insert">'.$data['previousYear'].'</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td align="right">(b)</td>
      <td colspan="6" class="insert">'.$data['currentYear'].'</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td colspan="2" class="insert">&nbsp;</td>
    </tr>
      <tr>
      <td colspan="16"></td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td>3.</td>
      <td colspan="4">Manfaat berupa barangan (Nyatakan:</td>
      <td colspan="7" class="insert">'.$data['benefitsState'].'</td>
      <td>)</td>
      <td colspan="2" class="insert">'.checkData($data['benefitsAmount']).'</td>
    </tr>
      <tr>
      <td colspan="16"></td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td>4.</td>
      <td colspan="3">Nilai tempat kediaman (Alamat:</td>
        <td colspan="8" class="insert" >'.$data['resiAdd'].'</td>
      <td>)</td>
      <td colspan="2" class="insert">'.checkData($data['resiValue']).'</td>
    </tr>
       <tr>
      <td colspan="16"></td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td>5.</td>
      <td colspan="9">Bayaran balik daripada Kumpulan Wang Simpanan/Pencen yang tidak diluluskan</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td colspan="2" class="insert">'.checkData($data['refundKWSP']).'</td>
    </tr>
      <tr>
      <td colspan="16"></td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td>6.</td>
      <td colspan="4">Pampasan kerana kehilangan pekerjaan</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td colspan="2" class="insert">'.checkData($data['reparationJob']).'</td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
    <tr>
        <td class="dark"><strong>C</strong></td>
      <td colspan="13"><strong>PENCEN DAN LAIN-LAIN</strong></td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td>1.</td>
      <td colspan="2">Pencen</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td colspan="2" class="insert">'.checkData($data['pensionAmount']).'</td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td>2.</td>
      <td colspan="4">Anuiti atau Bayaran Berkala yang lain</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td colspan="2" class="insert">'.checkData($data['annuitAmount']).'</td>
    </tr>
    <tr>
      <td>&nbsp;</td>
        <td colspan="3"><strong>JUMLAH</strong></td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td colspan="2" class="insert">'.checkData($data['totalPension']).'</td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
    <tr>
        <td class="dark"><strong>D</strong></td>
      <td colspan="5"><strong>JUMLAH POTONGAN</strong></td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td>1.</td>
      <td colspan="5">Potongan Cukai Bulanan (PCB) yang dibayar kepada LHDNM</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td colspan="2" class="insert">'.checkData($data['pcbAmount']).'</td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td>2.</td>
      <td colspan="5">Arahan Potongan CP 38</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td colspan="2" class="insert">'.checkData($data['deductionCP']).'</td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td>3.</td>
      <td colspan="5">Zakat yang dibayar melalui potongan gaji</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td colspan="2" class="insert">'.checkData($data['zakatAmount']).'</td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td>4.</td>
      <td colspan="6">Jumlah tuntutan potongan oleh pekerja melalui Borang TP1 berkaitan:</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>(a)</td>
      <td colspan="5">Pelepasan</td>
      <td>RM</td>
      <td colspan="4" class="insert">'.checkData($data['deductionTP1a']).'</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>(b)</td>
      <td colspan="5">Zakat selain yang dibayar melalui potongan gaji bulanan</td>
      <td>RM</td>
      <td colspan="4" class="insert">'.checkData($data['deductionTP1b']).'</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td colspan="16"></td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td>5.</td>
      <td colspan="4">Jumlah pelepasan bagi anak yang layak</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td colspan="2" class="insert">'.checkData($data['jumlahPele']).'</td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
    <tr>
        <td class="dark"><strong>E</strong></td>
      <td colspan="15"><strong>CARUMAN YANG DIBAYAR OLEH PEKERJA KEPADA KUMPULAN WANG SIMPANAN/PENCEN YANG DILULUSKAN DAN PERKESO</strong></td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td>1.</td>
      <td colspan="3">Nama Kumpulan Wang</td>
      <td colspan="11" class="insert">'.$data['fundName'].'</td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td colspan="9">Amaun caruman yang wajib dibayar (nyatakan bahagian pekerja sahaja) </td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>RM</td>
      <td colspan="2" class="insert">'.checkData($data['contribution']).'</td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td>2.</td>
      <td colspan="9">PERKESO : Amaun caruman yang wajib dibayar (nyatakan bahagian pekerja sahaja)</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>RM</td>
      <td colspan="2" class="insert">'.checkData($data['perkesoPaidAmount']).'</td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
    <tr>
        <td class="dark"><strong>F</strong></td>
      <td colspan="11"><strong>JUMLAH ELAUN / PERKUISIT / PEMBERIAN / MANFAAT YANG DIKECUALIKAN CUKAI</strong></td>
      <td>&nbsp;</td>
      <td>RM</td>
      <td colspan="2" class="insert">'.checkData($data['totalAllowances']).'</td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td colspan="11" rowspan="12" style="border: solid 1px black">
          <table width="100%" border="0" cellpadding="" cellspacing="2" class="fontsize">
        <tbody>
          <tr>
            <td width="5%">&nbsp;</td>
            <td width="30%" >&nbsp;</td>
            <td width="55%">&nbsp;</td>
            <td width="5%">&nbsp;</td>
            </tr>
          <tr>
            <td>&nbsp;</td>
            <td>Nama Pegawai</td>

            <td class="insert">'.$data['officerName'].'</td>
            <td>&nbsp;</td>
            </tr>
          <tr>
            <td colspan="5"></td>
            </tr>
          <tr>
            <td>&nbsp;</td>
            <td>Jawatan</td>

            <td class="insert">'.$data['officerPosition'].'</td>
            <td>&nbsp;</td>
            </tr>
          <tr>
            <td colspan="5"></td>
            </tr>
          <tr>
            <td>&nbsp;</td>
            <td valign="top">Nama dan Alamat Majikan</td>

            <td class="insert" align="left" valign="top" height="100px">'.$data['employerNameAdd'].'</td>
            <td>&nbsp;</td>
            </tr>
          <tr>
            <td colspan="5"></td>
            </tr>
          <tr>
            <td>&nbsp;</td>
            <td>No. Telefon Majikan</td>

            <td class="insert">'.$data['employerNum'].'</td>
            <td>&nbsp;</td>
            </tr>
          <tr>
            <td>&nbsp;</td>
            <td>&nbsp;</td>

            <td>&nbsp;</td>
            <td>&nbsp;</td>
            </tr>
          </tbody>
  </table>
</td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td colspan="2">&nbsp;</td>
      <td colspan="2" >&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td colspan="2">Tarikh</td>
      <td colspan="2" class="insert">'.$data['date'].'</td>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
  </tbody>
</table>

</body>
</html>
';
    ?>
<?php echo $table; ?>
