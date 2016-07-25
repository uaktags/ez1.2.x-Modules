<?php

defined('IN_EZRPG') or exit;

$hooks->add_hook('attack_upgrades', 'calculate_weapons');

function hook_calculate_weapons($container, $args = 0)
{
    $db = $container['db'];
    $pid = $args['player']->id;
    if ($args['type'] == 'defense') {
        $itemsowned = $db->execute('SELECT `player_id`, `item_id`, `owned`, items.`id`, items.`bonus`  FROM <ezrpg>item_inventory 
                            INNER JOIN <ezrpg>items as items WHERE `player_id`=' . $pid . ' AND items.`type`="defense" and items.`id` = `item_id`');
        $weapons = $db->fetchAll($itemsowned);
        $score = 0;
        foreach ($weapons as $weapon) {
            $score = $score + ($weapon->owned * $weapon->bonus);
        }
        $args = array_merge($args, array('weapons' => $score));

        return $args;
    }

    return 0;
}

?>