<?php
// 에러 출력을 위한 코드
//error_reporting(E_ALL);
//ini_set('display_errors',1);
include('dbcon.php');
//$serviceState = "매칭 대기 중";
$currentUser = $_POST['currentUser'];
//if($serviceState = $_GET['serviceState']){
//    error_log("serviceState 겟으로 받아온 경우, 즉, 리사이클러뷰 에 서비스 목록 보여주는 경우");
//    $sql ="select * from Services where serviceState='$serviceState'";
//
//}
//
//if($uid = $_POST['uid']){
//    error_log("uid 포스트로 받아온 경우, 즉, 상세보기 하는 경우");
//    $sql ="select * from Services where uid='$uid'";
//
//}


error_log($currentUser);

$android = strpos($_SERVER['HTTP_USER_AGENT'], "Android");

if( (($_SERVER['REQUEST_METHOD'] == 'GET') && isset($_GET['submit'])) || $android ){

    // 안드로이드 코드의 postParameters 변수에 적어준 이름을 가지고 값을 전달 받습니다.
    if(!isset($errMSG)) {
        try{
            /* 서비스 상태가 매칭 대기 중인 Service 불러오기 */
            $sql ="select * from myCard where currentUser='$currentUser'";
            //where serviceState='$serviceState'
//            if(!$android){
//                echo $sql;
//            }else{
//            }

            $stmt = $con->prepare($sql);
            $stmt -> execute();

            //제이슨으로 보낼 배열을 생성하고
            $data = array();

            while($row=$stmt->fetch(PDO::FETCH_ASSOC)){

                extract($row);
                $arrayMiddle = array (
                    'uid'=>$row["uid"],
                    'currentUser'=>$row["currentUser"],
                    'billing_key'=>$row["billing_key"],

                    'card_name'=>$row["card_name"],

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

