<?php
include './_database/config.php';




//upload monitoring debit ke database

//Upload 1
$StatusMikro = $_GET['mikro'];
class dht1
{
    public $link = '';
    function __construct($volume, $debit, $mikro)
    {
        $this->connect();
        $this->storeInDB($volume, $debit, $mikro);
    }

    function connect()
    {
        $this->link = mysqli_connect('localhost', 'root', '') or die('Cannot connect to the DB');
        mysqli_select_db($this->link, 'erp_device') or die('Cannot select the DB');
    }

    function storeInDB($volume, $debit, $mikro)
    {
        $query = "insert into debit_air (debit, volume, arah, mikro) values ($debit, $volume, 'masuk', $mikro)";

        $result = mysqli_query($this->link, $query) or die('Errant query:  ' . $query);
    }
}
if ($_GET['volume'] != '' and  $_GET['debit'] != '') {

    $dht1 = new dht1($_GET['volume'], $_GET['debit'], $StatusMikro);
}

//Upload 2

class dht2
{
    public $link2 = '';
    function __construct($volume2, $debit2, $mikro)
    {
        $this->connect();
        $this->storeInDB($volume2, $debit2, $mikro);
    }

    function connect()
    {
        $this->link2 = mysqli_connect('localhost', 'root', '') or die('Cannot connect to the DB');
        mysqli_select_db($this->link2, 'erp_device') or die('Cannot select the DB');
    }

    function storeInDB($volume2, $debit2, $mikro)
    {

        $query2 = "insert into debit_air (debit, volume, arah, mikro) values ($debit2, $volume2, 'keluar', $mikro)";
        $result2 = mysqli_query($this->link2, $query2) or die('Errant query:  ' . $query2);
    }
}
if ($_GET['volume2'] != '' and $_GET['debit2'] != '') {

    $dht2 = new dht2($_GET['volume2'], $_GET['debit2'], $StatusMikro);
}

date_default_timezone_set('Asia/Bangkok');
$Today = date("l", strtotime('now'));
$TodayTimeStamp = date("Y-m-d", strtotime('now'));
echo $Today;
echo $TodayTimeStamp;

//mengambil data debit dan volume dari database untuk sensor air masuk ke tandon

$VolHariNowIn = mysqli_query($koneksitest, "SELECT * from tbl_volume_harian where arah = 'masuk' and DATE(created_at) = '$TodayTimeStamp' ");
$VolHariNowOut = mysqli_query($koneksitest, "SELECT * from tbl_volume_harian where arah = 'keluar' and DATE(created_at) = '$TodayTimeStamp' ");
$VolMasukNow = mysqli_query($koneksitest, "SELECT volume from debit_air where arah = 'masuk' order by id DESC limit 1");
$VolKeluarNow = mysqli_query($koneksitest, "SELECT volume from debit_air where arah = 'keluar' order by id DESC limit 1");
$MikroStatus = mysqli_query($koneksitest, "SELECT mikro from debit_air order by id DESC limit 1");

//Fetch Data yang diambil

$FetchKeluarNow = mysqli_fetch_assoc($VolKeluarNow);
$FetchMasukNow = mysqli_fetch_assoc($VolMasukNow);
$FetchHariNowOut = mysqli_fetch_assoc($VolHariNowOut);
$FetchHariNowIn = mysqli_fetch_assoc($VolHariNowIn);
$FetchStatus = mysqli_fetch_assoc($MikroStatus);


//Mempersiapkan array untuk dimasuki data
$HariIniIn = array();
$HariIniOut = array();
$CurrentIn = array();
$CurrentOut = array();
$StatusMikro = array();

//Memasukkan data ke dalam array
array_push($HariIniIn, $FetchHariNowIn['volume']);
array_push($HariIniOut, $FetchHariNowOut['volume']);
array_push($CurrentIn, $FetchMasukNow['volume']);
array_push($CurrentOut, $FetchKeluarNow['volume']);
array_push($StatusMikro, $FetchStatus['mikro']);

// Update Backup Volume Air keluar Tandon dan dikonsumsi
if ($FetchHariNowOut == null) {
    mysqli_query($koneksitest, "INSERT into tbl_volume_harian (hari, volume, arah) values ('$Today', '$CurrentOut[0]', 'keluar')");
    echo "tidak ada data masuk 1";
} else if (($CurrentOut[0] > $HariIniOut[0]) && $StatusMikro[0] == 0) {
    $sekarang2 = $CurrentOut[0] + $HariIniOut[0];
    mysqli_query($koneksitest, "UPDATE tbl_volume_harian SET volume = '$sekarang2' WHERE arah = 'keluar' and DATE(created_at) = '$TodayTimeStamp' ");
    echo "Berhasil masuk ditambah";
} else if (($CurrentOut[0] > $HariIniOut[0]) && ($StatusMikro[0] == 1)) {
    mysqli_query($koneksitest, "UPDATE tbl_volume_harian SET volume = '$CurrentOut[0]' WHERE arah = 'keluar' and DATE(created_at) = '$TodayTimeStamp' ");
    echo "berhasil masuk normal";
} else {
    echo "Kesalahan tidak diketahui 1";
}

//Update Backup Volume Air masuk Tandon
if ($FetchHariNowIn == null) {
    mysqli_query($koneksitest, "INSERT into tbl_volume_harian (hari, volume, arah) values ('$Today', '$CurrentIn[0]', 'masuk')");
    echo "tidak ada data masuk 2";
} else if (($CurrentIn[0] > $HariIniIn[0]) && $StatusMikro[0] == 0) {
    $sekarang = $CurrentIn[0] + $HariIniIn[0];
    mysqli_query($koneksitest, "UPDATE tbl_volume_harian SET volume = '$sekarang'  WHERE arah = 'masuk' and DATE(created_at) = '$TodayTimeStamp' ");
    echo "Berhasil masuk ditambah 2";
} else if (($CurrentIn[0] > $HariIniIn[0]) && ($StatusMikro[0] == 1)) {
    mysqli_query($koneksitest, "UPDATE tbl_volume_harian SET volume = '$CurrentIn[0]'  WHERE arah = 'masuk' and  DATE(created_at) = '$TodayTimeStamp' ");
    echo "berhasil masuk normal 2";
} else {
    echo "Kesalahan tidak diketahui 2";
}
