<?php
    session_start();
    require_once('../../../db/config.php');
    require_once('../../../system/helper.php');

    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        redirectWithMessage('../', 'Request tidak valid');
    }

    if (!isset($_SESSION['idKakitangan'])) {
        redirectWithMessage('../../', 'Sesi anda sudah tamat, sila login semula');
    }

    $required = ['id_kakitangan', 'id_aset', 'id_lokasi', 'jenis_kepunyaan_aset'];
    foreach ($required as $field) {
        if (!isset($_POST[$field]) || empty($_POST[$field]) || $_POST[$field] == 0) {
            redirectWithMessage('../', "Sila isi semua field yang diperlukan");
        }
    }

    $id_kakitangan = escapeString($connect, $_POST['id_kakitangan']);
    $waktu_bengkel = escapeString($connect, $_POST['waktu_bengkel_kosong']);
    $id_lokasi = escapeString($connect, $_POST['id_lokasi']);
    $desc_lokasi = escapeString($connect, $_POST['desc_lokasi']);
    $id_aset = escapeString($connect, $_POST['id_aset']);
    $jenis_kepunyaan = escapeString($connect, $_POST['jenis_kepunyaan_aset']);
    $no_siri = escapeString($connect, $_POST['nombor_siri_pendaftaran_aset']);
    $tarikh = escapeString($connect, $_POST['tarikh_kerosakan']);
    $perihal = escapeString($connect, $_POST['perihal_kerosakan']);

    $nama_file = null;

    // Handle image upload jika ada
    if (isset($_FILES['image']) && $_FILES['image']['name']) {
        $upload = uploadImage($_FILES['image'], __DIR__ . '/uploads');
        
        if (!$upload['success']) {
            redirectWithMessage('../', $upload['error']);
        }
        
        $nama_file = $upload['filename'];
    }

    // Insert ke database
    $data = [
        'id_kakitangan' => $id_kakitangan,
        'waktu_bengkel_kosong' => $waktu_bengkel,
        'id_lokasi' => $id_lokasi,
        'desc_lokasi' => $desc_lokasi,
        'id_aset' => $id_aset,
        'jenis_kepunyaan_aset' => $jenis_kepunyaan,
        'nombor_siri_pendaftaran_aset' => $no_siri,
        'tarikh_kerosakan' => $tarikh,
        'perihal_kerosakan' => $perihal,
        'kos_penyelengaraan_terdahulu' => null,
        'nama_img_ref' => $nama_file,
        'kos_penyelengaraan_anggaran' => null,
        'ulasan_aduan' => null,
        'id_admin' => null,
        'status_aduan' => 1
    ];

    $result = insertDB($connect, 'aduan_kerosakan_komputer', $data);
    
    $message = $result['success'] ? 'Berjaya Hantar Aduan' : 'Gagal Hantar Aduan: ' . $result['error'];
    redirectWithMessage('../', $message);
?>
