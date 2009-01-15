<div id="viewSelect">
  <select onchange="document.location = this.options[this.selectedIndex].value;">
    <option value="index.php?view=standard" selected="selected">standard view</option>
    <option value="index.php?view=compact">compact view</option>
  </select>
</div>

<h2>Most recent extensions</h2>
<div class="pages">
  <div class="paginationBar">{$pagination_bar}</div>
  <div class="Results">({$nb_total} extensions)</div>
</div>

{foreach from=$revisions item=revision}
<div class="row">
{if isset($revision.thumbnail_src)}
  <a class="screenshot" href="{$revision.screenshot_url}"><img src="{$revision.thumbnail_src}"/></a>
{/if}
  <p class="extension_title"><strong><a href="extension_view.php?eid={$revision.extension_id}">{$revision.extension_name}</a></strong></p>

  <p><a href="{$revision.revision_url}">revision {$revision.name}</a></p>

  <ul>
    <li><em>author:</em> {$revision.author}</li>
    <li><em>released on:</em> {$revision.date}</li>
    <li><em>compatible with:</em> {$software} releases {$revision.compatible_versions}</li>
    <li><em>downloads:</em> {$revision.downloads}</li>
  </ul>

  <p class="revision_about"><strong>About:</strong> {$revision.about}</p>

  <p class="revision_changes"><strong>Changes:</strong> {$revision.description}</p>
</div>
{/foreach}

<div class="pages">
  <div class="paginationBar">{$pagination_bar}</div>
  <div class="Results">({$nb_total} extensions)</div>
</div>
<div style="clear : both;"></div>