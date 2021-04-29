<?php
/* 채팅방 목록 보여주는 코드 */

error_log("chatRoomSelect.php  ====  고객용앱  ====  채팅방 목록 보여주는 코드");

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

        if (empty(($_GET['currentUser']))&& empty(($_GET['whoClientManager'])) ){

            error_log("currentUser / whoSendSent 비어있음. ");

            echo json_encode(array( "status" => "false","message" => "currentUser / whoSendSent 비어있음.") );


        }else if(($_GET['currentUser']) && $_GET['whoClientManager'] == 1 ){ //whoSendSent
            error_log("currentUser 있음 / whoSendSent 1 -> 메세지 보낸사람이 현재 사용자인 경우 / 열람하는 사람이 고객임 ");

            $currentUser = $_GET['currentUser'];
            error_log($currentUser);

            $whoClientManager = $_GET['whoClientManager'];
            error_log($whoClientManager);



            $query= "SELECT * FROM `ChatRoom`  WHERE `memberList` LIKE '%$currentUser%'";


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

                error_log("해당 이메일로 검색했을 때, 채팅방 목록이 없습니다.");
                echo json_encode(array( "status" => "false","message" => "해당 이메일로 검색했을 때, 채팅방 목록이 없습니다.") );

            }




        }else if(($_GET['currentUser']) && $_GET['whoClientManager'] == 2 ){
            error_log("currentUser 있음 / whoSendSent 2 -> 메세지 보낸사람이 현재 사용자인 경우 / 열람하는 사람이 매니저임 ");

            $currentUser = $_GET['currentUser'];
            error_log($currentUser);

            $whoClientManager = $_GET['whoClientManager'];
            error_log($whoClientManager);
            

            /* 일단 서비스에서 유저가 겟유저인 서비스의 매니저 이름을 가져옴. 매니저 이름을 가져올 때, 문자열 잘라서 가져올 것.
            가져온 셀렉트 값들을 배열로 정리해서. 배열에서 하나씩 꺼내서 다음 sql문으로 셀렉트 할 것 */
//            $query= "SELECT * FROM `ChatData`  WHERE `chatRoomId` = ( SELECT `id` FROM `ChatRoom`  WHERE `memberList` LIKE '%$currentUser%')";

            $query= "SELECT * FROM `ChatRoom`  WHERE `memberList` LIKE '%$currentUser%'";


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

                error_log("해당 이메일로 검색했을 때, 채팅방 목록이 없습니다.");
                echo json_encode(array( "status" => "false","message" => "해당 이메일로 검색했을 때, 채팅방 목록이 없습니다.") );

            }


        }
    }

}else{
    echo json_encode(array( "status" => "false","message" => "Error occured, please try again!") );
}

?>