<?php

//アクセス数カウント
//ローカルストレージで同じ訪問者の回数は除外

require './config/env.php';

header("Access-Control-Allow-Origin: $origin_url");
// header("Access-Control-Allow-Origin: http://localhost:3000");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST, GET, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
header("Access-Control-Allow-Credentials: true");

try {
    $conn = new PDO($dsn, $user, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $conn->exec("SET CHARACTER SET utf8");

    $sql = "UPDATE visitors SET count = count + 0.5";
    $stmt = $conn->prepare($sql);
    $stmt->execute();

    $sql = "SELECT count FROM visitors";
    $stmt = $conn->prepare($sql);
    $stmt->execute();
    $count = $stmt->fetch(PDO::FETCH_ASSOC);

    echo json_encode(["message" => "訪問者数が正常に更新されました", "count" => $count["count"]]);
} catch (PDOException $e) {
    echo json_encode(["error" => "Connection failed: " . $e->getMessage()]);
} finally {
    $conn = null;
}
?>
