<?php 

    abstract class Utils {

        // permet d'appeler la fonction sans appeler la class
        public static function GenerateRandomNumber($min, $max) {
            return rand($min, $max); // max inclus
        }
    }

    // class commune à hero et ennemy
    class Characters {

        private $nom;
        private $nbBilles;

        public function __construct($nom, $nbBilles) {
            $this->nom = $nom;
            $this->nbBilles = $nbBilles;
        }

        // pour pouvoir recup le nb de billes durant la game
        public function getNbBilles() {
            return $this->nbBilles;
        }

        // pour pouvoir modif le nb de billes du héro
        public function setNbBilles($nbBilles) {
            $this->nbBilles = $nbBilles;
        }
    }

    class Hero extends Characters {

        // ajout propriete du hero
        private $bonus;
        private $malus;

        public function __construct($nom, $nbBilles, $bonus, $malus) {
            parent:: __construct($nom, $nbBilles);
            $this->bonus = $bonus;
            $this->malus = $malus;
        }

        public function getBonus() {
            return $this->bonus;
        }

        public function getMalus() {
            return $this->malus;
        }

        public function checkEvenOdd($choix) {
            if($choix == 0) {
                return "pair";
            } else {
                return "impair";
            }
        }

        // random pour pair ou impair 
        public function chooseEvenOdd() {
            return $this->checkEvenOdd(Utils::GenerateRandomNumber(0, 1));
        }


    }


    class Enemy extends Characters {
        private $age;

        public function __construct($nom, $nbBilles, $age) {
            parent:: __construct($nom, $nbBilles);
            $this->bonus = $age;
        }
    }


    class Game {

        private $heroes = [];
        private $enemies = [];

        public function createHero() {
            // on ajoute chaque hero à la fin du tableau
            $this->heroes[] = new Hero("Seong Gi-hun", 15, 2, 1);
            $this->heroes[] = new Hero("Kang Sae-byeok", 25, 1, 2);
            $this->heroes[] = new Hero("Cho Sang Woo", 35, 0, 3);
        }

        public function createEnemy() {

            // boucle création d'ennemy avec random pour les valeurs
            for($i = 0; $i < 21; $i++) {
                $this->enemies[] = new Enemy("Adversaire", Utils::GenerateRandomNumber(1, 20), Utils::GenerateRandomNumber(18,70) );
            }
        }


        public function GameStart() {

            // array_rand pour avoir un chiffre compris entre 1 et la taille max de l'array 
            $randomHeroIndex = array_rand($this->heroes);

            // dans l'array on lui dit de prendre l'élément d'index random 
            $hero = $this->heroes[$randomHeroIndex];

            // random pour difficulté
            $difficulte = Utils::GenerateRandomNumber(0,3);
            $manches = [5, 10, 20];
            $nbManches = $manches[$difficulte];

            // pour while
            $continue = True;
            
            while($continue && $nbManches > 0) {
                // random dans tableau enemy pour en choisir un
                $indexEnemy = array_rand($this->enemies);

                // on prend un enemy dans le tableau
                $enemy = $this->enemies[$indexEnemy];
                $gainPerte = 0;

                echo "Billes restantes au héro : " . $hero->getNbBilles() . "<br>";
                echo "Billes restantes adversaires : " . $enemy->getNbBilles() . "<br>";

                // random pair ou impair
                $choixHero = $hero->chooseEvenOdd();
                echo "Le héro choisit " . $choixHero . "<br>";

                //permet d'avoir un true or false
                $pairEnemy = $enemy->getNbBilles() % 2 == 0; 

                // on regarde si la déduction est bonne 
                if($choixHero == "pair" && $pairEnemy == True) {

                    // ajout du gain avec bonus aux billes du héro
                    $gainPerte = $hero->getNbBilles() + ($enemy->getNbBilles() + $hero->getBonus());
                    $hero->setNbBilles($gainPerte);
                    echo "Manche gagné ! <br>";
                    echo "Le héro a " . $hero->getNbBilles() . " billes <br>";

                    $nbManches -= 1;

                    // suppression de l'ennemy dans le tableau
                    // array_splice(tableau, index ou ça commence, nb élément a suppr)
                    array_splice($this->enemies, $indexEnemy, 1);

                } else {
                    // soustraction de la perte avec malus des billes du héro 
                    $gainPerte = $hero->getNbBilles() - ($enemy->getNbBilles() + $hero->getMalus());
                    $hero->setNbBilles($gainPerte); 
                    echo "Manche perdu ! <br>";
                    echo "Le héro a " . $hero->getNbBilles() . " billes <br>";

                    $nbManches -= 1;

                    // check nb billes pour savoir si on continue la partie
                    if($hero->getNbBilles() <= 0) {
                        echo "Partie perdu <br>";
                        $continue = False;
                    }
                }

                // on regarde si y a encore des manches à faire
                if($nbManches == 0 && $hero->getNbBilles() > 1) {
                    echo "Félicitations ! Vous avez gagné la partie et vous remportez 45,6 milliards de Won <br>";
                }
                
            }
        }
    }


    $game = new Game();
    $game->createHero();
    $game->createEnemy();
    $game->GameStart();


















?>