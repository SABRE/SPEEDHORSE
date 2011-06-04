<div class="block">
    <div class="element">
        <label>
            {'Length'|i18n('ibcaptcha/class/view')}
        </label>
        <p>{$class_attribute.data_int1}</p>
	</div>	   
	
    <div class="element">
        <label>
            {'Type'|i18n('ibcaptcha/class/view')}      
        </label>
        <p>
		    {section show=eq($class_attribute.data_int2,1)}{'Alphabetic'|i18n('ibcaptcha/class/view')}{/section}
		    {section show=eq($class_attribute.data_int2,2)}{'Numeric'|i18n('ibcaptcha/class/view')}{/section}
		    {section show=eq($class_attribute.data_int2,3)}{'Alphanumeric'|i18n('ibcaptcha/class/view')}{/section}
		</p>
	</div>      
</div>

<div class="block">
    <div class="element">
        <label>
            {'Width'|i18n('ibcaptcha/class/view')}      
        </label>
        <p>{$class_attribute.data_int3}</p>  
    </div>

	<div class="element">
        <label>
            {'Height'|i18n('ibcaptcha/class/view')}    
        </label>
        <p>{$class_attribute.data_int4}</p>   
    </div>
</div>

<div class="block">
    <label>
        {'Noise level'|i18n('ibcaptcha/class/view')}
    </label>
    <p>{$class_attribute.data_float1}</p>
</div>

<input type="hidden" value={""|ezroot} id="ibCaptchaImagesPath" />
<div class="block">
    <div class="element">
        <label>
            {'Characters color'|i18n('ibcaptcha/class/view')}     
        </label>
        <p>{$class_attribute.data_text1}</p> 
    </div>

    <div class="element">
        <label>
            {'Background color'|i18n('ibcaptcha/class/view')}  
        </label>
        <p>{$class_attribute.data_text2}</p> 
    </div>

    <div class="element">
        <label>
            {'Noise color'|i18n('ibcaptcha/class/view')}
        </label>
        <p>{$class_attribute.data_text3}</p>
    </div>
</div>

<div class="block">
    <label>
        {'User`s content objectd ID, for whom captcha will not be used'|i18n('ibcaptcha/class/view')}
    </label>
    <p>{if eq( $class_attribute.data_text4, '')}{'None'|i18n('ibcaptcha/class/view')}{else}{$class_attribute.data_text4}{/if}</p>
</div>