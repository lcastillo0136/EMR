{if not $TextEdit->GetReadOnly()}<input {if $TextEdit->GetPasswordMode()}type="password"{/if} class="sm_text" id="{$TextEdit->GetName()}" name="{$TextEdit->GetName()}" value="{$TextEdit->GetHTMLValue()}" {if $TextEdit->GetSize() != null}size="{$TextEdit->GetSize()}" style="width: auto;"{/if} {if $TextEdit->GetMaxLength() != null}maxlength="{$TextEdit->GetMaxLength()}"{/if}>{else}
{if !$TextEdit->GetPasswordMode()}{$TextEdit->GetValue()}{else}*************{/if}{/if}