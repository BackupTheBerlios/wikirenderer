<?php
/**
 * dokuwiki syntax to docbook 4.3
 *
 * @package WikiRenderer
 * @subpackage rules
 * @author Laurent Jouanneau <jouanneau@netcourrier.com>
 * @copyright 2008 Laurent Jouanneau
 * @link http://wikirenderer.berlios.de
 *
 * This library is free software; you can redistribute it and/or
 * modify it under the terms of the GNU Lesser General Public 2.1
 * License as published by the Free Software Foundation.
 *
 * This library is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU
 * Lesser General Public License for more details.
 *
 * You should have received a copy of the GNU Lesser General Public
 * License along with this library; if not, write to the Free Software
 * Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
 *
 */

class dokuwiki_to_docbook  extends WikiRendererConfig  {
    /**
        * @var array   liste des tags inline
    */
    public $inlinetags= array( 'dkdbk_strong','dkdbk_emphasis','dkdbk_underlined','dkdbk_monospaced',
        'dkdbk_subscript', 'dkdbk_superscript', 'dkdbk_del', 'dkdbk_link', 'dkdbk_footnote', 'dkdbk_image'
        /*'dkdbk_q',
        'dkdbk_cite','dkdbk_acronym','dkdbk_link',
        'dkdbk_anchor', 'dkdbk_footnote'*/);

    public $defaultTextLineContainer = 'WikiHtmlTextLine';

    public $availabledTextLineContainers = array('WikiHtmlTextLine');

   /**
   * liste des balises de type bloc reconnus par WikiRenderer.
   */
   public $bloctags = array('dkdbk_title', 'dkdbk_list', 'dkdbk_blockquote','dkdbk_table'/*'dkdbk_pre','dkdbk_hr',
                         ,'dkdbk_definition',*/ , 'dkdbk_para');


   public $simpletags = array("\\\\"=>"");

   public $escapeChar = '';

   public $sectionLevel= array();

    /**
    * called before the parsing
    */
   public function onStart($texte){
        $this->sectionLevel = array();
        return $texte;
    }

   /**
    * called after the parsing
    */
    public function onParse($finalTexte){
        $finalTexte.= str_repeat('</section>', count($this->sectionLevel));


        return $finalTexte;
    }
}

// ===================================== inline tags

class dkdbk_strong extends WikiTagXhtml {
    protected $name='emphasis';
    public $beginTag='**';
    public $endTag='**';
    protected $additionnalAttributes=array('role'=>'strong');
}

class dkdbk_emphasis extends WikiTagXhtml {
    protected $name='emphasis';
    public $beginTag='//';
    public $endTag='//';
}

class dkdbk_underlined extends WikiTagXhtml {
    protected $name='underlined';
    public $beginTag='__';
    public $endTag='__';
    public function getContent(){ return $this->contents[0];}
}

class dkdbk_monospaced extends WikiTagXhtml {
    protected $name='code';
    public $beginTag='\'\'';
    public $endTag='\'\'';
}


class dkdbk_subscript extends WikiTagXhtml {
    protected $name='subscript';
    public $beginTag='<sub>';
    public $endTag='</sub>';
}

class dkdbk_superscript extends WikiTagXhtml {
    protected $name='superscript';
    public $beginTag='<sup>';
    public $endTag='</sup>';
}

class dkdbk_del extends WikiTagXhtml {
    protected $name='del';
    public $beginTag='<del>';
    public $endTag='</del>';
    public function getContent(){ return '';}
}

class dkdbk_link extends WikiTagXhtml {
    protected $name='ulink';
    public $beginTag='[[';
    public $endTag=']]';
    protected $attribute=array('href','$$');
    public $separators=array('|');

