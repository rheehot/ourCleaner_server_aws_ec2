<?php

//이게 핵심이다.
function send_notification ($tokens, $data)
{
    $url = 'https://fcm.googleapis.com/fcm/send';
    //어떤 형태의 data/notification payload를 사용할것인지에 따라 폰에서 알림의 방식이 달라 질 수 있다.
    $msg = array(
        'title'	=> $data["title"],
        'body' 	=> $data["body"]
    );

    //data payload로 보내서 앱이 백그라운드이든 포그라운드이든 무조건 알림이 떠도록 하자.
    $fields = array(
        'registration_ids'		=> $tokens,
        'data'	=> $msg
    );

    //구글키는 config.php에 저장되어 있다.
    $headers = array(
        'Authorization:key =' . GOOGLE_API_KEY,
        'Content-Type: application/json'
    );

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt ($ch, CURLOPT_SSL_VERIFYHOST, 0);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));
    $result = curl_exec($ch);
    if ($result === FALSE) {
        die('Curl failed: ' . curl_error($ch));
    }
    curl_close($ch);
    return $result;
}


//데이터베이스 저장된 토큰을 array변수에 모두 담는다.
$host = 'ec2-52-79-179-66.ap-northeast-2.compute.amazonaws.com';
$username = 'aileen'; # MySQL 계정 아이디
$password = '!Nhskskp30415'; # MySQL 계정 패스워드
$dbname = 'fcm'; # DATABASE 이름

define("GOOGLE_API_KEY", "AAAAyzEIzdE:APA91bEfyfUBXZ7SE3d6xQ4mYy6cJQUkSHvGhYq5MtmxjYi3zDWbWZm5fHmhoiMZ1jhwaG9x7IQ9qRrWq08hQN6QBFw7XL8n8fo1Pm0r1qSDSh8P1Fgjuo3oar90DFu9m4vUvaVeJ7Pe");

$con = mysqli_connect($host,$username,$password,$dbname);

/* 한글 깨짐 방지 위함 */
mysqli_query($con, "set session character_set_connection=utf8;");
mysqli_query($con, "set session character_set_results=utf8;");
mysqli_query($con, "set session character_set_client=utf8;");



$sql = "Select token From users";

$result = mysqli_query($con,$sql);
$tokens = array();
if(mysqli_num_rows($result) > 0 ){
    //DB에서 가져온 토큰을 모두 $tokens에 담아 둔다.
    while ($row = mysqli_fetch_assoc($result)) {
        $tokens[] = $row["token"];
    }
}
mysqli_close($con);

//관리자페이지 폼에서 입력한 내용들을 가져와 정리한다.
$mTitle = $_POST['title'];
if($mTitle == '') $mtitle="공지사항입니다.";

$mMessage = $_POST['message'];
//알림할 내용들을 취합해서 $data에 모두 담는다. 프로젝트 의도에 따라 다른게 더 있을 수 있다.

$inputData = array("title" => $mTitle, "body" => $mMessage);

//마지막에 알림을 보내는 함수를 실행하고 그 결과를 화면에 출력해 준다.
$result = send_notification($tokens, $inputData);

echo $result;

echo '
		<br><br>
		<button><a href="/fcm/">돌아가기</a></button>
	';

?>
