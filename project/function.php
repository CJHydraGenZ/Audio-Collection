<?php
require_once '../config/config.php';


$con = mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_NAME);



function query($query)
{
    global $con;
    $result = mysqli_query($con, $query);
    $rows = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $rows[] = $row;
    }
    return $rows;
}


function tambah($data)
{
    // untuk mengambil $conn
    global $con;
    //   ambil data tiap element dalam form
    $judul = htmlspecialchars($data["judul"]);
    $artis = htmlspecialchars($data["artis"]);
    $deskripsi = htmlspecialchars($data["deskripsi"]);
    // $thumbnail = htmlspecialchars($data["thumbnail"]);
    // $music = htmlspecialchars($data["music"]);


    //  upload dulu
    $thumbnail = uploadThumbnail();
    if (!$thumbnail) {
        return false;
    }
    $music = uploadMusic();
    if (!$music) {
        return false;
    }

    //query insert data
    $query = "INSERT INTO tb_music
        VALUES
        ('','$judul','$artis','$deskripsi','$thumbnail','$music')
";
    mysqli_query($con, $query);
    return mysqli_affected_rows($con);
}




function uploadThumbnail()
{
    $namaFile = basename($_FILES['thumbnail']['name']);
    $ukuranFile = $_FILES['thumbnail']['size'];
    $tmpName = $_FILES['thumbnail']['tmp_name'];
    $error = $_FILES['thumbnail']['error'];


    // cek apakah tidak ada gambar di upload
    if ($error === 4) {
        echo "<script>
            alert('pilih gambar terlebih dahulu');
            </script>";
        return false;
    }
    // cek pakah yang di upload adalah gambar
    $extensiGambarValid = ['jpg', 'jpeg', 'png'];
    $extensiGambar = explode('.', $namaFile);
    $extensiGambar = strtolower(end($extensiGambar));

    if (!in_array($extensiGambar, $extensiGambarValid)) {
        echo "<script>
        alert('yang anda upload buka gambar!');
        </script>";
    }
    // cek jika ukuran terlalu besar
    if ($ukuranFile > 10000000) {
        echo "<script>
        alert('ukuran gambar terlalu besar');
        </script>";
        return false;
    }
    // lolos pengecekan gambar siap di upload
    move_uploaded_file($tmpName, '../assets/img/' . $namaFile);
    return $namaFile;
}


function uploadMusic()
{
    $namaFile = basename($_FILES['music']['name']);
    $ukuranFile = $_FILES['music']['size'];
    $tmpName = $_FILES['music']['tmp_name'];
    $error = $_FILES['music']['error'];



    if ($error === 4) {
        echo "<script>
            alert('pilih Music terlebih dahulu');
            </script>";
        return false;
    }

    $extensimusicValid = ['mp3', 'mp4', 'wav'];
    $extensiMusic = explode('.', $namaFile);
    $extensiMusic = strtolower(end($extensiMusic));

    if (!in_array($extensiMusic, $extensimusicValid)) {
        echo "<script>
        alert('yang anda upload buka Music!');
        </script>";
    }

    if ($ukuranFile > 20000000) {
        echo "<script>
        alert('ukuran Music terlalu besar');
        </script>";
        return false;
    }

    move_uploaded_file($tmpName, '../assets/music/' . $namaFile);
    return $namaFile;
}