    public function getContent(){
        $cntattr=count($this->attribute);
        $cnt=($this->separatorCount + 1 > $cntattr?$cntattr:$this->separatorCount+1);
        if($cnt == 1 ){
            $contents = $this->wikiContentArr[0];

            if(preg_match("/^\#(.+)$/", $contents, $m))
                return '<link linkterm="'.htmlspecialchars(trim($m[1])).'">'.htmlspecialchars($contents).'</link>';
            else
                return '<ulink url="'.htmlspecialchars(trim($contents)).'">'.htmlspecialchars($contents).'</ulink>';

        }else{
            if(preg_match("/^\#(.+)$/", $this->wikiContentArr[0], $m))
                return '<link linkterm="'.htmlspecialchars(trim($m[0])).'">'.$this->contents[1].'</link>';
            else
                return '<ulink url="'.htmlspecialchars(trim($this->wikiContentArr[0])).'">'.$this->contents[1].'</ulink>';
        }
    }
}

class dkdbk_footnote extends WikiTagXhtml {
    protected $name='footnote';
    public $beginTag='((';
    public $endTag='))';
    public function getContent(){ return '<footnote><para>'.$this->contents[0].'</para></footnote>';}
}


/*
class dkdbk_q extends WikiTagXhtml {
    protected $name='quote';
    public $beginTag='^^';
    public $endTag='^^';
}

class dkdbk_cite extends WikiTagXhtml {
    protected $name='cite';
    public $beginTag='{{';
    public $endTag='}}';
    protected $attribute=array('$$','title');
    public $separators=array('|');

    public function getContent(){ return $this->contents[0];}

}

class dkdbk_acronym extends WikiTagXhtml {
    protected $name='acronym';
    public $beginTag='??';
    public $endTag='??';
    protected $attribute=array('$$','title');
    public $separators=array('|');
    protected $ignoreAttribute = array('title');
}

class dkdbk_anchor extends WikiTagXhtml {
    protected $name='anchor';
    public $beginTag='~~';
    public $endTag='~~';
    protected $attribute=array('id');
    public $separators=array('|');
    public function getContent(){
        return '<anchor id="'.htmlspecialchars($this->wikiContentArr[0]).'"/>';
    }
}


*/

class dkdbk_image extends WikiTagXhtml {
    protected $name='image';
    public $beginTag='{{';
    public $endTag='}}';
    protected $attribute=array('fileref','title');
    public $separators=array('|');

    public function getContent(){
        $contents = $this->wikiContentArr;

        if(count($contents) == 1) {
            $href = $contents[0];
            $title = '';
        } else {
            $href = $contents[0];
            $title = $contents[1];
        }

        $align='';
        $width='';
        $height='';

        $m= array('','','','','','','','');
        if (preg_match("/^(\s*)([^\s\?]+)(\?(\d+)(x(\d+))?)?(\s*)$/", $href, $m)) {
            if($m[1] != '' && $m[7] != ''){
                $align='center';
            } elseif($m[1] != ''){
                $align='right';
            } elseif($m[7] != ''){
                $align='left';
            }
            if($m[3]) {
                $width=$height=$m[4];
                if($m[5])
                   $height=$m[6];
            }
            $href= $m[2];
        }

        $tag = '<inlinemediaobject><imageobject><imagedata fileref="'.$href.'"';
        if($width != '')
            $tag.=' contentwidth="'.$width.'px"';
        if($height != '')
            $tag.=' contentdepth="'.$height.'px"';
        if($align != '')
            $tag.=' align="'.$align.'"';

        $tag .='/></imageobject>';
        if($title != '') 
                $tag.='<textobject><phrase>'.htmlspecialchars($title).'</phrase></textobject>';

        return $tag.'</inlinemediaobject>';
    }
}



// ===================================== blocs

/**
 * traite les signes de types liste
 */
class dkdbk_list extends WikiRendererBloc {

    public $type='list';
    protected $_stack=array();
    protected $_firstTagLen;
    protected $regexp="/^(\s*)([\*\-])(.*)/";
    protected $_firstItem = true;

    public function open(){
        $this->_stack[] = array(strlen($this->_detectMatch[1]) ,  $this->_detectMatch[2]);
        $this->_firstTagLen = strlen($this->_detectMatch[1]);
        $this->_firstItem = true;
        if($this->_detectMatch[2] == '-')
            return "<orderedlist>\n";
        else
            return "<itemizedlist>\n";
   }

