{include file="file:[$THEME]header.tpl" TITLE="Create your Avatar!"}

<form action="index.php?mod=Avatar" method="post">
    <table width=600 align=center style="border: 0px solid #000000;" bgcolor=#FFFFFF>
        <tr>
            <td width=50% valign=top align=center>
                <font face=arial><b>{$gender}</b></font>
                <div style="border: 1px solid #000000; height: 322px; width: 247px; text-align: left;">
                    <img src="static/avatar/{$gender}/Backgrounds/None.png" width="247px;" height="322px" style="position: absolute;" id="Backgrounds">
                    <img src="static/avatar/{$gender}/Body/Torso.png" width="247px;" height="322px" style="position: absolute;">
                    <img src="static/avatar/{$gender}/Clothes/None.png" width="247px;" height="322px" style="position: absolute;" id="Clothes">
                    <img src="static/avatar/{$gender}/Clothes2/None.png" width="247px;" height="322px" style="position: absolute;" id="Clothes2">
                    <img src="static/avatar/{$gender}/Body/Head.png" width="247px;" height="322px" style="position: absolute;">
                    <img src="static/avatar/{$gender}/Eyebrows/None.png" width="247px;" height="322px" style="position: absolute;" id="Eyebrows"/>
                    <img src="static/avatar/{$gender}/Noses/None.png" width="247px;" height="322px" style="position: absolute;" id="Noses"/>
                    <img src="static/avatar/{$gender}/Markings/None.png" width="247px;" height="322px" style="position: absolute;" id="Markings"/>
                    <img src="static/avatar/{$gender}/Eyes/None.png" width="247px;" height="322px" style="position: absolute;" id="Eyes"/>
                    <img src="static/avatar/{$gender}/Hair/None.png" width="247px;" height="322px" style="position: absolute;" id="Hair"/>
                    <img src="static/avatar/{$gender}/Lips/None.png" width="247px;" height="322px" style="position: absolute;" id="Lips"/>
                    <img src="static/avatar/{$gender}/Other/None.png" width="247px;" height="322px" style="position: absolute;" id="Other"/>
                    <img src="static/avatar/{$gender}/Hats/None.png" width="247px;" height="322px" style="position: absolute;" id="Hats"/>
                </div>
            </td>
            <td valign=top>
                <table>
                    {foreach from=$dirFiles item=file key=val}
                        <tr>
                            <td align=right><b>{$val}:</b></td>
                            <td>
                                <select name="{$val}" onChange="jsDropDown('{$val}', '{$gender}/{$val}', this.value)">
                                    {foreach from=$file item=body}
                                        {foreach from=$body item=part}
                                            <option {if $part eq "None.png"}selected{/if}>{$part}</option>
                                        {/foreach}
                                    {/foreach}
                                </select>
                            </td>
                        </tr>
                    {/foreach}

                </table>
                <input type=hidden name=gender value="{$gender}">

                <br>
                <div style="text-align: right; padding-right: 22px;"><input type=submit name="create" value="Save your Avatar" style="border: 1px solid #006;"></div>
                <br>
</form>
</td></tr>
<tr><td colspan=2>
        <table width=600 align=center style="border: 1px dashed #dcdcdc">
            <tr><td align=center colspan=4><b>Select New Character Base</b></td></tr>
            <tr><td align=center><a href="index.php?mod=Avatar&gender=Lady"><font size=-1>Lady</a></td>
                <td align=center><a href="index.php?mod=Avatar&gender=Man"><font size=-1>Man</a></td>
                <td align=center><a href="index.php?mod=Avatar&gender=Young Lady"><font size=-1>Young Lady</a></td>
                <td align=center><a href="index.php?mod=Avatar&gender=Young Man"><font size=-1>Young Man</a></td></tr></table>
    </td></tr>
</table>

<center><font face=arial>
        <b>Graphic Credits:</b>  <i>Celianna</i><br>
        <b>Code by:</b> <i>Hardcopi</i>

</form>

        <script language=javascript>
            function jsDropDown(imgid,folder,newimg) {
                document.getElementById(imgid).src =  "http://dev-1.2.x.ezrpgproject.net/static/avatar/" + folder + "/" + newimg;
            }
        </script>

{include file="file:[$THEME]footer.tpl"}