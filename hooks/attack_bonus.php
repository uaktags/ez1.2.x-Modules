<?php

defined('IN_EZRPG') or exit;

$hooks->add_hook('attack', 'calculate_weapons');

function hook_calculate_weapons($container, $args = 0)
{
    $q = 'SELECT `player_id`, `item_id`, `owned`, items.`id`, items.`bonus`, items.`type`  FROM <ezrpg>item_inventory 
                            INNER JOIN <ezrpg>items as items WHERE `player_id`=' . $args->id . ' and items.`id` = `item_id`';
    $con = $container['db']->execute($q);
    $items = array();
    while($m = $container['db']->fetch($con)) {
        if(isset($items[$m->type]))
            $items[$m->type] = array('amount'=>($m->owned * $m->bonus) + $items[$m->type]['amount']);
        else
            $items[$m->type] = array('amount'=>$m->owned * $m->bonus);
    }
    $args = array_merge((array)$args, array('weapons' => $items));

    return (object) $args;

}

?>