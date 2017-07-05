<?php

class CommentsModuleComment extends ObjectModel
{
    public $id_commentsmodule_comment;
    public $id_product;
    public $id_user;
    public $grade;
    public $comment;
    public $date_add;

    public static $definition =        array(
        'table'     =>  'commentsmodule_comment',
        'primary'   =>  'id_commentsmodule_comment',
        'multilang' =>  false,
        'fields'    => array(
            'id_product'    =>  array(
                    'type'      =>  self::TYPE_INT,
                    'validate'  =>  'isUnsignedId',
                    'required'  =>  true,
            ),
            'id_user'    =>  array(
                'type'      =>  self::TYPE_INT,
                'validate'  =>  'isUnsignedId',
                'required'  =>  true,
            ),
            'grade'         =>  array(
                    'type'      => self::TYPE_INT,
                    'validate'  =>  'isUnsignedInt'
            ),
            'comment'       =>  array(
                    'type'      =>  self::TYPE_HTML,
                    'validate'  =>  'isCleanHtml'
            ),
            'date_add'      =>  array(
                    'type'      =>  self::TYPE_DATE,
                    'validate'  =>  'isDate',
                    'copy_post' =>  false
            ),
        ),
    );

    public static function createTable()
    {
        $requete= "CREATE TABLE IF NOT EXISTS `"._DB_PREFIX_."commentsmodule_comment` (
        `id_commentsmodule_comment` int(11) NOT NULL AUTO_INCREMENT,
        `id_product` int(11) NOT NULL,
        `id_user` int(11) NOT NULL,
        `grade` tinyint(1) NOT NULL,
        `comment` text NOT NULL,
        `date_add` datetime NOT NULL,
        PRIMARY KEY (`id_commentsmodule_comment`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;";
        Db::getInstance()->execute($requete);
    }

    public static function getCommentsNumber($id_product)
    {
        $nb_comments=Db::getInstance()->getValue('SELECT COUNT(`id_product`) FROM `'._DB_PREFIX_.'commentsmodule_comment` WHERE `id_product` = '.(int)$id_product);
        return $nb_comments;
    }

    public static function getProductComments($id_product,$limit_start,$limit_end =false)
    {
        $limit=(int)$limit_start;
        if($limit_end)
        {
            $limit=(int)$limit_start.','.(int)$limit_end;
        }

        /*$comments = Db::getInstance()->executeS('
		SELECT * FROM `'._DB_PREFIX_.'commentsmodule_comment` WHERE `id_product` = '.(int)$id_product.' 
		ORDER BY `date_add` DESC LIMIT '.(int)$limit_start.','.(int)$limit_end);*/

        $requete="
        SELECT d.id_commentsmodule_comment, d.comment, d.date_add, d.grade, u.firstname, u.lastname
        FROM "._DB_PREFIX_."commentsmodule_comment d
        INNER JOIN "._DB_PREFIX_."customer u
        ON d.id_user = u.id_customer
        WHERE d.id_product =$id_product
        ORDER BY d.date_add
        DESC LIMIT $limit_start,$limit_end";
        $comments=Db::getInstance()->executeS($requete);

        return $comments;
    }

    public static function getProductAverageGrade($id_product)
    {
        $requete="SELECT grade FROM "._DB_PREFIX_."commentsmodule_comment WHERE id_product=$id_product";
        $average=Db::getInstance()->executeS($requete);

        $nb_grades=0;
        $moyenne=0;
        if($average!=null) {
            for ($i = 0; $i < sizeof($average); $i++) {
                $moyenne = $moyenne + (int)$average[$i]['grade'];
                $nb_grades++;
            }

            $moyenne = $moyenne / $nb_grades;
            $moyenne = round($moyenne, 2);

            return $moyenne;
        }
        else
            return $moyenne;
    }

    public static function getFrontOfficeComments($id_product)
    {
        //$requete="SELECT * FROM "._DB_PREFIX_."commentsmodule_comment WHERE id_product=$id_product ORDER BY date_add DESC LIMIT 3";
        $requete="
        SELECT d.id_commentsmodule_comment, d.comment, d.date_add, d.grade, u.firstname, u.lastname
        FROM "._DB_PREFIX_."commentsmodule_comment d
        INNER JOIN "._DB_PREFIX_."customer u
        ON d.id_user = u.id_customer
        WHERE d.id_product =$id_product
        ORDER BY d.date_add
        DESC LIMIT 3";
        $comments=Db::getInstance()->executeS($requete);

        return $comments;
    }



}