<h2>My personnal homepage</h2>

<!-- Used to fix a margin bug with IE... -->
<br />

<p>My extensions:</p>
{if count($extensions) > 0}
<ul>
  {foreach from=$extensions item=extension}
  <li><a href="extension_view.php?eid={$extension.id}">{$extension.name}</a></li>
  {/foreach}
</ul>
{/if}

<div style="clear : both;"></div>