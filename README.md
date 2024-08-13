<div align="center" id="top"> 
  <img src="./.github/app.gif" alt="Ftth Photo Api" />

  &#xa0;

  <!-- <a href="https://ftthphotoapi.netlify.app">Demo</a> -->
</div>

<h1 align="center">FTTH PHOTO API 🔌</h1>

<p align="center">
  <img alt="Github top language" src="https://img.shields.io/github/languages/top/AFelix20100/ftth-photo-api?color=56BEB8">

  <img alt="Github language count" src="https://img.shields.io/github/languages/count/AFelix20100/ftth-photo-api?color=56BEB8">

  <img alt="Repository size" src="https://img.shields.io/github/repo-size/AFelix20100/ftth-photo-api?color=56BEB8">

  <img alt="License" src="https://img.shields.io/github/license/AFelix20100/ftth-photo-api?color=56BEB8">
</p>

<!-- Status -->

<!-- <h4 align="center"> 
	🚧  Ftth Photo Api 🚀 Under construction...  🚧
</h4> 

<hr> -->

<p align="center">
  <a href="#dart-À-propos">À propos</a> &#xa0; | &#xa0; 
  <a href="#sparkles-Fonctionnalités">Fonctionnalités</a> &#xa0; | &#xa0;
  <a href="#rocket-Technologies">Technologies</a> &#xa0; | &#xa0;
  <a href="#white_check_mark-Exigences">Exigences</a> &#xa0; | &#xa0;
  <a href="#checkered_flag-Démarrage-rapide">Démarrage rapide</a> &#xa0; | &#xa0;
  <a href="#memo-licence">Licence</a> &#xa0; | &#xa0;
  <a href="https://github.com/AFelix20100" target="_blank">Auteur</a>
</p>

<br>

## :dart: À propos ##

FTTH-API-PHOTO est une API RESTful développée avec Symfony, destinée à gérer les photos associées aux commandes, aux prestations et aux interventions dans un système de gestion de réseau FTTH (Fiber to the Home). Cette API permet de récupérer et de mettre à jour des photos en fonction des références de commandes, de prestations et d'interventions.

La fonctionnalité d'envoi d'image est en cours de développement.


## :sparkles: Fonctionnalités ##

:heavy_check_mark: CRUD des commandes internes;\
:heavy_check_mark: CRUD des prestations;\
:heavy_check_mark: CRUD des interventions;\
🚧: CRUD des images en utilisant les endpoints Photo;\
:heavy_check_mark: Génération des références des commandes internes automatiquement;\
:heavy_check_mark: Génération des références des prestations automatiquement;\
:heavy_check_mark: Génération des références des interventions automatiquement;\
:heavy_check_mark: Récupération des photos avec la référence d'une commande;\
:heavy_check_mark: Récupération des photos avec la référence d'une prestation;\
:heavy_check_mark: Récupération des photos avec la référence d'une intervention;\
:heavy_check_mark: Documentation API Plateform généré;\
:heavy_check_mark: Gestion des erreurs;\
:heavy_check_mark: Traduction des actions des différents endpoints;\


## :rocket: Technologies ##

Voici la liste des outils et technologies utilisés pour ce projet.

- [PHP v8.1.29](https://www.php.net/)
- [MariaDB v8.0.31](https://mariadb.org/)
- [Symfony v6.4.10](https://symfony.com/)
- [API Plaform v3](https://api-platform.com/)
- [Composer v2.7.7](https://getcomposer.org/)
- [Git v2.40.0](https://git-scm.com/)
- [Symfony CLI v5.10.2](https://symfony.com/download)

## :white_check_mark: Exigences ##

Pour faire fonctionner ce projet, vous devez avoir les outils suivants installés :

- PHP
- MariaDB
- Symfony
- API Platform
- Composer
- Git

Pour les versions spécifiques de ces outils, veuillez vous référer à la section [Technologies](#rocket-technologies).

## :gear: Configuration de la base de données ##

Avant de démarrer le projet, vous devez configurer l'URL de la base de données. 

1. Ouvrez le fichier `.env` à la racine du projet.
2. Modifiez la ligne suivante pour correspondre à l'URL de votre base de données :

```bash
    DATABASE_URL="mysql://user:password@127.0.0.1:3306/your_database_name"
```

   Remplacez `user`, `password`, `127.0.0.1`, `3306`, et `your_database_name` par vos propres informations de connexion.

3. Sauvegardez le fichier `.env` après avoir apporté les modifications nécessaires.
4. Par défaut, le projet est configuré pour l'environnement de développement.

## :checkered_flag: Démarrage rapide ##

Suivez ces étapes pour lancer rapidement le projet sur votre machine locale :

```bash

# Clonez le projet depuis le dépôt GitHub.
$ git clone https://github.com/AFelix20100/ftth-photo-api

# Accédez au dossier du projet.
$ cd ftth-photo-api

# Installez les dépendances du projet avec Composer.
$ composer install

# Créez la base de données
$ php bin/console doctrine:database:create

# Exécutez les migrations pour mettre à jour la structure de la base de données.
$ php bin/console doctrine:migrations:migrate

# Chargez les fixtures pour peupler la base de données avec des données de test.
$ php bin/console doctrine:fixtures:load

# Lancez le serveur web avec Symfony CLI.
$ symfony server:start

# Le serveur sera accessible à l'adresse suivante : http://localhost:8000

```

## :memo: Licence ##

Ce projet est sous licence MIT.


Fait par <a href="https://github.com/AFelix20100" target="_blank">Félix-Vincent ARTHUR</a>

&#xa0;

<a href="#top">Revenir en haut</a>
