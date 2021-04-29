<?php
// 에러 출력을 위한 코드
//error_reporting(E_ALL);
//ini_set('display_errors',1);
include('dbcon.php');


/* 아직 사용 안 함 */

//if($serviceState = $_GET['serviceState']){
//    error_log("serviceState 겟으로 받아온 경우, 즉, 리사이클러뷰 에 서비스 목록 보여주는 경우");
//    $sql ="select * from Services where serviceState='$serviceState'";
//
//}
//
//if($uid = $_GET['uid']){
//    error_log("uid 포스트로 받아온 경우, 즉, 상세보기 하는 경우");
//    $sql ="select * from Services where uid='$uid'";
//
//}

error_log("=========================================");

$uid = $_POST['uid'];
error_log($uid);

$serviceState = $_POST['serviceState'];
error_log($serviceState);

$whoSelect = $_POST['whoSelect'];
error_log($whoSelect);

//if($serviceState=="예약 취소1"){
//    error_log("예약 취소1");
//
//    try {
//        $sql ="update Services set serviceState='$serviceState' where uid='$uid'";
//        $stmt = $con->prepare($sql);
//        $stmt -> execute();
//        error_log("예약 취소1로 변경 완료");
//    } catch(PDOException $e) {
//        error_log("예약 취소 spl문 안 들어감");
//        error_log($e);
//    }
//
//}else if($whoSelect!="고객"&& $serviceState=="매칭 완료"){
//    error_log("고객이 아닌 사용자가 매칭 완료 요청을 보냄. 매니저가 해당 서비스 수락함");
//
//
//    try {
////        $sql ="update Services set 	managerName='$whoSelect', serviceState='$serviceState' where uid='$uid'";
//
//        //서비스테이블의 매니저 이름 이랑 매니저테이블의 이름이랑 같음. 변경하고자 하는 것은, 서비스 테이블의 매니저이름을 매니저테이블의 이름으로
////        $sql ="update Services as A inner JOIN ManagerInfo as B on A.managerName=B.nameStr set A.managerName=B.nameStr where A.uid='$uid' and B.idStr='$whoSelect'";
//        $sql ="update Services set serviceState='$serviceState', managerName= (select nameStr from ManagerInfo where idStr='$whoSelect') where uid='$uid'";
//
//        $stmt = $con->prepare($sql);
//        $stmt -> execute();
//        error_log("매니저 지정하고, 서비스 상태도 바꿈");
//
//
//
//
//
//    } catch(PDOException $e) {
//        error_log("예약 취소 spl문 안 들어감");
//        error_log($e);
//    }
//}


/* 법정동 주소 검색하는 코드 */

header("Content-Type:text/html;charset=utf-8");

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // echo $_SERVER["DOCUMENT_ROOT"];  // /home1/demonuts/public_html

    error_log("REQUEST_METHOD POST 서비스 게시물 자세히 보기 서버 코드");
    error_log($_SERVER["DOCUMENT_ROOT"]);

