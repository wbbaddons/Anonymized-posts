{if $__wbb->isActiveApplication() && (($templateName == "threadAdd" && $board->getPermission('canCreateAnonymizedThread')) || ($templateName == "postAdd" && $board->getPermission('canCreateAnonymizedPost'))) && !$board->anonymizationForced}
	<dt></dt>
	<dd>
		<label><input name="anonymize" type="checkbox" value="1"{if $anonymize|isset && $anonymize} checked="checked"{/if} /> {lang}wbb.post.option.anonymization{/lang}</label>
		<small>{lang}wbb.post.option.anonymization.description{/lang}</small>
	</dd>
{/if}
