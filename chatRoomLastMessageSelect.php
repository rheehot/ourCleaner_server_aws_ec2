<?php
/* 채팅방 목록 보여주는 코드 */

error_log("ChatRoomLastMessageSelectInterface 해당 채팅방의 마지막 메세지 가져오는 코드임");

header("Content-Type:text/html;charset=utf-8");

if($_SERVER['REQUEST_METHOD']=='GET'){

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

        if (empty(($_GET['currentUser'])) || empty(($_GET['whomSent1'])) || empty(($_GET['roomId'])) || empty(($_GET['whoClientManager'])) ){

            error_log(" currentUser / whomSent1 / roomId / whoClientManager 하나라도 비어있음. ");

            echo json_encode(array( "status" => "false","message" => " currentUser / whomSent1 / roomId / whoClientManager 하나라도 비어있음. ") );


        }else if($_GET['currentUser']  && $_GET['whomSent1'] && $_GET['roomId'] && $_GET['whoClientManager'] == 1 ){ //whoSendSent
            error_log("currentUser / whomSent1 / roomId 있음 / whoSendSent 1 -> 메세지 보낸사람이 현재 사용자인 경우 / 열람하는 사람이 고객임 ");

            $currentUser = $_GET['currentUser'];
            error_log($currentUser);

            $whomSent1 = $_GET['whomSent1'];
            error_log($whomSent1);

            $roomId = $_GET['roomId'];
            error_log($roomId);

            $whoClientManager = $_GET['whoClientManager'];
            error_log($whoClientManager);



            $query= "SELECT * FROM `ChatData` WHERE `chatRoomId` = '$roomId' order by `id` desc limit 1";


            $result= mysqli_query($con, $query);
            error_log("쿼리문");
            error_log($query);

            $emparray = array();
            if(mysqli_num_rows($result) > 0){
                while ($row = mysqli_fetch_assoc($result)) { //연관배열
                    $emparray[] = $row;
                }

                /*한글 깨짐 문제 해결 위한 옵션 추가 JSON_UNESCAPED_UNICODE */
                echo json_encode(array( "status" => "true","message" => "검색 완료!", "data" => $emparray) , JSON_UNESCAPED_UNICODE);

            }else{

                error_log("해당 방id로 검색했을 때, 채팅 데이터가 없습니다.");
                echo json_encode(array( "status" => "false","message" => "해당 방id로 검색했을 때, 채팅 데이터가 없습니다.") );

            }




        }else if($_GET['currentUser'] && $_GET['whomSent1'] && $_GET['roomId'] && $_GET['whoClientManager'] == 2 ){
            error_log("currentUser / whomSent1 / roomId 있음 / whoSendSent 2 -> 메세지 보낸사람이 현재 사용자인 경우 / 열람하는 사람이 매니저임 ");

            $currentUser = $_GET['currentUser'];
            error_log($currentUser);

            $whomSent1 = $_GET['whomSent1'];
            error_log($whomSent1);

            $roomId = $_GET['roomId'];
            error_log($roomId);

            $whoClientManager = $_GET['whoClientManager'];
            error_log($whoClientManager);



            $query= "SELECT * FROM `ChatData` WHERE `chatRoomId` = '$roomId' order by `id` desc limit 1";


            $result= mysqli_query($con, $query);
            error_log("쿼리문");
            error_log($query);

            $emparray = array();
            if(mysqli_num_rows($result) > 0){
                while ($row = mysqli_fetch_assoc($result)) { //연관배열
                    $emparray[] = $row;
                }

                /*한글 깨짐 문제 해결 위한 옵션 추가 JSON_UNESCAPED_UNICODE */
                echo json_encode(array( "status" => "true","message" => "검색 완료!", "data" => $emparray) , JSON_UNESCAPED_UNICODE);

            }else{

                error_log("해당 방id로 검색했을 때, 채팅 데이터가 없습니다.");
                echo json_encode(array( "status" => "false","message" => "해당 방id로 검색했을 때, 채팅 데이터가 없습니다.") );

            }


        }else{
            echo json_encode(array( "status" => "false","message" => "뭔가 오류가 있습니다.!! 받아온 get 변수들 확인하기 ") );

            error_log("뭔가 오류가 있습니다.!! 받아온 get 변수들 확인하기 ");
            $currentUser = $_GET['currentUser'];
            error_log($currentUser);

            $whomSent1 = $_GET['whomSent1'];
            error_log($whomSent1);

            $roomId = $_GET['roomId'];
            error_log($roomId);

            $whoClientManager = $_GET['whoClientManager'];
            error_log($whoClientManager);
        }
    }

}else{
    echo json_encode(array( "status" => "false","message" => "받아온 get이 없습니다.") );
}

?>