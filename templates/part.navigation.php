<!-- translation strings -->
<div style="display:none" id="new-password-string"><?php p($l->t('New password')); ?></div>

<script id="navigation-tpl" type="text/x-handlebars-template">
    <li id="new-password"><a href="#"><?php p($l->t('Add password')); ?></a></li>
    {{#each passwords}}
        <li class="password with-menu {{#if active}}active{{/if}}"  data-id="{{ id }}">
            <a href="#">{{ title }}</a>
            <div class="app-navigation-entry-utils">
                <ul>
                    <li class="app-navigation-entry-utils-menu-button svg"><button></button></li>
                </ul>
            </div>

            <div class="app-navigation-entry-menu">
                <ul>
                    <li><button class="delete icon-delete svg" title="delete"></button></li>
                </ul>
            </div>
        </li>
    {{/each}}
</script>

<ul></ul>