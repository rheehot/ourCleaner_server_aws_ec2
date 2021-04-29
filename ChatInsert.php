<?php
/* 서비스 상태 변화하는 코드 */

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
        $msgStr = $_POST['msgStr'];
        $getDate = $_POST['getDate'];
        $getTime = $_POST['getTime'];
        $whomSent = $_POST['whomSent'];
        $whoSend = $_POST['whoSend'];

        error_log("msgStr : ");
        error_log($msgStr);
        error_log("getDate : ");
        error_log($getDate);
        error_log("getTime : ");
        error_log($getTime);
        error_log("whomSent : ");
        error_log($whomSent);
        error_log("whoSend : ");
        error_log($whoSend);

        $query= "select * from `ChatRoom` WHERE memberList  like '%$whomSent%' and memberList  like '%$whoSend%' ";
        $result= mysqli_query($con, $query);
        error_log("채팅방이 있는지 확인하는 코드");
        error_log($query);
        $array = array();

        if(mysqli_num_rows($result) > 0){
            error_log(mysqli_num_rows($result));
            error_log("array 어레이 개수가 0보다 많음. 즉, 채팅방이 있음 ");
            error_log(count($array));

            /* 여기서부터 다시!!! */
//            $query= "select id from `ChatRoom` WHERE memberList like '%$whomSent%' and memberList  like '%$whoSend%' limit 1";
//            error_log($query);

            $query= "INSERT INTO  `ChatData` (whoSend, whomSent, getDate, msgStr, getTime, 	chatRoomId) VALUES ('$whoSend','$whomSent','$getDate','$msgStr','$getTime', (select id from `ChatRoom` WHERE memberList like '%$whomSent%' and memberList  like '%$whoSend%' limit 1))";
            error_log($query);

            if (!mysqli_query($con,$query)){
                die('Error: ' . mysqli_error($con));
                error_log("에러");
                error_log(mysqli_error($con));

                error_log("쿼리문");
                error_log($query);

                echo json_encode(array( "status" => "false","message" => mysqli_error($con)) , JSON_UNESCAPED_UNICODE);
            }else{

                error_log("채팅 데이터 저장 완료!! ");
                echo json_encode(array( "status" => "true","message" => "채팅 데이터 저장 완료!!  ") , JSON_UNESCAPED_UNICODE);

            }

        }else{
            error_log("array 어레이 개수가 0 임, 즉, 채팅방이 없음");

            $memberList = $whomSent."/"."$whoSend";

            /* 채팅방 저장하는 부분 */
            $query= "INSERT INTO  `ChatRoom` ( memberList ) VALUES ('$memberList')";
            error_log($query);

            if (!mysqli_query($con,$query)){

                error_log("채팅방 데이터 저장 에러!! ");
                error_log("쿼리문");
                error_log($query);

            }else{

                $query= "INSERT INTO  `ChatData` (whoSend, whomSent, getDate, msgStr, getTime, 	chatRoomId) VALUES ('$whoSend','$whomSent','$getDate','$msgStr','$getTime', (select id from `ChatRoom` WHERE memberList like '%$whomSent%' and memberList  like '%$whoSend%' limit 1))";
                error_log($query);

                if (!mysqli_query($con,$query)){
                    die('Error: ' . mysqli_error($con));
                    error_log("에러");
                    error_log(mysqli_error($con));

                    error_log("쿼리문");
                    error_log($query);

                    echo json_encode(array( "status" => "false","message" => mysqli_error($con)) , JSON_UNESCAPED_UNICODE);
                }else{

                    error_log("채팅 데이터 저장 완료!! ");
                    echo json_encode(array( "status" => "true","message" => "채팅 데이터 저장 완료!!  ") , JSON_UNESCAPED_UNICODE);

                }

            }



        }



//        $query= "INSERT INTO  `ChatData` (whoSend, whomSent, getDate, msgStr, getTime) VALUES ('$whoSend','$whomSent','$getDate','$msgStr','$getTime')";
//        error_log($query);
//
//        if (!mysqli_query($con,$query)){
//            die('Error: ' . mysqli_error($con));
//            error_log("에러");
//            error_log(mysqli_error($con));
//
//            error_log("쿼리문");
//            error_log($query);
//
//            echo json_encode(array( "status" => "false","message" => mysqli_error($con)) , JSON_UNESCAPED_UNICODE);
//        }else{
//
//            error_log("채팅 데이터 저장 완료!! ");
//            echo json_encode(array( "status" => "true","message" => "채팅 데이터 저장 완료!!  ") , JSON_UNESCAPED_UNICODE);
//
//        }


        mysqli_close($con);

    }

}else{
    echo json_encode(array( "status" => "false","message" => "Error occured, please try again!") );
}

?>