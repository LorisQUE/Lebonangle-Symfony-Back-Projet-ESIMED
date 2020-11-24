# Lebonangle

Le site LEBOANGLE permet de déposer et de consulter des annonces sur un environement mobile. Il est construit avec un admin Symfony et une API exposée via Api
Platform.

#Installation

Le projet doit être créé à l'aide du binaire symfony en utilisant la version full du framework

## Entités

Le projet contient les entités suivantes :

```
Category
id int primary not null
name string not null
```
```
Advert
id int primary not null
title string not null (min = 3, max = 100)
content text not null (max = 1200)
author string not null
email string not null
category ManyToOne not null
price float not null (min = 1, max = 1,000,000.00)
state string not null
createdAt DateTime not null
publishedAt DateTime
```
```
Picture (VichUploader)
id int primary not null
file (not mapped on database) File
path string not null
createdAt DateTime not null
advert ManyToOne delete cascade
```
```
AdminUser
id int primary not null
username string not null
email string not null
plainPassword (not maped on database) string
password string not null
```
# Encodage des mots de passe

Lorsqu'un admin est créé ou modifié, si son plainPassword (mot de passe en clair) est renseigné, il doit être encodé et sauvé dans password grace à un listener (ou
subscriber) doctrine. (https://symfony.com/doc/current/security.html#c-encoding-passwords)

# Renseignement de la date de création

Lorsqu'une annonce est créée, sa date de création est automatiquement renseignée. Il en est de même pour les images.

# Workflow des annonces

Les annonces sont rattachées à un workflow. Lorsqu'elles sont créées, elles sont dans le statut draft. Les admins peuvent les publier (status published) ou les
rejeter (status rejected). Une fois publiées, elle peuvent toujours être rejetées.
Quand une annonce passe du status draft à published, sa date de publication est automatiquement renseignée et un mail de notification est envoyé à l'utilisateur
qui l'a créée.

# Admin

Vous devez créer un admin sous symfony. Il n'est pas possible d'utiliser un bundle d'admin (type EasyAdminBundle).
L'admin est accessible uniquement aux utilisateurs de type AdminUser qui s'autentifient grace à un formulaire de connexion.


# Crud AdminUser

Les admins peuvent lister / ajouter / mettre à jour et supprimer des utilisateurs.

Lors de la création ou de la mise à jour, le champ password n'est pas accessible mais le champ plainPassword l'est.

Un admin ne peut pas supprimer son propre compte.

Il doit forcément rester un admin.

# Crud Category

Un admin peut lister / ajouter / mettre à jour et supprimer des catégories.
Si une catégorie est rattachées à au moins une annonce, elle ne peut pas être supprimée. La liste des catégories est paginée 30 par 30.

# Gestion des annonces

Un admin peut lister les annonces / consulter / publier ou rejeter une annonce.
Les annonces ne peuvent pas être créées / modifiées ou supprimées depuis l'admin.
Dans la liste, on doit connaitre le nombre de photos rattachées à l'annonce et dans la consultation, toutes les photos sont visibles. La liste des catégories est paginée
30 par 30.

## API

Une API permet de récupérer et de créer des informations. Elle est basée sur le bundle Api Bundle

### Category

On peut récupérer la liste des catégories ainsi qu'accéder à une seule catégorie

### Advert

On peut créer / lister / accéder au détail des annonces.
Les annonces peuvent être triées par date de publication ou par prix (ASC et DESC).
Les annonces peuvent être filtrées par catégorie ainsi que par prix (entre [min] et [max])

### PicturePicture

On peut créer / lister / accéder au détail des images. Les images sont créées via l'API avant les annonces (en uploadant le fichier) et l'annonce reçoit la liste des
images téléchargées lors de la création (c'est à ce moment là que le lien est fait entre annonce et image en base de données).

## NotificationNotification

Lorsqu'une annonce est créée, une notification par mail est envoyée à tous les AdminUser.
La notification contient 3 boutons : un permettant d'envoyer sur la fiche de consultation dans l'admin, un second permettant de publier l'annonce et un troisième de
rejeter l'annonce.

## Test

L'API doit être intégralement testée. Vous pouvez voir comment tester une API dans la documentation d'API platform

## L'accès mobile

L'API sera consommé par une interface mobile utilisant la technologie de votre choix à partir du moment où elle a été étudiée en cours.

Elle permet de lister et de filtrer les annonces publiées comme décrit dans la partie API des annonces

Elle offre également la posibilité de créer une annonce et les images rattachées.

La création d'une annonce se fait sans compte utilisateur. Elles ne peuvent pas être modifiées.

## Commandes (bonus)

Vous devez créer les commandes symfony suivantes :

Une commande permettant de supprimer toutes les annonces rejetées, créées il y a X jours (X étant un argument de la commande)

Une commande permettant de supprimer toutes les annonces publiées il y a X jours (X étant un argument de la commande). Ne pas se fier à la date de création mais
bien à celle de publication.

Une commande permettant de supprimer toutes les images non rattachées à une annonce créées il y a plus de X jours (X étant un argument de la commande)


