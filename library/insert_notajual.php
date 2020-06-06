<?php
    include('../library/confirm.php');

    $retur = mysqli_real_escape_string($db, $_REQUEST['total_retur']);
    $nominal = mysqli_real_escape_string($db, str_replace(",","",$_REQUEST['nominal_bayar']));
    $nobeli = mysqli_real_escape_string($db, $_REQUEST['nojual']);
    $jenis = "Kredit";
    $sisabayar = mysqli_real_escape_string($db, str_replace(",","",$_REQUEST['sisa']));
    $op = mysqli_real_escape_string($db, $_REQUEST['operator']);
    $rnamarim = mysqli_real_escape_string($db, $_REQUEST['rnamapengirim']);
    $rbankrim = mysqli_real_escape_string($db, $_REQUEST['rbankpengirim']);
    $rnorim = mysqli_real_escape_string($db, $_REQUEST['rnopengirim']);
    $rnamaima = mysqli_real_escape_string($db, $_REQUEST['rnamapenerima']);
    $rbankima = mysqli_real_escape_string($db, $_REQUEST['rbankpenerima']);
    $rnoima = mysqli_real_escape_string($db, $_REQUEST['rnopenerima']);

    $sisa = $sisabayar - $nominal;
    if($sisa == 0 || $sisa < 0){
        $st = "1";
        $sts = "1";
    }
    else
    {
        $st = "0";
        $sts = "2";
    }

        $cek = mysqli_query($db, "SELECT transaksi_status FROM transaksi WHERE transaksi_nota='$nobeli'");
        $rd  = mysqli_fetch_array($cek);

        $status = $rd['transaksi_status'];

        if($status == 1){
            echo "<script>alert ('Data Sudah Lunas')</script>";
			echo "<meta http-equiv=Refresh content=0;url=../nota_jual.php>";
        }else{

            $sql = "INSERT INTO bayar_nota_jual (tgl_bayar,transaksi_id,jenis,jumlah,sisa,id_karyawan,pengirim_nama,pengirim_bank,pengirim_no,penerima_nama,penerima_bank,penerima_no,status) VALUES (now(),'$nobeli', '$jenis','$nominal','$sisa','$userid','$rnamarim','$rbankrim','$rnorim','$rnamaima','$rbankima', '$rnoima','$st')";

            if(mysqli_multi_query($db, $sql))
            {
                $_SESSION['successexist'] = 1; 
                header('Location: ' . $_SERVER['HTTP_REFERER']);
            }else{
                echo "ERROR: Could not able to execute $sql. " . mysqli_error($db);
            }

            $tgl = date('Y-m-d H:i:s');

            $ubah_status = "UPDATE transaksi SET transaksi_status='$sts', tgl_dibayar_terakhir='$tgl' WHERE transaksi_nota='$nobeli'";
            mysqli_multi_query($db, $ubah_status);

        }

?>