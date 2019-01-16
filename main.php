<?php

require_once 'vendor/autoload.php';

use \td_orm\Article;   
use \td_orm\Categorie;   
use \td_orm\ConnectionFactory;

$conf = './conf/db.conf.ini';

ConnectionFactory::makeConnection($conf); 

$attr = ['nom' => "bloblo", 'tarif' => 3.65];
$article = new Article($attr);
// affichage des attributs 
print "******** Affichage des attributs d'un article avec méthode magique get **********\n";
print "id : $article->id desc : $article->descr nom : $article->nom tarif : $article->tarif\n\n";

$article->descr = "Un beau bloblo";

// On vérifie que le setter marche
print "******** mise à jour des attributs d'un article avec méthode magique set **********\n";
print "id : $article->id desc : $article->descr nom : $article->nom tarif : $article->tarif\n\n";

// insertion d'un article
$article->insert();
// après un insert on voit que l'on a bien un id d'article
print "******** verification que l'id de l'article inséré a été ajouté au modèle **********\n";
print "id : $article->id desc : $article->descr nom : $article->nom tarif : $article->tarif\n\n";

print "******** verification que l'article a été supprimé **********\n";
print ($article->delete()."\n\n");

$mesArticles = Article::all();
// on affiche le nom des articles
print "******** Affichage des noms des articles récuperés avec la méthode all() **********\n";
foreach($mesArticles as $article) print ($article->nom."\n");

// test methode find avec l'id de l'article a trouver en paramètre
print "\n******** Affichage de l'article 64 avec la méthode find() **********\n";
$a = Article::find(64);
var_dump($a);

// test methode find avec l'id de l'article a trouver en paramètre
print "\n\n******** Affichage de l'article 64 avec selection de colonne avec la méthode find() **********\n";
$a = Article::find(64,['nom','tarif']);
var_dump($a);

// test méthode find avec un paramètre
print "\n\n******** Affichage des articles avec tarif<80 avec la méthode find() **********\n";
$a = Article::find(['tarif',"<",'80']);
print_r($a);

// test méthode find avec paramètres multiple
print "\n\n******** Affichage des articles avec tarif<80 et id<65 avec la méthode find() **********\n";
$a = Article::find([['tarif',"<",'80'],["id","<",65]]);
print_r($a);

print "\n\n******** Affichage du premier article avec tarif<80 avec la méthode first() **********\n";
$a = Article::first(['tarif',"<",'80']);
print_r($a);

// Récuperation de la categorie de l'article 
print "\n\n******** Affichage de la catégorie de l'article 64 **********\n";
$a = Article::first(64);
$c = $a->belongs_to('Categorie','id_categ');
print_r($c);


?>