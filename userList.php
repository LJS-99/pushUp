<?php
//db 연결하기
//localhost = c:xampp/htdocs폴더를 주소화 한 것이라고 생각해야할듯?
$con = mysqli_connect('localhost', 'root', '1234', 'userlist');

if (!$con) {
    die('서버와의 연결이 실패되었습니다. : ' . mysqli_connect_error());
}
else
{
echo '서버와의 연결이 성공되었습니다.';
}
?>