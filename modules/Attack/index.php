<?php
/**
 * Created by PhpStorm.
 * User: Tim
 * Date: 6/29/2016
 * Time: 9:05 PM
 */

namespace ezRPG\Modules;
use \ezRPG\lib\Base_Module;
//This file cannot be viewed, it must be included
defined('IN_EZRPG') or exit;
/*
  Class: Attack_Module
  Preform the Attacks
  Requires an AttackLog Table
 */
class Module_Attack extends Base_Module
{
    public function __construct($container)
    {
        parent::__construct($container);
    }
    /*
          Function: start
          Displays the members list page.
         */
    public function start()
    {
        //Require login
        requireLogin();
        if(isset($_SERVER["HTTP_REFERER"]) && strpos($_SERVER["HTTP_REFERER"], 'mod=Members')){
            $player = $this->player;
            $numquery = $this->db->execute('SELECT count(*) as num FROM `<ezrpg>attack_log` WHERE alog_attacker = ? AND alog_defender = ? AND alog_timestamp > NOW() - INTERVAL 24 HOUR', array($player->id, $_GET['p']));
            $numofattacks = $this->db->fetch($numquery);
            if($player->energy !=0 && $player->hp !=0)
            {
                if($numofattacks->num < 100) {
                    $Attacker = $this->getAttack($player);
                    $query = $this->db->execute('SELECT * FROM `<ezrpg>players` INNER JOIN `<ezrpg>players_meta` ON `pid` = `<ezrpg>players`.`id` WHERE `<ezrpg>players`.`id` = ?',
                        array($_GET['p']));
                    $defquery = $this->db->fetch($query);
                    $Defender = $this->getDefense($defquery);
                    $results = "Attacker: " . $Attacker['Score'] . " ( Str=". $Attacker['Strength'] ." Agil=". $Attacker['Agility'] ." Multi=".$Attacker['Multi']. " ) ".
                        "<br/> Defender: " . $Defender['Score'] . " (Str=". $Defender['Strength']." * (Agil+Vit=" . $Defender['Agility']." + ". $Defender['Vitality']. ") * Multi=". $Defender['Multi'] ." )";
                    $insert = array();
                    $insert['alog_victory'] = $victor = "0";
                    $insert['alog_attacker'] = $player->id;
                    $insert['alog_defender'] = $defquery->id;
                    if ($Attacker['Score'] > $Defender['Score']) {
                        $insert['alog_victory'] = $victor = "1";
                    }
                    $this->db->insert('<ezrpg>attack_log', $insert);
                    $AttackDamage=$this->getdamage($player, $victor);
                    $AttackNewHP = $afields['hp'] = $AttackDamage['HP'];
                    $AttackNewXP = $afields['exp'] = $AttackDamage['XP'];
                    $afields['energy'] = $player->energy - 1;
                    $DefenseDamage = $this->getDamage($defquery, ($victor?'0':'1'));
                    $DefenseNewHP = $dfields['hp'] = $DefenseDamage['HP'];
                    $DefenseNewXP = $dfields['exp'] = $DefenseDamage['XP'];
                    $results = ("Battle Start " . "<br />".
                        "Attack Stats:" . $Attacker['HP'] . "HP " . $Attacker['XP'] . "XP" . "<br />".
                        "Defender Stats:" . $Defender['HP'] . "HP" . $Defender['XP'] . "XP" . "<br />".
                        "Attacker Results: " . $AttackNewHP . "HP " . $AttackNewXP . "XP". "<br />".
                        "Defender Results: " . $DefenseNewHP . "HP " . $DefenseNewXP . "XP" . "<br />".
                        "Attacker: ".$AttackDamage['Score'] . "  Defender: ". $DefenseDamage['Score'] . "  Winner: " . ($victor?'Attacker':'Defender'));
                    $this->db->update('<ezrpg>players_meta', $afields, "ID = '" . $this->player->id . "'");
                    $this->db->update('<ezrpg>players_meta', $dfields, "ID = '" . $defquery->id . "'");
                    addLog(intval($defquery->id), "You\'ve been attacked by ". $player->username . " and you " . ($victor?'Lost':'Won'), $this->container['db']);
                    killPlayerCache($player->id);
                    killPlayerCache($defquery->id);
                    $this->setMessage("You have " . ($insert['alog_victory'] == "1" ? 'won' : 'lost') . " your attack against " . $defquery->username);
                    $this->setMessage($results);
                    header('Location: index.php?mod=Members');
                }else{
                    $this->setMessage("You've already attacked this player 3x today!", 'warn');
                    header('Location: index.php?mod=Members');
                }
            }elseif($player->energy == 0){
                $this->setMessage("You don't have any Energy to attack!", 'warn');
                header('Location: index.php?mod=Members');
            }else{
                $this->setMessage("You don't have any HP to attack!", 'warn');
                header('Location: index.php?mod=Members');
            }
        }else{
            $this->setMessage("You can't access this page that way!", 'warn');
            header('Location: index.php?mod=Members');
        }
    }

