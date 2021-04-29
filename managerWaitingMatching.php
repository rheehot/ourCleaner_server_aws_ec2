<?php
// 에러 출력을 위한 코드
//error_reporting(E_ALL);
//ini_set('display_errors',1);
include('dbcon.php');
//$serviceState = "매칭 대기 중";
//$serviceState = $_GET['serviceState'];
//$managerNameId= $_GET['managerNameId'];



error_log("매니저앱에서 서비스 목록 불러오는 코드");

$android = strpos($_SERVER['HTTP_USER_AGENT'], "Android");


if( (($_SERVER['REQUEST_METHOD'] == 'GET') && isset($_GET['submit'])) || $android ){

    if(isset($_GET['serviceState'])){
        error_log("서비스 상태로 불러오는 경우");

        $serviceState = $_GET['serviceState'];


        try{
            /* 서비스 상태가 매칭 대기 중인 Service 불러오기 */
            $sql ="select * from Services where serviceState='$serviceState' and `managerName`= '매니저미지정' ";

            $stmt = $con->prepare($sql);
            $stmt -> execute();

            //제이슨으로 보낼 배열을 생성하고
            $data = array();

            while($row=$stmt->fetch(PDO::FETCH_ASSOC)){

                extract($row);
                $arrayMiddle = array (
                    'uid'=>$row["uid"],
                    'currentUser'=>$row["currentUser"],
                    'serviceState'=>$row["serviceState"],

                    'myplaceDTO_address'=>$row["myplaceDTO_address"],
                    'myplaceDTO_detailAddress'=>$row["myplaceDTO_detailAddress"],
                    'myplaceDTO_sizeStr'=>$row["myplaceDTO_sizeStr"],

                    'managerName'=>$row["managerName"],
                    'regularBool'=>$row["regularBool"],
                    'visitDate'=>$row["visitDate"],
                    'visitDay'=>$row["visitDay"],

                    'startTime'=>$row["startTime"],
                    'needDefTime'=>$row["needDefTime"],
                    'needDefCost'=>$row["needDefCost"],

                    'servicefocusedhashMap'=>$row["servicefocusedhashMap"],
                    'laundryBool'=>$row["laundryBool"],
                    'laundryCaution'=>$row["laundryCaution"],

                    'garbagerecycleBool'=>$row["garbagerecycleBool"],
                    'garbagenormalBool'=>$row["garbagenormalBool"],
                    'garbagefoodBool'=>$row["garbagefoodBool"],

                    'garbagehowto'=>$row["garbagehowto"],
                    'serviceplus'=>$row["serviceplus"],
                    'serviceCaution'=>$row["serviceCaution"],

                );

                //데이터 넣은 배열arrayMiddle 을 제이슨으로 보낼 데이터에 넣음
                array_push ( $data, $arrayMiddle );

            }

            header('Content-Type: application/json; charset=utf8');
            $json = json_encode($data, JSON_PRETTY_PRINT+JSON_UNESCAPED_UNICODE);
            echo $json;

        } catch(PDOException $e) {
            //die("Database error: " . $e->getMessage());
        }


    }else if(isset($_GET['managerNameId'])){
        error_log("매니저 이름으로 불러오는 경우");

        $managerNameId= $_GET['managerNameId'];

        error_log($managerNameId);


        try{
            /* 서비스 상태가 매칭 대기 중인 Service 불러오기 */

            $serviceState="매칭 완료";
            $date = $_GET['date'];
            error_log($date);

//            $sql ="select * from Services where `managerName`= '$managerNameId' and `serviceState=`'$serviceState' and `visitDate=`'$date'";

            $sql ="select * from Services where serviceState='$serviceState' and `managerName`= '$managerNameId' and `visitDate`='$date' 
                                            or serviceState='이동 중' and `managerName`= '$managerNameId' and `visitDate`='$date' 
                                            or serviceState='청소 시작' and `managerName`= '$managerNameId' and `visitDate`='$date'
                                            or serviceState='청소 완료' and `managerName`= '$managerNameId' and `visitDate`='$date'";
//            $sql ="select * from Services where `managerName`= '$managerNameId' and `visitDate=`'1.7' ";


            error_log($sql);

            $stmt = $con->prepare($sql);
            $stmt -> execute();

            //제이슨으로 보낼 배열을 생성하고
            $data = array();

            while($row=$stmt->fetch(PDO::FETCH_ASSOC)){

                extract($row);
                $arrayMiddle = array (
                    'uid'=>$row["uid"],
                    'currentUser'=>$row["currentUser"],
                    'serviceState'=>$row["serviceState"],

                    'myplaceDTO_address'=>$row["myplaceDTO_address"],
                    'myplaceDTO_detailAddress'=>$row["myplaceDTO_detailAddress"],
                    'myplaceDTO_sizeStr'=>$row["myplaceDTO_sizeStr"],

                    'managerName'=>$row["managerName"],
                    'regularBool'=>$row["regularBool"],
                    'visitDate'=>$row["visitDate"],
                    'visitDay'=>$row["visitDay"],

                    'startTime'=>$row["startTime"],
                    'needDefTime'=>$row["needDefTime"],
                    'needDefCost'=>$row["needDefCost"],

                    'servicefocusedhashMap'=>$row["servicefocusedhashMap"],
                    'laundryBool'=>$row["laundryBool"],
                    'laundryCaution'=>$row["laundryCaution"],

                    'garbagerecycleBool'=>$row["garbagerecycleBool"],
                    'garbagenormalBool'=>$row["garbagenormalBool"],
                    'garbagefoodBool'=>$row["garbagefoodBool"],

                    'garbagehowto'=>$row["garbagehowto"],
                    'serviceplus'=>$row["serviceplus"],
                    'serviceCaution'=>$row["serviceCaution"],

                );

                //데이터 넣은 배열arrayMiddle 을 제이슨으로 보낼 데이터에 넣음
                array_push ( $data, $arrayMiddle );

            }

            header('Content-Type: application/json; charset=utf8');
            $json = json_encode($data, JSON_PRETTY_PRINT+JSON_UNESCAPED_UNICODE);
            echo $json;

        } catch(PDOException $e) {
            //die("Database error: " . $e->getMessage());
        }



    }



}
?>
