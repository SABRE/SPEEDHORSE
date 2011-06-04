<div class="block">
    <div class="element">
        <label>
            {'Length (from 3 to 12 characters)'|i18n('ibcaptcha/class/edit')}     
        </label>
        <div class="labelbreak"></div>
        <input maxlength="6" size="4" name="ContentClass_ibCaptcha_length_{$class_attribute.id}" type="text" value="{$class_attribute.data_int1}"/>
	</div>	   
	
    <div class="element">
        <label>
            {'Type'|i18n('ibcaptcha/class/view')}      
        </label>
        <div class="labelbreak"></div>
        <select name="ContentClass_ibCaptcha_type_{$class_attribute.id}">
            <option value="1" {section show=eq($class_attribute.data_int2,1)}selected{/section}>
                {'Alphabetic'|i18n('ibcaptcha/class/view')}
            </option>
            <option value="2" {section show=eq($class_attribute.data_int2,2)}selected{/section}>
                {'Numeric'|i18n('ibcaptcha/class/view')}
            </option>
            <option value="3" {section show=eq($class_attribute.data_int2,3)}selected{/section}>
                {'Alphanumeric'|i18n('ibcaptcha/class/view')}
            </option>
        </select>
	</div>      
</div>

<div class="block">
    <div class="element">
        <label>
            {'Width (from 100 to 500 pixels)'|i18n('ibcaptcha/class/edit')}      
        </label>
        <div class="labelbreak"></div>
        <input maxlength="6" size="6" name="ContentClass_ibCaptcha_width_{$class_attribute.id}" type="text" value="{$class_attribute.data_int3}"/>   
    </div>

	<div class="element">
        <label>
            {'Height (from 30 to 200 pixels)'|i18n('ibcaptcha/class/edit')}    
        </label>
        <div class="labelbreak"></div>
        <input maxlength="6" size="6" name="ContentClass_ibCaptcha_height_{$class_attribute.id}" type="text" value="{$class_attribute.data_int4}"/>   
    </div>
</div>

<div class="block">
    <label>
        {'Noise level (float nubmer form 1 to 10)'|i18n('ibcaptcha/class/edit')}     
    </label>
    <div class="labelbreak"></div>
    <input maxlength="6" size="6" name="ContentClass_ibCaptcha_noiseLevel_{$class_attribute.id}" type="text" value="{$class_attribute.data_float1}"/>   
</div>

<input type="hidden" value={""|ezroot} id="ibCaptchaImagesPath" />
<div class="block">
    <div class="element">
        <label>
            {'Characters color'|i18n('ibcaptcha/class/view')}    
        </label>
        <div class="labelbreak"></div>
        <input maxlength="6" size="6" name="ContentClass_ibCaptcha_charsColor_{$class_attribute.id}" type="text" value="{$class_attribute.data_text1}" id="charsColor{$class_attribute.id}"/> <img id="charsColorColorPicker{$class_attribute.id}" src={"colorpicker/rainbow.png"|ezimage} alt="{'Choose color'|i18n('ibcaptcha/class/edit')}" title="{'Choose color'|i18n('ibcaptcha/class/edit')}" width="16" height="16" /> 
    </div>

    <div class="element">
        <label>
            {'Background color'|i18n('ibcaptcha/class/view')}  
        </label>
        <div class="labelbreak"></div>
        <input maxlength="6" size="6" name="ContentClass_ibCaptcha_bgColor_{$class_attribute.id}" type="text" value="{$class_attribute.data_text2}" id="bgColor{$class_attribute.id}" /> <img id="bgColorColorPicker{$class_attribute.id}" src={"colorpicker/rainbow.png"|ezimage} alt="{'Choose color'|i18n('ibcaptcha/class/edit')}" title="{'Choose color'|i18n('ibcaptcha/class/edit')}" width="16" height="16" />  
    </div>

    <div class="element">
        <label>
            {'Noise color'|i18n('ibcaptcha/class/view')}
        </label>
        <div class="labelbreak"></div>
        <input maxlength="6" size="6" name="ContentClass_ibCaptcha_noiseColor_{$class_attribute.id}" type="text" value="{$class_attribute.data_text3}" id="noiseColor{$class_attribute.id}" /> <img id="noiseColorColorPicker{$class_attribute.id}" src={"colorpicker/rainbow.png"|ezimage} alt="{'Choose color'|i18n('ibcaptcha/class/edit')}" title="{'Choose color'|i18n('ibcaptcha/class/edit')}" width="16" height="16" />  
    </div>
</div>

<script type="text/javascript">
window.addEvent('domready', function() {ldelim} 
	setAllColorPickers( {$class_attribute.id} );
{rdelim} );	
</script>

<div class="block">
    <label>
        {'User`s content objectd ID, for whom captcha will not be used (separated by comma)'|i18n('ibcaptcha/class/edit')}
    </label>
    <div class="labelbreak"></div>
    <input class="box" name="ContentClass_ibCaptcha_notUsedUserIDs_{$class_attribute.id}" type="text" value="{$class_attribute.data_text4}"/>   
</div>