<?php
/**
 * Created by PhpStorm.
 * User: Tim
 * Date: 7/9/2016
 * Time: 11:34 PM
 */
namespace ezRPG\Modules;

use ezRPG\lib\Base_Module;

//This file cannot be viewed, it must be included
defined('IN_EZRPG') or exit;

/*
  Class: Module_Items
  Handles the purchasing/equipping of Items
  Requires a table for Items
  Requires a table for Player-Item-Equipment
  (Optional) to make it work for armies
 */

class Module_Army extends Base_Module
{
    public function __construct($container)
    {
        parent::__construct($container);
    }

    /*
      Function: start
      Display the items page
     */
    public function start()
    {
        if(isset($_POST['buy']))
        {
            $this->buy();
            header('Location: index.php?mod=Army');
        }elseif(isset($_POST['sell']))
        {
            $this->sell();
            header('Location: index.php?mod=Army');
        }
        $this->loadView('army.tpl', 'Army');
    }

    public function buy()
    {
        $totalCost = 0;
        $items = [];
        foreach($_POST as $key=>$var)
        {
            if(is_numeric($key) && $var != 0)
            {
                $totalCost += $this->getCost($key) * $var;
                $q = $this->db->execute('SELECT owned FROM <ezrpg>armies_trained WHERE army_id = '.$key);
                $owned = $this->db->fetch($q)->owned;
                if($owned !== $_POST[$key.'_owned'])
                    $this->setMessage("Don't try changing the owned amount", "warn");

                $items['item_'.$key] = array('id'=> $key, 'owned'=> $owned, 'amount'=>$var);
            }
        }
        if($totalCost <= $this->player->money)
        {
            $this->processBuy($items, $totalCost);
        }else{
            $this->setMessage('You don\'t have enough money to make this purchase!', "warn");
        }
    }

    public function sell()
    {
        $totalCost = 0;
        $items = [];
        foreach($_POST as $key=>$var)
        {
            if(is_numeric($key) && $var != 0)
            {
                $q = $this->db->execute('SELECT owned FROM <ezrpg>armies_trained WHERE army_id = '.$key);
                $owned = $this->db->fetch($q)->owned;
                if($owned !== $_POST[$key.'_owned'])
                    $this->setMessage("Don't try changing the owned amount", "warn");
                $items['item_'.$key] = array('id'=> $key, 'owned'=> $owned, 'amount'=>$var);
            }
        }
        $this->processSell($items);
    }

    private function processBuy($items, $cost)
    {
        foreach($items as $k => $v){
            if($v['owned']){
                $this->db->update('<ezrpg>armies_trained', array('owned'=> $v['owned']+$v['amount']), "army_id={$v['id']} and player_id=" . $this->player->id);
            }else{
                $ins['army_id'] = $v['id'];
                $ins['player_id'] = $this->player->id;
                $ins['owned'] = $v['amount'];
                $this->db->insert('<ezrpg>armies_trained', $ins);
            }
        }
        $this->db->update('<ezrpg>players_meta', array('money'=>$this->player->money - $cost), "pid=". $this->player->id);
    }

    private function processSell($items)
    {
        foreach($items as $k => $v){
            if($v['owned']){
                $this->db->update('<ezrpg>armies_trained', array('owned'=> $v['owned']-$v['amount']), "army_id={$v['id']} and player_id=" . $this->player->id);
            }
        }
    }

    private function getCost($item){
        $q = $this->db->execute('SELECT cost from <ezrpg>armies WHERE id='. $item);
        return $this->db->fetch($q)->cost;
    }

    public function install($id=0){
        $this->db->execute("CREATE TABLE IF NOT EXISTS `<ezrpg>armies` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  `type` enum('civilian','worker','offense','defense','spyoffense','spydefense') NOT NULL,
  `bonus` int(11) NOT NULL,
  `cost` int(11) NOT NULL,
  `upgradeID` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;");
        $this->db->execute('CREATE TABLE IF NOT EXISTS `<ezrpg>armies_trained` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `player_id` int(11) NOT NULL,
  `army_id` int(11) NOT NULL,
  `owned` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;');
        $this->container['menu']->add_menu(1,'Training','Training', '', 'index.php?mod=Army', 0, $id);

        //TODO add settings for the citizens/starting stats.
    }

    public function uninstall(){
        $this->db->execute('DROP TABLE IF EXISTS `<ezrpg>armies_trained`; DROP TABLE IF EXISTS `<ezrpg>armies`;');
    }
}
