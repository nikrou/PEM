<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
        "http://www.w3.org/TR/html4/loose.dtd">
<html>
  <head>
    <meta http-equiv="content-type" content="text/html; charset=utf-8">
    {if isset($meta)}{$meta}{/if}
    <title>{$title}</title>
    <style type="text/css" media="all">@import "template/style.css";</style>
    <link
      rel="alternate"
      type="application/rss+xml"
      href="extensions.rss"
      title="Extensions"
    />
    <script type="text/javascript" src="template/functions.js"></script>
    {$specific_header}
  </head>
  
  <body> 
    {$banner}
    
    <div id="overall">
      <div id="Menus">
        <div class="menu">
          <form method="post" action="{$action}" style="margin:0;padding:0;">
          Category<br />
          <select name="category">
            <option value="0">-------</option>
{foreach from=$categories item=category}
            <option value="{$category.id}" {$category.selected}>{$category.name}</option>
{/foreach}
          </select><br />

          Search<br />
          <input name="search" type="text" value="{if isset($search)}{$search}{/if}"/><br />

          Version<br />
          <select name="pwg_version">
            <option value="0">-------</option>
{foreach from=$menu_versions item=version}
            <option value="{$version.id}" {$version.selected}>{$version.name}</option>
{/foreach}
          </select>

          Author<br />
          <select name="user">
            <option value="0">-------</option>
{foreach from=$filter_users item=user}
            <option value="{$user.id}" {$user.selected}>{$user.name}</option>
{/foreach}
          </select>

          <p class="filter_buttons">
            <input type="submit" value="Filter" name="filter_submit" />
            <input type="submit" value="Reset" name="filter_reset" />
          </p>
          </form>
        </div>
    
        <div class="menu">
{if $has_disclaimer}
          <a href="disclaimer.php">Disclaimer</a><br/>
{/if}

{if !$user_is_logged}
	<form method="post">
			<ul class="ident">
				<li><a href="register.php">Register</a><br /></li>
				<li><a href="identification.php">Login</a><br /></li>
			</ul>
			Username<br />
			<input type="text" name="username" />
			Password<br />
			<input type="password" name="password" /><br />
		<div>
			<p class="filter_buttons">
				<input type="submit" name="quickconnect_submit" value="Submit" />
			</p>
		</div>
	</form>
{else}
          <p>Hello {$username}</p>
          <ul>
            <li><a href="identification.php?action=logout">Disconnect</a></li>
            <li><a href="my.php">Home</a></li>
            <li><a href="extension_add.php">Add extension</a></li>
  {if $user_is_admin}
            <li><a href="admin/index.php">Administration</a></li>
  {/if}
          </ul>
{/if}
        </div>        
      </div> <!-- Menus -->
    
      <div id="Content">
        <div id="quickNav"><a href="index.php">Index</a></div>
{if count($languages) > 0}
        <div id="langSelect">
          <select onchange="document.location = this.options[this.selectedIndex].value;">
  {foreach from=$languages item=language}
            <option
              value="{$self_uri}lang={$language.code}"
              {if ($lang == $language.code)}selected="selected"{/if}
            >
              {$language.label}
            </option>
  {/foreach}
          </select>
        </div>
{/if}
      {$main_content}
      </div>
    </div> <!-- overall -->
    
    <div id="footer">
      <a href="rss.php" title="notification feed">news feed</a>
      - page generated in {$generation_time}
      - powered by <a href="https://gna.org/projects/pem/">PEM</a> {$subversion_revision}
    </div> <!-- footer -->

    {$footer}
  </body>
</html>