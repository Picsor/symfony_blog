# SETUP

## Installation de l'application 

- afin d'installer tous les paquets necessaires au bon fonctionnement de l'application, vous devrez ouvrir un terminal au niveau du dossier dans lequel elle se situe

- tapez ensuite la commande `composer install`

- si besoin, modifez la valeur `DATABASE_URL` dans le fichier .env

- créer la base de données avec la commande `php bin/console doctrine:database:create`

- construire la structure de la base de données `php bin/console doctrine:migrations:migrate`

- en cas de problème, essayez de supprimer la base de données et de la re créer avec les commandes au dessus : `php bin/console doctrine:database:delete --force`

- Vous pouvez lancer l'application avec la commande `symfony server:start`

## Création d'un utilisateur par défaut 

- vous pouvez créer un administrateur en allant sur l'adresse suivante : localhost:8000/register/{username}/{password} (attention à respecter les contraintes de création) 

- Admin et P!ssw4rd dont des noms d'utilisateur et mots de passe fonctionnels.
