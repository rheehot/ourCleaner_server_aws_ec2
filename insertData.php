<?php
// 에러 출력을 위한 코드
error_reporting(E_ALL);
ini_set('display_errors',1);

//디비 연결을 위한 코드
include('dbcon.php');


$android = strpos($_SERVER['HTTP_USER_AGENT'], "Android");

if( ($_SERVER['REQUEST_METHOD'] == 'POST') && isset($_POST['submit']) || $android){


    $email=$_POST['email'];
    $nickname=$_POST['nickname'];
    $phoneNum=$_POST['phoneNum'];

    echo "email : ".$email;
    echo "nickname : ".$nickname;
    echo "phoneNum : ".$phoneNum;



    if(!isset($errMSG)){

        try{
        $stmt = $con->prepare('INSERT INTO users_ge(email, nickname, phoneNum) VALUES(:email, :nickname, :phoneNum)');
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':nickname', $nickname);
        $stmt->bindParam(':phoneNum', $phoneNum);


        if($stmt->execute()){
            $successMSG = "새로운 사용자를 추가했습니다.";


        }
        else{
            $errMSG = "사용자 추가 에러";
            echo "사용자 추가 에러";
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

