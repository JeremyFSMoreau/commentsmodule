<?php

require_once (dirname(__FILE__).'/classes/CommentsModuleComment.php');

class CommentsModule extends Module
{
    public function __construct()
    {
        $this->name='commentsmodule';
        $this->author='Equipe ESGI';
        $this->version="1.0";
        $this->displayName=$this->l('My comments module');
        $this->description=$this->l('A module for adding comments and notes');
        $this->bootstrap=true;
        parent::__construct();
    }

    public function install()
    {
        parent::install();
        // création de la table
        CommentsModuleComment::createTable();
        $this->registerHook('displayReassurance');
        $this->registerHook('actionFrontControllerSetMedia');
        return true;
    }

    public function hookActionFrontControllerSetMedia($params){
        $this->context->controller->registerJavascript(
            'monscript',
            'modules/'.$this->name.'/js/commentsmodule.js',
            [	'priority' => 10,
            ]
        );

    }

    public function hookDisplayReassurance($params)
    {
        $id_produit=Tools::getValue('id_product');
        if($id_produit != false) {
            $this->processComments();
            $this->assignConfigValues();
            $this->assignCommentsFrontOffice();
            return $this->display(__FILE__, 'displayReassurance.tpl');
        }
    }

    public function getContent(){
        $this->processFormConfig();
        $htmlConfirm=$this->fetch(_PS_MODULE_DIR_ ."commentsmodule/views/templates/hook/getContent.tpl");
        $htmlConfirm.=$this->fetch("module:commentsmodule/views/templates/hook/getContent.tpl");
        $htmlForm=$this->renderForm();
        return $htmlConfirm.$htmlForm;
        //$this->assignConfigValues();
        //return $this->display(__FILE__,'getContent.tpl');
    }

    public function processFormConfig(){
        if(Tools::isSubmit('submit_commentsmodule_form')){
            //enregistrer les modifications de config
            $enable_grades=Tools::getValue('enable_grades');
            $enable_comments=Tools::getValue('enable_comments');

            Configuration::updateValue('MONMOD_GRADES',$enable_grades);
            Configuration::updateValue('MONMOD_COMMENTS',$enable_comments);

            // confirmation dans le smarty

            $this->context->smarty->assign('confirmation','ok');

        }
    }

    public function processComments(){
        if (Tools::isSubmit('commentsmodule_pc_submit_comment')){
            $grade=Tools::getValue('grade');
            $comment=Tools::getValue('comment');
            $id_produit=Tools::getValue('id_product');
            $id_user=$this->context->customer->id;

            /*$data = array(
                'id_product' => (int)$id_produit,
                'grade' => (int)$grade,
                'comment' => pSQL($comment),
                'date_add' => date('Y-m-d H:i:s'),
            );
            Db::getInstance()->insert('commentsmodule_comment', $data);*/

            $CommentsModuleComment = new CommentsModuleComment();
            $CommentsModuleComment->id_product = $id_produit;
            $CommentsModuleComment->id_user = $id_user;
            $CommentsModuleComment->grade = $grade;
            $CommentsModuleComment->comment=$comment;
            $CommentsModuleComment->add();

            $this->context->smarty->assign('scroll','ok');

        }

    }

    public function renderForm(){
        $fields_form = array(
            'form' => array(
                'legend' => array(
                    'title' => $this->l('My Module configuration'),
                    'icon' => 'icon-envelope'
                ),
                'input' => array(
                    array(
                        'type' => 'switch',
                        'label' => $this->l('Enable grades:'),
                        'name' => 'enable_grades',
                        'desc' => $this->l('Enable grades on products.'),
                        'values' => array(
                            array(
                                'id' => 'enable_grades_1',
                                'value' => 1,
                                'label' => $this->l('Enabled')
                            ),
                            array(
                                'id' => 'enable_grades_0',
                                'value' => 0,
                                'label' => $this->l('Disabled')
                            )
                        ),
                    ),
                    array(
                        'type' => 'switch',
                        'label' => $this->l('Enable comments:'),
                        'name' => 'enable_comments',
                        'desc' => $this->l('Enable comments on products.'),
                        'values' => array(
                            array(
                                'id' => 'enable_comments_1',
                                'value' => 1,
                                'label' => $this->l('Enabled')
                            ),
                            array(
                                'id' => 'enable_comments_0',
                                'value' => 0,
                                'label' => $this->l('Disabled')
                            )
                        ),
                    ),
                ),
                'submit' => array(
                    'title' => $this->l('Save'),
                )
            ),
        );

        $helper = new HelperForm();
        $helper->table = 'commentsmodulecomments';
        $helper->default_form_language = (int)Configuration::get('PS_LANG_DEFAULT');
        $helper->allow_employee_form_lang = (int)Configuration::get('PS_BO_ALLOW_EMPLOYEE_FORM_LANG');
        $helper->submit_action = 'submit_commentsmodule_form';
        $helper->currentIndex = $this->context->link->getAdminLink('AdminModules',
                false).'&configure='.$this->name.'&tab_module='.$this->tab.'&module_name='.$this->name;
        $helper->token = Tools::getAdminTokenLite('AdminModules');
        $helper->tpl_vars = array(
            'fields_value' => array(
                'enable_grades' => Tools::getValue('enable_grades',Configuration::get('MONMOD_GRADES')),
                'enable_comments' => Tools::getValue('enable_comments',Configuration::get('MONMOD_COMMENTS')),
            ),
            'languages' => $this->context->controller->getLanguages()
        );
        return $helper->generateForm(array($fields_form));

    }

    public function assignCommentsFrontOffice(){

        $id_produit=Tools::getValue('id_product');
        $comments = CommentsModuleComment::getFrontOfficeComments($id_produit);
        $moyenne = CommentsModuleComment::getProductAverageGrade($id_produit);

        $this->context->smarty->assign('id_product',$id_produit);
        $this->context->smarty->assign('moyenne',$moyenne);
        $this->context->smarty->assign('commentaires',$comments);
        $this->context->smarty->assign('logged',$this->context->customer->isLogged());
    }


    public function assignConfigValues(){

        $enable_grades=Configuration::get('MONMOD_GRADES');
        $enable_comments=Configuration::get('MONMOD_COMMENTS');

        $this->context->smarty->assign('enable_grades',$enable_grades);
        $this->context->smarty->assign('enable_comments',$enable_comments);

    }








}