# Actions à effectuer – Admin Dikitisivi

## Modifications déjà appliquées

1. **Migrations déplacées** – Les migrations ont été déplacées de `database/factories/` vers `database/migrations/`.
2. **Modèle Status** – Utilisation de `Spatie\Translatable\HasTranslations` à la place du trait Filament.
3. **Modèle User** – Implémentation de `FilamentUser` avec `canAccessPanel()`.
4. **Filament Shield** – `is_scoped_to_tenant` passé à `false`.
5. **Modèle Aws** – Renommé de `aws` en `Aws` (PSR-4).
6. **MediaResource** – Remplacement du `dd()` par un message d’information et un lien vers la création de catégories.
7. **Config app.php** – Suppression des doublons de `AdminPanelProvider`.
8. **Migration users** – Structure enrichie (firstname, lastname, country_id, status_id, etc.).
9. **Migration role_user** – Création de la table pivot pour User ↔ Role.
10. **Migration roles** – Ajout des colonnes `role_name` et `role_description` pour le modèle Role.

---

## Actions à effectuer manuellement

### 1. Migrations et base de données

Si la base existe déjà, vérifier la cohérence des tables :

```bash
php artisan migrate:status
```

**Base neuve :**

```bash
php artisan migrate
```

**Réinitialisation complète (ATTENTION : perte des données) :**

```bash
php artisan migrate:fresh
```

---

### 2. Tables – migrations créées

27 migrations ont été créées selon votre schéma SQL (ordre des dépendances respecté) :

| Ordre | Migration |
|-------|-----------|
| 1-3 | groups, countries, youtube_access_tokens |
| 4-5 | statuses, types |
| 6-9 | users, roles, sessions, role_user |
| 10-11 | categories, pricings |
| 12-14 | books, medias, category_media |
| 15-17 | legal_info_subjects, legal_info_titles, legal_info_contents |
| 18-21 | carts, donations, orders, payments |
| 22-27 | notifications, media_session, media_user, media_views, password_resets, aws |

**Note :** La table `general_settings` et `personal_access_tokens` existent déjà via d'autres migrations.

---

### 3. Seeders

**Super Admin (déjà configuré)** – Exécuter :

```bash
php artisan db:seed --class=SuperAdminSeeder
```

Identifiants : `contact@silasmas.com` / `silasmas`

**Import des données Dikitisivi** – Pour importer le dump SQL :

1. Créez le fichier `database/seeders/dumps/dikitivi_data.sql` avec votre dump phpMyAdmin (uniquement les `INSERT`, dans l’ordre des relations).
2. Décommentez `DikitisiviDataSeeder::class` dans `DatabaseSeeder.php`.
3. Exécutez : `php artisan db:seed --class=DikitisiviDataSeeder`

Ou lancez tout : `php artisan db:seed`

---

### 4. Accès au panel Filament

L'accès est réservé aux utilisateurs ayant **au moins un rôle différent de "Membre"**.

**Super admin déjà créé** : `contact@silasmas.com` / `silasmas` (via `php artisan db:seed --class=SuperAdminSeeder`).

---

### 5. Configuration .env

À configurer selon votre environnement :

- `DB_*` – Base de données.
- `AWS_*` – S3 (AwsResource).
- `FILAMENT_FILESYSTEM_DISK` – Disque par défaut pour Filament.

---

### 6. Vérifications après déploiement

```bash
php artisan config:clear
php artisan cache:clear
php artisan route:clear
php artisan view:clear
```

---

## Structure des migrations

Ordre d’exécution des nouvelles migrations :

1. groups, countries, youtube_access_tokens
2. statuses (→ groups), types (→ groups)
3. users (→ countries, statuses)
4. roles, sessions (→ users), role_user (→ roles, users)
5. categories, pricings, books, medias, category_media
6. legal_info_*, carts, donations, orders, payments
7. notifications, media_session, media_user, media_views, password_resets, aws
