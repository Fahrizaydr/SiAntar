<?php

//koneksi
$koneksi = mysqli_connect("localhost", "root", "", "db_sekolah");

// check koneksi
/* if (mysqli_connect_errno()) {
  echo "Gagal terhubung ke database";
} else {
  echo "berhasil terhubung";
} */

//url induk
$main_url = "http://localhost/ojol/";
