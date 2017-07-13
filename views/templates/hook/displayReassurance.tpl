<div id="bloc_commentaires" {if isset($scroll)}data-scroll="true"{/if}>
    <h2 class="page-product-heading">{l s='Reviews' mod='commentsmodule'}</h2>
    {if $enable_grades eq 1 and $moyenne > 0}
        <h3>{l s='Note moyenne : ' mod='commentsmodule'} {$moyenne}/5 </h3><br>
    {/if}

    {if $enable_comments eq 1 or $enable_grades eq 1}
        {foreach from=$commentaires item=commentaire}
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
                </p><br>
            </div>
        {/foreach}

        <div class="rte">
            {assign var=params value=
                [
                    'id_product'=> $smarty.get.id_product
                ]
            }
            <p align="left">
                <a href="{url entity='module' name='commentsmodule' controller='monControleur' params=$params}">
                    {l s='See all comments' mod='commentsmodule' }
                </a>
            </p>

        </div>
        {if $logged}
            <div class="rte">
                <form action="" method="POST" id="comment-form">
                    <div class="form-group">
                        {if $enable_grades eq 1}
                            <label for="grade">Note:</label><br>
                            <div class="col-xs-5">
                                <select id="grade" class="form-control" name="grade">
                                    <option value="" disabled selected>{l s='-- Choose --' mod='commentsmodule'}</option>
                                    <option value="1">1</option>
                                    <option value="2">2</option>
                                    <option value="3">3</option>
                                    <option value="4">4</option>
                                    <option value="5">5</option>
                                </select>
                            </div>
                        {else}
                            {l s='Grades disabled' mod='commentsmodule'}
                        {/if}
                    </div><br><br>
                    {if $enable_comments eq 1}
                        <div class="form-group">
                            <label for="comment">{l s='Comment : ' mod='commentsmodule'}</label>
                            <textarea name="comment" id="comment" class="form-control"></textarea>
                        </div>
                    {else}
                        {l s='Comments disabled.' mod='commentsmodule'}
                    {/if}
                    <div class="submit">
                        <button type="submit" name="commentsmodule_pc_submit_comment" class="btn btn-primary">
                        <span>Envoyer
                        <i class="icon-chevron-right right"></i>
                        </span>
                        </button>
                    </div>
                </form>
            </div>
        {else}
            <p>{l s='You have to be connected if you want to review this product.' mod='commentsmodule'}</p>
        {/if}

    {else}
        {l s='Comments and grades disabled' mod='commentsmodule'}
    {/if}
</div>
