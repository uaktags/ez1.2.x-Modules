<?php

defined('IN_EZRPG') or exit;

$hooks->add_hook('register_after', 'citizens');

function hook_citizens($container, $args = 0)
{
    //TODO get the id of untrained-citizens and provide it to new registers
    //$container['db']->update('<ezrpg>armies_trained', array('money'=>$gold, 'strength'=>$str, 'vitality'=>$vit, 'agility'=>$agi, 'dexterity'=>$dex), 'pid='.$args);
    return $args;
}

?>