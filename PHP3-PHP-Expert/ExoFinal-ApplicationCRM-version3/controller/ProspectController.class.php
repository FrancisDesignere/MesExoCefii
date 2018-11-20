<?php
/**
 * Controller pour les clients, il hérite de la classe Controller 
 * sur laquelle il repose en totalité
 */
class ProspectController extends Controller
{

private $view;
protected $model;


    public function __construct() 
    {
       parent::__construct();        
        $this->view = new ProspectView;
        $this->model = new PersonneModel;
    }
    
    public function frm($post=null){
        if ($post == null){
            $this->view->displayAdd(parent::$lstCateg);
        }else{
            $objItem = $this->model->getItemById($post);
            $this->view->displayUpdate($objItem, parent::$lstCateg);
        }
    }

    public function frmDel($post=null){
        $objItem = $this->model->getItemById($post);
        $this->view->displayDelete($objItem, parent::$lstCateg);
    }    


    public function maj($item){
        $this->model->update($item);
        if((isset($item['btnNewClient'])||(isset($item['fk_id_category']) && ($item['fk_id_category']>1) ))){
            $this->listItems('client');
        }else{
            $this->listItems();
        }
    }
    
    public function listItems($ent="prospect"){
        // ?? je ne comprend pas pourquoi j'ai du mettre cette condition
        // normalement la valeur par défaut est déclaré dans la signature, mais ça ne marche pas !!!
        if ($ent==''){$ent='prospect';}
        
        $list = $this->model->getList($ent);
        $this->view->displayList($list, $ent);        
    }
}    
