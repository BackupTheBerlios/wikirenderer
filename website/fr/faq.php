<?php
$path_link=array('FAQ'=>'faq.php');
require('header.inc.php');
?>

<h2>Foire Aux Questions</h2>

<dl>
   <dt id="echappement">Je ne veux pas que des caract�res correspondants � des tags
   wiki soient interpr�t�s, comment faire ?</dt>
   <dd>Mettre un anti-slash devant le caract�re. <br />
   Exemple : <code>\[ exemple \]</code> ne sera pas interpr�t� comme
   �tant un lien.</dd>
</dl>


<?php

require('footer.inc.php');
?>








