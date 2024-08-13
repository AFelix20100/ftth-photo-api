<div align="center" id="top"> 
  <img src="./.github/app.gif" alt="Ftth Photo Api" />

  &#xa0;

  <!-- <a href="https://ftthphotoapi.netlify.app">Demo</a> -->
</div>

<h1 align="center">Ftth Photo Api 🔌</h1>

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
  <a href="#dart-about">À propos</a> &#xa0; | &#xa0; 
  <a href="#sparkles-features">Fonctionnalités</a> &#xa0; | &#xa0;
  <a href="#rocket-technologies">Technologies</a> &#xa0; | &#xa0;
  <a href="#white_check_mark-requirements">Exigences</a> &#xa0; | &#xa0;
  <a href="#checkered_flag-starting">Démarrage rapide</a> &#xa0; | &#xa0;
  <a href="#memo-license">License</a> &#xa0; | &#xa0;
  <a href="https://github.com/{{YOUR_GITHUB_USERNAME}}" target="_blank">Auteur</a>
</p>

<br>

## :dart: À propos ##

Describe your project

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


## :rocket: Technologies ##

Voici la liste des outils et technologies utilisés pour ce projet.

- [PHP v8.1.29](https://www.php.net/)
- [MariaDB v8.0.31](https://mariadb.org/)
- [Symfony v6.4.10](https://symfony.com/)
- [API Plaform v3](https://api-platform.com/)
- [Composer v2.7.7](https://getcomposer.org/)
- [Git v2.40.0](https://git-scm.com/)
- [Symfony CLI v5.10.2](https://symfony.com/download)

## :white_check_mark: Exigences

Pour faire fonctionner ce projet, vous devez avoir les outils suivants installés :

- PHP
- MariaDB
- Symfony
- API Platform
- Composer
- Git

Pour les versions spécifiques de ces outils, veuillez vous référer à la section [Technologies](#rocket-technologies).

## :checkered_flag: Démarrage rapide

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

## :memo: License ##

This project is under license from MIT. For more details, see the [LICENSE](LICENSE.md) file.


Fait par <a href="https://github.com/AFelix20100" target="_blank">Félix-Vincent ARTHUR</a>

&#xa0;

<a href="#top">Revenir en haut</a>
