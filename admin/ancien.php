<?php
 
 class Ancien
{
    
    
    private $sql;
    function prepareData($data)
    {
        
        $data=str_replace("_"," ",$data);
        $data=str_replace("^","'",$data);
        $data=str_replace("@","%",$data);
        return $data;
    }
    function getData($table, $nor,$champ_tri, $ordre,$conds)
    {
        include("../database/connexion.php");
        if($conds!=null){
           $conds = $this->prepareData($conds);
        }else{
           $conds=1; 
        }
        $limite=($nor!=null)?"limit ".$nor:"";
        
        $orderclause="";
        if($champ_tri!=null ){
            $orderclause.="order by ".$champ_tri;
        }
        if($ordre!=null ){
            $orderclause.=" ".$ordre;
        }
        
        $this->sql =
            "SELECT * FROM `$table` WHERE $conds $orderclause $limite;";
            echo $this->sql;
            try{
                $req = $bdd->prepare($this->sql);
                $req->execute();
                $result= array();
                while ( $row  = $req->fetch(PDO::FETCH_ASSOC)) {  
                  array_push($result, $row);
              }
              echo json_encode($result, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);

          }
          catch(Exception $e)
          {
              die('Erreur : ' . $e->getMessage());
          }
        
    }
    function getTestData($table, $nor,$champ_tri, $ordre,$conds)
    {
        if($conds!=null){
           $conds = $this->prepareData($conds); 
        }else{
           $conds=1; 
        }
        $orderclause="";
        if($champ_tri!=null ){
            $orderclause.="order by ".$champ_tri;
        }
        if($ordre!=null ){
            $orderclause.=" ".$ordre;
        }
        
        
            echo "Debug (You have defined) :\n
            table: $table\n
            conds: $conds\n
            orderclause: $orderclause\n
            nor: $nor.";
        
    }
    function checkDeleteData($table, $field,$value){
        include("../database/connexion.php");
    $this->getData($table, null,null, null,"$field<$value");
    }
    function deleteData($table, $field,$value){
        include("../database/connexion.php");
        $this->sql =
            "DELETE FROM  `$table` WHERE $field < $value ;";
            try{
                $req = $bdd->prepare($this->sql);
                $req->execute();
    }catch(Exception $e)
    {
        die('Erreur : ' . $e->getMessage());
    }
    }
}
?>