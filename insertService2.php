<?php 

// 서비스 db에 저장하는 부분 

    // 에러 출력을 위한 코드
    error_reporting(E_ALL); 
    ini_set('display_errors',1); 

    include('dbcon.php');

    error_log("서비스 내용 등록하는 서버 코드 ");

    $currentUser=$_POST['currentUser'];
    $serviceState=$_POST['serviceState'];

    $myplaceDTO_placeName=$_POST['myplaceDTO_placeName'];
    $myplaceDTO_address=$_POST['myplaceDTO_address'];
    $myplaceDTO_detailAddress=$_POST['myplaceDTO_detailAddress'];
    $myplaceDTO_sizeStr=$_POST['myplaceDTO_sizeStr'];

    $managerName=$_POST['managerName'];
    $regularBool=$_POST['regularBool'];
    $visitDate=$_POST['visitDate'];
    $visitDay=$_POST['visitDay'];
    $startTime=$_POST['startTime'];
    $needDefTime=$_POST['needDefTime'];
    $needDefCost=$_POST['needDefCost'];

    $servicefocusedhashMap=$_POST['servicefocusedhashMap'];
    $laundryBool=$_POST['laundryBool'];
    $laundryCaution=$_POST['laundryCaution'];

    $garbagerecycleBool=$_POST['garbagerecycleBool'];
    $garbagenormalBool=$_POST['garbagenormalBool'];
    $garbagefoodBool=$_POST['garbagefoodBool'];
    $garbagehowto=$_POST['garbagehowto'];

    $serviceplus=$_POST['serviceplus'];
    $serviceCaution=$_POST['serviceCaution'];

    $recentUid=$_POST['recentUid'];
    $recentBilling_key=$_POST['recentBilling_key'];
    $recentCard_name=$_POST['recentCard_name'];

    $receipt_id = $_POST['receipt_id'];

    error_log("영수증 번호");
    error_log($receipt_id);


$conn = mysqli_connect($host, $username, $password,$dbname);

    //mysql 한글 깨짐 현상
    mysqli_set_charset($conn,"utf8");

    if (mysqli_connect_errno($conn)){

        error_log("데이터베이스 연결 실패: ");
        error_log(mysqli_connect_error());

    }
    else{

        // 쿼리문
        $query = "INSERT INTO `Services` (currentUser, serviceState, myplaceDTO_placeName, myplaceDTO_address, myplaceDTO_detailAddress,
        myplaceDTO_sizeStr, managerName, regularBool, visitDate, visitDay, startTime, needDefTime, needDefCost,
        servicefocusedhashMap, laundryBool, laundryCaution, garbagerecycleBool, garbagenormalBool, garbagefoodBool,
        garbagehowto, serviceplus, serviceCaution,cardUid, cardBilling_key,cardCard_name, receipt_id
        ) 
        VALUES ('".$currentUser."','".$serviceState."','".$myplaceDTO_placeName."','".$myplaceDTO_address."','".$myplaceDTO_detailAddress."'
        ,'".$myplaceDTO_sizeStr."','".$managerName."','".$regularBool."','".$visitDate."','".$visitDay."'
        ,'".$startTime."','".$needDefTime."','".$needDefCost."','".$servicefocusedhashMap."','".$laundryBool."'
        ,'".$laundryCaution."'
        ,'".$garbagerecycleBool."','".$garbagenormalBool."','".$garbagefoodBool."','".$garbagehowto."'
        ,'".$serviceplus."','".$serviceCaution."','".$recentUid."','".$recentBilling_key."','".$recentCard_name."','".$receipt_id."' )
        ;";

        if(mysqli_query($conn, $query)){
            error_log("서비스 데이터베이스 insert into ");

            if($managerName=="매니저미지정"){
                /* 매니저미지정인 경우,  */
                error_log("매니저 미지정한 경우, 알람 디비 넣을 필요 없음...");
            }else{
                error_log("매니저 지정한 경우, 알람 생성 및, 알람 db 업데이트");

                $index = strpos($managerName, ",");
                $managerEmail = substr($managerName,$index+1);

                error_log("이메일 추출한거");
                error_log($managerEmail);

                /* mysqli에서 금방 업데이트된 데이터의 autoincrement한 id 받아오기
                mysqli_query($conn, $query) 실행한 후에 실행시켜야 함.

                참고)
                $conn = mysqli_connect($host, $username, $password,$dbname); */
                $id = mysqli_insert_id($conn);
                error_log($id);

                $query = "INSERT INTO `alarmForService`(fromEmail, toEmail, ServiceNum) 
                VALUES ('".$currentUser."','".$managerEmail."','".$id."')
                ;";

                if(mysqli_query($conn, $query)) {
                    error_log("알람 데이터 insert into ");


                }else{
                    error_log("알람 데이터 Error");
                    error_log(mysqli_error($conn));
                }


            }






        }else{
            error_log("서비스 데이터베이스 Error");
            error_log(mysqli_error($conn));
        }

        // to do something$myplaceDTO_placeName

    }
    
?>











