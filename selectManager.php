<?php
/* 매니저 정보를 열람하는 서버 코드 */
include('dbcon.php');
$managerEmail = $_POST['managerEmail'];

error_log("매니저 정보를 열람하는 서버 코드");
error_log($managerEmail);

// 안드로이드 코드의 postParameters 변수에 적어준 이름을 가지고 값을 전달 받습니다.
if(!isset($errMSG)) {
    try {
        /* 서비스 상태가 매칭 대기 중인 Service 불러오기 */
        $sql = "select * from ManagerInfo where idStr='$managerEmail'";

        $stmt = $con->prepare($sql);
        $stmt->execute();

        //제이슨으로 보낼 배열을 생성하고
        $data = array();

        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {

            extract($row);

            $arrayMiddle = array(
                'uid' => $row["uid"],
                'nameStr' => $row["nameStr"],
                'phoneNumStr' => $row["phoneNumStr"],
                'addressStr' => $row["addressStr"],
                'desiredWorkAreaList' => $row["desiredWorkAreaList"],
                'profileImagePathStr' => $row["profileImagePathStr"],
                'professionalList' => $row["professionalList"],
                'activeInt' => $row["activeInt"],
            );

            //데이터 넣은 배열arrayMiddle 을 제이슨으로 보낼 데이터에 넣음
            array_push($data, $arrayMiddle);

        }

        header('Content-Type: application/json; charset=utf8');
        $json = json_encode($data, JSON_PRETTY_PRINT + JSON_UNESCAPED_UNICODE);
        echo $json;

    } catch (PDOException $e) {
        //die("Database error: " . $e->getMessage());
    }
}
?>

