{include file="file:[$THEME]header.tpl" TITLE="Home"}

<h1>Items</h1>
<form action="index.php?mod=Items" method="post">
{hook n=items}
<h2>Offensive Items</h2>
<table class="tg">
    <thead>
        <tr>
            <th class="tg-yw4l">Name</th>
            <th class="tg-yw4l">Body</th>
            <th class="tg-yw4l">Cost</th>
            <th class="tg-yw4l">Bonus</th>
            <th class="tg-yw4l">Sell</th>
            <th class="tg-yw4l">Owned</th>
            <th class="tg-yw4l">Buy</th>
        </tr>
    </thead>
    <tbody>
        {foreach from=$hook_items.items item=item}
            {if $item['type'] == "offense"}
            <tr>
                <td class="tg-yw4l">{$item['name']}</td>
                <td class="tg-yw4l">{$item['body']}</td>
                <td class="tg-yw4l">{$item['cost']}</td>
                <td class="tg-yw4l">{$item['bonus']}</td>
                <td class="tg-yw4l">{$item['sell']}</td>
                <td class="tg-yw4l">{if $item['owned'] != NULL}{$item['owned']}{else}0{/if}</td>
                <td class="tg-yw4l"><input name="{$item['id']}" value="0" /></td>
                <input type="hidden" value="{if $item['owned'] != NULL}{$item['owned']}{else}0{/if}" name="{$item['id']}_owned"/>
            </tr>
            {/if}
        {/foreach}
    </tbody>
</table>
    <input type="submit" id='buy' name="buy" value="Buy"/> <input type="submit" name="sell" id='sell' value="Sell"/>
<h2>Defensive Items</h2>
<table class="tg">
    <thead>
    <tr>
        <th class="tg-yw4l">Name</th>
        <th class="tg-yw4l">Body</th>
        <th class="tg-yw4l">Cost</th>
        <th class="tg-yw4l">Bonus</th>
        <th class="tg-yw4l">Sell</th>
        <th class="tg-yw4l">Owned</th>
        <th class="tg-yw4l">Buy</th>
    </tr>
    </thead>
    <tbody>
    {foreach from=$hook_items.items item=item}
        {if $item['type'] == "defense"}
            <tr>
                <td class="tg-yw4l">{$item['name']}</td>
                <td class="tg-yw4l">{$item['body']}</td>
                <td class="tg-yw4l">{$item['cost']}</td>
                <td class="tg-yw4l">{$item['bonus']}</td>
                <td class="tg-yw4l">{$item['sell']}</td>
                <td class="tg-yw4l">{if $item['owned'] != NULL}{$item['owned']}{else}0{/if}</td>
                <td class="tg-yw4l"><input name="{$item['id']}" value="0" /></td>
                <input type="hidden" value="{if $item['owned'] != NULL}{$item['owned']}{else}0{/if}" name="{$item['id']}_owned"/>
            </tr>
        {/if}
    {/foreach}
    </tbody>
</table>
    <input type="submit" id='buy' name="buy" value="Buy"/> <input type="submit" name="sell" id='sell' value="Sell"/>
<h2>Spy Offense Items</h2>
<table class="tg">
    <thead>
    <tr>
        <th class="tg-yw4l">Name</th>
        <th class="tg-yw4l">Body</th>
        <th class="tg-yw4l">Cost</th>
        <th class="tg-yw4l">Bonus</th>
        <th class="tg-yw4l">Sell</th>
        <th class="tg-yw4l">Owned</th>
        <th class="tg-yw4l">Buy</th>
    </tr>
    </thead>
    <tbody>
    {foreach from=$hook_items.items item=item}
        {if $item['type'] == "spyoffense"}
            <tr>
                <td class="tg-yw4l">{$item['name']}</td>
                <td class="tg-yw4l">{$item['body']}</td>
                <td class="tg-yw4l">{$item['cost']}</td>
                <td class="tg-yw4l">{$item['bonus']}</td>
                <td class="tg-yw4l">{$item['sell']}</td>
                <td class="tg-yw4l">{if $item['owned'] != NULL}{$item['owned']}{else}0{/if}</td>
                <td class="tg-yw4l"><input name="{$item['id']}" value="0" /></td>
                <input type="hidden" value="{if $item['owned'] != NULL}{$item['owned']}{else}0{/if}" name="{$item['id']}_owned"/>
            </tr>
        {/if}
    {/foreach}
    </tbody>
</table>
    <input type="submit" id='buy' name="buy" value="Buy"/> <input type="submit" name="sell" id='sell' value="Sell"/>
<h2>Spy Defense Items</h2>
<table class="tg">
    <thead>
    <tr>
        <th class="tg-yw4l">Name</th>
        <th class="tg-yw4l">Body</th>
        <th class="tg-yw4l">Cost</th>
        <th class="tg-yw4l">Bonus</th>
        <th class="tg-yw4l">Sell</th>
        <th class="tg-yw4l">Owned</th>
        <th class="tg-yw4l">Buy</th>
    </tr>
    </thead>
    <tbody>
    {foreach from=$hook_items.items item=item}
        {if $item['type'] == "spydefense"}
            <tr>
                <td class="tg-yw4l">{$item['name']}</td>
                <td class="tg-yw4l">{$item['body']}</td>
                <td class="tg-yw4l">{$item['cost']}</td>
                <td class="tg-yw4l">{$item['bonus']}</td>
                <td class="tg-yw4l">{$item['sell']}</td>
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