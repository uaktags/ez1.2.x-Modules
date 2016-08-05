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
        requireLogin();
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
                $q = $this->db->execute('SELECT owned FROM <ezrpg>item_inventory WHERE item_id = '.$key. ' AND player_id='. $this->player->id);
                $owned = $this->db->fetch($q)->owned;
                if($owned == null)
                    $owned = 0;
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
                $totalCost += $this->getRefund($key) * $var;
                $q = $this->db->execute('SELECT owned FROM <ezrpg>item_inventory WHERE item_id = '.$key.'. and player_id=' . $this->player->id);
                $owned = $this->db->fetch($q)->owned;
                if($owned == null)
                    $owned = 0;
                if($owned !== $_POST[$key.'_owned'])
                    $this->setMessage("Don't try changing the owned amount", "warn");
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
}