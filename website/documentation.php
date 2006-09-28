<?php
$path_link=array('documentation'=>'documentation.php');
require('header.inc.php');
?>

<h2>Utilisation de Wikirenderer 3.0</h2>
<p style="font-style:italic">Derni�re mise � jour le 29/09/2006</p>
<h3>utilisation simple</h3>
<pre><code> include('WikiRenderer.lib.php');
 $wkr = new WikiRenderer();
 $monTexteXHTML = $wkr->render($monTexteWiki);
</code></pre>

<p>Par d�faut, cela utilise les r�gles classicwr_to_xhtml. si vous voulez utilisez d'autres r�gles :</p>

<pre><code> include('WikiRenderer.lib.php');
  include('rules/classicwr_to_text.php');

 $config = new ConfigClassicwrToText();

 $wkr = new WikiRenderer(<strong>$config</strong>);
 $monTexteXHTML = $wkr->render($monTexteWiki);
</code></pre>

<h3>Conna�tre les erreurs</h3>
<p>Il est possible de savoir si lors de la transformation, WikiRenderer a rencontr�
des erreurs (balises wikis malform�e, imbriqu�e...). Il suffit, apr�s la
transformation, de regarder le contenu de la propri�t� <code>errors</code>. Exemple :</p>
<pre><code>include('WikiRenderer.lib.php');
$wkr = new WikiRenderer();
$monTexteXHTML = $wkr->render($monTexteWiki);

if($wkr->errors){
   echo '&lt;p style="color:red;">Il y a des erreurs wiki aux lignes : ';
   echo implode(',',array_keys($wkr->errors)),'&lt/p>' ;
}
</code></pre>
<p>La propri�t� <code>errors</code> est un tableau d'�lements dont la cl�
est un num�ro de ligne, et la valeur le contenu de la ligne en question. On peut
donc si on le d�sire, afficher aussi les lignes en erreur.</p>
<p>WikiRenderer ne s'arrete pas � la premi�re erreur rencontr�e. Les tags wiki qui
posent probl�mes ne sont pas interpret�s, ni enlev�s dans le texte r�sultat.</p>

<h3>Les param�tres de configuration</h3>
<p>Ils sont situ�s dans un objet qui doit avoir les propri�t�s suivantes : </p>

<dl>
<dt><code>inlinetags</code></dt>
<dd>liste des noms des classes qui prennent en charge les
 tags wiki que l'on peut utiliser � l'int�rieur les phrases (tags "inlines"). Voir
<a href="documentation_avancee.php">la partie configuration avan��e</a>.
</dd>
<dt><code>textLineContainer</code></dt>
<dd>nom de la classe qui prend en charge le reste du texte � l'int�rieur des phrases. En g�n�ral,
vous n'avez pas � changer �a.
</dd>
<dt><code>bloctags</code></dt>
<dd>liste des noms de classes des tags de type blocs. Voir
<a href="documentation_avancee.php">la partie configuration avan��e</a>.</dd>
<dt><code>simpletags</code></dt>
<dd>tags simples pour lesquels il y a juste un remplacement � faire. C'est donc un tableau PHP
d'�lements 'chaine � remplacer'=>'chaine remplacante'.</dd>
<dt><code>checkWikiWordFunction</code></dt>
<dd>Indique le nom de la fonction qui sera appel�e si la d�tection des mots wiki "CamelCase" est activ�e.
Cette fonction devra r�cup�rer en param�tre une liste de mots wiki, et devra
renvoy� une liste des chaines qui remplaceront les mots wiki indiqu�s.
Cette fonction est � impl�menter par vous-m�me selon votre application. Voir
<a href="documentation_avancee.php">la partie configuration avan��e</a>.</dd>
</dl>

<h3>Les tags wiki des rules "classicwr"</h3>

<h4>de types bloc :</h4>
<ul>
<li>Paragraphe       : 2 sauts de lignes</li>
<li>Trait HR          : <code>====</code> (4 signes "�gale" ou plus) + saut de ligne</li>
<li>Liste             : une ou plusieurs <code>*</code> ou  <code>-</code> (liste simple) ou
                        <code>#</code> (liste num�rot�e) par item + saut de ligne</li>
<li>Tableaux          : <code>| texte | texte</code>. <code>|</code> <strong>encadr� par des espaces</strong>
(sauf pour le premier)     = caractere s�parateur de colonne, chaque ligne �crite =
                     une ligne de tableau</li>
<li>sous titre niveau 1 : <code>!!!</code>titre + saut de ligne</li>
<li>sous titre niveau 2 : <code>!!</code>titre + saut de ligne</li>
<li>sous titre niveau 3 : <code>!</code>titre + saut de ligne</li>
<li>texte pr�format� :  un espace + texte + saut de ligne</li>
<li>citation (blockquote) :  un ou plusieurs <code>&gt;</code> + texte + saut de ligne</li>
<li>D�finitions : <code>;</code>terme<code> : </code>d�finition + saut de ligne
(le <code>:</code> doit �tre <strong>encadr� par des espaces</strong>)</li>
</ul>

<h4>de type inline :</h4>
<ul>
<li>emphase forte (gras)   : <code>__</code>texte<code>__</code> (2 underscores)</li>
<li>emphase simple (italique) : <code>''</code>texte<code>''</code> (deux apostrophes)</li>
<li>Retour � la ligne forc�e    : <code>%%%</code></li>
<li>Lien    : <code>[</code> nomdulien <code>|</code> lien <code>|</code> langue <code>|</code> d�scription (title)<code>]</code></li>
<li>Image    : <code>((</code> lien vers l'image <code>|</code> textalternatif
             <code>|</code> position <code>|</code> longue d�scription <code>))</code> .
               valeurs de position : l/L/g/G => gauche, r/R/d/D =>droite,
               rien : en ligne. Dans le code g�n�r�, c'est une balise style qui est cr�e, et non un attribut align (obsol�te).</li>
<li>code            : <code>@@</code>code<code>@@</code></li>
<li>citation         : <code>^^</code>phrase<code>|</code>langue<code>|</code>lien source<code>^^</code></li>
<li>ref�rence (cite)      : <code>{{</code>reference<code>}}</code></li>
<li>acronym         : <code>??</code>acronyme<code>|</code>signification<code>??</code></li>
<li>ancre : <code>~~</code>monancre<code>~~</code></li>
</ul>

<h4>Note</h4>
<p>Dans un texte wiki "classicwr", on peut d�sactiver l'interpretation d'un tag wiki
en mettant un antislash devant la balise d'ouverture (et de fermeture
pour les tags en lignes). Exemple : <code>\__emphase\__</code>.</p>


<?php

require('footer.inc.php');
?>








