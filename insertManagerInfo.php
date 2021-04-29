<?php 

// 서비스 db에 저장하는 부분 

    // 에러 출력을 위한 코드
    error_reporting(E_ALL); 
    ini_set('display_errors',1); 

    include('dbcon.php');

    $nameStr=$_POST['nameStr'];
    $idStr=$_POST['idStr'];

    $passwordStr=$_POST['passwordStr'];
    $phoneNumStr=$_POST['phoneNumStr'];
    $termsAgreeInt=$_POST['termsAgreeInt'];


    $conn = mysqli_connect($host, $username, $password,$dbname);

    //mysql 한글 깨짐 현상
    mysqli_set_charset($conn,"utf8");

    if (mysqli_connect_errno($conn)){

        error_log("데이터베이스 연결 실패: ");
        error_log(mysqli_connect_error());

    }
    else{
        // to do something

        // 테이블 명
        $table = 'ManagerInfo';

        // 쿼리문
        $query = "INSERT INTO ".$table."(nameStr, idStr, passwordStr, phoneNumStr,	termAgreeInt) 
        VALUES ('".$nameStr."','".$idStr."','".$passwordStr."','".$phoneNumStr."','".$termsAgreeInt."');";

        if(mysqli_query($conn, $query)){
            error_log("ManagerInfo에 매니저 추가 완료");
            error_log("nameStr");
            error_log($nameStr);

        }else{
            error_log("ManagerInfo에 매니저 추가 안 됨 에러 확인!!!!!!");
            error_log(mysqli_error($conn));
        }

    }
    
?>











