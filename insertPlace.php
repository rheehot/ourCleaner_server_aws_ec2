<?php
    // 에러 출력을 위한 코드
    error_reporting(E_ALL); 
    ini_set('display_errors',1); 

    include('dbcon.php');
    //echo "<br>\nemailCheck.php 코드 시작함<br>\n";

    $android = strpos($_SERVER['HTTP_USER_AGENT'], "Android");

    if(($_SERVER['REQUEST_METHOD'] == 'POST')&&$android){
        //echo "안드로이드에서 post로 받음";
        
        $currentUser=$_POST['currentUser']; //변경 불가
        $placeNameStr=$_POST['placeNameStr']; 
        $address=$_POST['address']; //변경 불가
        $detailAddress=$_POST['detailAddress'];
        $sizeStr=$_POST['sizeStr']; // 변경 불가
        $sizeIndexint=$_POST['sizeIndexint']; // 변경 불가
        $petDog=$_POST['petDog'];
        $petCat=$_POST['petCat'];
        $petEtc=$_POST['petEtc'];
        $childBool=$_POST['childBool'];
        $cctvBool=$_POST['cctvBool'];
        $parkingBool=$_POST['parkingBool'];
        $petGuideStr=$_POST['petGuideStr'];
        $parkingGuideStr=$_POST['parkingGuideStr'];


        try{
            $stmt = $con->prepare('INSERT INTO myPlace(currentUser, placeNameStr, address, detailAddress, sizeStr, sizeIndexint, 
            petDog, petCat, petEtc,
            childBool, cctvBool, parkingBool,
            petGuideStr, parkingGuideStr) VALUES(:currentUser, :placeNameStr, :address, :detailAddress, :sizeStr, :sizeIndexint, 
            :petDog, :petCat, :petEtc,
            :childBool, :cctvBool, :parkingBool,
            :petGuideStr, :parkingGuideStr)');
            $stmt->bindParam(':currentUser', $currentUser);
            $stmt->bindParam(':placeNameStr', $placeNameStr);
            $stmt->bindParam(':address', $address);
            $stmt->bindParam(':detailAddress', $detailAddress);
            $stmt->bindParam(':sizeStr', $sizeStr);
            $stmt->bindParam(':sizeIndexint', $sizeIndexint);
            $stmt->bindParam(':petDog', $petDog);
            $stmt->bindParam(':petCat', $petCat);
            $stmt->bindParam(':petEtc', $petEtc);
            $stmt->bindParam(':childBool', $childBool);
            $stmt->bindParam(':cctvBool', $cctvBool);
            $stmt->bindParam(':parkingBool', $parkingBool);
            $stmt->bindParam(':petGuideStr', $petGuideStr);
            $stmt->bindParam(':parkingGuideStr', $parkingGuideStr);
    
            if($stmt->execute()){
                error_log("success!!");
            }
            else{
                error_log("error!");
            }
    
            } catch(PDOException $e) {
                die("Database error: " . $e->getMessage());

                error_log("error! : "+$e);
            }
        
        

    }



            // header('Content-Type: application/json');

        // $myplace1_detail = array("detailAddress" => $detailAddress, 
        // "petDog" => $petDog, "petCat" => $petCat, "petEtc" => $petEtc, 
        // "childBool" => $childBool, "cctvBool" => $cctvBool, "parkingBool" => $parkingBool,
        // "petGuideStr" => $petGuideStr, "parkingGuideStr" => $parkingGuideStr);


        // // 장소 정보를 myplaceData에 저장
        // $myplaceData = array("currentUser" => $currentUser, "address" => $address, "sizeStr" => $sizeStr, "sizeIndexint" => $sizeIndexint, "placeNameStr" => $placeNameStr);
        // $myplaceData['myplace1_detail'] = $myplace1_detail;

         
        // // 3명의 데이터가 JSON Array 문자열로 변환됨
        // // 두번째 파라미터가 있으면, myplace1_detail도 저장 가능 함
        // $output =  json_encode($myplaceData, JSON_UNESCAPED_UNICODE);
     
        // // 출력
        // echo  urldecode($output);
        // error_log($output);

        // echo $output;

        // //안드로이드에서 받아온 데이터를 json으로 변경
        // //json으로 변경한 데이터를 



?>
