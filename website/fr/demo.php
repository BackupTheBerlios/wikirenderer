<?php
if($_SERVER['SERVER_NAME'] == 'wikirenderer.berlios.de'){
   require('wikirenderer/WikiRenderer.lib.php');
   require('wikirenderer/rules/wr3_to_text.php');
   require('wikirenderer/rules/wr3_to_xhtml.php');

}else{
   require('../../tests/wikirenderer/WikiRenderer.lib.php');
   require('../../tests/wikirenderer/rules/wr3_to_text.php');
   require('../../tests/wikirenderer/rules/wr3_to_xhtml.php');
}

$rulesList = array(
    'wr3_to_xhtml'=>'WR3 to XHTML',
    'wr3_to_text'=>'WR3 to text',
    'wr3_to_docbook'=>'WR3 to Docbook',
    'wr3_to_dokuwiki'=>'WR3 to Dokuwiki',
    'dokuwiki_to_xhtml'=>'Dokuwiki to XHTML',
    'dokuwiki_to_docbook'=>'Dokuwiki to Docbook',
    'trac_to_xhtml'=>'Trac syntax to XHTML',
    'phpwiki_to_dokuwiki'=>'Phpwiki to dokuwiki',
);

$examples = array(
    'wr3_to_xhtml'=>'wr3',
    'wr3_to_text'=>'wr3',
    'wr3_to_docbook'=>'wr3',
    'wr3_to_dokuwiki'=>'wr3',
    'dokuwiki_to_xhtml'=>'dokuwiki',
    'dokuwiki_to_docbook'=>'dokuwiki',
    'trac_to_xhtml'=>'trac',
    'phpwiki_to_dokuwiki'=>'phpwiki',
);


$examplesContent = array(
    'wr3'=>
"!!! Main title

This example use the WR3 syntax.

!! title level 2

This is a paragraph with a __strong emphasis__ and a ''normal emphasis''.
this sentence contains a [[link|http://jelix.org|en|web site of the famous php5 framework]]
and a reference to a foot note$$ this is the foot note$$.
Now here is a list

*item 1
*item 2
**nested item A
**nested item B
*item 3

Wiki tag can be \\\__escaped\\\__.

Of course, some other wiki 'tags' are available for this syntax.
",

    'dokuwiki'=>"==== Main title ====

This example use the dokuwiki syntax.

=== title level 2 ===

This is a paragraph with a **strong emphasis** and a //normal emphasis//.
this sentence contains a [[http://jelix.org|link]] and a reference to a foot note((this is the foot note)).
Now here is a list

  * item 1
  * item 2
     * nested item A
     * nested item B
  * item 3

Of course, some other wiki 'tags' are available for this syntax. 
",

    'phpwiki'=>"!!! Main title

This example use the Phpwiki syntax.

!! title level 2

This is a paragraph with a __strong emphasis__ and a ''normal emphasis''.
this sentence contains a [link|http://jelix.org].
Now here is a list:

*item 1
*item 2
**nested item A
**nested item B
*item 3

Wiki tag can be \\\__escaped\\\__.

Of course, some other wiki 'tags' are available for this syntax.
",

  'trac'=>"= Main title =

This example use the trac syntax.

== title level 2 ==

This is a paragraph with a '''strong emphasis''' and a ''normal emphasis''.
this sentence contains [http://jelix.org a link].
Now here is a list

  * item 1
  * item 2
     * nested item A
     * nested item B
  * item 3

Of course, some other wiki 'tags' are available for this syntax.
",


);




$texte='';
$rule = 'wr3_to_xhtml';

if(isset($_POST['texte'])){
    if(get_magic_quotes_gpc())
       $texte=stripslashes($_POST['texte']);
    else
       $texte=$_POST['texte'];

    if (isset($rulesList[$_POST['rule']]))
        $rule = $_POST['rule'];
}

$path_link=array('démo'=>'demo.php');
require('header.inc.php');
?>

<script type="text/javascript">

<?php foreach($examplesContent as $k=>$content) {
  echo 'var '.$k.'Text ="'.str_replace("\n",'\n', $content).'"'."\n\n";
}
?>


function exemple(){
    var rule = document.test.rule.value;
    var text = '';
    if (rule == 'wr3_to_xhtml' || rule == 'wr3_to_text' || rule == 'wr3_to_docbook'  || rule == 'wr3_to_dokuwiki')
        text = wr3Text;
    else if (rule == 'dokuwiki_to_xhtml' || rule == 'dokuwiki_to_docbook')
        text = dokuwikiText;
    else if ( rule == 'trac_to_xhtml')
        text = tracText;
    else if ( rule == 'phpwiki_to_dokuwiki')
        text = phpwikiText;
   document.test.texte.value = text;
}

</script>


<h2>Testez Wikirenderer <?php echo WIKIRENDERER_VERSION; ?></h2>
<p>Vous pouvez tester le moteur de WikiRenderer : saisissez votre texte wiki, validez, et
celui-ci sera transformé.</p>

<form action="demo.php#resultats" method="POST" id="test" name="test">
<fieldset><legend>Saisissez un texte wiki</legend>
<label>texte :
<textarea style="border:1px solid;" name="texte" cols="50" rows="20"><?php echo htmlspecialchars($texte)?></textarea></label>
<br />
<label>Transformation en :
<select name="rule">
<?php foreach($rulesList as $r=>$label) {
    echo '<option value="'.$r.'"';
    if ($r == $rule) echo ' selected="selected"';
    echo '>'.htmlspecialchars($label).'</option>';
}?>
</select>  <input type="button" value="editer un exemple" onclick="exemple()" />
<br />
<input type="submit" value="Valider et voir la transformation" />
</fieldset>
</form>

<?php
if($texte!=''){

    $ctr=new WikiRenderer($rule);
    echo '<h2 id="resultats">Source du resultat:</h2>';

    $texte=$ctr->render($texte);
    if($ctr->errors){
        echo '<p style="color:red;">Il y a ';
        if(count($ctr->errors)>1)
           echo 'des erreurs wiki aux lignes : ',implode(',',array_keys($ctr->errors)),'</p>' ;
        else{
           list($num,$l)=each($ctr->errors);
           echo 'une erreur wiki à la ligne ', $num,'</p>';
       }
    }

   $texte2=htmlspecialchars($texte);
   echo '<pre style="overflow:auto">';
   echo $texte2;
   echo '</pre>',"\n\n";

}

require('footer.inc.php');

