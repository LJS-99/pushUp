<?php

include('userList.php');

if(isset($_POST['id']) && isset($_POST['pwd']) && isset($_POST['repwd']) &&isset($_POST['uname']))
{
    // 보안을 더욱 강화 ( 시큐어코딩, 보안코딩 ) 해쉬랑 비슷하다고 생각하면 될 듯?
    $id = mysqli_real_escape_string($con, $_POST['id']);
    $pwd = mysqli_real_escape_string($con, $_POST['pwd']);
    $repwd = mysqli_real_escape_string($con, $_POST['repwd']);
    $uname = mysqli_real_escape_string($con, $_POST['uname']);
    $identify = mysqli_real_escape_string($con, $_POST['identify']);

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
    else if(empty($repwd))
    {
    echo "<script> 
        alert('비밀번호를 확인해주세요.'); 
        history.back();
        </script>";
    }
    else if(empty($uname))
    {
    echo "<script> 
        alert('닉네임을 설정해주세요.'); 
        history.back();
        </script>";
    }
    else
    {
        // 비밀번호 해쉬 암호화
        $pwd = password_hash($pwd, PASSWORD_DEFAULT);

        // 아이디 또는 닉네임의 중복이 있을경우
        $sql_same = "SELECT * FROM info where info_id = '$id' and info_uname = '$uname'";
        $order = mysqli_query($con, $sql_same);

        if(mysqli_num_rows($order) > 0)
        {
            echo"<script>
            alert('이미 존재하는 아이디 또는 닉네임 입니다.');
            history.back();
            </script>";
        }
        else
        {

            // 아이디,비밀번호,닉네임을 데이터베이스에 저장
            $sql_save = "insert into info(info_id,info_pwd,info_uname) value('$id','$pwd','$uname')";
            $result = mysqli_query($con, $sql_save);

            if($result)
            {
            echo"<script>
            alert('회원가입이 완료되었습니다.');
            location.replace('/webSite.html');
            </script>";
            }
            else
            {
            echo"<script>
            alert('회원가입의 실패하였습니다.');
            alert('다시 시도하여 주세요.');
            location.replace('/webSite.html');
            </script>";
            }
        }
    }
}
?>