{extends file='page.tpl'}


{block name='page_content'}
    <h1>{l s='Comments on product' mod='commentsmodule'} {$product->name}</h1><br>
    <a href="{$url_product}">{l s='Return to product\'s page' mod='commentsmodule'}</a><br><br>
    {if $enable_grades eq 1}
        <h2>Note moyenne : {$moyenne}/5</h2><br>
    {/if}
    <div>

        {foreach from=$comments item=commentaire}
            <div class="comment">
                {if $enable_grades eq 1 and $enable_comments eq 0}
                    <h3>{l s='Grade by ' mod='commentsmodule'} {$commentaire.firstname} {$commentaire.lastname[0]}. ({$commentaire.date_add|date_format:"%d.%m.%y"}) :</h3>
                {/if}
                {if $enable_grades eq 0 and $enable_comments eq 1}
                    <h3>{l s='Comment by ' mod='commentsmodule'} {$commentaire.firstname} {$commentaire.lastname[0]}. ({$commentaire.date_add|date_format:"%d.%m.%y"}) :</h3>
                {/if}
                {if $enable_grades eq 1 and $enable_comments eq 1}
                    <h3>{l s='Comment and grade by ' mod='commentsmodule'} {$commentaire.firstname} {$commentaire.lastname[0]}. ({$commentaire.date_add|date_format:"%d.%m.%y"}) :</h3>
                {/if}
                {if $enable_grades eq 1}

                    <b>{$commentaire.grade}</b>/5 <br><br>

                {/if}
                {if $enable_comments eq 1}
                    <p>{$commentaire.comment}</p>
                {/if}
                <hr>
            </div>
        {/foreach}


    </div>

    <ul class="pagination">
        {for $count=1 to $nb_pages}
            {assign var=params value=[
            'id_product' => $smarty.get.id_product,
            'page' => $count
            ]}
            {if $pageEnCours ne $count}
                <li>
                    <a href="{url entity='module' name='commentsmodule' controller='monControleur' params = $params}">
                        <span>{$count}</span>
                    </a>
                </li>
            {else}
                <li class="active current">
                    <span>{$count}</span>
                </li>
            {/if}
        {/for}
    </ul>
    <style>
        .panel_homeLogo{
            background-image: url('{$link->getImageLink($product_r,$id_image,'large_default')}');
        }
        #logo-background{
            visibility: hidden;
        }
        #logo-container{
            visibility: hidden;
        }
    </style>
{/block}