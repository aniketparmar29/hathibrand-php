<?php   
        session_start();
        $conn=mysqli_connect("89.117.188.52","u303467217_aniket_op","close777@A","u303467217_hathibrand_op");
        if($conn){
                echo "connection successfull";
        }else{
                echo "no connection";
        }
?>