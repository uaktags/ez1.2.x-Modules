<?php

defined('IN_EZRPG') or exit;

$hooks->add_hook('register_after', 'start_citizens');

function hook_start_citizens($container, $args = 0)
{
    $container['db']->insert('<ezrpg>armies_trained', array('army_id'=>1, 'owned'=>20, 'player_id='.$args));
    return $args;
}

?>