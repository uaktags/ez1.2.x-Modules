<?php

defined('IN_EZRPG') or exit;

$hooks->add_hook('header', 'avatar');

function hook_avatar($container, $args = 0)
{
    if(LOGGED_IN)
    {
        if(isset($container['player']->username))
        {
            $filename = CUR_DIR . "/web/static/avatar/users/" . $container['player']->username . ".png";
            if (file_exists($filename)) {
                $avatar = 'data:image/image/png;base64,' . base64_encode(file_get_contents($filename));
                $container['tpl']->assign('avatar', "<img src='". $avatar ."' style=\"border: 1px solid #000000;\" width=150>");
            } else {
                $container['tpl']->assign('avatar', "<a href=\"index.php?mod=Avatar\">Create Your Avatar</a><br><br>");
            }
        }
    }
    return $args;
}

?>