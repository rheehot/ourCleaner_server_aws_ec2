<?php

$api_version = 1.0;

$start = microtime(true);

error_log("도큐먼트 루트");
error_log($_SERVER['DOCUMENT_ROOT']);

$dir = $_SERVER['DOCUMENT_ROOT']."/uploads/";

error_log("디렉토리");
error_log($dir);

if( isset($_FILES['image']['name']) ) {
//    $file_name = time().basename($_FILES['image']['name']);
    $file_name = $_FILES['image']['name'];

    error_log("파일 이름");
    error_log($file_name);

    $extension = strtolower(pathinfo($file_name,PATHINFO_EXTENSION));
    error_log("파일 형식");
    error_log($extension);

    if ($extension == 'png' || $extension == 'jpg' || $extension == 'jpeg') {

        error_log("파일 형식 요건 충족함 파일 크기 : ");
        error_log($_FILES["image"]["size"]);

        if($_FILES["image"]["size"] < 20000000){

            $file = $dir.$file_name;

            error_log("파일 크기가 4000001 보다 작음 if문 안으로 들어옴");
            error_log($file);

            if( move_uploaded_file($_FILES['image']['tmp_name'], $file) ) {
                $arr = array(
                    'status'=>1,
                    'message'=>"File Uploaded",
                    'file_name'=>$file_name
                );
            }else{
                $arr = array(
                    'status'=>0,
                    'error'=>"Something Went Wrong Please Retry",
                    'file_name'=>$file_name
                );
            }
        }else{

            error_log("파일 크기 너무 큼");
            error_log($extension);

            $arr = array(
                'status'=>0,
                'error'=>"File size cant exceed 4 MB"
            );
        }
    }else{

        error_log("파일 형식 확인 요망");

        $arr = array(
            'status'=>0,
            'error'=>"Only .png, .jpg and .jpeg format are accepted"
        );
    }
}else{

    error_log("파일 들어오지도 않음");

    $arr = array(
        'status'=>1,
        'message'=>"Please try Post Method"
    );
}

$arr[ 'api' ] = $api_version;
$arr[ 'time' ] = ( microtime(true) - $start );

print_r( json_encode( $arr ) );

?>
