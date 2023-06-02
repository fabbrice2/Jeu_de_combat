
<!DOCTYPE html>
<html>
<head>
    <title>Jeu de combat</title>
</head>
<body>
    <h1>Jeu de combat</h1>

    <form method="POST" action="<?php echo $_SERVER["PHP_SELF"]; ?>">
    <!-- PHP_SELF renvoie le nom du fichier en cours d'exécution -->
        <h2>Personnage 1</h2>
        <label for="nomPersonnage1">Nom :</label>
        <input type="text" name="nomPersonnage1" required><br>
        <label for="typePersonnage1">Type :</label>
        <select name="typePersonnage1" required>
            <option value="guerrier">Guerrier</option>
            <option value="magicien">Magicien</option>
        </select><br>

        <h2>Personnage 2</h2>
        <label for="nomPersonnage2">Nom :</label>
        <input type="text" name="nomPersonnage2" required><br>
        <label for="typePersonnage2">Type :</label>
        <select name="typePersonnage2" required>
            <option value="guerrier">Guerrier</option>
            <option value="magicien">Magicien</option>
        </select><br><br>

        <input type="submit" name="attaquer" value="Attaquer">
        <input type="submit" name="defendre" value="Défendre"><br><br>
    </form>
</body>
</html>

<?php

// je cree ma classe 
class Personnage {

    //ici mes attributs
    public $nom;
    public $pointsDeVie;
    public $attaqueMin;
    public $attaqueMax;
    public $defense;
    public $endormi;
    public $dernierEndormissement;


    public function __construct($nom, $pointsDeVie, $attaqueMin, $attaqueMax, $defense) {
      
        $this->nom = $nom;
        $this->pointsDeVie = $pointsDeVie;
        $this->attaqueMin = $attaqueMin;
        $this->attaqueMax = $attaqueMax;
        $this->defense = $defense;
        $this->endormi = false;
        // time renvoie l'heure actuelle
        $this->dernierEndormissement = time(); 
    }

    //attaquer un adversaire
    public function attaquer($adversaire) {
        if (!$this->endormi) {
           
            $attaque = rand($this->attaqueMin, $this->attaqueMax);
            $degats = max(0, $attaque - $adversaire->defense);
            $adversaire->pointsDeVie -= $degats;
            echo $this->nom . " attaque " . $adversaire->nom . " et lui applique " . $degats . " points de dégâts.<br>";

            // Verification de l'invincibilité
            if ($adversaire->pointsDeVie <= 0) {
                echo $adversaire->nom . " est vaincu.<br>";
            }
        } else {
            echo $this->nom . " est endormi et ne peut pas attaquer.<br>";
        }
    }

    public function endormir($adversaire) {
        $tempsActuel = time();
        $tempsDepuisDernierEndormissement = $tempsActuel - $this->dernierEndormissement;
        
        // Vérification de la recharge du pouvoir d'endormissement chaque 120s
        if ($tempsDepuisDernierEndormissement >= 120) {
            $this->dernierEndormissement = $tempsActuel; 
            $adversaire->endormi = true;
            echo $adversaire->nom . " est endormi.<br>";
            // Mise en sommeil pendant 15 seconde 
            sleep(15);
            $adversaire->endormi = false;
            echo $adversaire->nom . " se réveille.<br>";
        } else {
            echo "Le pouvoir d'endormissement n'est pas encore rechargé.<br>";
        }
    }
}


if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $nomPersonnage1 = $_POST["nomPersonnage1"];
    $typePersonnage1 = $_POST["typePersonnage1"];
    $nomPersonnage2 = $_POST["nomPersonnage2"];
    $typePersonnage2 = $_POST["typePersonnage2"];


    if ($typePersonnage1 == "guerrier") {
        //instance de l'objet 
        $personnage1 = new Personnage($nomPersonnage1, 100, 20, 40, rand(10, 19));
    } else {
         //instance de l'objet 
        $personnage1 = new Personnage($nomPersonnage1, 100, 5, 10, 0);
    }

    if ($typePersonnage2 == "guerrier") {
         //instance de l'objet 
        $personnage2 = new Personnage($nomPersonnage2, 100, 20, 40, rand(10, 19));
    } else {
         //instance de l'objet 
        $personnage2 = new Personnage($nomPersonnage2, 100, 5, 10, 0);
    }

    // Attaque
    if (isset($_POST["attaquer"])) {
        $personnage1->attaquer($personnage2);
    }

    // Defense
    if (isset($_POST["defendre"])) {
        if ($typePersonnage2 == "magicien") {
            $tempsActuel = time();
            $tempsDepuisDernierEndormissement = $tempsActuel - $personnage2->dernierEndormissement;
    
            if ($tempsDepuisDernierEndormissement >= 120) {
                $personnage1->endormi = true;
                echo $personnage1->nom . " est endormi.<br>";
                sleep(15);
                $personnage1->endormi = false;
                echo $personnage1->nom . " se réveille.<br>";
            } else {
                echo "Le pouvoir d'endormissement du magicien n'est pas encore rechargé.<br>";
            }
        } else {
            echo $personnage2->nom . " ne peut pas défendre car il est un guerrier.<br>";
        }
    }
    
}

?>


