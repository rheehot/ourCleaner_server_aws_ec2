<!doctype html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, user-scalable=no">
    <meta name="Generator" content="EditPlus®">
    <meta name="Author" content="">
    <meta name="Keywords" content="">
    <meta name="Description" content="">
    <title>Firebase Cloud Messaging - 관리자페이지</title>

    <link rel="stylesheet" href="/styles.css">
</head>
<body>

<div class="container">
    <div class="outer">
        <div class="inner">

            <h2>Cloud Messaging</h2>

            <h4>푸시알림 보내기</h4>
            <form action="push_notification.php" method="post">
                <div class="messageWrapper">
                    <div class="textbox">
                        <label for="noti_title">제목</label>
                        <input type="text" id="noti_title" name="title"><br>
                        <textarea name="message" rows="3" cols="40" placeholder="메세지를 입력하세요"  required></textarea><br>
                    </div>
                </div>
                <input type="submit" name="submit" value="알림보내기" id="submitButton">
            </form>


            <div id="tokenList">
                <?php
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


                if(mysqli_connect_errno()){
                    echo "연결실패! ".mysqli_connect_error();
                }

                $query = "SELECT * FROM users";
                $result = mysqli_query($con, $query);

                echo '<br>
		<table border=1 background=#cc99ff width=90% align=center border-collapse style="table-layout: fixed">
			<thead>
				<th>토큰</th>
			</thead>';
                while($data = mysqli_fetch_array($result)){

                    echo '<tr><td align=left style="text-overflow:ellipsis; overflow:hidden; white-space:nowrap;">';
                    echo ($data['token']);
                    echo '</td></tr>';

                }
                echo '</table>';

                mysqli_close($con);
                ?>
            </div>

        </div>
    </div>
</div>

</body>
</html>

