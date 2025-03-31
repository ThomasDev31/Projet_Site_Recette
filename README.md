# Projet_Site_Recette
Projet réalisé lors de mes cours, il s'agit d'un site web développé avec Symfony, permettant aux utilisateurs de partager et consulter des recettes. Les fonctionnalités incluent l’ajout de recettes, la notation, les avis et d’autres interactions pour enrichir l’expérience utilisateur.

# Pour utiliser le projet 
cloner le projet dans votre fichier souhaitez.
puis aller dans le fichier avec "cd".
Installer préalablement composer : https://getcomposer.org/download/
enfin installer les dépandances avec "composer install".

# Utilisation de la base de donnée 
Vous devez créer un nouvelle base de données en local 
puis dans le fichier env changer le type de base de donnée que vous utilisez (mysql, postgresql, sqlite). Au niveau de la ligne 26 
vous changer les paramètre de la constante en fonction de votre utilisation (localhost, dbname, ect ...)

puis dans le terminal du projet: excuter la commande "symfony.exe console doctrine:migrations:migrate" 

Maintenant vous pouvez utiliser le site comme vous le souhaiter.

# Information
Si vous utilisez ce projet n'hesitez pas à ajoutez du style c'est directement dans le dossier assets styles et le fichier app.css.

Pour ajouter des classes ou des id à des éléments c'est dans le fichier templates vous avez les views html.twig 