<?php
include('dbcon.php');

/* 매니저용 로그인을 위한 문서 */

$UserEmail = $_POST["userID"];
$UserPwd = $_POST["userPassword"];

error_log($UserEmail);
error_log($UserPwd);

//$statement = mysqli_prepare($con, "SELECT * FROM ManagerInfo WHERE idStr = ? AND passwordStr = ?");
try{
    /* 서비스 상태가 매칭 대기 중인 Service 불러오기 */

    if($UserPwd=="auto"){
        //자동로그인인 경우,
        $sql ="select * from ManagerInfo where idStr='$UserEmail'";

    }else{
        $sql ="select * from ManagerInfo where idStr='$UserEmail' and passwordStr= '$UserPwd'";
    }

    $stmt = $con->prepare($sql);
    $stmt -> execute();

    //제이슨으로 보낼 배열을 생성하고
    $data = array();
//    $response["success"] = true;
    while($row=$stmt->fetch(PDO::FETCH_ASSOC)){

        extract($row);
        $arrayMiddle = array (
            'success'=>true,
            'nameStr'=>$row["nameStr"],
            'idStr'=>$row["idStr"],
            'passwordStr'=>$row["passwordStr"],
            'phoneNumStr'=>$row["phoneNumStr"],
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
?>