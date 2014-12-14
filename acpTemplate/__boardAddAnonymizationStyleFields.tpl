<dl>
	<dt><label for="anonymizationMode">{lang}wcf.acp.option.wbb_post_anonymization_mode{/lang}</label></dt>
	<dd>
		<select id="anonymizationMode" name="anonymizationMode">
			<option value="default"{if $anonymizationMode == 'default'} selected="selected"{/if}>{lang}wcf.global.noSelection{/lang}</option>
			<option value="hash"{if $anonymizationMode == 'hash'} selected="selected"{/if}>{lang}wcf.acp.option.wbb_post_anonymization_mode.hash{/lang}</option>
			<option value="fixed"{if $anonymizationMode == 'fixed'} selected="selected"{/if}>{lang}wcf.acp.option.wbb_post_anonymization_mode.fixed{/lang}</option>
			<option value="list"{if $anonymizationMode == 'list'} selected="selected"{/if}>{lang}wcf.acp.option.wbb_post_anonymization_mode.list{/lang}</option>
		</select>
		<small>{lang}wcf.acp.option.wbb_post_anonymization_mode.description{/lang}</small>
	</dd>
</dl>
