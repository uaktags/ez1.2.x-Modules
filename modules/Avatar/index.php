<?php

namespace ezRPG\Modules;

use \ezRPG\lib\Base_Module;

//This file cannot be viewed, it must be included
defined('IN_EZRPG') or exit;

/*
  Class: Module_Avatar
  A basic avatar building module from Hardcopi
*/
class Module_Avatar extends Base_Module
{
    /*
      Function: start
      Renders  avatar.tpl
    */
    public function start()
    {
        if(isset($_POST['create'])){
            $this->createpng();
        }
        if(isset($_GET['gender']))
        {
            $gender = $_GET['gender'];
        }else{
            $gender = "Young Lady";
        }
        $this->tpl->assign('gender' , $gender);

        $gens = scandir(CUR_DIR . "/web/static/avatar/" . $gender);
        foreach($gens as $bodypart)
        {
            if ($bodypart != "." && $bodypart != ".." && $bodypart != "Body")
                $dirFiles[$bodypart] = array();
        }

        foreach($dirFiles as $body => $sf) {
            $dir = scandir(CUR_DIR . "/web/static/avatar/" . $gender . "/" . $body);
            $parts = array();
            foreach ($dir AS $img) {
                if ($img != "." && $img != "..") {
                    array_push($parts, $img);
                }
            }
            array_push($dirFiles[$body], $parts);
        }

        $this->tpl->assign('dirFiles', $dirFiles);
        $this->tpl->display('avatar.tpl', 'Avatar');
    }

    private function createpng(){
        $gender = $_POST['gender'];
        $backgrounds = CUR_DIR . "/web/static/avatar/". $gender . "/Backgrounds/" . $_POST['Backgrounds'];
        $body = CUR_DIR . "/web/static/avatar/". $gender . "/Body/Torso.png";
        $head = CUR_DIR . "/web/static/avatar/". $gender . "/Body/Head.png";
        $clothes = CUR_DIR . "/web/static/avatar/". $gender . "/Clothes/" . $_POST['Clothes'];
        $clothes2 = CUR_DIR . "/web/static/avatar/". $gender . "/Clothes2/" . $_POST['Clothes2'];
        $eyebrows = CUR_DIR . "/web/static/avatar/". $gender . "/Eyebrows/" . $_POST['Eyebrows'];
        $eyes = CUR_DIR . "/web/static/avatar/". $gender . "/Eyes/" . $_POST['Eyes'];
        $nose = CUR_DIR . "/web/static/avatar/". $gender . "/Noses/" . $_POST['Noses'];
        $other = CUR_DIR . "/web/static/avatar/". $gender . "/Other/" . $_POST['Other'];
        $hair = CUR_DIR . "/web/static/avatar/". $gender . "/Hair/" . $_POST['Hair'];
        $markings = CUR_DIR . "/web/static/avatar/". $gender . "/Markings/" . $_POST['Markings'];
        $lips = CUR_DIR . "/web/static/avatar/". $gender . "/Lips/" . $_POST['Lips'];
        $hats = CUR_DIR . "/web/static/avatar/". $gender . "/Hats/" . $_POST['Hats'];

        if ($gender == "Young Lady") { $width = 247; }
        if ($gender == "Lady") { $width = 247; }
        if ($gender == "Young Man") { $width = 265; }
        if ($gender == "Man") { $width = 265; }

        $back = imagecreatefrompng($backgrounds);
        $bg   = imagecreatefrompng($body);
        $body   = imagecreatefrompng($body);
        $head = imagecreatefrompng($head);
        $clothes2 = imagecreatefrompng($clothes2);
        $clothes = imagecreatefrompng($clothes);
        $eyebrows = imagecreatefrompng($eyebrows);
        $markings = imagecreatefrompng($markings);
        $other = imagecreatefrompng($other);
        $eyes = imagecreatefrompng($eyes);
        $nose = imagecreatefrompng($nose);
        $hair = imagecreatefrompng($hair);
        $lips = imagecreatefrompng($lips);
        $hats = imagecreatefrompng($hats);

        imagealphablending($bg, false);
        imagesavealpha($bg, true);

        imagecopymerge($bg, $back, 0, 0, 0, 0, $width, 322, 100);
        $this->imagecopymerge_alpha($bg, $body, 0, 0, 0, 0, $width, 322, 100);
        $this->imagecopymerge_alpha($bg, $head, 0, 0, 0, 0, $width, 322, 100);
        $this->imagecopymerge_alpha($bg, $clothes, 0, 0, 0, 0, $width, 322, 100);
        $this->imagecopymerge_alpha($bg, $clothes2, 0, 0, 0, 0, $width, 322, 100);
        $this->imagecopymerge_alpha($bg, $eyebrows, 0, 0, 0, 0, $width, 322, 100);
        $this->imagecopymerge_alpha($bg, $markings, 0, 0, 0, 0, $width, 322, 100);
        $this->imagecopymerge_alpha($bg, $eyes, 0, 0, 0, 0, $width, 322, 100);
        $this->imagecopymerge_alpha($bg, $nose, 0, 0, 0, 0, $width, 322, 100);
        $this->imagecopymerge_alpha($bg, $other, 0, 0, 0, 0, $width, 322, 100);
        $this->imagecopymerge_alpha($bg, $hair, 0, 0, 0, 0, $width, 322, 100);
        $this->imagecopymerge_alpha($bg, $lips, 0, 0, 0, 0, $width, 322, 100);
        $this->imagecopymerge_alpha($bg, $hats, 0, 0, 0, 0, $width, 322, 100);

        if (isset($this->player)) {
            $filename = CUR_DIR . "/web/static/avatar/users/" . $this->player->username . ".png";
            ImagePng ($bg, $filename);
            $this->setMessage("Your Avatar has been created ","info");
        } else {
            Header("Content-type: image/jpeg");
            ImagePng ($bg);
        }
    }

