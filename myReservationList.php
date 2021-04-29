<?php
    // 에러 출력을 위한 코드
    //error_reporting(E_ALL); 
    //ini_set('display_errors',1); 
    include('dbcon.php');
    $currentUser = $_GET['currentUser'];
    $date = $_GET['date'];
    error_log("예약리스트 보여주는 코드");
    error_log($currentUser);
    error_log($date);


    $android = strpos($_SERVER['HTTP_USER_AGENT'], "Android");

    if( (($_SERVER['REQUEST_METHOD'] == 'GET') && isset($_GET['submit'])) || $android ){

        // 안드로이드 코드의 postParameters 변수에 적어준 이름을 가지고 값을 전달 받습니다.
        if(!isset($errMSG)) {
            try{
                $sql ="select * from Services where currentUser='$currentUser' and visitDate='$date'";
                if(!$android){
                    echo $sql;
                }else{
                }

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
                                'myplaceDTO_placeName'=>$row["myplaceDTO_placeName"],
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
    } ?>


    <?php 
    if (isset($errMSG)) 
    //echo $errMSG;
    if (isset($successMSG)) 
    //echo $successMSG;

    $android = strpos($_SERVER['HTTP_USER_AGENT'], "Android");
   
    if( !$android ){
?>
    <html>
       <body>

            <form action="<?php $_PHP_SELF ?>" method="GET">
            currentUser: <input type = "text" name = "currentUser" />
                <input type = "submit" name = "submit" />
            </form>
       
       </body>
    </html>

<?php 
    }
?>

