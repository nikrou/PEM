<h2>{$message_title}</h2>
<p>{$message_text}</p>

{if isset($u_redirect)}
<p>Automatic redirection in {$time_redirect} seconds.</p>
<p><a href="{$u_redirect}">Click here if don't want to wait.</a></p>
{/if}

{if $go_back}
<p><a href="javascript:history.back();">Back to previous page</a>.</p>
{/if}