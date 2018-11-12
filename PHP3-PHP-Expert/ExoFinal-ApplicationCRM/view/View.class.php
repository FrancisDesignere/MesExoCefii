<?php

class View
{
    protected $page;
    protected $frm;

    
    /** le constructeur rempli déjà $page avec le header et la nav*/
    function __construct() {
        $this->page = file_get_contents('view/html/header.html');
        $this->page .= file_get_contents('view/html/nav.html');
    }

    // fonction appelée par les view fille qui gère les formulaire spécifique aux entités
    protected function displayForm(){
        // ajout du token (dans le formulaire et la session)
        $token = uniqid(rand(), true);
        $_SESSION['token']=$token;
        $this->frm = str_replace('{token}', $token, $this->frm);
        // insertion du frm dans la page 
        $this->page .= $this->frm;
        $this->display();
    }    
    
    /** Affichage de la page (après l'avoir complété du footer) */
    protected function display() {
        $this->page .= file_get_contents('view/html/footer.html');
        echo $this->page;
    }
    
    /** Alimentation de la page demandée */
    public function displayPageHtml($page){
        $fileName = 'view/html/' . $page . '.html';
        if(file_exists($fileName)===true){ 
            $this->page .= file_get_contents($fileName);
        }else{
            $this->page .= "Else : il n'existe pas de page ".$fileName;
        }
        $this->display();          
    }
            
    /** Alimentation de la page liste à partir d’un tableau reçu par paramètre */
    public function displayList($list, $entite) {
        $entete = true;
        $token = uniqid(rand(), true);
        $_SESSION['token']= $token;

        // ajout d'un boutou pour ajout d'un item
        $this->page .= '<h3>Liste des '.$entite.'s</h3>';
        
        // construction d'un tableau pour afficher la liste des enregistrements
        $this->page .= '<table class="table table-bordered table-striped table-condensed">';
        foreach ($list as $item) {
            //gestion de la ligne d'entete (nom des attributs au passage du premier item)
            if ($entete){
                $this->page .= "<tr>";
                foreach ($item as $key=>$element) {
                    $this->page .= "<th>".$key."</th>";        
                }
                $this->page .= "<td>Action</td>";
                $this->page .= "</tr>";
                $entete = FALSE;
            };
           // gestion des valeurs
            $this->page .= "<tr>";
                // boucle sur toutes les valeur d'attribut
                foreach ($item as $key=>$element) {
                    $this->page .= "<td>".$element."</td>";
                }

                //colonne spécifique pour les icones d'action
                $this->page .= "<td>";
                    // les bouton correspondant aux actions 
                    $this->page .= '<button form="frmUpdt'.$item['id'].'" id="validModifItem" type="submit" title="modifier" ><span class="glyphicon glyphicon-pencil" ></span></button>'; 
                    $this->page .= '<button form="frmDel'.$item['id'].'" id="validModifItem" type="submit" title="modifier" ><span class="glyphicon glyphicon-trash" ></span></button>'; 
                $this->page .= "</td></div>";
                // les formulaires correspondants aux actions (cachés, mais nécessaires...)
                //le formulaire de maj
                $this->page .= '<div class="display-inline"><form id="frmUpdt'.$item['id'].'" action="./index.php?action=frm&entite='.$entite.'" method="POST">';
                    $this->page .= '<input type="hidden" name="itemId" value="'.$item['id'].'">';
                    $this->page .= '<input type="hidden" name="token" value="'.$token.'">';
                $this->page .= '</form></div>';
                //formulaire de suppression
                $this->page .= '<div class="display-inline"><form id="frmDel'.$item['id'].'" action="./index.php?action=frmDel&entite='.$entite.'" method="POST">';
                    $this->page .= '<input type="hidden" name="itemId" value="'.$item['id'].'">';
                    $this->page .= '<input type="hidden" name="token" value="'.$token.'">';
                $this->page .= '</form>'; 

            $this->page .= "</tr>";
        }
        $this->page .= "</table>";
        
        // ajout d'un boutou pour ajout d'un item
        $this->page .= "<div>";
        $this->page .= '<a href="./?action=frm&entite='.$entite.'"><button><i class="glyphicon glyphicon-plus"></i> Ajout </button></a>'; 
        $this->page .= "<div>";
        $this->display();
    }
}