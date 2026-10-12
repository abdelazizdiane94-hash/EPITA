# StockPulse

[Voir la présentation du projet](./presentation.html)

Démo de gestion d’inventaire composée d’une interface JavaScript autonome et d’une API PHP/MySQL expérimentale. Les deux parties sont séparées : **l’interface de démonstration n’appelle pas l’API**.

## Démo navigateur

1. Ouvrez `client/index.html` dans un navigateur ou servez le dépôt avec AMPPS.
2. Ajoutez ou retirez des unités depuis les contrôles de stock.
3. Les quantités sont conservées dans le stockage local de ce navigateur. Le bouton de réinitialisation restaure les valeurs d’exemple.

La démo reste utilisable sans serveur PHP. Le graphique utilise Chart.js chargé depuis un CDN ; si la bibliothèque n’est pas accessible, l’inventaire et ses contrôles restent disponibles.

## API PHP (prototype séparé)

### Prérequis et base

- PHP 8 avec PDO MySQL, et MySQL ou MariaDB.
- Importez [`sql/schema.sql`](./sql/schema.sql) dans une base `stock_pulse`.
- Configurez `STOCK_DB_HOST`, `STOCK_DB_NAME`, `STOCK_DB_USER` et `STOCK_DB_PASSWORD`. Les valeurs par défaut (`127.0.0.1`, `stock_pulse`, `root`, mot de passe vide) conviennent uniquement à une installation locale.
- Définissez `STOCKPULSE_JWT_SECRET` avec une valeur aléatoire d’au moins 32 caractères.
- L’API exige un jeton JWT Bearer valide signé en HS256, avec `iss` égal à `stock-pulse`, un `sub` numérique et une date `exp` future. **Aucun endpoint de connexion ou de génération de jeton n’est fourni** : le jeton doit être généré par un outil de test ou un service externe de confiance.

### Routes

En utilisant le point d’entrée `api/index.php` :

| Méthode | Chemin | Description |
| --- | --- | --- |
| `GET` | `?` (ou `/inventory`) | Lister les produits |
| `POST` | `/inventory` | Créer un produit (`name`, `quantity`, `price`) |
| `PUT` | `/inventory/{id}` | Modifier un produit ; un mouvement est enregistré si la quantité change |
| `DELETE` | `/inventory/{id}` | Supprimer un produit |

Chaque requête doit inclure `Authorization: Bearer <jeton>` et, pour les écritures, un corps JSON. Par exemple :

```json
{
  "name": "Clavier",
  "quantity": 12,
  "price": 24.9
}
```

Les réponses d’erreur utilisent des codes HTTP et un objet JSON contenant `error`. Les secrets de base de données et de signature se configurent dans l’environnement, jamais dans le dépôt.

## Organisation du projet

```text
api/
├── config/       # Connexion PDO
├── controllers/  # Opérations sur l’inventaire
├── middleware/   # Vérification des jetons JWT
└── index.php     # Routage HTTP
client/           # Démo HTML, CSS et JavaScript indépendante
sql/              # Schéma MySQL
```

## Limites actuelles

La démo navigateur utilise des données locales, pas les données SQL. Le prototype d’API n’inclut ni gestion des comptes, ni création de jetons, ni interface de connexion. Les opérations disponibles couvrent l’inventaire de base, sans gestion d’entrepôts dans les routes.

## Auteur

M'Fabdel-Aziz (Abdel-Aziz) DIANÉ · [GitHub](https://github.com/abdelazizdiane94-hash)
