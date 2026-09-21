<?php
$host = "aws-0-ap-northeast-2.pooler.supabase.com";
$port = "5432";
$db   = "postgres";
$user = "postgres.mhpjfiorvrvejtwbsyck";
$pass = "ZOBwczTj2xTBTi1U";




try {

    $pdo = new PDO(
        "pgsql:host=$host;port=$port;dbname=$db;sslmode=require",
        $user,
        $pass
    );

    $pdo->setAttribute(
        PDO::ATTR_ERRMODE,
        PDO::ERRMODE_EXCEPTION
    );

} catch (PDOException $e) {

    die(
        "Koneksi database gagal: "
        . $e->getMessage()
    );

}