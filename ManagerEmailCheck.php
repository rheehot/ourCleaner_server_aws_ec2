<?php
// 에러 출력을 위한 코드
error_reporting(E_ALL);
ini_set('display_errors',1);

include('dbcon.php');

$UserEmail=$_POST['UserEmail'];

if(!isset($errMSG)) {
    try{
        $sql ="select * from ManagerInfo where idStr='$UserEmail'";

        $stmt = $con->prepare($sql);
        $stmt -> execute();
        $data = array();
        $data["success"] = true;

        /* 체크할 때는 boolean 이용 */
        while($row=$stmt->fetch(PDO::FETCH_ASSOC)) {
            extract($row);
            $data["success"] = false;
            $data["UserEmail"] = $row["idStr"];

//            array_push($data,
//                array('success'=>false,
//                    'idStr'=>$row["idStr"],
//                    ));
        }

        header('Content-Type: application/json; charset=utf8');
        echo json_encode($data, JSON_PRETTY_PRINT+JSON_UNESCAPED_UNICODE);

//        if($stmt->rowCount() == 0) {
//
//            array_push($data,
//                array('email'=>'not_exist',));
//
//            header('Content-Type: application/json; charset=utf8');
//            $json = json_encode(array("ourCleaner"=>$data), JSON_PRETTY_PRINT+JSON_UNESCAPED_UNICODE);
//            echo $json;
//        }else {
//
//            while($row=$stmt->fetch(PDO::FETCH_ASSOC)) {
//                extract($row);
//                array_push($data,
//                    array('email'=>$row["email"],
//
//                    ));
//            }
//
//            header('Content-Type: application/json; charset=utf8');
//            $json = json_encode(array("ourCleaner"=>$data), JSON_PRETTY_PRINT+JSON_UNESCAPED_UNICODE);
//            echo $json;
//
//        }

    } catch(PDOException $e) {
        die("Database error: " . $e->getMessage());
    }




} ?>
?>