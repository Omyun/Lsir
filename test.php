<?php
/*
$password 的参数为用户输入的密码存入redis中，随着cookie、token的释放一起删除。
这个方案可以防止数据库在被脱裤盗取数据的时候密码泄露问题。

$返回加密的电话号码存入数据库，电话字段即可。只有用户在线状态redis中才有密码，一旦离线数据库的手机号将很难还原为手机明文
*/


function pwd_en($password,$phone){
    $A_Z = array('A','B','C','D','E','F','G','H','I','J','K','L','M','N','O','P','Q','R','S','T','U','V','W','X','Y','Z');
    #创建加密后的密码变量
    $len = strlen($password);
    #让$A_Z向前滚动$len次 
    for($i=0;$i<$len;$i++){
        $A_Z[] = array_shift($A_Z);
    }
    $lit =array(1=>$A_Z[0],2=>$A_Z[1],3=>$A_Z[2],4=>$A_Z[3],5=>$A_Z[4],6=>$A_Z[5],7=>$A_Z[6],8=>$A_Z[7],9=>$A_Z[8],10=>$A_Z[9],11=>$A_Z[10],12=>$A_Z[11],13=>$A_Z[12],14=>$A_Z[13],15=>$A_Z[14],16=>$A_Z[15],17=>$A_Z[16],18=>$A_Z[17],19=>$A_Z[18],20=>$A_Z[19],21=>$A_Z[20],22=>$A_Z[21],23=>$A_Z[22],24=>$A_Z[23],25=>$A_Z[24],26=>$A_Z[25]);

    #加密
    $pwd_en = '';
    $phone_list = str_split($phone);
    foreach($phone_list as $v){
        $pwd_en = $pwd_en.$lit[$v];
    }
    return $pwd_en;
   
}


function pwd_de($password,$phone)
{
    $A_Z = array('A','B','C','D','E','F','G','H','I','J','K','L','M','N','O','P','Q','R','S','T','U','V','W','X','Y','Z');
    $len = strlen($password);
    #让$A_Z向前滚动$len次 
    for($i=0;$i<$len;$i++){
        $A_Z[] = array_shift($A_Z);
    }
    $max = array($A_Z[0]=>1,$A_Z[1]=>2,$A_Z[2]=>3,$A_Z[3]=>4,$A_Z[4]=>5,$A_Z[5]=>6,$A_Z[6]=>7,$A_Z[7]=>8,$A_Z[8]=>9,$A_Z[9]=>10,$A_Z[10]=>11,$A_Z[11]=>12,$A_Z[12]=>13,$A_Z[13]=>14,$A_Z[14]=>15,$A_Z[15]=>16,$A_Z[16]=>17,$A_Z[17]=>18,$A_Z[18]=>19,$A_Z[19]=>20,$A_Z[20]=>21,$A_Z[21]=>22,$A_Z[22]=>23,$A_Z[23]=>24,$A_Z[24]=>25,$A_Z[25]=>26);
    #解密
    $pwd_list = str_split($phone);
    $pwd_de = '';
    foreach($pwd_list as $v){
        $pwd_de = $pwd_de.$max[$v];
    }
    return $pwd_de;
}
$enp = pwd_en("Aa123","12345678901");
echo $enp."<br>";
$dep = pwd_de("Aa123",$enp);
echo $dep;


?>