<?php
/* 매니저 프로필 수정 및 열람 위한 코드 */

error_log("managerSelect 매니저 목록 보여주는 서버 코드");

header("Content-Type:text/html;charset=utf-8");

if($_SERVER['REQUEST_METHOD']=='GET'){

//    error_log("REQUEST_METHOD GET 아래는 현재 경로");
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

        if (empty(($_GET['visit']))){

            error_log("visit 비어있음. 근처 매니저 보여주는 부분");

            /* 여기서부터 post로 받아온 값으로 sql문 넣기 시작 함 */
            $siGunGuStr = $_GET['siGunGuStr'];
            $user = $_GET['user'];


            error_log($siGunGuStr);
            error_log($user);



/*      이용 경험있는 매니저 이름 목록 가져오는  sql문임    $userids     */
            /* 일단 서비스에서 유저가 겟유저인 서비스의 매니저 이름을 가져옴. 매니저 이름을 가져올 때, 문자열 잘라서 가져올 것.
가져온 셀렉트 값들을 배열로 정리해서. 배열에서 하나씩 꺼내서 다음 sql문으로 셀렉트 할 것 */

            $query= "SELECT left (`managerName`, 3) FROM `Services` WHERE `currentUser`='$user'GROUP BY `managerName`";
            $result= mysqli_query($con, $query);
            error_log("쿼리문ddddddddddddddddd");
            error_log($query);
            $managerArray = array();

            if(mysqli_num_rows($result) > 0){

                while ($row = mysqli_fetch_array($result)) {
                    $managerArray = $row;
                }


            }
            error_log(mysqli_num_rows($result));
            error_log("eeeeeeeeeeeeeeeeeeee 어레이 갯수임!!!! ");
            error_log(count($managerArray));
//            $in_list = empty($managerArray)?'NULL':"'".join("','", $managerArray)."'";

// AND `managerName`!=`매니저미지정`










            //$str = implode('`,`', $managerArray);
            $str = "`nameStr` NOT IN ('".implode("' , '",$managerArray)."')"; //$managerArray= array('배수지','김제니');




            /*        $managerArray = array(); */
            $query= "SELECT * FROM `ManagerInfo` WHERE `desiredWorkAreaList` LIKE '%$siGunGuStr%' and $str";
            $result= mysqli_query($con, $query);

            error_log($query);

            if(mysqli_num_rows($result) > 0) {


                $query= "SELECT * FROM `ManagerInfo` WHERE `desiredWorkAreaList` LIKE '%$siGunGuStr%' and $str";
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



        }else{


            error_log("=================  visit 있음 방문했던 매니저 보여주는 부분");

            /* 여기서부터 get으로 받아온 이메일 값으로 sql문 넣기 시작 함 */
            $siGunGuStr = $_GET['siGunGuStr'];
            $user = $_GET['user'];

            error_log($siGunGuStr);
            error_log($user);

            /* 일단 서비스에서 유저가 겟유저인 서비스의 매니저 이름을 가져옴. 매니저 이름을 가져올 때, 문자열 잘라서 가져올 것.
            가져온 셀렉트 값들을 배열로 정리해서. 배열에서 하나씩 꺼내서 다음 sql문으로 셀렉트 할 것 */

            $query= "SELECT left (`managerName`, 3) FROM `Services` WHERE `currentUser`='$user'GROUP BY `managerName`";
            $result= mysqli_query($con, $query);
            error_log("쿼리문ddddddddddddddddd");
            error_log($query);
            $managerArray = array();
            if(mysqli_num_rows($result) > 0){
                while ($row = mysqli_fetch_array($result)) {
                    $managerArray = $row;
                }
            }

            error_log("eeeeeeeeeeeeeeeeeeee 어레이 갯수임!!!! ");
            error_log(count($managerArray));
//            $in_list = empty($managerArray)?'NULL':"'".join("','", $managerArray)."'";

// AND `managerName`!=`매니저미지정`



            //$managerArray= array('배수지','김제니');
            $userids = "`nameStr` = '".implode("' \n   OR nameStr = '",$managerArray)."'";
            error_log($userids);




//            /* 하.. 그냥 프로필 이미지 설정한 매니저 전체 다 보여주자..ㅎㅎ */
//            /*        $managerArray = array();*/
//            $query= "SELECT * FROM `ManagerInfo` WHERE `desiredWorkAreaList` LIKE '%$siGunGuStr%' AND '$userids'";

            $query= "SELECT * FROM `ManagerInfo` WHERE `desiredWorkAreaList` LIKE '%$siGunGuStr%' AND $userids";

            $result= mysqli_query($con, $query);

            error_log($query);

            if(mysqli_num_rows($result) > 0) {

                $query= "SELECT * FROM `ManagerInfo` WHERE `desiredWorkAreaList` LIKE '%$siGunGuStr%' AND $userids";
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

    }

}else{
    echo json_encode(array( "status" => "false","message" => "Error occured, please try again!") );
}

?>