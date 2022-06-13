<?php
session_start();

include('userList.php');

if(isset($_POST['id']) && isset($_POST['pwd']))
{
    // 보안을 더욱 강화 ( 시큐어코딩, 보안코딩 ) 해쉬랑 비슷하다고 생각하면 될 듯?
    $id = mysqli_real_escape_string($con, $_POST['id']);
    $pwd = mysqli_real_escape_string($con, $_POST['pwd']);
    $identify = mysqli_real_escape_string($con, $_POST['identify']);

    // 해당 라인이 작성되지 않았을 경우 에러를 발생시킨다.
    if(empty($id))
    {
    echo "<script> 
        alert('아이디를 입력해주세요'); 
        history.back();
        </script>";
    }
    else if(empty($pwd))
    {
    echo "<script> 
        alert('비밀번호를 입력해주세요.'); 
        history.back();
        </script>";
    }
    else
    {
        // info에서 id의 값이 사용자가 입력한 id값과 같은 값이 있는지를 확인
        $select = "select * from info where info_id = '$id'";
        // 데이터베이스 con에서 select_id를 수행해 이를 행하는건 변수 result야 라는 뜻
        $result = mysqli_query($con, $select);

        // id의 값이 같은게 있냐라는 뜻
        if(mysqli_num_rows($result) === 1)
        {
            // 데이터베이스에서 해당 항목의 가로열을 다 가져오는 함수
            $row = mysqli_fetch_assoc($result);
            // 데이터베이스에 암호화 된 패스워드를 가져와서 hash변수에 넣어줬다
            $hash = $row['info_pwd'];
            // 패스워드 값과 hash 값을 비교해준다
            if(password_verify($pwd,$hash))
            {

                $_SESSION['info_id'] = $row['info_id'];
                $_SESSION['info_pwd'] = $row['info_pwd'];
                $_SESSION['info_repwd'] = $row['info_repwd'];
                $_SESSION['info_uname'] = $row['info_uname'];
                $_SESSION['info_count'] = $row['info_count'];
                $_SESSION['idenitify'] = $row['idenitify'];

                echo"<script>
                alert('로그인에 성공하였습니다.');
                location.replace('/webLogInSite.php');
                </script>";

                $count = "UPDATE info set info_count = info_count + 1 where '$id'";
                $countDown = "UPDATE info set identify = identify - 1 where '$id'";

                if(empty($identify)){
                    $count;
                    $countDown;
                }
                else{
                    echo  "<script> 
                    alert('에러가 발생했습니다.'); 
                    history.back();
                    </script>";
                }
            }
            else
            {
                echo"<script>
                alert('로그인에 실패하였습니다.');
                history.back();
                </script>";
            }
            // 해당 코드에 사용해도 되는 함수 설명
            // 배열을 출력해주는 함수 -> echo 함수는 배열을 출력 X
            //print_r($row);
            // 한 줄 띄어쓰는 php의 함수로 쓰인다.
            // echo '<br>';
            // print_r과 다르게 값들의 타입까지 알려주는 함수로 똑같이 배열을 출력해준다.
            // var_dump($row);
        }
        else
        {
            echo"<script>
            alert('ID가 잘못 입력되었습니다.');
            history.back();
            </script>";
        }
    }
}
?>