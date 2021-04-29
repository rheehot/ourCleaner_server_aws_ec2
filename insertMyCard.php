<?php
// 에러 출력을 위한 코드
error_reporting(E_ALL);
ini_set('display_errors',1);

//디비 연결을 위한 코드
include('dbcon.php');


$android = strpos($_SERVER['HTTP_USER_AGENT'], "Android");

if( ($_SERVER['REQUEST_METHOD'] == 'POST') && isset($_POST['submit']) || $android){


    $currentUser=$_POST['currentUser'];
    $billing_key=$_POST['billing_key'];

    $pg_name=$_POST['pg_name'];
    $pg=$_POST['pg'];
    $method_name=$_POST['method_name'];
    $method=$_POST['method'];

    $card_code=$_POST['card_code'];
    $card_name=$_POST['card_name'];

    $e_at=$_POST['e_at'];
    $c_at=$_POST['c_at'];
    $receipt_id=$_POST['receipt_id'];
    $action=$_POST['action'];




    if(!isset($errMSG)){

        try{
            $stmt = $con->prepare('INSERT INTO myCard(currentUser, billing_key, pg_name, pg, method_name, method, 
card_code, card_name, e_at, c_at, receipt_id, action) VALUES(:currentUser, :billing_key, :pg_name, :pg, :method_name,
:method, :card_code, :card_name, :e_at, :c_at, :receipt_id, :action)');
            $stmt->bindParam(':currentUser', $currentUser);
            $stmt->bindParam(':billing_key', $billing_key);

            $stmt->bindParam(':pg_name', $pg_name);
            $stmt->bindParam(':pg', $pg);
            $stmt->bindParam(':method_name', $method_name);
            $stmt->bindParam(':method', $method);

            $stmt->bindParam(':card_code', $card_code);
            $stmt->bindParam(':card_name', $card_name);
            $stmt->bindParam(':e_at', $e_at);
            $stmt->bindParam(':c_at', $c_at);
            $stmt->bindParam(':receipt_id', $receipt_id);
            $stmt->bindParam(':action', $action);


            if($stmt->execute()){
                error_log("새로운 카드 추가했습니다.");

                /* billing_key로 최근에 등록한 데이터 가져오기 -> 리사이클러뷰에서 목록 찾으려면, 이 부분 변경해야 함 */
                $sql ="select * from myCard where billing_key='$billing_key'";

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

                        'pg_name'=>$row["pg_name"],
                        'pg'=>$row["pg"],
                        'method_name'=>$row["method_name"],

                        'method'=>$row["method"],
                        'card_code'=>$row["card_code"],
                        'card_name'=>$row["card_name"],
                        'e_at'=>$row["e_at"],

                        'c_at'=>$row["c_at"],
                        'receipt_id'=>$row["receipt_id"],
                        'action'=>$row["action"],

                    );

                    //데이터 넣은 배열arrayMiddle 을 제이슨으로 보낼 데이터에 넣음
                    array_push ( $data, $arrayMiddle );

                }

                header('Content-Type: application/json; charset=utf8');
                $json = json_encode($data, JSON_PRETTY_PRINT+JSON_UNESCAPED_UNICODE);
                echo $json;




            }
            else{
                error_log("카드 추가에 실패했습니다.");

            }


        } catch(PDOException $e) {
            die("Database error: " . $e->getMessage());
        }

    }

}
?>



<?php
if (isset($errMSG)) {

    echo $errMSG;

}

if (isset($successMSG)) {
    echo $successMSG;

}

if( !$android ){

    echo "=====================<br>";
    echo "안드로이드 아닌 경우에는 다음을 출력한다. ";
    echo "<br>=====================<br>";

    ?>



    <html>
    <script>console.log("=== insert.php 파일의 html 태그 시작")</script>

    <body>
    <script>console.log("body 태그 시작")</script>


    <form action="<?php $_PHP_SELF ?>" method="POST">
        <script>console.log("form 태그 시작 : _PHP_SELF : <?php $_PHP_SELF ?>")</script>

        <!-- 안드로이드에서 입력할 데이터 로 세팅 함. -->

        email: <input type = "text" name = "email" />
        <script>console.log("form email:")</script>
        nickname: <input type = "text" name = "nickname" />
        <script>console.log("form nickname:")</script>
        phoneNum: <input type = "text" name = "phoneNum" />
        <script>console.log("form phoneNum:")</script>

        <script>console.log("form 에서 input 입력란 끝")</script>

        <input type = "submit" name = "submit" />
        <script>console.log("form 에서 input type submit 끝")</script>

    </form>
    <script>console.log("form 태그 끝")</script>

    </body>
    <script>console.log("body 태그 끝")</script>

    </html>
    <script>console.log("=== html 태그 끝")</script>


    <?php
}
?>

