# Symfony_Project
(les symfony peut être echangés avec php bin/console)
commande pour remettre les fixtures a 0 : symfony console d:f:l (fixture = remplissement auto des tables)
commande pour faire un formulaire : symfony console make:form
commande pour créer une table : symfony console make:entity
commande pour faire faire les tables en bdd : symfony console make:migration puis symfony console d:m:m
commande pour load les fixtures : symfony console d:f:l
commande pour créer le controller d'une entité : symfony console make:controller [entité]Controller
Bundle Security pour l'authentification
commande pour créer une table user : symfony console make:user
pour rajouter des champs dans une table existante (exemple table user) : symfony console make:entity [table]
rajouter un private $plainPassword;
avec les getters et setters : 
public function getplainPassword()
    {
        return $this->plainPassword;
    }

    public function setplainPassword($plainPassword)
    {
        $this->plainPassword = $plainPassword;

        return $this;
    }
Pour le createdAt, ne pas oublier la fonction suivante dans l'entité : 
public function __construct()
    {
        $this->createdAt = new \DateTimeImmutable();
    }
NotBlank = ni null ni chaine de caractère vide

Pour le hash : 
private UserPasswordHasherInterface $hash;

    public function __construct(UserPasswordHasherInterface $hash)
    {
        $this->hash = $hash;
    }
et : 
$hashPassword = $this->hash->hashPassword(
                $user,
                ['password']
            );

            $user->setPassword($hashPassword);