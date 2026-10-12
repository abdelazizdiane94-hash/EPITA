# CollabBoard

[Voir la présentation du projet](./presentation.html)

Tableau Start / Stop / Continue pour organiser une rétrospective d’équipe. L’interface s’utilise au clavier ou à la souris et permet de déplacer les cartes, répartir cinq votes et télécharger un compte rendu Markdown.

## Démo navigateur

Ouvrez `public/index.html` dans un navigateur ou servez le projet avec AMPPS. La démo :

- propose trois cartes d’exemple et permet d’en ajouter jusqu’à 100 ;
- enregistre cartes, colonnes et votes dans le stockage local du navigateur ;
- limite à cinq le nombre de votes par tableau ;
- permet de réordonner les idées entre colonnes par glisser-déposer ou avec le sélecteur de destination ;
- exporte le tableau courant dans un fichier Markdown.

Le stockage est local à un navigateur et un appareil ; il ne s’agit pas d’une synchronisation en temps réel entre membres d’une équipe. L’export PDF n’est pas implémenté.

## Prototype d’API

`api/sync.php` est un endpoint PHP/MySQL distinct de l’interface. L’interface de démonstration n’envoie pas de requêtes à cet endpoint.

1. Utilisez PHP 8 avec PDO MySQL et MySQL ou MariaDB.
2. Créez la base `collab_board`, puis importez [`sql/schema.sql`](./sql/schema.sql).
3. Configurez `COLLAB_DB_HOST`, `COLLAB_DB_NAME`, `COLLAB_DB_USER` et `COLLAB_DB_PASSWORD`. Les paramètres locaux par défaut sont `127.0.0.1`, `collab_board`, `root` et mot de passe vide.
4. Définissez `COLLABBOARD_JWT_SECRET` avec au moins 32 caractères aléatoires.
5. Envoyez un `POST` JSON à `api/sync.php`, avec l’en-tête `Authorization: Bearer <jeton>` et un objet de la forme :

   ```json
   {
     "sprint_id": 1,
     "cards": [
       {
         "title": "Documenter les décisions",
         "description": "Garder une trace des choix.",
         "status": "start",
         "position": 0
       }
     ]
   }
   ```

   L’identifiant de sprint doit exister dans la base. Le jeton doit être signé en HS256, avoir `iss` égal à `collab-board`, un `sub` numérique positif et une date `exp` future. Aucun endpoint de connexion ou de génération de jeton n’est fourni. Chaque requête remplace toutes les cartes du sprint indiqué dans une transaction.

## Structure

```text
api/       # Endpoint de synchronisation et vérification JWT
public/    # Démo statique : HTML, CSS, JavaScript
sql/       # Tables de sprints, cartes, utilisateurs et votes
```

## Pour aller plus loin

Une prochaine étape serait de connecter l’interface à l’API après avoir ajouté une gestion des utilisateurs et des jetons, puis d’écrire des tests automatisés pour la validation et la synchronisation.

## Auteur

M'Fabdel-Aziz (Abdel-Aziz) DIANÉ · [GitHub](https://github.com/abdelazizdiane94-hash)
