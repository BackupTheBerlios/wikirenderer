<?php
$path_link=array('documentation'=>'documentation.php');
require('header.inc.php');
?>

<h2>Utilisation de Wikirenderer 2.0</h2>
<p style="font-style:italic">Derni�re mise � jour le 16/05/2004</p>
<h3>Simple</h3>
<pre><code> include('WikiRenderer.lib.php');
 $wkr = new WikiRenderer();
 $monTexteXHTML = $wkr->render($monTexteWiki);
</code></pre>

<h3>Modifier la configuration par d�faut</h3>
<p>Il suffit d'instancier l'objet WikiRendererConfig et de modifier ses propri�t�s :</p>
<pre><code> include('WikiRenderer.lib.php');

 $config = new WikiRendererConfig();

 <span style="text-style:italic">// exemple de d�sactivation de l'interpretation des balises wiki pour les tableaux</span>
 $config->bloctags['table']=false;

  <span style="text-style:italic">// exemple de d�sactivation de l'interpretation des balises wiki pour les citations</span>
 unset($config->inlinetags['cite']);

  <span style="text-style:italic">// ajout de l'interpretation d'un smiley</span>
 $config->simpletags[';-)']='&lt;img src="smiley_clindoeil.png" alt=""/>';

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
<p>Ils sont situ�s dans l'objet WikiRendererConfig</p>
<dl>
<dt><code>inlinetags</code></dt>
<dd>liste des tags wiki que l'on peut utiliser � l'int�rieur les phrases. Voir
<a href="#confavancee">la partie configuration avan��e</a>.
</dd>
<dt><code>bloctags</code></dt>
<dd>liste des tags de type blocs disponible. La cl� de chaque �lement de la liste
est le nom du bloc et la valeur, <code>true</code> ou <code>false</code>
suivant si on active ou non la d�tection du bloc. Voir
<a href="#confavancee">la partie configuration avan��e</a>.</dd>
<dt><code>simpletags</code></dt>
<dd>tags simples pour lesquels il y a juste un remplacement � faire. C'est donc un tableau PHP
d'�lements 'chaine � remplacer'=>'chaine remplacante'.</dd>
<dt><code>minHeaderLevel</code></dt>
<dd>Niveau minimal pour la g�n�ration des titres avec la balise <code>&lt;Hx&gt;</code>.
Par exemple, si on met 3, !!! donnera  <code>&lt;H3&gt;</code>, !! donnera <code>&lt;H4&gt;</code>
et ! donnera <code>&lt;H5&gt;</code>.
</dd>
<dt><code>headerOrder</code></dt>
<dd> indique le sens dans lequel il faut interpreter le nombre de signe de titre :
   <ul>
   <li><var>true</var> : ! = titre , !! = sous titre, !!! = sous-sous-titre</li>
   <li><var>false</var> : !!! = titre , !! = sous titre, ! = sous-sous-titre</li>
   </ul>
</dd>
<dt><code>inlineTagSeparator</code></dt>
<dd>S�parateur des differents attributs des tags wiki. Par defaut : <code>|</code></dd>
<dt><code>checkWikiWord</code></dt>
<dd>Indique si il faut d�tecter les mots wiki (un mot wiki est un mot commen�ant
par une majuscule, et contenant au moins une deuxi�me majuscule � l'interieur du mot)</dd>
<dt><code>checkWikiWordFunction</code></dt>
<dd>Indique le nom de la fonction qui sera appel�e si la d�tection des mots wiki est activ�.
Cette fonction devra recuperer en param�tre une liste de mots wiki, et devra
renvoy� une liste des chaines qui remplaceront les mots wiki indiqu�s.
Cette fonction est � impl�menter par vous-m�me selon votre application. Voir
<a href="#confavancee">la partie configuration avan��e</a>.</dd>
<dt><code>escapeSpecialChars</code> (version >= 2.0.5)</dt>
<dd>Indique si il faut �chapper (true) ou non les balises html incluses dans le texte wiki pour ne
pas qu'elles soient interpret�es par le navigateur. Par mesure de s�curit�, toujours
laisser � <var>true</var> sauf si vous vous fa�tes une configuration pour transformer dans
un format autre que XHTML/HTML.</dd>
</dl>

<h3>Les tags wiki par d�fauts</h3>

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
<p>Dans un texte wiki, on peut d�sactiver l'interpretation d'un tag wiki
en mettant un antislash devant la balise d'ouverture (et de fermeture
pour les tags en lignes). Exemple : <code>\__emphase\__</code>.</p>

<h2 id="confavancee">Configuration avan��e</h2>

