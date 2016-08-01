{include file="file:[$THEME]header.tpl" TITLE="Home"}

<h1>Army</h1>
<form action="index.php?mod=Army" method="post">
{hook n=armies}
    <h2>Workers</h2>
    <table class="tg">
        <thead>
        <tr>
            <th class="tg-yw4l">Name</th>
            <th class="tg-yw4l">Cost</th>
            <th class="tg-yw4l">Bonus</th>
            <th class="tg-yw4l">Owned</th>
            <th class="tg-yw4l">Buy</th>
        </tr>
        </thead>
        <tbody>
        {foreach from=$hook_armies.troops item=item}
            {if $item['type'] == "worker"}
                <tr>
                    <td class="tg-yw4l">{$item['name']}</td>
                    <td class="tg-yw4l">{$item['cost']}</td>
                    <td class="tg-yw4l">+{$item['bonus']} Gold/day</td>
                    <td class="tg-yw4l">{if $item['owned'] != NULL}{$item['owned']}{else}0{/if}</td>
                    <td class="tg-yw4l"><input name="{$item['id']}" value="0" /></td>
                    <input type="hidden" value="{if $item['owned'] != NULL}{$item['owned']}{else}0{/if}" name="{$item['id']}_owned"/>
                </tr>
            {/if}
        {/foreach}
        </tbody>
    </table>
    <input type="submit" id='buy' name="buy" value="Buy"/> <input type="submit" name="sell" id='sell' value="Sell"/>
    <h2>Offensive Troops</h2>
<table class="tg">
    <thead>
        <tr>
            <th class="tg-yw4l">Name</th>
            <th class="tg-yw4l">Cost</th>
            <th class="tg-yw4l">Bonus</th>
            <th class="tg-yw4l">Owned</th>
            <th class="tg-yw4l">Buy</th>
        </tr>
    </thead>
    <tbody>
        {foreach from=$hook_armies.troops item=item}
            {if $item['type'] == "offense"}
            <tr>
                <td class="tg-yw4l">{$item['name']}</td>
                <td class="tg-yw4l">{$item['cost']}</td>
                <td class="tg-yw4l">+{$item['bonus']} Offense</td>
                <td class="tg-yw4l">{if $item['owned'] != NULL}{$item['owned']}{else}0{/if}</td>
                <td class="tg-yw4l"><input name="{$item['id']}" value="0" /></td>
                <input type="hidden" value="{if $item['owned'] != NULL}{$item['owned']}{else}0{/if}" name="{$item['id']}_owned"/>
            </tr>
            {/if}
        {/foreach}
    </tbody>
</table>
    <input type="submit" id='buy' name="buy" value="Buy"/> <input type="submit" name="sell" id='sell' value="Sell"/>
<h2>Defensive Troops</h2>
<table class="tg">
    <thead>
    <tr>
        <th class="tg-yw4l">Name</th>
        <th class="tg-yw4l">Cost</th>
        <th class="tg-yw4l">Bonus</th>
        <th class="tg-yw4l">Owned</th>
        <th class="tg-yw4l">Buy</th>
    </tr>
    </thead>
    <tbody>
    {foreach from=$hook_armies.troops item=item}
        {if $item['type'] == "defense"}
            <tr>
                <td class="tg-yw4l">{$item['name']}</td>
                <td class="tg-yw4l">{$item['cost']}</td>
                <td class="tg-yw4l">+{$item['bonus']} Defense</td>
                <td class="tg-yw4l">{if $item['owned'] != NULL}{$item['owned']}{else}0{/if}</td>
                <td class="tg-yw4l"><input name="{$item['id']}" value="0" /></td>
                <input type="hidden" value="{if $item['owned'] != NULL}{$item['owned']}{else}0{/if}" name="{$item['id']}_owned"/>
            </tr>
        {/if}
    {/foreach}
    </tbody>
</table>
    <input type="submit" id='buy' name="buy" value="Buy"/> <input type="submit" name="sell" id='sell' value="Sell"/>
<h2>Spy Offense Troops</h2>
<table class="tg">
    <thead>
    <tr>
        <th class="tg-yw4l">Name</th>
        <th class="tg-yw4l">Cost</th>
        <th class="tg-yw4l">Bonus</th>
        <th class="tg-yw4l">Owned</th>
        <th class="tg-yw4l">Buy</th>
    </tr>
    </thead>
    <tbody>
    {foreach from=$hook_armies.troops item=item}
        {if $item['type'] == "spyoffense"}
            <tr>
                <td class="tg-yw4l">{$item['name']}</td>
                <td class="tg-yw4l">{$item['cost']}</td>
                <td class="tg-yw4l">+{$item['bonus']} Spy Offense</td>
                <td class="tg-yw4l">{if $item['owned'] != NULL}{$item['owned']}{else}0{/if}</td>
                <td class="tg-yw4l"><input name="{$item['id']}" value="0" /></td>
                <input type="hidden" value="{if $item['owned'] != NULL}{$item['owned']}{else}0{/if}" name="{$item['id']}_owned"/>
            </tr>
        {/if}
    {/foreach}
    </tbody>
</table>
    <input type="submit" id='buy' name="buy" value="Buy"/> <input type="submit" name="sell" id='sell' value="Sell"/>
<h2>Spy Defense Troops</h2>
<table class="tg">
    <thead>
    <tr>
        <th class="tg-yw4l">Name</th>
        <th class="tg-yw4l">Cost</th>
        <th class="tg-yw4l">Bonus</th>
        <th class="tg-yw4l">Owned</th>
        <th class="tg-yw4l">Buy</th>
    </tr>
    </thead>
    <tbody>
    {foreach from=$hook_armies.troops item=item}
        {if $item['type'] == "spydefense"}
            <tr>
                <td class="tg-yw4l">{$item['name']}</td>
                <td class="tg-yw4l">{$item['cost']}</td>
                <td class="tg-yw4l">+{$item['bonus']} Spy Defense</td>
                <td class="tg-yw4l">{if $item['owned'] != NULL}{$item['owned']}{else}0{/if}</td>
                <td class="tg-yw4l"><input name="{$item['id']}" value="0" /></td>
                <input type="hidden" value="{if $item['owned'] != NULL}{$item['owned']}{else}0{/if}" name="{$item['id']}_owned"/>
            </tr>
        {/if}
    {/foreach}
    </tbody>
</table>
    <input type="submit" id='buy' name="buy" value="Buy"/> <input type="submit" name="sell" id='sell' value="Sell"/>
</form>
{include file="file:[$THEME]footer.tpl"}