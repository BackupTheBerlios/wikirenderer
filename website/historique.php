<?php
$path_link=array('Historique'=>'historique.php');
require('header.inc.php');
?>

<h2>Historique de Wikirenderer.</h2>
<p>WikiRenderer est distribu� sous <a href="http://www.gnu.org/licenses/licenses.html#LGPL">licence LGPL</a>.</p>

<dl>
   <dt>Version 3.0 RC1, 10/10/2006</dt>
   <dd>
        <ul>
            <li>Ajout d'une classe de base pour la configuration</li>

            <li>La classe de configuration peut r�d�finir des hooks : onStart, onParse. Cela
            permet de modifier le texte en entr�e, mais aussi le texte en sortie.</li>

            <li>Ajout d'une propri�t� pointant vers la config, dans les objets d�rivant de wikitag ou
            de WikiRendererBloc</li>

            <li>le constructeur accepte maintenant un nom de config. Les objets de config de r�gles
            doivent avoir le m�me nom que le fichier de r�gle. renommage en cons�quences des noms
            des objets de config existants.</li>

            <li>Nouvelle syntaxe WR3, similaire � classicwr, mais avec la prise en charge de notes
            de bas de page, de blocs type pre entour� de &lt;code&gt; au lieu de chaque ligne
            commen�ant par un �space.</li>
            <li>ajout des r�gles classicwr_to_wr3, pour convertir un texte classicwr en syntaxe wr3.</li>
            <li>ajout des r�gles wr3_to_text</li>
        </ul>
   </dd>

   <dt>Version 3.0 beta, 28/09/2006</dt>
   <dd> Refonte compl�te du moteur pour r�soudre certains probl�mes :
        <ul>
            <li>[FIX] bug sur les tags de lien : si il n'y avait que l'url et que celle-ci comportait par
               inadvertance des balises wiki, cela g�n�rait du code invalide (des balises xhtml dans les attributs par
               exemple)</li>
            <li>On ne parle plus de "configuration", mais de "r�gles" de transformation (rules)</li>
            <li>Il n'y a plus de fonctions de formatage pour les balises type inline : ce sont maintenant
                des objets � part enti�re</li>
            <li>Caract�re de s�paration d'attributs dans les tags inlines, param�trables pour chaque tag</li>
            <li>Possibilit� d'indiqu� quel attribut servira de contenu, donc quel attribut accepte des
               tag wiki</li>
            <li>Modifications dans l'api des objets traitant les tags wiki de type blocs</li>
            <li>Possiblit� d'avoir une syntaxe de bloc utilisant un d�limiteur de d�but et de fin, et non
                pas qu'une syntaxe se reposant sur un caract�re significatif en d�but de chaque ligne de bloc.</li>
       </ul>
   </dd>


   <dt>Version 2.0.6, 26/09/2004.</dt>
   <dd>
    <ul>
 <li>[FIX] probl�me d'expression r�guli�re lors de la recherche de tags simples comportant certaines lettres ;</li>
 <li>[FIX] bug dans le moteur qui avait un impact sur les bloc commen�ant par les espaces ;</li>
 <li>[FIX] bug sur les caract�res s�parateurs : ils ne s'affichaient toujours pas dans les tags inline qui
 n'avaient pas d'attributs.</li>
 <li>[NEW] on peut d�sormais avoir un caract�re | dans la valeur d'un attribut, il suffit de l'�chapper. <br/>
 <code>[aaa\|aa|bbb]</code> donne <code>&lt;a href="bbb">aaa|aa&lt;/a></code></li>
 <li>[FIX] suppression d'une erreur "notice" lors de la g�n�ration HTML d'un tag wiki vide</li>
 <li>[FIX] probl�me d'interpretation des balises wiki qui suivent un \\</li>
 <li>[FIX]  generation de la g�n�ration d'un attribut lang au lieu de hreflang sur les liens</li>
 </ul>

   </dd>
   <dt>Version 2.0.5, 16/05/2004.</dt>
   <dd><ul>
      <li>[FIX] bug critique : les balises html contenues dans le texte wiki n'�taient pas �chapp�es dans certains cas.</li>
      <li>[FIX] les caract�res s�parateurs (|) qui �taient en dehors de balises wiki disparaissaient</li>
      <li>[FIX] Le caract�re d'�chappement \ disparaissait aussi syst�matiquement, m�me si il n'�chappait rien.
         Dor�navant, pour l'avoir dans un texte, il faut le doubler.</li>

      <li>[NEW] possibilit� d'indiquer dans la config si on veut �chapper ou non les balises HTML
      et autres caract�res sp�ciaux inclus dans le texte wiki,
      ceci pour les configurations de transformations autre qu'au format xhtml/xml</li>
      <li>[FIX] bug sur la g�n�ration des listes dans certains cas</li>
      <li>petite corrections sur le fichier DOCUMENTATION</li>
      </ul>
