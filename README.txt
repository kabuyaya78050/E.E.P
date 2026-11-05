Site de l'Église Évangélique de Peniel (Laravel) : kit complet

Depuis le dossier du projet Laravel (peniel), après "composer require labyrinthe/payment" :
  copiez le CONTENU de ce dossier par-dessus le projet (remplacer les fichiers existants)
  cat .env.labyrinthe.example >> .env      (une seule fois)
  (dans .env : APP_LOCALE=fr)
  php artisan migrate
  php artisan db:seed --class=PenielSeeder
  php artisan peniel:admin votre@email.com "Votre nom"      (premier administrateur)
  php artisan serve

Administration (/connexion) :
  Publications (photo, titre, description, lieu, heure et lieu de départ), Prédications,
  Dons, Réglages (direct, contact, carte, YouTube / Facebook / TikTok / Instagram), Administrateurs (ajouter, modifier, supprimer).

À personnaliser :
  config/church.php : horaires, vision (7 points), chronologie, pasteur
  public/images/ : logo.png, hero.jpg, histoire.jpg, pasteur.jpg
  public/images/cultes/ : lundi.jpg, mercredi.jpg, vendredi.jpg, samedi.jpg, dimanche.jpg
