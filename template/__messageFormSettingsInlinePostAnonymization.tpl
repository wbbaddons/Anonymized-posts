{if $__wbb->isActiveApplication() && $inThreadQuickReply|isset && $board->getPermission('canCreateAnonymizedPost') && !$board->anonymizationForced}
	<dt></dt>
	<dd>
		<label><input id="anonymize" name="anonymize" type="checkbox" value="1"{if $thread->getFirstPost()->isAnonymized} checked="checked"{/if} /> {lang}wbb.post.option.anonymization{/lang}</label>
		<small>{lang}wbb.post.option.anonymization.description{/lang}</small>
	</dd>
{/if}
{if $__wbb->isActiveApplication() && $inThreadQuickReply|isset && ($board->getPermission('canCreateAnonymizedPost') || $board->anonymizationForced)}
	<script data-relocate="true">
	//<!CDATA[
	(function (window, $, undefined) {
		$(function() {
			{if !$board->anonymizationForced}
			$('#anonymize').on('change', function() {
				if ($(this).is(':checked')) {
			{/if}
					$('#messageQuickReply .userAvatar, #messageQuickReply .userCredits, #messageQuickReply .userTitle').hide();
					$('#messageQuickReply .username span').text('{$anonymizationUsername|encodeJS}');
			{if !$board->anonymizationForced}
				}
				else {
					$('#messageQuickReply .userAvatar, #messageQuickReply .userCredits, #messageQuickReply .userTitle').show();
					$('#messageQuickReply .username span').text(WCF.User.username);
				}
			}).trigger('change');
			{/if}
		});
	})(this, jQuery);
	//]]>
	</script>
{/if}