</dd>


   <dt>Version 2.0.4, <!--<a href="download/WikiRenderer_2.0.4.zip">WikiRenderer_2.0.4.zip</a>-->
  28/01/2004</dt>
   <dd><p>Petite modification au niveau de la syntaxe wiki pour les tableaux et les d�finitions.
Lors de l'interpretation des tableaux, il y avait confusion entre le | separateur de
   colonne et le | separateur d'attributs pour les tags inlines (comme les liens).
   La syntaxe pour les tableaux impose dor�navant d'avoir un
   �space <em>avant et apr�s</em> chaque s�parateur de colonne
   (sauf pour le premier | en d�but de ligne).</p>
<p>Probl�me identique pour les d�finitions, avec le caract�re : qui sert de s�parateur
  entre le terme et la d�finition. Quand il y avait un lien complet au niveau du terme
  (http://truc.com), le ':' du lien etait pris comme s�parateur.
  Changement de syntaxe donc pour les d�finitions
  o� il faut dor�navant encadrer le ':' s�parateur par des espaces.</p></dd>
   <dt>Version 2.0.3, <!--<a href="download/WikiRenderer_2.0.3.zip">WikiRenderer_2.0.3.zip</a>-->
       22/01/2004.</dt>
   <dd>correction d'un bug sur le remplacement des mots wiki lorsque un mot wiki est
   r�p�t� plusieurs fois dans une m�me ligne.</dd>

   <dt>Version 2.0.2, <!--<a href="download/WikiRenderer_2.0.2.zip">WikiRenderer_2.0.2.zip</a>-->
   21/01/2004.</dt>
   <dd>Correction sur les tags inlines qui n'ont pas de fonctions sp�cialis�es :
      mauvaise g�n�ration des attributs html.</dd>
   <dt>Version 2.0.1, <!--<a href="download/WikiRenderer_2.0.1.zip">WikiRenderer_2.0.1.zip</a>--> 19/01/2004.</dt>
   <dd>Toute petite correction sur la g�n�ration des acronymes (la d�scription allait dans
   l'attribut lang)</dd>

   <dt>Version 2.0.0, <!--<a href="download/WikiRenderer_2.0.0.zip">WikiRenderer_2.0.0.zip</a>--> 18/01/2004.</dt>
   <dd> Am�liorations par rapport � la 2.0 RC2 :
      <ul>
      <li>l�g�res optimisations</li>
      <li>la m�thode WikiRendererBloc::getRenderedLine n'accepte plus la ligne courante
         en param�tre pour des raisons d'optimisations puisqu'en fait, on la
         retrouve dans $this->_detectMatch[0].</li>
      <li>ajout d'un param�tre dans la configuration par d�faut, pour indiquer l'ordre
      d'importance des signes de titre ( ! &gt; !! &gt; !!! ou !!! &gt; !! &gt; ! )</li>
      <li>citations imbriqu�es dor�navant possibles</li>
      <li>correction de bugs sur le traitement des attributs des tags wiki inlines (WikiTag)</li>
      <li>correction de bug sur la d�tection des mots wiki dans les lignes ne contenant pas de balises wiki</li>
      <li>correction d'un bug sur la num�rotation des lignes indiqu�es dans les erreurs.</li>
      </ul>
   </dd>


   <dt>Version 2.0RC2, <!--<a href="download/WikiRenderer_20RC2.zip">WikiRenderer_20RC2.zip</a>--> 07/01/2004.</dt>
   <dd>Am�liorations par rapport � la 2.0 RC1 :
      <ul>
         <li>Meilleure prise en charge pour les liens : interdit les liens de type javascript
         pour plus de s�curit�, et pour les liens tout simple (<code>[http://site.com/]</code>),
         mais tr�s long, troncage � l'affichage.</li>
         <li>Ajout de la d�tection des mots wiki, d�sactiv� par d�faut.</li>
      </ul>
   </dd>
   <dt>Version 2.0RC1, <!--<a href="download/WikiRenderer_20RC1.zip">WikiRenderer_20RC1.zip</a>-->  23/12/2003.</dt>
   <dd>Refonte totale avec un code xhtml g�n�r� valide en toute circonstance (sauf bug ;-)</dd>
   <dt>Version 1.0 : <!--<a href="download/WikiRenderer_10.zip">WikiRenderer_10.zip</a>--> , 15/04/2003.</dt>
</dl>





<?php

require('footer.inc.php');
?>








