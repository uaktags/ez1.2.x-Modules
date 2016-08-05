<?php

defined('IN_EZRPG') or exit;

$hooks->add_hook('cron_daily', 'cron_civilians');

function hook_cron_civilians($container, $args = 0)
{
    $query = "SELECT `<ezrpg>players`.id, `<ezrpg>players_meta`.level from <ezrpg>players INNER JOIN <ezrpg>players_meta ON `<ezrpg>players`.id = `<ezrpg>players_meta`.pid";
    $res = $container['db']->execute($query);
    $players = $container['db']->fetchAll($res);
    foreach($players as $i){
        $q = "SELECT owned from <ezrpg>armies_trained WHERE army_id=1 and player_id=".$i->id;
        $r = $container['db']->execute($q);
        $count = $container['db']->fetch($r);
        if($count !== false){
            $query = "UPDATE <ezrpg>armies_trained SET owned = owned + ". $i->level ." WHERE army_id = 1 and player_id=".$i->id;
            $container['db']->execute($query);
        }else{
            $container['db']->execute("INSERT INTO <ezrpg>armies_trained(`player_id`, `army_id`, `owned`) VALUES (". $i->id . ", 1, 1)");
        }
    }

    return $args;
}

?>