<?php
/* 서비스 예약 취소하는 코드 */

header("Content-Type:text/html;charset=utf-8");

if($_SERVER['REQUEST_METHOD']=='POST'){



    error_log("서비스 상태 변화하는 코드");
//    error_log($_SERVER["DOCUMENT_ROOT"]);

    $host = 'ec2-52-79-179-66.ap-northeast-2.compute.amazonaws.com';
    $username = 'aileen'; # MySQL 계정 아이디
    $password = '!Nhskskp30415'; # MySQL 계정 패스워드
    $dbname = 'ourCleaner'; # DATABASE 이름

    $con = mysqli_connect($host,$username,$password,$dbname);

    /* 한글 깨짐 방지 위함 */
    mysqli_query($con, "set session character_set_connection=utf8;");
    mysqli_query($con, "set session character_set_results=utf8;");
    mysqli_query($con, "set session character_set_client=utf8;");


    if (mysqli_connect_errno($con)){

        echo "DB 연결 실패:" . mysqli_connect_error();
        error_log("DB 연결 실패:");
        error_log(mysqli_connect_error());

    }else{
        error_log("DB 연결 성공");


        /* 여기서부터 post로 받아온 값으로 sql문 넣기 시작 함 */
        $ServiceId = $_POST['ServiceId'];
        $State = $_POST['State'];

        error_log($ServiceId);
        error_log($State);


        if($State == "예약 취소"){
            error_log("거절당한 경우, 매니저이름을 매니저미지정 으로 변경");

            $query= "UPDATE `Services` SET `managerName`='$State' WHERE `uid`= '$ServiceId'";

            if (!mysqli_query($con,$query)){
                die('Error: ' . mysqli_error($con));
                error_log("에러");
                error_log(mysqli_error($con));

                error_log("쿼리문");
                error_log($query);

                echo json_encode(array( "status" => "false","message" => mysqli_error($con)) , JSON_UNESCAPED_UNICODE);
            }else{

                error_log("서비스 상태를 예약 취소로 변경 완료 ");
                echo json_encode(array( "status" => "true","message" => "서비스 상태를 예약 취소로 변경 완료 ") , JSON_UNESCAPED_UNICODE);

            }




        }else{


//            error_log("수락한 경우, 서비스 상태를 매칭 완료로 변경");
//
//            $query= "UPDATE `Services` SET `serviceState`='$State' WHERE `uid`= '$ServiceId'";
//
//            if (!mysqli_query($con,$query)) {
//
//                die('Error: ' . mysqli_error($con));
//                error_log("에러");
//                error_log(mysqli_error($con));
//
//                error_log("쿼리문");
//                error_log($query);
//
//                echo json_encode(array( "status" => "false","message" => mysqli_error($con)) , JSON_UNESCAPED_UNICODE);
//
//            }else{
//
//                $query= "UPDATE `alarmForService` SET `activate`= 0 WHERE `ServiceNum`= '$ServiceId'";
//
//                if (!mysqli_query($con,$query)){
//
//                    die('Error: ' . mysqli_error($con));
//                    error_log("에러");
//                    error_log(mysqli_error($con));
//                    echo json_encode(array( "status" => "false","message" => mysqli_error($con)) , JSON_UNESCAPED_UNICODE);
//
//                }else{
//
//                    error_log("서비스 상태를 매칭 완료로 변경 - 읽음 표시 개념임");
//                    echo json_encode(array( "status" => "true","message" => "서비스 상태를 매칭 완료로 변경 + 읽음 표시까지 완료 ") , JSON_UNESCAPED_UNICODE);
//
//                }
//
//            }
        }

        mysqli_close($con);

    }

}else{
    echo json_encode(array( "status" => "false","message" => "Error occured, please try again!") );
}

?>