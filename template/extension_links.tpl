<h2>{$extension_name}</h2>

<h3>Manage extension links</h3>

<ul class="actionLinks">
  <li><a href="{$u_extension}" title="See extension"><img src="template/images/see_extension.png"></a></li>
</ul>

<form method="post" action="{$f_action}" enctype="multipart/form-data">
  <fieldset>
    <legend>Add a link</legend>

    <table>
      <tr>
        <th><strong>Name *</strong></th>
        <td>
          <input
            type="text"
            name="link_name"
            maxlength="50"
            value="{$f_link_name}"
          />
        </td>
      </tr>
      <tr>
        <th><strong>URL *</strong></th>
        <td>
          <input
            type="text"
            name="link_url"
            size="50"
            maxlength="255"
            value="{$f_link_url}"
          />
        </td>
      </tr>
      <tr>
      </tr>
      <tr>
        <th>Description</th>
        <td>
          <textarea cols="80" rows="3" name="link_description">{$f_link_description}</textarea>
        </td>
      </tr>
    </table>

    <div>
      <input type="submit" value="Submit" name="submit_add" />
    </div>
  </fieldset>
</form>

{if count($links) > 0}
<form method="post" action="{$f_action}" enctype="multipart/form-data">
  <fieldset>
    <legend>Manage links</legend>

    <ul class="linkManagement">
  {foreach from=$links item=link}
      <li>
        <a href="{$link.u_delete}" onclick="return confirm_del();" class="linkAction"><img src="template/images/delete.png"></a>
        <p>
          <strong><a href="{$link.url}">{$link.name}</a></strong>
          {$link.description}
        </p>
        <p>
          <label>
            Rank:
            <input type="text" name="linkRank[{$link[id]}]" value="{$link[rank]}" size="4"/>
          </label>
        </p>
      </li>
  {/foreach}
    </ul>

    <div>
      <input type="submit" value="Submit" name="submit_order" />
    </div>
  </fieldset>
</form>
{/if}