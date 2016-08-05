<?php

namespace ezRPG\Modules;

use \ezRPG\lib\Base_Module;

//This file cannot be viewed, it must be included
defined('IN_EZRPG') or exit;

/*
  Class: Module_AccountSettings
  Lets the user change their password.
 */

class Module_Profile extends Base_Module
{
    /*
      Function: start
      Begins the account settings page/
     */

    public function start()
    {
        
        if(isset($_GET['player']))
            $player = $_GET['player'];
        else
            $player = $this->player->username;

        if(!isClean($player)){
            $this->setMessage("Your query could not be executed", "warn");
            header("Location: index.php?mod=Members");
        }

        $playerquery = "SELECT `<ezrpg>players`.id, `<ezrpg>players`.username, `<ezrpg>players_meta`.* FROM <ezrpg>players INNER JOIN <ezrpg>players_meta on `<ezrpg>players_meta`.pid = `<ezrpg>players`.id WHERE `<ezrpg>players`.username = '" . $player . "'";
        $playerres = $this->db->execute($playerquery);
        $player = $this->db->fetchAll($playerres);

        $this->tpl->assign('avatar', false);
        if(isModuleActive("Avatar"))
        {
            $this->tpl->assign('avatar', true);
            if(file_exists($filename = CUR_DIR . "/web/static/avatar/users/" . $player['0']->username . ".png"))
            {
                $avatar = 'data:image/image/png;base64,' . base64_encode(file_get_contents($filename));
                $playerArray = array_merge((array) $player[0], array('avatar'=>"<img src='". $avatar ."' style=\"border: 1px solid #000000;\" width=150>"));
                $player[0] = (object) $playerArray;
            }

        }
        $this->tpl->assign('profile', $player);
        
        $this->loadView('profile.tpl', 'Profile');
    }
}

?>