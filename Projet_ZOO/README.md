# Projet_ZOO — Zoo de Milo

Projet académique réalisé en BTS SIO SLAM au Lycée Parc de Vilgénis. Cette application PHP/MySQL illustre la gestion d'un parc zoologique avec des menus adaptés au rôle enregistré en session.

## Fonctionnalités

- Authentification et session : `index.php`, `login.php`, `logout.php` et formulaire d'inscription `enregistrer.html` / `enregistrer.php`.
- Menus distincts : `menu_directeur.php` donne accès à la gestion du personnel, des espèces et des animaux; `menu_employe.php` propose la consultation et la recherche d'animaux.
- Animaux et espèces : ajout, liste et recherche; la suppression d'un animal est réservée au rôle Directeur.
- Personnel : ajout, liste et recherche.
- Visuels locaux du Zoo de Milo et images d'animaux (`lion.png`, `tiger.png`, `girafe.png`, `eagle.png`, `crocodile.png`, `rhinoceros.png`).

La base ne contient pas de page de modification d'animal et le menu Employé n'expose pas la gestion des employés. Les menus sont des pages HTML; le contrôle des rôles côté serveur est incomplet et ce projet reste une démonstration pédagogique.

## Schéma relationnel

Importer `gestionzoo.sql` crée les tables suivantes :

- `personnel` contient les comptes, fonctions, informations RH et photos.
- `animaux` référence facultativement `especes` et stocke la date de naissance, le sexe, le commentaire et la photo.
- `especes` référence `types_nourriture`.
- `enclos` référence éventuellement un membre du personnel responsable.
- `loc_animaux` relie les animaux et les enclos avec des dates d'arrivée et de sortie.
- `types_nourriture` liste les nourritures disponibles.

Les clés étrangères et index sont définis à la fin du dump SQL. Le dump contient un compte de démonstration au mot de passe faible en clair (`Abdel` / `1`) et le stockage des mots de passe de l'application n'est pas adapté à un usage réel. Changez ou supprimez ce compte avant tout partage; ne réutilisez aucun mot de passe personnel.

## Installation locale avec AMPPS

1. Démarrer Apache et MySQL dans AMPPS.
2. Dans phpMyAdmin, créer la base `gestionzoo` avec le jeu de caractères `utf8mb4`, puis importer `Projet_ZOO/gestionzoo.sql`.
3. Vérifier dans `connexion.php` les paramètres MySQL locaux. La configuration fournie utilise `localhost`, `root`, `mysql` et `gestionzoo`; adaptez le mot de passe à votre installation AMPPS.
4. Placer le dépôt dans le répertoire web d'AMPPS, puis ouvrir `http://localhost/EPITA/Projet_ZOO/index.php` (ajuster le chemin selon l'emplacement choisi).
5. Pour tester les téléversements, créer `uploads/animaux/` et `uploads/personnel/` sous `Projet_ZOO/` et autoriser Apache à y écrire.

## Sécurité et limites

La connexion ne contient que les identifiants locaux de développement. Plusieurs pages historiques utilisent encore des requêtes interpolées, un stockage de mot de passe non haché ou un contrôle d'accès limité aux menus. N'exposez pas cette version sur un serveur public sans corriger ces points, ajouter des jetons CSRF et échapper toutes les sorties HTML.