<?php
/* 채팅방 목록 보여주는 코드 */

error_log("ChatRoomUnreadMessageSelectInterface 해당 채팅방 아이템 하나의 읽지 않은 메세지 가져오는 코드");

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

        if (empty(($_GET['currentUser'])) || empty(($_GET['whomSent1'])) || empty(($_GET['roomId']))
            || empty(($_GET['whenSend']))   || empty(($_GET['lastMessage']))  || empty(($_GET['id']))
            || empty(($_GET['whoClientManager']))  ){

            error_log(" currentUser / whomSent1 / roomId / whenSend / lastMessage / id / whoClientManager 하나라도 비어있음. ");

            echo json_encode(array( "status" => "false","message" => "currentUser / whomSent1 / roomId / whenSend / lastMessage / id / whoClientManager 하나라도 비어있음. ") );


        }else if($_GET['currentUser']  && $_GET['whomSent1'] && $_GET['roomId']
            && $_GET['whenSend']  && $_GET['lastMessage'] && $_GET['id']
            && $_GET['whoClientManager'] == 1 ){ //whoSendSent

            error_log(" currentUser / whomSent1 / roomId / whenSend / lastMessage / id 있음  whoSendSent 1 -> 메세지 보낸사람이 현재 사용자인 경우 / 열람하는 사람이 고객임 ");

            $currentUser = $_GET['currentUser'];
            error_log($currentUser);

            $whomSent1 = $_GET['whomSent1']; //상대방. 비교 단서
            error_log($whomSent1);

            $roomId = $_GET['roomId'];
            error_log($roomId);

            $whenSend = $_GET['whenSend']; //메세지 보낸 시간. 비교 단서 : blank 확인
            error_log($whenSend);

            $lastMessage = $_GET['lastMessage']; //메세지 마지막 내용. 비교 단서 : blank 확인
            error_log($lastMessage);

            $id = $_GET['id'];
            error_log($id);

            $whoClientManager = $_GET['whoClientManager'];
            error_log($whoClientManager);


            if($whenSend == "blank" && $lastMessage == "blank" ){
                /* 마지막 메세지가 저장된 것이 없음
                즉, 새로 만들어진 방!! */


                $query= "SELECT * FROM `ChatData` WHERE `chatRoomId` = '$roomId' and `whoSend` = '$whomSent1'  ";


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

                    error_log("새로 생성된 채팅방입니다. 그냥 채팅방id 랑 내가 보낸 메세지 아닌거 모두 가져옴");
                    echo json_encode(array( "status" => "false","message" => "새로 생성된 채팅방입니다. 그냥 채팅방id 랑 내가 보낸 메세지 아닌거 모두 가져옴") );

                }
            } else{



                $query= "SELECT * FROM `ChatData` WHERE `chatRoomId` = '$roomId' and `whoSend` = '$whomSent1' and `id` between (select id from `ChatData` where `chatRoomId` = '$roomId' and `getTime` = '$whenSend' and `msgStr` = '$lastMessage') and '$id' ";


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








        }else if($_GET['currentUser']  && $_GET['whomSent1'] && $_GET['roomId']
            && $_GET['whenSend']  && $_GET['lastMessage'] && $_GET['id']
            && $_GET['whoClientManager'] == 2 ){
            error_log(" currentUser / whomSent1 / roomId / whenSend / lastMessage / id 있음 / whoSendSent 2 -> 메세지 보낸사람이 현재 사용자인 경우 / 열람하는 사람이 매니저임 ");

            $currentUser = $_GET['currentUser'];
            error_log($currentUser);

            $whomSent1 = $_GET['whomSent1']; //상대방. 비교 단서
            error_log($whomSent1);

            $roomId = $_GET['roomId'];
            error_log($roomId);

            $whenSend = $_GET['whenSend']; //메세지 보낸 시간. 비교 단서
            error_log($whenSend);

            $lastMessage = $_GET['lastMessage']; //메세지 마지막 내용. 비교 단서
            error_log($lastMessage);

            $id = $_GET['id'];
            error_log($id);

            $whoClientManager = $_GET['whoClientManager'];
            error_log($whoClientManager);


            if($whenSend == "blank" && $lastMessage == "blank" ){
                /* 마지막 메세지가 저장된 것이 없음
                즉, 새로 만들어진 방!! */


                $query= "SELECT * FROM `ChatData` WHERE `chatRoomId` = '$roomId' and `whoSend` = '$whomSent1'  ";


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

                    error_log("새로 생성된 채팅방입니다. 그냥 채팅방id 랑 내가 보낸 메세지 아닌거 모두 가져옴");
                    echo json_encode(array( "status" => "false","message" => "새로 생성된 채팅방입니다. 그냥 채팅방id 랑 내가 보낸 메세지 아닌거 모두 가져옴") );

                }
            } else{



                $query= "SELECT * FROM `ChatData` WHERE `chatRoomId` = '$roomId' and `whoSend` = '$whomSent1' and `id` between (select id from `ChatData` where `chatRoomId` = '$roomId' and `getTime` = '$whenSend' and `msgStr` = '$lastMessage') and '$id' ";


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

        }else{
            echo json_encode(array( "status" => "false","message" => "뭔가 오류가 있습니다.!! 받아온 get 변수들 확인하기 ") );

            error_log("뭔가 오류가 있습니다.!! 받아온 get 변수들 확인하기 ");
            $currentUser = $_GET['currentUser'];
            error_log($currentUser);

            $whomSent1 = $_GET['whomSent1'];
            error_log($whomSent1);

            $roomId = $_GET['roomId'];
            error_log($roomId);

            $whenSend = $_GET['whenSend'];
            error_log($whenSend);

            $lastMessage = $_GET['lastMessage'];
            error_log($lastMessage);

            $id = $_GET['id'];
            error_log($id);

            $whoClientManager = $_GET['whoClientManager'];
            error_log($whoClientManager);
        }
    }

}else{
    echo json_encode(array( "status" => "false","message" => "받아온 get이 없습니다.") );
}

?>