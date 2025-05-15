 # ReadMe Store

**ReadME** est un site eCommerce complet permettant aux utilisateurs de consulter des produits, les ajouter à leur panier, passer des commandes, et gérer leurs informations. Une interface d'administration permet également d'ajouter de nouveaux produits et de suivre les commandes.

## Fonctionnalités principales

- **Affichage des produits** : Avec nom, description, prix et image.
- **Recherche** : Par prix de produit.
- **Panier d'achat** : Ajouter/supprimer des produits, mise à jour automatique du total.
- **Gestion des stocks** : Le stock est automatiquement décrémenté à la validation d'une commande, restauré en cas d'annulation.
- **Commandes** : Historique des commandes, détail ligne par ligne, total global.
- **Authentification** : Inscription, connexion, sessions utilisateurs.
- **Administration** :
  - Ajout de produits avec stock initial.
  - Consultation des commandes avec leur statut.
- **Responsive Design** : Interface adaptative avec Tailwind CSS.
- **Sécurité** : Utilisation de `mysqli_real_escape_string`, vérifications côté serveur.

## Base de données

- **Tables** :
  - `users` : informations des utilisateurs.
  - `products` : produits en vente avec gestion du stock.
  - `cart` : panier des utilisateurs.
  - `orders` : commandes globales.
  - `order_details` : détails produits par commande.
  - `message` : formulaire de contact.
  - `historique_commandes_annulees` : log des commandes annulées.

- **Triggers** :
  - `maj_stock_apres_validation` : décrémente le stock après validation.
  - `restaurer_stock_apres_annulation` : restaure le stock en cas d'annulation.
  - `trace_annulation_commande` : enregistre les commandes annulées dans un historique.

- **Procédures stockées** :
  - `afficher_details_commande(p_order_id)` : détails et total d'une commande.
  - `finaliser_commande(p_order_id)` : marque la commande comme validée.
  - `afficher_historique_commandes(p_user_id)` : historique des commandes d’un utilisateur.

## Technologies utilisées

- **PHP** : Back-end, traitement logique, gestion sessions, panier, commandes.
- **MySQL / MariaDB** : Stockage des utilisateurs, produits, commandes, avec triggers & procédures.
- **HTML/CSS** : Structure et mise en page.
- **Tailwind CSS** : Design moderne et responsive.
- **JavaScript** : Pour les interactions utilisateurs.

## À venir / Améliorations possibles

- Paiement en ligne (Stripe, PayPal…).
- Tableau de bord admin plus avancé.
- Système de notifications.
- Filtrage par catégories / prix.