<p>Si le balisage wiki propos�e par d�faut ne vous convient pas, il faut le red�finir.
Voici comment.</p>

<h3>Principes</h3>
<p>Il faut :</p>

<ol>
<li>cr�er un objet de configuration selon le mod�le de WikiRendererConfig (ou surcharger WikiRendererConfig)</li>
<li>Cr�er des nouveaux objets de type WikiRendererBloc pour les balises
de type blocs et les indiquer dans votre objet de configuration</li>
<li>Passer cet objet de configuration en param�tre au constructeur de WikiRenderer</li>
</ol>
<p>Voir l'exemple avec le fichier WikiRenderer_w2x.conf.php, qui redefinit
un balisage compatible avec <a href="http://www.neokraft.net/sottises/wiki2xhtml/">wiki2xhtml</a> : </p>

<pre><code>
 include('WikiRenderer.lib.php');
 include('WikiRenderer_w2x.conf.php');

 $wkr = new WikiRenderer(<strong>new Wiki2XhtmlConfig()</strong>);
 $monTexteXHTML = $wkr->render($monTexteWiki);
</code></pre>


<h3>Modifier les tags wiki inlines</h3>

<p>Les tags wiki inlines sont les tags que l'on utilise � l'interieur des textes :
liens, emphases (gras, italique...), acronymes etc.. Ils sont d�finis dans la
variable <code>inlinetags</code> de la classe de configuration
WikiRendererConfig.</p>

<h4>Les propri�t�s d'un tag wiki</h4>

<p><code>inlinetags</code> est un tableau d'�lements d�finissant chaque tag wiki :</p>
<pre><code>array(
  'nomtagwiki'=>array( propri�t�s du tag...),
  'nomtagwiki2'=>array( propri�t�s du tag...),
  ...);
