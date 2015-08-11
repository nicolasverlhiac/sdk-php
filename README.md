# SDK PHP

SDK PHP pour l'API Payname

[English version](./README_EN.md)

<!-- markdown-toc start - Don't edit this section. Run M-x markdown-toc/generate-toc again -->
**Table des matières**

- [Prérequis](#prrequis)
- [Installation](#installation)
    - [Depuis les sources](#depuis-les-sources)
    - [Via Composer](#via-composer)
- [Configuration](#configuration)
- [Exemples](#exemples)
- [Documentation](#documentation)
- [Tests](#tests)
- [Changements](#changements)
    - [Build 4 - 2015 08 11](#build-4---2015-08-11)
    - [Build 3 - 2015 06 22](#build-3---2015-06-22)
    - [Build 2 - 2015 06 22](#build-2---2015-06-22)
    - [Build 1 - 2015 06 19](#build-1---2015-06-19)

<!-- markdown-toc end -->

# Prérequis

* PHP 5.3 ou ultérieur
* Optionnel : [cURL](http://php.net/manual/en/book.curl.php) installé.

# Installation

## Depuis les sources

Les classes PHP sont dans le dossier `src/Payname`.

Pour installer le SDK, télécharger ce dossier et le copier dans votre projet, par exemple dans un dossier `vendor/Payname/`.

## Via Composer

*Bientot disponible*

# Configuration

1. Copier `Payname/Config.class.php.sample` en `Payname/Config.class.php`
2. Modifier `Payname/Config.class.php` et renseigner l'ID et la clé secrète (disponible dans le panneau d'administration).

**ASTUCES :**

* Utiliser la clé secrète de test pour passer le SDK en mode test.
* Si le type d'authentification du compte est paramétré sur "OAuth et simple", le SDK gère automatiquement l'authentification.
  Sinon, l"utilisation de `Auth::token()` et `Payname::token()` est indispensable.
* Par defaut, le SDK utilise les fonctions de base de PHP pour les appels HTTP.
  Pour utiliser cURL à la place, setter `Config::USE_CURL` a `true`. 


# Exemples

Des exemples d'intégration sont disponibles dans le dossier `examples/`.

Ils permettent de voir comment manipuler les utilisateurs, les popups, et d'autres fonctionnalités de l'API Payname.


# Documentation

Une documentation PHPDoc est disponible dans le dossier `doc/`.


# Tests

Requiert [phpunit](https://phpunit.de/)

Les tests unitaired dans dans le dossier `tests/`.

Ils sont en cours d'implémentation.


# Changements

## Build 4 - 2015 08 11

* Ajout support paiement par carte et tokens de carte
  * Ajout classes `Payment` et `Card`
* Ajout exemple de paiement par carte `examples/payment_card.php`
* Ajout exemple de paiement par token de carte `examples/payment_token.php`


## Build 3 - 2015 06 22

* Amélioration exemple de popup avec une vraie popup
* Ajout de méthodes `User->doc()` and `User->iban()` pour gérer plus facilement les docs/IBANs des utilisateurs


## Build 2 - 2015 06 22

* Ajout support appels HTTP sans cURL pour les environnements n'ayant pas l'extention
* Ajout option dans `Config` pour pouvoir activer/désactiver le support de cURL à la demande


## Build 1 - 2015 06 19

* Ajout classes principales Payname, Exception, Config
* Ajout gestion de l'authentification (Auth)
* Ajout gestion des utilisateurs (User) et leurs dépendances (Doc et IBAN) + exemple
* Ajout création de Popup + exemple
