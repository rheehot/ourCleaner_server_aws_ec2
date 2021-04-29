<?php

    error_log("fcm register.php");

if(extension_loaded("curl")){

    echo "cURL extension is loaded";

}else{

    echo 'cURL extension failed';

}



    if(isset($_POST["Token"])) {
        $token = $_POST["Token"];
        $email = $_POST["Email"];

        //데이터베이스에 접속해서 토큰을 저장
        include_once 'config.php';

        error_log($token);
        $conn = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);

        if (mysqli_connect_errno($conn)){

            echo "DB 연결 실패:" . mysqli_connect_error();
            error_log("DB 연결 실패:");
            error_log(mysqli_connect_error());

        }else{
            $query = "INSERT INTO users(email, Token) Values ('$email','$token') ON DUPLICATE KEY UPDATE Token = '$token'; ";

            if(mysqli_query($conn, $query)){
                error_log("토큰 저장 완료");

            }else{
                error_log("ManagerInfo에 매니저 추가 안 됨 에러 확인!!!!!!");

                error_log(mysqli_error($conn));
                error_log($query);

            }

        }
        mysqli_close($conn);
    }

?>

