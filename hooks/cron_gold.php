<?php

defined('IN_EZRPG') or exit;

$hooks->add_hook('cron_30min', 'cron_gold');

function hook_cron_gold($container, $args = 0)
{

    $query = "SELECT `<ezrpg>players`.id, `<ezrpg>players`.username, `<ezrpg>players_meta`.level from <ezrpg>players INNER JOIN <ezrpg>players_meta ON `<ezrpg>players`.id = `<ezrpg>players_meta`.pid";
    $res = $container['db']->execute($query);
    $players = $container['db']->fetchAll($res);
    foreach($players as $i){
        $q = "SELECT `<ezrpg>armies`.id, `<ezrpg>armies`.bonus, `<ezrpg>armies_trained`.owned from <ezrpg>armies INNER JOIN <ezrpg>armies_trained ON `<ezrpg>armies`.id = `<ezrpg>armies_trained`.army_id WHERE `<ezrpg>armies`.type='worker' and `<ezrpg>armies_trained`.player_id=".$i->id;
        $r = $container['db']->execute($q);
        $workers = $container['db']->fetchAll($r);
        $gold = $i->level * 10; //starting bonus for the halfhour
        foreach($workers as $k)
        {
            $gold += ($k->bonus * $k->owned);
        }
        echo $gold . " ". $i->username. "\n";
        $query = "UPDATE <ezrpg>players_meta SET money = money + ". $gold ." WHERE pid=".$i->id;
        $container['db']->execute($query);

    }

    return $args;
}

?>