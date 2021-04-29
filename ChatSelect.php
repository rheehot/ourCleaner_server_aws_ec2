<?php
/* 채팅방에서 채팅 내용 불러오기 / 없으면, 마지막 방id 가져오는 코드임 */

header("Content-Type:text/html;charset=utf-8");

if($_SERVER['REQUEST_METHOD']=='POST'){

    error_log("REQUEST_METHOD GET 아래는 현재 경로");
    error_log($_SERVER["DOCUMENT_ROOT"]);

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






        if($_POST['managerEmail'] == "blank"){

            error_log("managerEmail blank인 경우.");
            $clientEmail = $_POST['clientEmail'];
            error_log($clientEmail);



            $query= "SELECT `whoSend` FROM `ChatData` where whomSent = '$clientEmail' decs limit 1";
            $result= mysqli_query($con, $query);

            error_log("쿼리문");
            error_log($query);
//
            $emparray = array();
            if(mysqli_num_rows($result) > 0){
                while ($row = mysqli_fetch_assoc($result)) {
                    $emparray[] = $row;
                }
            }
            /*한글 깨짐 문제 해결 위한 옵션 추가 JSON_UNESCAPED_UNICODE */
            echo json_encode(array( "status" => "true","message" => "채팅 목록들 가져오기!!! ", "data" => $emparray) , JSON_UNESCAPED_UNICODE);









        }else{


            //        /* 하.. 그냥 프로필 이미지 설정한 매니저 전체 다 보여주자..ㅎㅎ */

        /* 여기서부터 post로 받아온 값으로 sql문 넣기 시작 함 */
        $managerEmail = $_POST['managerEmail'];
        $clientEmail = $_POST['clientEmail'];

        error_log("managerEmail : ");
        error_log($managerEmail);
        error_log("clientEmail : ");
        error_log($clientEmail);

        /*        $managerArray = array();*/
        $query= "SELECT * FROM `ChatData` where whoSend = '$managerEmail' and whomSent = '$clientEmail' or whoSend = '$clientEmail' and whomSent = '$managerEmail'";
        $result= mysqli_query($con, $query);

        error_log($query);

        if(mysqli_num_rows($result) > 0) {

            error_log("채팅 내용이 있습니다.");
//
            $query= "SELECT * FROM `ChatData` where whoSend = '$managerEmail' and whomSent = '$clientEmail' or whoSend = '$clientEmail' and whomSent = '$managerEmail'";
            $result= mysqli_query($con, $query);

            error_log("쿼리문");
            error_log($query);
//
            $emparray = array();
            if(mysqli_num_rows($result) > 0){
                while ($row = mysqli_fetch_assoc($result)) {
                    $emparray[] = $row;
                }
            }
            /*한글 깨짐 문제 해결 위한 옵션 추가 JSON_UNESCAPED_UNICODE */
            echo json_encode(array( "status" => "true","message" => "채팅 목록들 가져오기!!! ", "data" => $emparray) , JSON_UNESCAPED_UNICODE);



        }else{
            error_log("채팅 내용이 없습니다.");
            $query= "SELECT * FROM `ChatRoom` ORDER BY `ChatRoom`.`id` DESC limit 1";
            $result= mysqli_query($con, $query);

            error_log("쿼리문");
            error_log($query);
//
            $emparray = array();
            if(mysqli_num_rows($result) > 0){
                while ($row = mysqli_fetch_assoc($result)) {
                    $emparray[] = $row;
                }
            }

            echo json_encode(array( "status" => "false","message" => "채팅 내용이 없습니다. 마지막 방 정보 : ", "data" => $emparray) , JSON_UNESCAPED_UNICODE);

        }






        }



























    }

}else{
    echo json_encode(array( "status" => "false","message" => "Error occured, please try again!") );
}

?>