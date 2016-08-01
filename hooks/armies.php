<?php

defined('IN_EZRPG') or exit;

$hooks->add_hook('armies', 'troops');

function hook_troops($container, $args = 0)
{
    $query = "SELECT * FROM <ezrpg>armies";
    $con = $container['db']->execute($query);
    $allItems = array();
    while($m = $container['db']->fetch($con)) {
        $allItems[$m->name . '_'. $m->id] = array('name' => $m->name, 'type' => $m->type, 'id'=> $m->id, 'cost'=>$m->cost, 'bonus'=>$m->bonus, 'armory'=>$m->upgradeID);
    }
    $q2 = "SELECT  `<ezrpg>armies`.name,  `<ezrpg>armies_trained`.* 
FROM  `<ezrpg>armies` INNER JOIN  `<ezrpg>armies_trained` ON  `<ezrpg>armies`.id =  `<ezrpg>armies_trained`.army_id
WHERE  `<ezrpg>armies_trained`.player_id =". $container['player']->id;
    $con = $container['db']->execute($q2);
    $items = array();
    while($m = $container['db']->fetch($con)) {
        $items[$m->name . '_'. $m->army_id] = array('owned'=>$m->owned);
    }
    $inventory = array_merge_recursive($allItems, $items);
    $args = array_merge($args, array('troops' => $inventory));

    return $args;
}

?>