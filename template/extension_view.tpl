<h2>{$extension_name}</h2>

{if $can_modify}
<ul class="actionLinks">
  <li><a href="{$u_modify}" title="Modify extension"><img src="template/images/modify.png" alt="Modify extension"></a></li>
  <li><a href="{$u_delete}" onclick="return confirm_del();" title="Delete extension"><img src="template/images/delete.png" alt="Delete extension"></a></li>
  <li><a href="{$u_links}" title="Manage links"><img src="template/images/links.png" alt="links"></a></li>
  <li><a href="{$u_screenshot}" title="Manage screenshot"><img src="template/images/screenshot.png" alt="screenshot"></a></li> 
  <li><a href="{$u_add_rev}" title="Add revision"><img src="template/images/add_revision.png" alt="Add revision"></a></li>
</ul>
{/if}

{if isset($thumbnail)}
<a class="screenshot" href="{$thumbnail.url}"><img src="{$thumbnail.src}"/></a>
{/if}

<ul class="extensionInfos">
  <li><em>Author:</em> {$author}</li>
  <li><em>First revision date:</em> {$first_date}</li>
  <li><em>Latest revision date:</em> {$last_date}</li>
  <li><em>Compatible with:</em> {$software} releases {$compatible_with}</li>
  <li><em>Downloads:</em> {$extension_downloads}</li>
</ul>

<p><strong>About:</strong> {$description}</p>

{if count($links) > 0}
<h3>Related links</h3>

<ul>
  {foreach from=$links item=link}
  <li><strong><a href="{$link.url}">{$link.name}</a></strong>: {$link.description}</li>
  {/foreach}
</ul>
{/if}

<h3 id="revisionListTitle">Revision List</h3>

<p class="listButton">
  <a onclick="fullToggleDisplay()" class="javascriptButton">expand/collapse all</a>
</p>

{if isset($revisions)}
<div id="changelog">
  {foreach from=$revisions item=rev}
  <div id="rev{$rev.id}" class="changelogRevision">

    <div
      id="rev{$rev.id}_header"
  {if $rev.expanded}
      class="changelogRevisionHeaderExpanded"
  {else}
      class="changelogRevisionHeaderCollapsed"
  {/if}
      onclick="revToggleDisplay('rev{$rev.id}_header', 'rev{$rev.id}_content')"
    >
      <span class="revisionTitle">Revision {$rev.version}</span>
      <span class="revisionDate">released on {$rev.date}</span>
    </div>

    <div
      id="rev{$rev.id}_content"
      class="changelogRevisionContent"
  {if !$rev.expanded}
      style="display:none"
  {/if}
    >
      <a href="{$rev.u_download}" title="Download revision {$rev.version}" rel="nofollow"><img class="download" src="template/images/download.png" /></a>
      <p>Revision: {$rev.version}</p>
      <p>Released on: {$rev.date}</p>
      <p>Compatible with: {$rev.versions_compatible}</p>
      <p>Downloads: {$rev.downloads}</p>
    
      <blockquote>
        <p>{$rev.description}</p>
      </blockquote>

  {if $rev.can_modify}
      <ul class="revActionLinks">
        <li><a href="{$rev.u_modify}" title="Modify revision"><img src="template/images/modify.png"></a></li>
      <li><a href="{$rev.u_delete}" onclick="return confirm_del();" title="Delete revision"><img src="template/images/delete.png"></a></li>
      </ul>
  {/if}
    </div>
  </div> <!-- rev{$rev.id} -->
  {/foreach}
</div> <!-- changelog -->
{else}
<p><em>No revision available for this extension. Either because there is no
revision at all or because there is no revision compatible with the verion
filter you set.</em></p>
{/if}