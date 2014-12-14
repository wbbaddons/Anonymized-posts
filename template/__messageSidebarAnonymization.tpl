{if $templateName|isset && $templateName == 'thread' && $post->isAnonymized && !$userProfile->userID}
	<div class="userTitle">
		<p class="badge red">{lang}wbb.post.anonymized{/lang}</p>
	</div>
{/if}