////including the database connection file
//    include_once("dbcon.php");

    $host = 'ec2-52-79-179-66.ap-northeast-2.compute.amazonaws.com';
    $username = 'aileen'; # MySQL 계정 아이디
    $password = '!Nhskskp30415'; # MySQL 계정 패스워드
    $dbname = 'ourCleaner'; # DATABASE 이름

    $con = mysqli_connect($host, $username, $password, $dbname);

    /* 한글 깨짐 방지 위함 */
    mysqli_query($con, "set session character_set_connection=utf8;");
    mysqli_query($con, "set session character_set_results=utf8;");
    mysqli_query($con, "set session character_set_client=utf8;");

    if (mysqli_connect_errno($con)) {

        echo "DB 연결 실패:" . mysqli_connect_error();
        error_log("DB 연결 실패:");
        error_log(mysqli_connect_error());

    } else {
        error_log("DB 연결 성공");

         /* 1. 키워드를 받아오는 경우 */
            error_log("1. 게시물 id로 게시물 내용 검색 ");

            $query ="select * from `Services` where Services.uid='$uid' ";
            $result = mysqli_query($con, $query);

            if (mysqli_num_rows($result) > 0) {


                    if($whoSelect == "매니저"){
                        error_log("============ 매니저 열람 ============ 매니저 지정한 경우임 ");


                        $query ="select * from `Services` join `ManagerInfo` on Services.managerName  like CONCAT('%', ManagerInfo.idStr, '%') join `users_ge` on Services.currentUser =  users_ge.email join `myPlace` on Services.myplaceDTO_address = myPlace.address where Services.uid='$uid' ";

                        $result = mysqli_query($con, $query);

                        error_log("쿼리문");
                        error_log($query);


                        while ($row = mysqli_fetch_assoc($result)) {
                            $emparray[] = $row;
                        }

                        /* 여기서 배열에 서비스 개수 넣기 -> 서비스의 매니저 / 사용자가 같은 서비스의 개수  */



                        $query ="select * FROM `Services` where (currentUser,  managerName) = (select currentUser, managerName from Services where Services.uid='$uid')  and serviceState = '청소 완료'";

                        $result = mysqli_query($con, $query);

                        error_log("================== 쿼리문");
                        error_log($query);

                        $count = mysqli_num_rows($result);
                        error_log($count);


                        echo json_encode(array("status" => "true", "count" => $count,"message" => "매니저 지정", "data" => $emparray), JSON_UNESCAPED_UNICODE);

                    }elseif ($whoSelect == "고객"){
                        error_log("============ 고객 열람 ============ 매니저 지정한 경우임  ");

                        $query ="select * from `Services` join `ManagerInfo` on Services.managerName  like CONCAT('%', ManagerInfo.idStr, '%') where Services.uid='$uid' ";

                        $result = mysqli_query($con, $query);

                        error_log("쿼리문");
                        error_log($query);


                        while ($row = mysqli_fetch_assoc($result)) {
                            $emparray[] = $row;
                        }
                        echo json_encode(array("status" => "true", "message" => "매니저 지정", "data" => $emparray), JSON_UNESCAPED_UNICODE);


                    }


//                error_log(" 2 - (1). 게시물 내용이 있는 경우, -------- 매니저 지정 여부 체크하는 구간임. ");
//                $query ="select * from `Services` join `ManagerInfo` on Services.managerName  like CONCAT('%', ManagerInfo.idStr, '%') where Services.uid='$uid' ";
//                $result = mysqli_query($con, $query);
//
//                error_log("쿼리문");
//                error_log($query);




//
//                $emparray = array();
//                if (mysqli_num_rows($result) > 0) {
//
//
//
//
//
//
//                    if($whoSelect == "매니저"){
//                        error_log("3 - (1). 매니저 지정한 경우임 ============ 매니저 열람");
//
//
//
//                        while ($row = mysqli_fetch_assoc($result)) {
//                            $emparray[] = $row;
//                        }
//                        echo json_encode(array("status" => "true", "message" => "매니저 지정", "data" => $emparray), JSON_UNESCAPED_UNICODE);
//
//                    }elseif ($whoSelect == "고객"){
//                        error_log("3 - (1). 매니저 지정한 경우임 ============ 고객 열람 ");
//
//
//
//                        while ($row = mysqli_fetch_assoc($result)) {
//                            $emparray[] = $row;
//                        }
//                        echo json_encode(array("status" => "true", "message" => "매니저 지정", "data" => $emparray), JSON_UNESCAPED_UNICODE);
//
//
//                    }
//
//
//
//
//
//
//
//
//
//
//
//                }else{
//
//                    $query ="select * from `Services` where Services.uid='$uid'  ";
//                    /*한글 깨짐 문제 해결 위한 옵션 추가 JSON_UNESCAPED_UNICODE */
//                    $result = mysqli_query($con, $query);
//
//                    if (mysqli_num_rows($result) > 0) {
//                        error_log("3 - (2). 매니저를 지정하지 않은 경우 임.");
//
//                        $query ="select * from `Services` where Services.uid='$uid'  ";
//
//                        $emparray = array();
//                        while ($row = mysqli_fetch_assoc($result)) {
//                            $emparray[] = $row;
//                        }
//
//                        echo json_encode(array("status" => "true", "message" => "매니저 미지정", "data" => $emparray), JSON_UNESCAPED_UNICODE);
//                    }
//                }












            } else {
                header('Content-Type: application/json; charset=utf8');
                echo json_encode(array("status" => "false", "message" => "존재하는 데이터가 없습니다."), JSON_UNESCAPED_UNICODE);

                error_log("존재하는 데이터가 없습니다. 쿼리문에 문제 있음.. ");
                error_log($query);

            }
            mysqli_close($con);



    }

} else {
    echo json_encode(array("status" => "false", "message" => "Error occured, please try again!"));
}












