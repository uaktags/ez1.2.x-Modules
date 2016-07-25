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

class Module_Items extends Base_Module
{
    public function __construct($container, $menu)
    {
        parent::__construct($container, $menu);
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
            header('Location: index.php?mod=Items');
        }elseif(isset($_POST['sell']))
        {
            $this->sell();
            header('Location: index.php?mod=Items');
        }
        $this->loadView('items.tpl', 'Items');
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
                $owned = (int) $_POST[$key . '_owned'];
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
                $totalCost += $this->getRefund($key) * $var;
                $owned = (int) $_POST[$key . '_owned'];
                $items['item_'.$key] = array('id'=> $key, 'owned'=> $owned, 'amount'=>$var);
            }
        }
        $this->processSell($items, $totalCost);
    }

    private function processBuy($items, $cost)
    {
        foreach($items as $k => $v){
            if($v['owned']){
                $this->db->update('<ezrpg>item_inventory', array('owned'=> $v['owned']+$v['amount']), "item_id={$v['id']} and player_id=" . $this->player->id);
            }else{
                $ins['item_id'] = $v['id'];
                $ins['player_id'] = $this->player->id;
                $ins['owned'] = $v['amount'];
                $this->db->insert('<ezrpg>item_inventory', $ins);
            }
        }
        $this->db->update('<ezrpg>players_meta', array('money'=>$this->player->money - $cost), "pid=". $this->player->id);
    }

    private function processSell($items, $cost)
    {
        foreach($items as $k => $v){
            if($v['owned']){
                $this->db->update('<ezrpg>item_inventory', array('owned'=> $v['owned']-$v['amount']), "item_id={$v['id']} and player_id=" . $this->player->id);
            }
        }
        $this->db->update('<ezrpg>players_meta', array('money'=>$this->player->money + $cost), "pid=". $this->player->id);
    }

    private function getCost($item){
        $q = $this->db->execute('SELECT cost from <ezrpg>items WHERE id='. $item);
        return $this->db->fetch($q)->cost;
    }

    private function getRefund($item){
        $q = $this->db->execute('SELECT sell from <ezrpg>items WHERE id='. $item);
        return $this->db->fetch($q)->sell;
    }

    public function install($id = 0)
    {
        $this->db->execute('CREATE TABLE IF NOT EXISTS `<ezrpg>items` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  `type` enum(\'offense\',\'defense\',\'spyoffense\',\'spydefense\') NOT NULL,
  `body` enum(\'Weapon\',\'Helm\',\'Armor\',\'Boots\',\'Bracers\',\'Shield\') NOT NULL,
  `cost` int(11) NOT NULL DEFAULT \'100\',
  `bonus` int(11) NOT NULL,
  `sell` int(11) NOT NULL,
  `armory` int(11) NOT NULL DEFAULT \'0\',
  UNIQUE KEY `id` (`id`),
  KEY `id_2` (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;');
        $this->db->execute('CREATE TABLE IF NOT EXISTS `<ezrpg>item_inventory` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `player_id` int(11) NOT NULL,
  `item_id` int(11) NOT NULL,
  `owned` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;');
        $this->db->execute("INSERT INTO `<ezrpg>items` (`id`, `name`, `type`, `body`, `cost`, `bonus`, `sell`, `armory`) VALUES
(1, 'Daggers', 'offense', 'Weapon', 12500, 25, 3125, 0),
(2, 'Padded Hood', 'offense', 'Helm', 3000, 6, 750, 0),
(3, 'Padded Armor', 'offense', 'Armor', 9500, 19, 2375, 0),
(4, 'Padded Boots', 'offense', 'Boots', 3000, 6, 750, 0),
(5, 'Small Wooden Sheild', 'offense', 'Shield', 6000, 12, 1500, 0),
(6, 'Sling', 'defense', 'Weapon', 12500, 25, 3125, 0),
(7, 'Padded Hood', 'defense', 'Helm', 3000, 6, 750, 0),
(8, 'Padded Armor', 'defense', 'Armor', 9500, 19, 2375, 0),
(9, 'Padded Boots', 'defense', 'Boots', 3000, 6, 750, 0),
(10, 'Padded Bracers', 'defense', 'Bracers', 1500, 3, 375, 0),
(11, 'Padded Bracers', 'offense', 'Bracers', 1500, 3, 375, 0),
(12, 'Small Wooded Shield', 'defense', 'Shield', 6000, 12, 1500, 0),
(13, 'Sling', 'spyoffense', 'Weapon', 12500, 25, 3125, 0),
(14, 'Sling', 'spydefense', 'Weapon', 12500, 25, 3125, 0),
(15, 'Cloth Cap', 'spyoffense', 'Helm', 3000, 6, 750, 0),
(16, 'Padded Hood', 'spydefense', 'Helm', 3000, 6, 750, 0),
(17, 'Dark Cloth Armor', 'spyoffense', 'Armor', 9500, 19, 2375, 0),
(18, 'Padded Armor', 'spydefense', 'Armor', 9500, 19, 2375, 0),
(19, 'Dark Cloth Boots', 'spyoffense', 'Boots', 3000, 6, 750, 0),
(20, 'Padded Boots', 'spydefense', 'Boots', 3000, 6, 750, 0),
(21, 'Dark Cloth Bracers', 'spyoffense', 'Bracers', 1500, 3, 375, 0),
(22, 'Padded Bracers', 'spydefense', 'Bracers', 1500, 3, 375, 0),
(23, 'Rope', 'spyoffense', 'Shield', 6000, 12, 1500, 0),
(24, 'Small Wooden Shield', 'spydefense', 'Shield', 6000, 12, 1500, 0);
");
        $this->container['menu']->add_menu(1,'Items','Inventory', '', 'index.php?mod=Items', 0, $id);
        return true;
    }

    public function uninstall()
    {
        $this->db->execute('DROP TABLE IF EXISTS `<ezrpg>item_inventory`; DROP TABLE IF EXISTS `<ezrpg>items`;');
    }
}
