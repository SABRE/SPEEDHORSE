{* Adding comment START *}
{def $user=fetch( 'user', 'current_user' )}
{def $anonymous_user_id=ezini('UserSettings', 'AnonymousUserID' )}
{def $is_anonymous=$user.contentobject_id|eq( $anonymous_user_id )}
{def $comment_notified=ezini( 'GlobalSettings', 'EnableNotification', 'ezcomments.ini' )}
{def $fields = ezini( 'FormSettings', 'AvailableFields', 'ezcomments.ini' )}
{def $fieldRequiredText = '<span class="ezcom-field-mandatory">*</span>'}

<form name="CommentAdd">
<input type="hidden" name="ContentObjectID" value="{$contentobject_id}" />
<input type="hidden" name="CommentLanguageCode" value="{$language_code}" />
<input type="hidden" name="RedirectURI" value={$redirect_uri|ezurl( , 'full' )} />

<div class="ezcom-add">
        <div class="ezcom-function-title">
            <h4>
             {'Post comment'|i18n( 'ezcomments/comment/add/form' )}
            </h4>
        </div>

        {if $fields|contains( 'title' )}
        {def $titleRequired = ezini( 'title', 'Required', 'ezcomments.ini' )|eq( 'true' )}
            <div class="ezcom-field ezcom-field-title">
                <label>
                    {'Title:'|i18n( 'ezcomments/comment/add/form' )}{if $titleRequired}{$fieldRequiredText}{/if}
                </label>
                <input type="text" class="box" maxlength="100" name="CommentTitle" />
            </div>
        {undef $titleRequired}
        {/if}

        {if $fields|contains( 'name' )}
        {def $nameRequired = ezini( 'name', 'Required', 'ezcomments.ini' )|eq( 'true' )}
            <div class="ezcom-field ezcom-field-name">
                <div class="ezcom-filed-error"></div>
                <label>
                    {'Name:'|i18n( 'ezcomments/comment/add/form' )}{if $nameRequired}{$fieldRequiredText}{/if}
                </label>
                <input type="text" class="box" maxlength="50" id="CommentName" name="CommentName" />
            </div>
        {undef $nameRequired}
        {/if}

        {if $fields|contains( 'website' )}
        {def $websiteRequired = ezini( 'website', 'Required', 'ezcomments.ini' )|eq( 'true' )}
            <div class="ezcom-field ezcom-field-website">
                <label>
                    {'Website:'|i18n( 'ezcomments/comment/add/form' )}{if $websiteRequired}{$fieldRequiredText}{/if}
                </label>
                <input type="text" class="box" maxlength="100" name="CommentWebsite" />
            </div>
        {undef $websiteRequired}
        {/if}

        {if $fields|contains( 'email' )}
        {def $emailRequired = ezini( 'email', 'Required', 'ezcomments.ini' )|eq( 'true' )}
            <div class="ezcom-field ezcom-field-email">
                <label>
                    {'Email:'|i18n( 'ezcomments/comment/add/form' )}{if $emailRequired}{$fieldRequiredText}{/if}&nbsp;<span class="ezcom-field-emailmessage">{'(The email address will not be shown)'|i18n( 'ezcomments/comment/add/form' )}</span>
                </label>
                {if $is_anonymous|not}
                    <input type="text" maxlength="100" class="box" id="CommentEmail" disabled="disabled" />
                    <input type="hidden" name="CommentEmail" />
                {else}
                    <input type="text" maxlength="100" class="box" id="CommentEmail" name="CommentEmail" />
                {/if} 
            </div>
        {undef $emailRequired}
        {/if}

        <div class="ezcom-field ezcom-field-content">
            <label>
                {'Content:'|i18n( 'ezcomments/comment/add/form' )}{$fieldRequiredText}
            </label>
            <textarea class="box" name="CommentContent" rows="" cols=""></textarea>
        </div>

        {if $fields|contains( 'notificationField' )}
            {* When email is enabled or email is enabled but user logged in *}
            {if or( $fields|contains( 'email' ), and( $fields|contains( 'email' )|not, $is_anonymous|not ) )}
                <div class="ezcom-field ezcom-field-notified">
                    <label>
                        <input type="checkbox" name="CommentNotified" {if $comment_notified|eq('true')}checked="checked"{/if} value="1" />
                        {'Notify me of new comments'|i18n( 'ezcomments/comment/add/form' )}
                    </label>
                </div>
            {/if}
        {/if}
        {if $fields|contains( 'recaptcha' )}
            {*Fetch captcha limitation*}
             {def $bypass_captcha = fetch( 'comment', 'has_access_to_security', hash( 'limitation', 'AntiSpam',
                                                                                        'option_value', 'bypass_captcha' ) )}
             {if $bypass_captcha|not}
                <div class="ezcom-field ezcom-field-recaptcha">
                  <fieldset>
                  <legend>{'Security text:'|i18n( 'ezcomments/comment/add/form' )}{$fieldRequiredText}</legend>
                       <script type="text/javascript">
                       {literal}
                           jQuery(document).ready(function($){
                               $('#ngcomments_recaptcha_html').appendTo('.ezcom-field-recaptcha fieldset').show();
                           });
                       {/literal}
                       </script>
                 </fieldset>
                </div>
             {/if}
             {undef $bypass_captcha}
        {/if}
        {if $is_anonymous}
            <div class="ezcom-field ezcom-field-remember">
                <label>
                    <input type="checkbox" name="CommentRememberme" checked="checked" value="1" />
                    {'Remember me'|i18n( 'ezcomments/comment/add/form' )}
                </label>
            </div>
        {/if}
        <div class="ezcom-field ezcom-field-controls">
            <input type="button" value="{'Add comment'|i18n( 'ezcomments/comment/add/form' )}" class="button" name="AddCommentButton" />
            <input type="reset" class="button" value="{'Reset form'|i18n( 'ezcomments/comment/add/form' )}" />
            <div class="add-comment-loader" style="display:none"><img src={'ajax-loader-comments.gif'|ezimage} alt="{'Loading ...'|i18n( 'ngcomments/comment' )}" /></div>
        </div>
</div>
</form>

{ezscript_require( array( 'ezjsc::yui3', 'ezjsc::yui3io', 'ezcomments.js' ) )}

<script type="text/javascript">
eZComments.cfg = {ldelim}
                    postbutton: '#ezcom-post-button',
                    postform: '#ezcom-comment-form',
                    postlist: '#ezcom-comment-list',
                    postcontainer: '#ezcom-comment-list',
                    sessionprefix: '{ezini('Session', 'SessionNamePrefix', 'site.ini')}',
                    sortorder: '{ezini('GlobalSettings', 'DefaultEmbededSortOrder', 'ezcomments.ini')}',
                    fields: {ldelim} 
                                name: '#CommentName',
                                email: '#CommentEmail' 
                            {rdelim}
                 {rdelim};
eZComments.init();
</script>

{undef $comment_notified $fields}
{undef $user $anonymous_user_id $is_anonymous}
{* Adding comment END *}