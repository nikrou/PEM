<h2>Languages</h2>

<form method="post" action="{$f_action}">
<fieldset>
  <legend>Select available language for PEM interface</legend>
  <p><a class="checkAll" data="int_languages">Check all</a> / <a class="uncheckAll" data="int_languages">Uncheck all</a></p>
  <p>{html_checkboxes name='int_languages' options=$int_languages selected=$selected_int separator='<br />'}</p>
</fieldset>

<p>&nbsp;</p>

<fieldset>
  <legend>Select available language for extensions</legend>
  <p><a class="checkAll" data="ext_languages">Check all</a> / <a class="uncheckAll" data="ext_languages">Uncheck all</a></p>
  <p>{html_checkboxes name='ext_languages' options=$ext_languages selected=$selected_ext separator='<br />'}</p>
</fieldset>

<p>&nbsp;</p>
<p><input type="submit" value="Submit" name="submit" /></p>

</form>


{known_script id="jquery" src="../template/jquery.min.js"}
<script type="text/javascript">
a = document.getElementsByName('int_languages[]');
for (i=0;i<a.length;i++) {ldelim}
  if (a[i].value == '{$default_language}') {ldelim}
    a[i].checked = true;
    a[i].disabled = true;
  }
}

$(".checkAll").click(function() {ldelim}
  $("input[name^='"+ $(this).attr('data') +"']:not(:disabled)").attr("checked", "checked");
});

$(".uncheckAll").click(function() {ldelim}
  $("input[name^='"+ $(this).attr('data') +"']:not(:disabled)").removeAttr("checked");
});
</script>