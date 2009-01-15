<h2>{$extension_name}</h2>

<h3>Manage extension screenshot</h3>

<ul class="actionLinks">
  <li><a href="{$u_extension}" title="See extension"><img src="template/images/see_extension.png"></a></li>
</ul>

<form method="post" action="{$f_action}" enctype="multipart/form-data">
  <fieldset>
    <legend>Add or replace screenshot</legend>

    <table>
      <tr>
        <th><strong>File *</strong></th>
        <td>
          <input
            type="file"
            name="picture"
            maxlength="50"
            value="{$f_picture_name}"
          />
        </td>
      </tr>
    </table>

    <div>
      <input type="submit" value="Submit" name="submit_add" />
    </div>
  </fieldset>
</form>

{if isset($current)}
<form method="post" action="{$f_action}" enctype="multipart/form-data">
  <fieldset>
    <legend>Delete screenshot</legend>

    <table>
      <tr>
        <td>
          <a class="screenshot" href="{$current.u_screenshot}"><img src="{$current.thumbnail_src}"/></a>
        </td>
        <td valign="middle">
          <input type="submit" value="Submit" name="submit_delete" />
        </td>
      </tr>
    </table>
  </fieldset>
</form>
{/if}