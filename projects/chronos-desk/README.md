# ChronosDesk

[Voir la présentation du projet](./presentation.html)

Application PHP de démonstration pour créer et suivre des tickets d’assistance. Le projet sert à pratiquer PHP, PDO, MySQL, les sessions et une séparation simple entre modèles, contrôleurs et vues.

## Fonctionnalités présentes

- Connexion à partir d’un compte déjà enregistré dans la base.
- Création et consultation de tickets.
- Changement de statut réservé aux rôles `Technicien` et `Admin`.
- Jeton CSRF sur les formulaires de connexion, déconnexion, création et changement de statut.
- Échappement des valeurs affichées dans les vues.
- Démo interactive depuis le portfolio : elle est simulée en JavaScript et ne se connecte pas à cette application PHP.

L’inscription, le chat et le calcul du SLA ne sont pas reliés à l’application web actuelle. Les fichiers `public/js/async_chat.js` et `public/js/sla_timer.js` sont des prototypes séparés, non chargés par le point d’entrée.

## Prérequis

- PHP 8 avec l’extension PDO MySQL.
- MySQL ou MariaDB.
- AMPPS, XAMPP ou un serveur PHP équivalent.

## Installation avec AMPPS

1. Placez le dépôt dans la racine web d’AMPPS et démarrez Apache et MySQL.
2. Créez une base nommée `chronos_desk`, puis importez [`sql/schema.sql`](./sql/schema.sql).
3. Configurez la connexion avec les variables d’environnement `CHRONOS_DB_HOST`, `CHRONOS_DB_NAME`, `CHRONOS_DB_USER` et `CHRONOS_DB_PASSWORD`. Sans configuration, l’application utilise `127.0.0.1`, `chronos_desk`, `root` et un mot de passe vide (configuration locale uniquement).
4. Créez un compte de test : générez d’abord un hash avec `php -r "echo password_hash('MotDePasseTest123!', PASSWORD_DEFAULT), PHP_EOL;"`, puis insérez l’utilisateur avec ce hash. Exemple de requête SQL, en supposant que le hash généré est copié dans `HASH_A_REMPLACER` :

   ```sql
   INSERT INTO users (username, password, email, role_id)
   VALUES (
       'demo',
       'HASH_A_REMPLACER',
       'demo@example.invalid',
       (SELECT id FROM roles WHERE role_name = 'Collaborateur')
   );
   ```

5. Ouvrez `http://localhost/EPITA/projects/chronos-desk/public/` (adaptez `EPITA` au nom de votre dossier).

Connectez-vous avec `demo` et le mot de passe choisi lors de la génération du hash. Aucun compte prêt à l’emploi n’est fourni.

## Structure

```text
app/
├── Controllers/    # Traitement des actions et vérifications
├── Models/         # Accès aux données avec PDO
└── Views/          # Pages PHP affichées
public/
└── index.php       # Point d’entrée et routes par paramètre
sql/
└── schema.sql      # Tables et données des rôles
```

## Périmètre et prochaines étapes

Les formulaires invalides renvoient un code HTTP explicite; l’application n’a pas encore de page de création de compte, de pagination, de tests automatisés ou de messagerie connectée à la base. Les identifiants par défaut de MySQL sont uniquement prévus pour une installation locale : utilisez un compte dédié hors environnement de démonstration.

## Auteur

M'Fabdel-Aziz (Abdel-Aziz) DIANÉ · [GitHub](https://github.com/abdelazizdiane94-hash)