   public function close(){
        $str='';

        for($i=count($this->_stack)-1; $i >=0; $i--){
            if($this->_stack[$i][0] < $this->_firstTagLen) break;

            $str.=($this->_stack[$i][1]== '-'?"</listitem></orderedlist>\n":"</listitem></itemizedlist>\n");
            array_pop($this->_stack);
        }
        return $str;
   }

    public function getRenderedLine(){
        $t=end($this->_stack);
        $newLen = strlen($this->_detectMatch[1]);
        $d=$t[0] - $newLen;
        $str='';

        if( $d < 0 ){ // un niveau de plus
            $this->_stack[] = array($newLen ,  $this->_detectMatch[2]);
            $str=($this->_detectMatch[2] == '-'?"<orderedlist><listitem>":"<itemizedlist><listitem>");

        } else {
            if( $d > 0 ){ // on remonte d'un ou plusieurs cran dans la hierarchie...
                for($i=count($this->_stack)-1; $i >=0; $i--){
                    if($this->_stack[$i][0] <= $newLen){
                        break;
                    } else {
                        $str.=($this->_stack[$i][1]== '-'?"</listitem></orderedlist>\n":"</listitem></itemizedlist>\n");
                    }
                    array_pop($this->_stack);
                }
                if(count($this->_stack) == 0) {
                    $this->_firstTagLen = $newLen;
                    $this->_firstItem = true;
                    $t = array($newLen,   $this->_detectMatch[2]);
                    $this->_stack[] = $t;
                    if($t[1] == '-')
                        $str .= "<orderedlist>\n";
                    else
                        $str .= "<itemizedlist>\n";
                } else {
                    $t=end($this->_stack);
                }

            }

            if($t[1] != $this->_detectMatch[2]) {
                if(!$this->_firstItem)
                    $str .='</listitem>';

                if($t[1] == '-')
                    $str .= "<orderedlist>\n<listitem>";
                else
                    $str .= "<itemizedlist>\n<listitem>";
                array_pop($this->_stack);
                $this->_stack[] = array($newLen ,  $this->_detectMatch[2]);
            } else {
                if($this->_firstItem)
                    $str.="<listitem>";
                else
                    $str.="</listitem>\n<listitem>";
            }

        }
        $this->_firstItem = false;
        return $str.$this->_renderInlineTag(trim($this->_detectMatch[3]));

    }
}



/**
 * traite les signes de types titre
 */
class dkdbk_title extends WikiRendererBloc {
    public $type='title';
    protected $regexp="/^\s*(\={1,6})([^=]*)(\={1,6})\s*$/";
    protected $_closeNow=true;

    public function getRenderedLine(){
        $level = strlen($this->_detectMatch[1]);

        $conf = $this->engine->getConfig();

        $output='';
        if(count($conf->sectionLevel)) {
            $last = end($conf->sectionLevel);
            if($last < $level) {
                while($last = end($conf->sectionLevel) && $last <= $level) {
                    $output.= '</section>';
                    array_pop($conf->sectionLevel);
                }
            }else if($last > $level) {

            }else{
                array_pop($conf->sectionLevel);
                $output.= '</section>';
            }
        }

        $conf->sectionLevel[] = $level;
        return $output.'<section><title>'.$this->_renderInlineTag(trim($this->_detectMatch[2])).'</title>';
    }
}

/**
 * traite les signes de type paragraphe
 */
class dkdbk_para extends WikiRendererBloc {
    public $type='para';
    protected $_openTag='<para>';
    protected $_closeTag='</para>';
    
    public function detect($string){
        if($string=='') return false;
        if(preg_match("/^([^\*\-\=\|\^>;<=].*)/",$string, $m)) {
            $this->_detectMatch=array($m[1],$m[1]);
            return true;
        }
        return false;
    }
}



