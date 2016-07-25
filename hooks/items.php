<?php

defined('IN_EZRPG') or exit;

$hooks->add_hook('items', 'items');

function hook_items($container, $args = 0)
{
    $query = "SELECT * FROM <ezrpg>items";
    $con = $container['db']->execute($query);
    $allItems = array();
    while($m = $container['db']->fetch($con)) {
        $allItems[$m->name . '_'. $m->id] = array('name' => $m->name, 'type' => $m->type, 'id'=> $m->id, 'body'=>$m->body, 'cost'=>$m->cost, 'bonus'=>$m->bonus, 'sell'=>$m->sell, 'armory'=>$m->armory);
    }
    $q2 = "SELECT  `<ezrpg>items`.name,  `<ezrpg>item_inventory`.* 
FROM  `<ezrpg>items` INNER JOIN  `<ezrpg>item_inventory` ON  `<ezrpg>items`.id =  `<ezrpg>item_inventory`.item_id
WHERE  `<ezrpg>item_inventory`.player_id =". $container['player']->id;
    $con = $container['db']->execute($q2);
    $items = array();
    while($m = $container['db']->fetch($con)) {
        $items[$m->name . '_'. $m->item_id] = array('owned'=>$m->owned);
    }
    $inventory = array_merge_recursive($allItems, $items);
    $args = array_merge($args, array('items' => $inventory));

    return $args;
}

?>