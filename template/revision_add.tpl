<h2>{$extension_name}</h2>

<form method="post" action="{$f_action}" enctype="multipart/form-data">
  <fieldset>
    <legend>{'Add a revision'|translate}</legend>

    <table>
      <tr>
        <th>{'Version'|translate}</th>
        <td>
          <input
            type="text"
            name="revision_version"
            size="10"
            maxlength="10"
            value="{$name}"
          />
        </td>
      </tr>
      <tr>
        <th>{'File'|translate}</th>
        <td>
          <input type="file" name="revision_file" size="35" />
        </td>
      </tr>
      <tr>
        <th>{'Compatibility'|translate}</th>
        <td>
          <div class="checkboxBox">
{foreach from=$versions item=version}
            <label>
              <input type="checkbox" name="compatible_versions[]" value="{$version.id_version}" {$version.checked} />{$version.name}
            </label>
{/foreach}
          </div>
        </td>
      </tr>
{if $authors|@count > 1}
      <tr>
        <th>{'Author'|translate}</th>
        <td>
          {html_radios name="author" values=$authors output=$authors|@get_author_name selected=$selected_author}
        </td>
      </tr>
{/if}
      <tr>
        <th>{'Notes'|translate}</th>
        <td>
          <textarea cols="80" rows="10" name="revision_changelog">{$description}</textarea>
        </td>
      </tr>
{if $use_agreement}
      <tr>
        <th>{'Agreement'|translate}</th>
        <td>
          <label><input type="checkbox" name="accept_agreement" {$accept_agreement_checked}>{$agreement_description}</label>
        </td>
      </tr>
{/if}
    </table>

    <div>
      <input type="submit" value="{'Submit'|translate}" name="submit" />
    </div>
  </fieldset>
</form>