/**
 * traite les signes de type blockquote
 */
class dkdbk_blockquote extends WikiRendererBloc {
   public $type='bq';
   protected $regexp="/^\s*(\>+)(.*)/";

   public function open(){
      $this->_previousTag = $this->_detectMatch[1];
      $this->_firstTagLen = strlen($this->_previousTag);
      $this->_firstLine = true;
      return str_repeat('<blockquote>',$this->_firstTagLen).'<para>';
   }

   public function close(){
      return '</para>'.str_repeat('</blockquote>',strlen($this->_previousTag));
   }


   public function getRenderedLine(){

      $d=strlen($this->_previousTag) - strlen($this->_detectMatch[1]);
      $str='';

      if( $d > 0 ){ // on remonte d'un cran dans la hierarchie...
         $str='</para>'.str_repeat('</blockquote>',$d).'<para>';
         $this->_previousTag=$this->_detectMatch[1];
      }elseif( $d < 0 ){ // un niveau de plus
         $this->_previousTag=$this->_detectMatch[1];
         $str='</para>'.str_repeat('<blockquote>',-$d).'<para>';
      }else{
         if($this->_firstLine)
            $this->_firstLine=false;
      }
      return $str.$this->_renderInlineTag($this->_detectMatch[2]);
   }
}




/**
 * traite les signes de types table
 */
class dkdbk_table extends WikiRendererBloc {
    public $type='table';
    protected $regexp="/^\s*(\||\^)(.*)/";
    protected $_openTag='<table>';
    protected $_closeTag='</table>';

    protected $_colcount=0;
    protected $oldTextLineContainer = null;

    public function open(){
        $this->_colcount=0;
        $this->oldTextLineContainer = $this->config->defaultTextLineContainer;
        $this->config->defaultTextLineContainer = '';

        return $this->_openTag;
    }

    public function close(){
        return $this->_closeTag;
    }


    public function getRenderedLine(){

        $result=preg_split('/(\||\^)/',trim($this->_detectMatch[1]));
        $str='';
        $t='';

        if((count($result) != $this->_colcount) && ($this->_colcount!=0))
            $t='</table><table border="1">';
        $this->_colcount=count($result);

        for($i=0; $i < $this->_colcount; $i++){
            $str.='<td>'.$this->engine->inlineParser->parse($result[$i]).'</td>';
        }
        $str=$t.'<tr>'.$str.'</tr>';

        return $str;
    }

}


/**
 * traite les signes de types pre (pour afficher du code..)
 */
class dkdbk_pre extends WikiRendererBloc {

    public $type='pre';
    protected $_openTag='<literallayout>';
    protected $_closeTag='</literallayout>';
    protected $isOpen = false;


   public function open(){
      $this->isOpen = true;
      return $this->_openTag;
   }

   public function close(){
      $this->isOpen=false;
      return $this->_closeTag;
   }

    public function getRenderedLine(){
        return htmlspecialchars($this->_detectMatch);
    }

    public function detect($string){
        if($this->isOpen){
            if(preg_match('/(.*)<\/code>\s*$/',$string,$m)){
                $this->_detectMatch=$m[1];
                $this->isOpen=false;
            }else{
                $this->_detectMatch=$string;
            }
            return true;

        }else{
            if(preg_match('/^\s*<code>(.*)/',$string,$m)){
                $this->_detectMatch=$m[1];
                return true;
            }else{
                return false;
            }
        }
    }
}

/**
 * definition list
 */
class dkdbk_definition extends WikiRendererBloc {

   public $type='dfn';
   protected $regexp="/^\s*;(.*) : (.*)/i";
   protected $_openTag='<variablelist>';
   protected $_closeTag='</variablelist>';

   public function getRenderedLine(){
      $dt=$this->_renderInlineTag($this->_detectMatch[1]);
      $dd=$this->_renderInlineTag($this->_detectMatch[2]);
      return "<varlistentry><term>$dt</term>\n<listitem>$dd</listitem></varlistentry>\n";
   }
}



?>