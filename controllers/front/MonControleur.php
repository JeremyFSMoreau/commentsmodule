<?php

class CommentsModuleMonControleurModuleFrontController extends ModuleFrontController
{
    public function initContent()
    {
        parent::initContent();
        $this->initList();
        $this->setTemplate('module:commentsmodule/views/templates/front/list.tpl');
    }



    public function initList()
    {
        //Création de l'objet
        $id_product=Tools::getValue('id_product');
        $link=new Link();
        $url_product=$link->getProductLink($id_product);
        $this->product = new Product((int)$id_product, false, $this->context->cookie->id_lang);
        $enable_grades=Configuration::get('MONMOD_GRADES');
        $enable_comments=Configuration::get('MONMOD_COMMENTS');

        // PAGINATION

        // Récupération du nombre de commentaires
        $nb_comments = CommentsModuleComment::getCommentsNumber($id_product);

        $page=1;
        if(Tools::getValue('page')!=''){
            $page=Tools::getValue('page');
        }

        // Initialisation
        $nb_per_page = 10;

        $nb_page=ceil($nb_comments/$nb_per_page);

        $limit_start = ($page - 1) * $nb_per_page;
        $limit_end = $nb_per_page;
        $comments= CommentsModuleComment::getProductComments($id_product,$limit_start,$limit_end);

        $moyenne=CommentsModuleComment::getProductAverageGrade($id_product);


        $product = new Product((int)Tools::getValue('id_product'));
        $images = Image::getImages($this->context->cookie->id_lang, (int)$id_product);

        $this->context->smarty->assign('id_image', (int)$images[0]['id_image']);
        $this->context->smarty->assign('product_r', $product->link_rewrite[1]);

        //envoyer au smarty
        $this->context->smarty->assign('product',$this->product);
        $this->context->smarty->assign('url_product',$url_product);

        $this->context->smarty->assign('moyenne',$moyenne);
        $this->context->smarty->assign('comments',$comments);

        $this->context->smarty->assign('pageEnCours',$page);
        $this->context->smarty->assign('nb_pages',$nb_page);

        // si activé
        $this->context->smarty->assign('enable_grades',$enable_grades);
        $this->context->smarty->assign('enable_comments',$enable_comments);



    }

}