    public function install($id=0){
        $this->db->execute('CREATE TABLE IF NOT EXISTS `<ezrpg>attack_log` (
  `alog_id` int(11) NOT NULL AUTO_INCREMENT,
  `alog_attacker` int(11) NOT NULL,
  `alog_defender` int(11) NOT NULL,
  `alog_victory` int(11) NOT NULL,
  `alog_timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`alog_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8');
    }

    public function uninstall(){
        $this->db->execute('DROP TABLE IF EXISTS `<ezrpg>attack_log`');
    }

    private function getAttack($attacker){
        $attmult = rand(7, 8);
        $attstr = $attacker->strength;
        $attagil = $attacker->agility * $attmult;
        $strhook = $this->container['hooks']->run_hooks('attack', $attacker);
        if(isset($strhook->troops['offense']['amount']))
            $attstr += $strhook->troops['offense']['amount'];
        if(isset($strhook->weapons['offense']['amount']))
            $attstr += $strhook->weapons['offense']['amount'];

        return array('Score'=>((($attstr) + ($attagil)) * $attmult),
            'Strength'=>$attstr,
            'Agility' => $attagil,
            'Multi'   => $attmult,
            'HP'      => $attacker->hp,
            'XP'      => $attacker->exp,
            'Level'   => $attacker->level
        );
    }
    private function getDefense($defender){
        $defmulti = rand(5, 7);
        $defstr = $defender->strength;
        $defvit = $defender->vitality * $defmulti;
        $defagil = $defender->agility * $defmulti;
        $strhook = $this->container['hooks']->run_hooks('attack', $defender);
        if(isset($strhook->troops['defense']['amount']))
            $defstr += $strhook->troops['defense']['amount'];
        if(isset($strhook->weapons['defense']['amount']))
            $defstr += $strhook->weapons['defense']['amount'];
        $defscore = (($defstr) + (($defagil + ($defvit))) * $defmulti);
        return array('Strength' => $defstr,
            'Agility' => $defagil,
            'Vitality' => $defvit,
            'Score' => $defscore,
            'Multi' => $defmulti,
            'HP'      => $defender->hp,
            'XP'      => $defender->exp,
            'Level'   => $defender->level
        );
    }
    private function getDamage($player, $victor = false)
    {
        if($victor) {
            return array('HP' => $player->hp - round(rand($player->hp / 6, $player->hp)),
                'XP' => ($player->exp !='0' ? $player->exp + round(rand($player->exp/ 5, $player->exp / 2)): rand(6,12)),
                'Debug' => $player->exp
            );
        }else{
            return array('HP' => $player->hp - rand($player->hp / 2, $player->hp + 10),
                'XP' => ($player->exp !='0' ? $player->exp + round(rand($player->exp/ 6, $player->exp / 4)) : rand(3,8)),
                'Debug' => $player->exp
            );
        }
    }
}