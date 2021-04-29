<?php
/* 매니저 프로필 수정 및 열람 위한 코드 */

header("Content-Type:text/html;charset=utf-8");

if($_SERVER['REQUEST_METHOD']=='GET'){

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


        /* 여기서부터 post로 받아온 값으로 sql문 넣기 시작 함 */
        $managerEmail = $_GET['managerEmail'];

        error_log($managerEmail);

        /* 하.. 그냥 프로필 이미지 설정한 매니저 전체 다 보여주자..ㅎㅎ */
        /*        $managerArray = array();*/
        $query= "SELECT * FROM `ManagerInfo` where idStr = '$managerEmail'";
        $result= mysqli_query($con, $query);

        error_log($query);

        if(mysqli_num_rows($result) > 0) {

            $query= "SELECT * FROM `ManagerInfo` where idStr = '$managerEmail'";
            $result= mysqli_query($con, $query);

            error_log("쿼리문");
            error_log($query);

            $emparray = array();
            if(mysqli_num_rows($result) > 0){
                while ($row = mysqli_fetch_assoc($result)) {
                    $emparray[] = $row;
                }
            }
            /*한글 깨짐 문제 해결 위한 옵션 추가 JSON_UNESCAPED_UNICODE */
            echo json_encode(array( "status" => "true","message" => "검색 완료!", "data" => $emparray) , JSON_UNESCAPED_UNICODE);



        }else{
            error_log("매니저 정보가 없습니다.");
        }



    }

}else{
    echo json_encode(array( "status" => "false","message" => "Error occured, please try again!") );
}

?>