//$android = strpos($_SERVER['HTTP_USER_AGENT'], "Android");
//
//if( (($_SERVER['REQUEST_METHOD'] == 'GET') && isset($_GET['submit'])) || $android ){
//
//    // 안드로이드 코드의 postParameters 변수에 적어준 이름을 가지고 값을 전달 받습니다.
//    if(!isset($errMSG)) {
//        try{
//            /* 서비스 상태가 매칭 대기 중인 Service 불러오기 */
//            //where serviceState='$serviceState'
//            $sql ="select * from `Services`, `ManagerInfo` where Services.managerName like CONCAT('%', ManagerInfo.id, '%') , Services.uid='$uid', ";
//
//            $stmt = $con->prepare($sql);
//            $stmt -> execute();
//
//            //제이슨으로 보낼 배열을 생성하고
//            $data = array();
//
//            while($row=$stmt->fetch(PDO::FETCH_ASSOC)){
//
//                extract($row);
//                $arrayMiddle = array (
//                    'uid'=>$row["uid"],
//                    'currentUser'=>$row["currentUser"],
//                    'serviceState'=>$row["serviceState"],
//
//                    'myplaceDTO_placeName'=>$row["myplaceDTO_placeName"],
//                    'myplaceDTO_address'=>$row["myplaceDTO_address"],
//                    'myplaceDTO_detailAddress'=>$row["myplaceDTO_detailAddress"],
//                    'myplaceDTO_sizeStr'=>$row["myplaceDTO_sizeStr"],
//
//                    'managerName'=>$row["managerName"],
//                    'regularBool'=>$row["regularBool"],
//                    'visitDate'=>$row["visitDate"],
//                    'visitDay'=>$row["visitDay"],
//
//                    'startTime'=>$row["startTime"],
//                    'needDefTime'=>$row["needDefTime"],
//                    'needDefCost'=>$row["needDefCost"],
//
//                    'servicefocusedhashMap'=>$row["servicefocusedhashMap"],
//                    'laundryBool'=>$row["laundryBool"],
//                    'laundryCaution'=>$row["laundryCaution"],
//
//                    'garbagerecycleBool'=>$row["garbagerecycleBool"],
//                    'garbagenormalBool'=>$row["garbagenormalBool"],
//                    'garbagefoodBool'=>$row["garbagefoodBool"],
//
//                    'garbagehowto'=>$row["garbagehowto"],
//                    'serviceplus'=>$row["serviceplus"],
//                    'serviceCaution'=>$row["serviceCaution"],
//
//                    'cardBilling_key'=>$row["cardBilling_key"],
//
//
//                );
//
//                //데이터 넣은 배열arrayMiddle 을 제이슨으로 보낼 데이터에 넣음
//                array_push ( $data, $arrayMiddle );
//
//            }
//
//            header('Content-Type: application/json; charset=utf8');
//            $json = json_encode($data, JSON_PRETTY_PRINT+JSON_UNESCAPED_UNICODE);
//            echo $json;
//
//        } catch(PDOException $e) {
//            //die("Database error: " . $e->getMessage());
//        }
//    }
//}


?>