    /**
     * merge two true colour images while maintaining alpha transparency of both
     * images.
     *
     * known issues : Opacity values other than 100% get a bit screwy, the source
     *                composition determines how much this issue will annoy you.
     *                if in doubt, use as you would imagecopy_alpha (i.e. keep
     *                opacity at 100%)
     *
     * @access public
     *
     * @param  resource $dst  Destination image link resource
     * @param  resource $src  Source image link resource
     * @param  int      $dstX x-coordinate of destination point
     * @param  int      $dstY y-coordinate of destination point
     * @param  int      $srcX x-coordinate of source point
     * @param  int      $srcY y-coordinate of source point
     * @param  int      $w    Source width
     * @param  int      $h    Source height
     * @param  int      $pct  Opacity or source image
     ******************************************************************************/
    private function imagecopymerge_alpha($dst, $src, $dstX, $dstY, $srcX, $srcY, $w, $h, $pct)
    {
        $pct /= 100;

        /* sanity check before going any further */
        $pct  = max(min(1, $pct), 0);

        if ($pct == 0)
        {
            /* would anyone really attempt to call us with 0% opacity? */
            return;
        }

        $toy  = $dstY;

        for ($y = $srcY; $y < ($srcY + $h); $y++)
        {
            $tox = $dstX;

            for ($x = $srcX; $x < ($srcX + $w); $x++)
            {
                /* get source image's pixel RGBA index */
                $src_c  = imagecolorat($src, $x, $y);

                if ($src_c === false)
                {
                    /* we must be 'out of bounds' on source image so skip        */
                    /* essentially leaving a fully transparent area through to   */
                    /* destination image. is this correct behaviour or should we */
                    /* fill black/white ??                                       */
                    $tox++;
                    continue;
                }

                /* get source alpha channel level and decide if we need to continue */
                $src_a  = ($src_c >> 24) & 0xFF;

                if ($pct < 1)
                {
                    /* fake opacity level by adjusting alpha channel level */
                    $src_a = ($src_a == 0) ? 127 - (127 * $pct) : $src_a + (127 * $pct);
                    $src_a = ($src_a > 127) ? 127 : (int)$src_a;
                }

                if ($src_a == 127)
                {
                    /* fully transparent areas of source image can be skipped */
                    $tox++;
                    continue;
                }

                $src_r = ($src_c >> 16) & 0xFF;
                $src_g = ($src_c >>  8) & 0xFF;
                $src_b = ($src_c)       & 0xFF;

                /* get destination image's pixel RGBA index */
                $dst_c = imagecolorat($dst, $tox, $toy);
                $dst_a = ($dst_c >> 24) & 0xFF;
                $dst_r = ($dst_c >> 16) & 0xFF;
                $dst_g = ($dst_c >>  8) & 0xFF;
                $dst_b = ($dst_c)       & 0xFF;

                /* alpha multiplier */
                $alpha = $src_a / 127;

                /* RGB compensation for alpha channel level */
                $new_r = $src_r - ($src_r * $alpha) + ($dst_r * $alpha);
                $new_g = $src_g - ($src_g * $alpha) + ($dst_g * $alpha);
                $new_b = $src_b - ($src_b * $alpha) + ($dst_b * $alpha);

                /* sanity check nothing gets above 255 */
                $new_r = ($new_r > 255 )? 255 : (int)$new_r;
                $new_g = ($new_g > 255 )? 255 : (int)$new_g;
                $new_b = ($new_b > 255 )? 255 : (int)$new_b;

                /* alpha channel compensation, I doubt it's as simple as this.    */
                /* this requires further investigation, with some of our test     */
                /* images applying even just a little reduction in opacity starts */
                /* to intoduce artifacts around the edges of source's non         */
                /* transparent image area when the  merge area of destination     */
                /* image is fully transparent. it also gets very 'screwy' with    */
                /* small opacity levels. it works for us at the moment but it's   */
                /* not right and I suspect the issue lies here but it may also be */
                /* related to some of the compensation calculations above         */
                $new_a = min($src_a, $dst_a);

                if ($new_a > 0)
                {
                    $new_a = $dst_a * $alpha;
                    $new_a = ($new_a > 127) ? 127 : (int)$new_a;
                }

                /* finally get and set this pixel's RGBA colour index */
                $rgba = ImageColorAllocateAlpha($dst, $new_r, $new_g, $new_b, $new_a);
                if ($rgba == -1) {
                    $rgba = ImageColorClosestAlpha($dst, $new_r, $new_g, $new_b, $new_a);
                }

                imagesetpixel($dst, $tox, $toy, $rgba);
                $tox++;
            }
            $toy++;
        }
    }
}

?>