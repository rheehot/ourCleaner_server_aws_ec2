<?php 


    // 에러 출력을 위한 코드
    error_reporting(E_ALL); 
    ini_set('display_errors',1); 

    include('dbcon.php');
    //echo "<br>\nemailCheck.php 코드 시작함<br>\n";

    $email=isset($_POST['email'])? $_POST['email'] : '';
    //echo "post로 받은 이메일 확인 email : ". $email."<br>\n";

    if(empty($email)){

        if(!$android){

            $errMSG = "이름을 입력하세요.";
            
        }else{

        }
    }

    $android = strpos($_SERVER['HTTP_USER_AGENT'], "Android");



    if( (($_SERVER['REQUEST_METHOD'] == 'POST') && isset($_POST['submit'])) || $android ){

        // 안드로이드 코드의 postParameters 변수에 적어준 이름을 가지고 값을 전달 받습니다.

        if(!isset($errMSG)) // 이름과 나라 모두 입력이 되었다면 
        {
            try{
                // SQL문을 실행하여 데이터를 MySQL 서버의 person 테이블에 저장합니다. 
                // $stmt = $con->prepare('SELECT * FROM users_ge WHERE email =:email');
                // $stmt->bindParam(':email', $email);

                $sql ="select * from users_ge where email='$email'";

                if(!$android){
                    echo $sql;
                }else{
                }

                $stmt = $con->prepare($sql);
                $stmt -> execute();

                if($stmt->rowCount() == 0)
                {
                        $data = array(); 

                        if(!$android){

                            echo "'";
                            echo $email;
                            echo "'는 찾을 수 없습니다. ";
                            echo "배열data : ";
                            var_dump($data);
                            
                        }else{

                        }
                    
                        array_push($data, 
                        array('email'=>'not_exist',

                    ));
                        if(!$android){
                            echo "<pre>";
                            print_r($data);
                            echo "</pre>";
                        }else{
                            header('Content-Type: application/json; charset=utf8');
                            $json = json_encode(array("ourCleaner"=>$data), JSON_PRETTY_PRINT+JSON_UNESCAPED_UNICODE);
                            echo $json;
                        }

                }
                else
                {
                    $data = array(); 

                    // echo "배열data : ";
                     //var_dump($data);

                    if(!$android){
                        echo "배열data : ";
                        var_dump($data);
                    }else{

                    }

                    while($row=$stmt->fetch(PDO::FETCH_ASSOC))
                    {

                    if(!$android){
                        echo "====== 반목문 시작 <br>\n";
                    }else{

                    }
                        extract($row);
                
                        array_push($data, 
                            array('email'=>$row["email"],
    
                        ));
                    }

                    if(!$android){
                        echo "====== 반목문 탈출 <br>\n";
                    }else{

                    }

                    if(!$android){
                        echo "<pre>";
                        print_r($data);
                        echo "</pre>";
                    }else{
                        header('Content-Type: application/json; charset=utf8');
                        $json = json_encode(array("ourCleaner"=>$data), JSON_PRETTY_PRINT+JSON_UNESCAPED_UNICODE);
                        echo $json;
                    }

            

                }

            } catch(PDOException $e) {
                die("Database error: " . $e->getMessage()); 
            }



        }

    } ?>


    <?php 
    if (isset($errMSG)) 
    echo $errMSG;
    if (isset($successMSG)) 
    echo $successMSG;

    $android = strpos($_SERVER['HTTP_USER_AGENT'], "Android");
   
    if( !$android )
    {
?>
    <html>
       <body>

            <form action="<?php $_PHP_SELF ?>" method="POST">
            email: <input type = "text" name = "email" />
                <input type = "submit" name = "submit" />
            </form>
       
       </body>
    </html>

<?php 
    }
?>







