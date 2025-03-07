<?php
// ========== CONNEXION À LA BASE DE DONNÉES ==========
// Établit une connexion à la base de données MySQL avec les paramètres suivants :
// - Serveur : localhost
// - Utilisateur : root
// - Mot de passe : (vide)
// - Base de données : pokedex
$conn = new mysqli("localhost", "root", "", "pokedex");

// Vérifie si la connexion a réussi, sinon affiche un message d'erreur et arrête le script
if ($conn->connect_error) {
    die("Échec de la connexion: " . $conn->connect_error);
}

// Définit l'encodage de caractères à UTF-8 pour prendre en charge les accents et caractères spéciaux
$conn->set_charset("utf8");

// ========== REQUÊTE SQL ==========
// Cette requête récupère tous les Pokémon avec leurs informations et leurs types
// - La table principale est "pokemon" (alias p)
// - Jointure avec la table "types" (alias t1) pour le premier type
// - Jointure gauche avec la table "types" (alias t2) pour le second type (peut être NULL)
// - Les résultats sont triés par ID de Pokémon
$sql = "SELECT p.IdPokemon, p.nom, p.UrlPhoto, p.PV, p.PtsAttaque, p.PtsDefense, 
               p.PtsSpeciale, p.PtsVitesse, t1.nomtype as type1, t2.nomtype as type2 
        FROM pokemon p
        JOIN types t1 ON p.idType1 = t1.idType
        LEFT JOIN types t2 ON p.idType2 = t2.idType
        ORDER BY p.IdPokemon";

// Exécute la requête et stocke le résultat dans la variable $result
$result = $conn->query($sql);
?>

<!-- ========== DÉBUT DU HTML ========== -->
<!DOCTYPE html>
<html lang="fr">
<head>
<!-- Métadonnées de base -->
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Pokedex Du Professeur Chen</title>

<!-- Liens externes -->
<link rel="stylesheet" href="styles.css">
<link rel="icon" type="image/svg" href="assets/pokeball.svg" />

<!-- ========== STYLES CSS INTÉGRÉS ========== -->
<style>
    /* Style de base pour le tableau */
    table { 
        width: 100%; 
        border-collapse: collapse; /* Supprime l'espace entre les cellules */
    }
    
    /* Style pour les cellules et en-têtes */
    th, td { 
        padding: 8px; 
        text-align: left; 
        border-bottom: 1px solid #ddd; /* Ligne horizontale entre les rangées */
    }
    
    /* Fond gris pour les en-têtes */
    th { 
        background-color: #f2f2f2; 
    }
    
    /* Effet de survol sur les lignes */
    tr:hover { 
        background-color: #f5f5f5; 
    }
    
    /* Style pour les badges de type (aspect pilule) */
    .type-pill { 
        padding: 2px 8px; 
        border-radius: 10px; 
        display: inline-block; 
        color: white; 
        margin-right: 5px;
    }
    
    /* Couleurs spécifiques pour chaque type de Pokémon */
    .type-Plante { background-color: #78C850; }
    .type-Feu { background-color: #F08030; }
    .type-Eau { background-color: #6890F0; }
    .type-Insecte { background-color: #A8B820; }
    .type-Normal { background-color: #A8A878; }
    .type-Poison { background-color: #A040A0; }
    .type-Electrique { background-color: #F8D030; }
    .type-Sol { background-color: #E0C068; }
    .type-Vol { background-color: #A890F0; }
    .type-Combat { background-color: #C03028; }
    .type-Psy { background-color: #F85888; }
    .type-Spectre { background-color: #705898; }
    .type-Glace { background-color: #98D8D8; }
    .type-Roche { background-color: #B8A038; }
    .type-Dragon { background-color: #7038F8; }
    
    /* Taille fixe pour les images */
    img { 
        height: 50px; 
    }
</style>
</head>
<body>
<!-- ========== EN-TÊTE ========== -->
<header>
<!-- Lien vers la page d'accueil avec le titre -->
<a href="index.php">
    <h1>Pokedex Du Professeur Chen</h1>
</a>

<!-- Formulaire de recherche -->
<form id="search-bar" action="search_pokemon.php">
    <span class="input-group">
        <input id="q" name="q" type="search" placeholder="Rechercher un pokémon">
        <button type="submit">🔎</button>
    </span>
</form>
</header>

<!-- ========== STRUCTURE PRINCIPALE ========== -->
<div id="main-wrapper">
<!-- Inclusion du menu latéral -->
<?php require_once("menu.php"); ?>

<!-- Contenu principal -->
<main id="main">
    <!-- ========== TABLEAU DES POKÉMON ========== -->
    <table>
        <!-- En-tête du tableau -->
        <thead>
            <tr>
                <th>#</th>                 <!-- Numéro du Pokémon -->
                <th>Image</th>             <!-- Image du Pokémon -->
                <th>Nom</th>               <!-- Nom du Pokémon -->
                <th>Types</th>             <!-- Types du Pokémon -->
                <th>PV</th>                <!-- Points de vie -->
                <th>Attaque</th>           <!-- Points d'attaque -->
                <th>Défense</th>           <!-- Points de défense -->
                <th>Spéciale</th>          <!-- Points d'attaque spéciale -->
                <th>Vitesse</th>           <!-- Points de vitesse -->
            </tr>
        </thead>
        
        <!-- Corps du tableau (données des Pokémon) -->
        <tbody>
            <?php 
            // Vérifie si des Pokémon ont été trouvés
            if ($result->num_rows > 0): 
                // Boucle sur chaque Pokémon
                while ($row = $result->fetch_assoc()): 
            ?>
                <tr>
                    <!-- Affiche le numéro du Pokémon (formaté avec des zéros devant) -->
                    <td><?= str_pad($row["IdPokemon"], 3, '0', STR_PAD_LEFT) ?></td>
                    
                    <!-- Affiche l'image du Pokémon -->
                    <td><img src="<?= $row["UrlPhoto"] ?>" alt="<?= $row["nom"] ?>"></td>
                    
                    <!-- Affiche le nom du Pokémon -->
                    <td><?= $row["nom"] ?></td>
                    
                    <!-- Affiche les types du Pokémon (un ou deux) -->
                    <td>
                        <!-- Affiche toujours le premier type -->
                        <span class="type-pill type-<?= $row["type1"] ?>"><?= $row["type1"] ?></span>
                        
                        <!-- Affiche le second type uniquement s'il existe -->
                        <?php if ($row["type2"]): ?>
                            <span class="type-pill type-<?= $row["type2"] ?>"><?= $row["type2"] ?></span>
                        <?php endif; ?>
                    </td>
                    
                    <!-- Affiche les statistiques du Pokémon -->
                    <td><?= $row["PV"] ?></td>
                    <td><?= $row["PtsAttaque"] ?></td>
                    <td><?= $row["PtsDefense"] ?></td>
                    <td><?= $row["PtsSpeciale"] ?></td>
                    <td><?= $row["PtsVitesse"] ?></td>
                </tr>
            <?php 
                endwhile; 
            else: 
                // Message affiché si aucun Pokémon n'est trouvé
            ?>
                <tr><td colspan="9">Aucun Pokémon trouvé.</td></tr>
            <?php endif; ?>
        </tbody>
    </table>
</main>
</div>

<!-- ========== FERMETURE DE LA CONNEXION ========== -->
<?php 
// Ferme la connexion à la base de données pour libérer les ressources
$conn->close(); 
?>
</body>
</html>