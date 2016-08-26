<?php

defined('IN_EZRPG') or exit;

$hooks->add_hook('login_after', 'store_info');
$hooks->add_hook('register_after', 'store_info');

function hook_store_info($container, $args = 0)
{
    if(isset($_SERVER['HTTP_REFERER']))
        $ref=$_SERVER['HTTP_REFERER'];
    else
        $ref="No referer";
    if(isset($_SERVER['HTTP_USER_AGENT']))
        $agent=$_SERVER['HTTP_USER_AGENT'];
    else
        $agent="User Agent Unk";

    $ip=$_SERVER['REMOTE_ADDR'];
    if(isset($_SERVER['X-Forwarded-For']))
        $ip=$_SERVER['X-Forwarded-For'];
    elseif(isset($_SERVER['HTTP_X_FORWARDED_FOR'])){
        $ip=$_SERVER['HTTP_X_FORWARDED_FOR'];
    }
    $host_name = gethostbyaddr($_SERVER['REMOTE_ADDR']);
    if(is_numeric($args))
        $id=$args;
    else{
        $id=$args->id;
    }
    $strSQL = "INSERT INTO <ezrpg>players_tracking(tm, ref, agent, ip, host_name, pid)VALUES(curdate(),'$ref','$agent','$ip','$host_name','". $id."')";
    $container['db']->execute($strSQL);

    return $args;
}


?>