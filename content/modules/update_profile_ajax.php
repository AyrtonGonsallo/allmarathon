<?php
if(isset($_POST['function']) && ($_POST['function']=="check_if_exist") ){

    if (isset($_POST['nom']) && isset($_POST['prenom'])) {
        // Récupération des données POST
        $nom = $_POST['nom'];
        $prenom = $_POST['prenom'];
    
        // Concatenation pour rechercher le nom complet
        $nom_complet1 = $nom . ' ' . $prenom;
        $nom_complet2 = $prenom . ' ' . $nom;
    
        // Connexion à la base de données
        try {
            include("../database/connexion.php");
    
            // Préparation de la requête SQL avec des paramètres nommés
            $stmt = $bdd->prepare("SELECT * FROM `champions` WHERE nom LIKE :nom_complet1 OR nom LIKE :nom_complet2");
            $stmt->execute([
                ':nom_complet1' => '%' . $nom_complet1 . '%',
                ':nom_complet2' => '%' . $nom_complet2 . '%',
            ]);
    
            // Récupération des résultats
            $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
            // Envoi de la réponse au format JSON
            echo json_encode($results);
    
        } catch (PDOException $e) {
            echo 'Erreur : ' . $e->getMessage();
        }
    } else {
        echo 'Les données nom et prenom sont manquantes.';
    }
}
else if(isset($_POST['function']) && ($_POST['function']=="get_athlete_datas") ){

    if (isset($_POST['selected_id'])) {
        if($_POST['selected_id']=="none"){

            // Récupération des données POST
            $user_id = $_POST['user_id'];
        
            // Connexion à la base de données
            try {
                include("../database/connexion.php");
            
                // Préparation de la requête SQL avec des paramètres nommés
                $stmt1 = $bdd->prepare("SELECT * FROM `users` WHERE id = :user_id");
                $stmt1->bindParam(':user_id', $user_id, PDO::PARAM_INT);
                $stmt1->execute();
            
                // Récupération du résultat
                $result = $stmt1->fetch(PDO::FETCH_ASSOC);
            
                if ($result) {
                    
                    // Insertion du nouveau champion
                    $stmt2 = $bdd->prepare("INSERT INTO `champions` 
                    (user_id, `Nom`, `Taille`, `Poids`, `Site`, DateChangementNat, `NvPaysID`, `Lien_site_équipementier`, `Facebook`, `Equipementier`, `Instagram`, `Bio`) 
                    VALUES (:user_id, :user_name, 180, 65, '', '9999-12-31', 'AFG', '', '', '', '', '')");
                        
                    
                    // Bind parameters
                    $stmt2->bindValue(':user_id', $user_id, PDO::PARAM_INT);
                    $stmt2->bindValue(':user_name', $result["nom"] . " " . $result["prenom"], PDO::PARAM_STR);
                    
                    // Execute statement
                    $stmt2->execute();
            
                    // Récupérer l'ID du dernier enregistrement inséré
                    $champion_id = $bdd->lastInsertId();
            
                    // Récupérer les détails du champion créé
                    $stmt3 = $bdd->prepare("SELECT * FROM `champions` WHERE ID = :champion_id");
                    $stmt3->bindParam(':champion_id', $champion_id, PDO::PARAM_INT);
                    $stmt3->execute();
            
                    // Récupérer le champion créé
                    $champion = $stmt3->fetch(PDO::FETCH_ASSOC);
            
                    // Envoi de la réponse au format JSON
                    echo json_encode($champion);
                } else {
                    echo json_encode(['error' => 'User not found']);
                }
            
            } catch (PDOException $e) {
                echo json_encode(['error' => 'Erreur : ' . $e->getMessage()]);
            }
        }else{

            // Récupération des données POST
            $selected_id = $_POST['selected_id'];
            $user_id = $_POST['user_id'];
            // Connexion à la base de données
            try {
                include("../database/connexion.php");
                $stmt1 = $bdd->prepare("Update `champions` set user_id=:user_id WHERE ID = :selected_id");
                $stmt1->bindParam(':selected_id', $selected_id, PDO::PARAM_INT);
                $stmt1->bindParam(':user_id', $user_id, PDO::PARAM_INT);
                $stmt1->execute();

                  // Préparation de la requête SQL avec des paramètres nommés
                $stmt = $bdd->prepare("SELECT * FROM `champions` WHERE ID = :selected_id");
                $stmt->bindParam(':selected_id', $selected_id, PDO::PARAM_INT);
                $stmt->execute();

                // Récupération des résultats
                $result = $stmt->fetch(PDO::FETCH_ASSOC);
        
                // Envoi de la réponse au format JSON
                echo json_encode($result);
        
            } catch (PDOException $e) {
                echo json_encode(['error' => 'Erreur : ' . $e->getMessage()]);
            }
        }

    } else {
        echo 'La donnée selected_id est manquante.';
    }
}
else if (isset($_GET['search_event'])) {
    $search = $_GET['search_event'];
    try {
        include("../database/connexion.php");
        $stmt = $bdd->prepare("SELECT e.ID,m.prefixe,m.Nom,Year(e.DateDebut) as annee FROM `evenements` e,marathons m WHERE e.DateDebut<:today and m.id=e.marathon_id and LOWER(m.Nom) LIKE LOWER(:search) order by e.DateDebut asc");
        $stmt->bindValue(':search', '%' . $search . '%', PDO::PARAM_STR);
        $stmt->bindValue('today', date('Y-m-d'), PDO::PARAM_STR); 
        $stmt->execute();
        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
        // Encode les résultats en JSON et les renvoie
        echo json_encode($results);
    } catch (PDOException $e) {
        echo json_encode(['error' => 'Erreur : ' . $e->getMessage()]);
    }
}





?>