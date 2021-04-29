<?php
/* 법정동 주소 검색하는 코드 */

header("Content-Type:text/html;charset=utf-8");

if($_SERVER['REQUEST_METHOD']=='POST'){
    // echo $_SERVER["DOCUMENT_ROOT"];  // /home1/demonuts/public_html

    error_log("REQUEST_METHOD POST 아래는 현재 경로");
    error_log($_SERVER["DOCUMENT_ROOT"]);

////including the database connection file
//    include_once("dbcon.php");

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


        /* 여기서부터 post로받아온 값으로 sql문 넣기 시작 함 */
        $researchKeyword = $_POST['researchKeyword'];
        $nowAddress = $_POST['nowAddress'];
        error_log($researchKeyword);
        error_log($nowAddress);


        if(isset($researchKeyword)){
            /* 1. 키워드를 받아오는 경우 */
            error_log("1. 키워드를 받아오는 경우");

            $query= "SELECT * FROM `AddressDB_B` WHERE `eupmyundongName` LIKE '%$researchKeyword%' OR `sigunguName` LIKE '%$researchKeyword%' ";
            $result= mysqli_query($con, $query);

            if(mysqli_num_rows($result) > 0){
                $query= "SELECT * FROM `AddressDB_B` WHERE `eupmyundongName` LIKE '%$researchKeyword%' OR `sigunguName` LIKE '%$researchKeyword%' ";
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
                header('Content-Type: application/json; charset=utf8');
                echo json_encode(array( "status" => "false","message" => "존재하는 데이터가 없습니다."), JSON_UNESCAPED_UNICODE );

                error_log("존재하는 데이터가 없습니다. 이 경우에는, 현재 주소로 데이터 보낼 것.");
            }
            mysqli_close($con);

        }else if(isset($nowAddress)){
            /* 2. 현재 위치로 주소 받아오는 경우 */
            error_log("2. 현재 위치로 주소 받아오는 경우");

            $query= "SELECT * FROM `AddressDB_B` WHERE `eupmyundongName` LIKE '%$nowAddress%' OR `sigunguName` LIKE '%$nowAddress%' ";
            $result= mysqli_query($con, $query);

            if(mysqli_num_rows($result) > 0){
                $query= "SELECT * FROM `AddressDB_B` WHERE `eupmyundongName` LIKE '%$nowAddress%' OR `sigunguName` LIKE '%$nowAddress%' ";
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
                header('Content-Type: application/json; charset=utf8');
                echo json_encode(array( "status" => "false","message" => "존재하는 데이터가 없습니다."), JSON_UNESCAPED_UNICODE );

                error_log("존재하는 데이터가 없습니다. 이 경우에는, 현재 주소로 데이터 보낼 것.");
            }
            mysqli_close($con);




        }else {
            /* 3. 둘 다 비어 있음 */
            error_log("3. 둘 다 비어 있음");
            error_log($researchKeyword);
            error_log($nowAddress);
            echo json_encode(array( "status" => "false","message" => "Parameter missing!") );
        }




//        if( $username == '' || $password == '' ){
//            echo json_encode(array( "status" => "false","message" => "Parameter missing!") );
//        }else{
//            $query= "SELECT * FROM registerDemo WHERE username='$username' AND password='$password'";
//            $result= mysqli_query($con, $query);
//
//            if(mysqli_num_rows($result) > 0){
//                $query= "SELECT * FROM registerDemo WHERE username='$username' AND password='$password'";
//                $result= mysqli_query($con, $query);
//                $emparray = array();
//                if(mysqli_num_rows($result) > 0){
//                    while ($row = mysqli_fetch_assoc($result)) {
//                        $emparray[] = $row;
//                    }
//                }
//                echo json_encode(array( "status" => "true","message" => "Login successfully!", "data" => $emparray) );
//            }else{
//                echo json_encode(array( "status" => "false","message" => "Invalid username or password!") );
//            }
//            mysqli_close($con);
//        }

    }

}else{
    echo json_encode(array( "status" => "false","message" => "Error occured, please try again!") );
}

?>