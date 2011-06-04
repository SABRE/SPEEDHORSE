{default $attribute_base='ContentObjectAttribute'}

{section show=eq( $attribute.data_int, 0 ) }
    <div class="block">
        <div class="labelbreak"></div>
        <p>{'To prevent commentspamming, please enter the secure code you see in the image below in the input box beneath the image'|i18n('ibcaptcha/object/edit')} <b>{'small latin letters'|i18n('ibcaptcha/object/edit')}</b>.</p>        
        <img id="IBCaptcha{$attribute.id}" alt="IB Captcha" title="IB Captcha" src="{'ibcaptcha/get/'|ezurl("no")}/{$attribute.id}/{$attribute.version}/0"/> <br />
        <a href="#" id="refreshIBCaptcha{$attribute.id}">{'Regenerate'|i18n('ibcaptcha/object/edit')}</a>
    </div>  
    <div class="block">
        <input name="{$attribute_base}_ibCaptcha_captchaText_{$attribute.id}" type="text" size="{$attribute.contentclass_attribute.data_int1}" maxlength="{$attribute.contentclass_attribute.data_int1}" value="{$attribute.data_text}" />
    </div>
    
	{literal}
	<script type="text/javascript">
	window.addEvent('domready', function() {
		var attributeID   = {/literal}{$attribute.id}{literal};
		var regenerateIRL = '{/literal}{'ibcaptcha/get/'|ezurl("no")}/{$attribute.id}/{$attribute.version}/1{literal}';
		$( 'refreshIBCaptcha' + attributeID ).addEvent( 'click', function( e ) {
			$( 'IBCaptcha' + attributeID ).set( 'src', false );
			$( 'IBCaptcha' + attributeID ).set( 'src', regenerateIRL + '/' + $random( 99999, 9999999 ) );	
			e.stop();	
		} );
	} );	
	</script>
	{/literal}    
{/section}

{section show=eq( $attribute.data_int, 1 ) }
    <p>{'Captcha`s secure code is allready entered'|i18n('ibcaptcha/object/edit')}</p> 
{/section}