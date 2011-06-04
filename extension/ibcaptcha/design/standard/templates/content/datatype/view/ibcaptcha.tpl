<div class="block">
    <div class="labelbreak"></div>
    <p>
        {section show=eq( $attribute.data_int, 0 ) }
	        {'Captcha secure code wasn`t entered'|i18n('ibcaptcha/object/view')} 
        {/section}
		{section show=eq( $attribute.data_int, 1 ) }
            {'Captcha secure code was entered'|i18n('ibcaptcha/object/view')}: {$attribute.data_text} 
        {/section}
	</p>
</div>