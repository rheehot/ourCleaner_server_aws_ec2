<?php

error_log("fcm selectToken.php");

/* 이메일을 받아서 토큰값 보내는 코드임 */


if(isset($_POST["Email"])) {
    $email = $_POST["Email"];
    $whichClientManager = $_POST["whichClientManager"]; //1이면 클라이언트, 2면 매니저

    if($whichClientManager == 1){
        error_log("클라이언트 임");
        error_log($whichClientManager);

    }else{
        error_log("매니저 임");
        error_log($whichClientManager);

    }


    //데이터베이스에 접속해서 토큰을 저장
    include_once 'config.php';

    error_log($email);
    $conn = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);

    if (mysqli_connect_errno($conn)){

        echo "DB 연결 실패:" . mysqli_connect_error();
        error_log("DB 연결 실패:");
        error_log(mysqli_connect_error());

    }else{

        if($whichClientManager == 1){

            $query = "SELECT `Token` FROM `users` where `email` = '$email'; ";

            $result= mysqli_query($conn, $query);

            error_log("쿼리문");
            error_log($query);

            $emparray = array();
            if(mysqli_num_rows($result) > 0){
                while ($row = mysqli_fetch_assoc($result)) {
                    $emparray[] = $row;
                }
            }
            /*한글 깨짐 문제 해결 위한 옵션 추가 JSON_UNESCAPED_UNICODE */
            echo json_encode(array( "status" => "true","message" => "일반 고객 토큰 검색 완료!", "data" => $emparray) , JSON_UNESCAPED_UNICODE);



        }else{

            $query = "SELECT `Token` FROM `managers` where `email` = '$email'; ";

            $result= mysqli_query($conn, $query);

            error_log("쿼리문");
            error_log($query);

            $emparray = array();
            if(mysqli_num_rows($result) > 0){
                while ($row = mysqli_fetch_assoc($result)) {
                    $emparray[] = $row;
                }
            }
            /*한글 깨짐 문제 해결 위한 옵션 추가 JSON_UNESCAPED_UNICODE */
            echo json_encode(array( "status" => "true","message" => "매니저 고객 토큰 검색 완료!", "data" => $emparray) , JSON_UNESCAPED_UNICODE);



        }

    }
    mysqli_close($conn);
}
?>