</code></pre>
<p>Les propri�t�s du tag sont, dans l'ordre : </p>
<ol>
<li>symbole de d�but (cha�ne);</li>
<li>symbole de fin (cha�ne);</li>
<li>liste (tableau) des attributs xhtml (Si il n'y en a pas : null );</li>
<li>nom d'une fonction � appeler pour g�n�rer la balise xhtml, pour les balises
au fonctionnement complexe (si il n'y en a pas : null).</li>
</ol>

<h4>G�n�ration du xhtml par d�faut</h4>
<p>Quand il n'y a pas de fonction indiqu�e pour g�n�rer la balise xhtml,
WikiRenderer la g�n�rera automatiquement. Il
prendra le nom du tag wiki comme nom de balise xhtml.</p>
<p>D�finissons par exemple une balise wiki pour faire une emphase
(balise html <code>strong</code>)
avec pour d�limiteurs <code>__</code> : </p>
<pre><code>var $inlinetags= array(
   'strong' =>array('__','__', null,null),
   ...
   );
</code></pre>
<p>Si on �crit donc <code>__mon emphase__</code>, cela prendra <code>strong</code> comme
nom de balise, et sera transform� alors en <code>&lt;strong&gt;mon emphase&lt;/strong&gt;</code>.</p>

<p>On a vu que l'on pouvait indiquer une liste d'attributs xhtml dans les propri�t�s du tag.
Dans ce cas, WikiRenderer r�cuperera chaque cha�ne se trouvant entre le s�parateur
<code>|</code> (s�parateur configurable) dans le tag wiki, et
seront utilis�es comme valeur aux attributs indiqu�s.</p>
<p>Par exemple, admettons que l'on d�finisse ceci :</p>
<pre><code>var $inlinetags= array(
   'acronym'=>array('??','??', array('lang','title'),null),
   ...
   );
</code></pre>
<p>Si on �crit alors <code>??aaaa|bbbb|cccc??</code> :</p>
<ul>
<li>La valeur <code>aaaa</code>
sera utilis�e comme valeur entre la balise ouvrante et fermante XHTML,</li>
<li>La valeur <code>bbbb</code> sera la valeur de l'attribut <code>lang</code></li>
<li>La valeur <code>cccc</code> sera la valeur de l'attribut <code>title</code></li>
</ul>
<p>Le code XHTML r�sultant sera donc
 <code>&lt;acronym lang="bbbb" title="cccc"&gt;aaaa&lt;/acronym&gt;.</code>.</p>


<h4>Utilisation d'une fonction g�n�ratrice sp�cifique</h4>
<p>Quand la g�n�ration par d�faut ne suffit pas, qu'il faille un traitement particulier,
il faut alors indiquer une fonction de g�n�ration xhtml. Dans ce cas, le nom du tag
importe peu, voir m�me la liste des attributs.
Il faut juste que le nom soit diff�rent des autres.
Cette fonction devra accepter en param�tre deux tableaux :</p>
<ol>
<li>Liste des chaines trouv�es entre le symbole de d�but et le symbole de fin;</li>
<li>Liste des noms d'attributs de la propri�t� 3 du tag.</li>
</ol>
<p>Par exemple, si les propri�t�s d'un tag sont  :</p>
<pre><code>var $inlinetags= array(
   'link'   =>array('[',']', array('href','lang','title'),'wikibuildlink'),
   ...
   );
</code></pre>
<p>Et si on �crit <code>[aaaa|bbbb|cccc|dddd]</code>, la fonction <code>wikibuildlink</code>
sera appel�e avec les param�tres suivants :</p>
<ul>
   <li><code>array('aaaa','bbbb','cccc','dddd')</code></li>
   <li><code>array('href','lang','title')</code></li>
</ul>

<p>La fonction devra retourner une cha�ne contenant le code XHTML g�n�r�.
Vous pouvez voir des exemples de telles fonctions dans WikiRenderer.conf.php : <code>wikibuildlink</code>,
<code>wikibuildimage</code>, <code>wikibuildanchor</code>.</p>


<h3>Modifier les tags wiki de bloc de texte</h3>

<p>Les tags wiki de blocs permettent d'indiquer la nature d'un bloc de texte : titre, paragraphe,
liste, citation etc.. Pour prendre en charge un type de bloc de texte, il faut
d�velopper une classe d�rivant de <code>WikiRendererBloc</code>. Et ensuite indiquer
cette classe dans la classe de configuration.</p>

<p>Si vous voulez seulement modifier quelques propri�t�s d'un bloc existant
dans la configuration par d�faut (par exemple red�finir l'expression r�guli�re, donc
le tag du bloc), vous pouvez simplement �crire une classe d�rivant du blog d'origine
et indiquer la nouvelle expression r�guli�re, comme ceci :</p>
<pre><code>class WRB_monTitleAMoi extends WRB_title {
   var $type='titleamoi';
   var $regexp="/^(\={1,3})(.*)/"; // et non plus /^(\!{1,3})(.*)/
}</code></pre>

<h4>Les propri�t�s</h4>
<p>Voici les propri�t�s de WikiRendererBloc que vous pouvez modifier dans votre
propre classe :</p>
<dl>
<dt><code>type</code></dt>
<dd>C'est un nom que vous donnez arbitrairement � votre tag. Il doit �tre
unique parmis les noms des autres tag wiki de blocs (propri�t� obligatoire).</dd>

<dt><code>regexp</code></dt>
<dd>Expression r�guli�re qui sera appliqu�e sur chaque ligne du texte, pour savoir
si la ligne appartient au bloc (Propri�t� obligatoire).</dd>

<dt><code>_openTag</code></dt>
<dd>C'est la balise XHTML qui sera ins�r�e au d�but du bloc de texte. Propri�t�
obligatoire si la m�thode <code>open</code> n'est pas red�finie
ou si la propri�t� <code>_closeNow</code>
est � false et/ou que vous n'avez pas redefini <code>getRenderedLine</code>.</dd>

<dt><code>_closeTag</code></dt>
<dd>C'est la balise XHTML qui sera ins�r�e � la fin du bloc de texte. Propri�t�
obligatoire si la m�thode <code>close</code> n'est pas red�finie ou
si la propri�t� <code>_closeNow</code>
est � false et/ou que vous n'avez pas redefini <code>getRenderedLine</code>.</dd>

<dt><code>_closeNow</code></dt>
<dd>C'est un boolean (<code>true</code> ou <code>false</code>), qui indique si le bloc doit �tre
ferm�e immediatement apr�s son ouverture. On mettra donc <code>true</code> si le
bloc ne fait qu'une ligne, comme c'est le cas pour les titres ou les s�parateurs
HTML <code>&lt;hr /&gt;</code>. (Propri�t� obligatoire).</dd>

</dl>

<p>Il existe �galement une propri�t�, <code>_detectMatch</code>, qui contient ce
qui a �t� trouv�e par l'expression r�guli�re, si celle-ci a des parenth�ses capturantes.
Vous pourrez donc faire appel � cette propri�t� dans les m�thodes <code>open</code>
 ou <code>getRenderedLine</code> pour �viter �ventuellement d'avoir � refaire une analyse
 de la ligne de texte pour la transformer.</p>


<h4>Les m�thodes</h4>
<dl>
<dt><code>constructeur</code></dt>
<dd>C'est dans le constructeur que vous pourrez initialiser les propri�t�s de
votre objet. Le constructeur doit accepter en param�tre l'objet WikiRenderer, pass�
en r�f�rence. Ainsi, si votre classe s'appelle <code>WRB_title</code>, vous devrez
d�buter la d�claration du constructeur comme ceci: <code>function WRB_title(&amp;$wr){</code>.<br />
Le fait d'avoir l'objet <code>WikiRenderer</code> en param�tre vous permet
d'acc�der �ventuellement � la configuration. Ex: <code>$this->_minlevel = $wr->config->minHeaderLevel;</code>.<br />
Vous devrez �galement imp�rativement faire appel au constructeur de
<code>WikiRendererBloc</code> comme ceci : <code>parent::WikiRendererBloc($wr);</code>.
</dd>

<dt><code>detect</code></dt>
<dd>Cette m�thode est appel�e pour detecter si la ligne de texte courante
fait partie du bloc ou pas. En temps normal, vous ne devriez pas avoir
� redefinir cette m�thode.</dd>

<dt><code>open</code></dt>
<dd>C'est une m�thode appel�e quand le d�but du bloc a �t� d�t�ct�. Elle
doit renvoyer du texte HTML � inserer au d�but du bloc g�n�r� (<code>&lt;ul&gt;</code>
pour une liste par exemple). Par d�faut,
renvoi la valeur de la propri�t� <code>_openTag</code>. Vous
pouvez redefinir cette m�thode si vous voulez �ffectuer d'autres
traitements � ce moment l�.</dd>

<dt><code>close</code></dt>
<dd>C'est une m�thode appel�e quand la fin du bloc a �t� d�t�ct�e. Elle
doit renvoyer du texte HTML � inserer � la fin du bloc g�n�r�
 (<code>&lt;/ul&gt;</code> pour une liste par exemple). Par d�faut,
renvoi la valeur de la propri�t� <code>_closeTag</code>. Vous
pouvez redefinir cette m�thode si vous voulez effectuer d'autres
traitements � ce moment l�.</dd>

<dt><code>closeNow</code></dt>
<dd>Renvoi par d�faut la valeur de la propri�t� <code>_closeNow</code> (un bool�en).
Elle indique donc au moteur de WikiRenderer si il faut fermer
immediatement le bloc juste apr�s l'ouverture. En temps normal, vous ne devriez pas avoir
� redefinir cette m�thode.</dd>

<dt><code>getRenderedLine</code></dt>
<dd>Elle doit renvoyer la ligne courante transform�e en XHTML, en utilisant notamment
la m�thode <code>_renderInlineTag</code>.
(<strong>Note : </strong> Avant la version 2.0 finale, <code>getRenderedLine</code> acceptait
en param�tre la ligne de texte courante. Ce n'est plus le cas pour des raisons
d'optimisation. En effet, on trouve celle ci dans le premier �lement de
<code>_detectMatch</code> : <code>$ligneOriginale=$this->_detectMatch[0]</code>).<br />

Par d�faut, elle fait juste �a : <code>return $this->_renderInlineTag($this->_detectMatch[1]);</code>.<br />
<code>_detectMatch</code> contenant le r�sultat de l'�valuation de l'expression r�guli�re
<code>regexp</code> de votre bloc, cela signifie donc qu'il doit y avoir au moins
une parenth�se capturante dans l'expression. Si il y a plus d'une parenth�se capturante
ou pas du tout, il vous faudra donc red�finir getRenderedLine pour en tenir compte.<br />
Vous pourriez aussi avoir besoin de faire des choses suppl�mentaires. Par exemple, si il
s'agit d'un bloc de liste, rajouter les balises XHTML <code>&lt;li&gt;</code> et
<code>&lt;/li&gt;</code>, avant et apr�s le texte transform�. Cela donnerait :
<code>return '&lt;li&gt;'.$this->_renderInlineTag($this->_detectMatch[1]).'&lt;/li&gt;';</code>.
<br />
Il peut arriver aussi parfois, de ne pas tenir compte de la ligne de texte, et de
renvoyer directement un contenu, comme cela peut �tre le cas pour la s�paration <code>&lt;hr /&gt;</code> :
<code>return '&lt;hr /&gt;';</code>.
</dd>

<dt><code>_renderInlineTag</code></dt>
<dd>Cette m�thode appelle le moteur de rendu des tags wiki inline (WikiInlineParser). Elle
prend en argument une chaine format�e wiki et renvoie la chaine correspondante en XHTML.
En principe, vous n'avez pas � la red�finir.</dd>
</dl>

<h4>Nommage de votre classe</h4>
<p>Le nom de votre classe doit commencer par <code>WRB_</code> et se finir
par un nom que vous indiquerez dans la propri�t� <code>bloctags</code>
de la configuration. Ainsi, si vous nommez votre classe <code>WRB_titre</code>,
vous mettrez dans la configuration :</p>
<pre><code>var $blogtags = array( ... , 'titre'=>true, ... );</code></pre>

<h4>Principes de fonctionnement</h4>
<p>Voici quelques informations qui vont vous permettre de mieux comprendre comment
est utilis� un objet WikiRendererBloc par le moteur WikiRenderer.</p>
<ul>
<li>WikiRenderer analyse une � une les lignes du texte wiki.</li>
<li>� chaque ligne, il va appeler la m�thode <code>detect</code> du bloc courant.</li>
<li>Si elle renvoie <code>true</code>, c'est que la ligne fait encore partie du bloc.
Il va donc demander au bloc de transformer la ligne en XHTML en appelant <code>getRenderedLine</code>.</li>
<li>Si la d�tection a �chou�, WikiRenderer ferme le bloc (en �xecutant la m�thode <code>close</code>
du bloc), et va appeler la m�thode <code>detect</code> de chaque type de bloc qui sont
r�f�renc�s dans la propri�t� <code>bloctags</code> de la configuration.</li>
<li>Il va ainsi savoir quel est le type du nouveau bloc. Celui-ci deviendra le bloc courant
et la m�thode <code>open</code> sera appel�, ainsi que <code>getRenderedLine</code>.</li>
<li>Ainsi de suite jusqu'� la fin</li>
</ul>

<h4>Pour en savoir plus sur les blocs</h4>
<p>Regardez comment sont d�velopp�s les blocs par d�faut, dans le fichier
<code>WikiRenderer.conf.php</code>.</p>

<h3>Fonction de traitement des mots wikis</h3>
<p>Les mots Wiki sont des mots qui commencent par
une majuscule et en contiennent au moins 2. Ex : <code>CeciEstUnMotWiki</code>. Cela est utilis�
dans les syst�mes wiki, pour faire automatiquement des liens  vers les pages qui portent
le m�me nom.</p>
<p>WikiRenderer permet de d�tecter ces mots Wiki, mais ce n'est pas activ� par d�faut, car
le traitement des mots Wiki est sp�cifique � l'usage que vous en faites. Pour utiliser
les mots wiki vous devez donc : </p>
<ol>
   <li>Modifier dans l'objet de configuration les propri�t�s <code>checkWikiWord</code>
   (activer la d�tection)
   et <code>checkWikiWordFunction</code> (indiquant la fonction de traitement des mots wiki).</li>

   <li>D�finir une fonction de traitement des mots wiki</li>
</ol>

<p>Voici un exemple de configuration :</p>
<pre><code>class ConfWikiRenderer extends CopixWikiRendererConfig {
  var $checkWikiWord = true;
  var $checkWikiWordFunction = 'evalWikiWord';
}</code></pre>
<p>Ici WikiRenderer fera appel � la fonction <code>evalWikiWord</code>.</p>

<p>La fonction que vous indiquerez devra accepter en param�tre une liste de mots
wiki qui ont �t� trouv� dans la ligne de texte courante. Et devra retourner
une liste de chaine qui remplaceront ces mots wiki dans le texte. Le contenu
de cette liste est dans le m�me ordre que la liste des mots wiki : la
premi�re cha�ne correspond au premier mot wiki, la deuxi�me au deuxi�me
mot wiki etc.</p>
<p>Exemple de fonction pour un syst�me d'edition wiki, qui retourne des liens
HTML pour chaque mot wiki, liens qui sont diff�rents si ces mots correspondent ou pas
� des pages wiki : </p>
<pre><code>function evalWikiWord($wikiWordList){

   $result=array();

   foreach($wikiWordList as $word){
      // findWikiPage = fonction imaginaire, qui tenterait de trouver dans un syst�me wiki, la page correspondante au mot
      if(findWikiPage($word))
         // page wiki trouv�e
         $result[]='&lt;a href="wiki.php?wiki='.$word.'" class="wikiword">'.$word.'&lt;/a>';
      else
         // page wiki non trouv�e
         $result[]='&lt;a href="wiki.php?wiki='.$word.'&amp;action=edit" class="unknowwikiword">'.$word.'&lt;/a>';
   }
   return $result;
}
</code></pre>



<?php

require('footer.inc.php');
?>








