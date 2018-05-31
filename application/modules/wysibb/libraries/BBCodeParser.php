<?php

/**
 * Description of BBCodeParser
 *
 * @author thibault
 */
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

require_once APPPATH . 'modules/wysibb/third_party/FileCodeDefinition.php';

class BBCodeParser extends JBBCode\Parser
{

    public function __construct()
    {
        parent::__construct();
        $this->addCodeDefinitionSet(new JBBCode\DefaultCodeDefinitionSet());

        $baseUrl = base_url();

        $builder = new JBBCode\CodeDefinitionBuilder('code', '<pre><code class="{option}">{param}</code></pre>');
        $builder->setUseOption(true);
        $this->addCodeDefinition($builder->build());

        $builder = new JBBCode\CodeDefinitionBuilder('image', '<img src="' . $baseUrl . '{option}" alt="{param}"/>');
        $builder->setUseOption(true);
        $this->addCodeDefinition($builder->build());

        $builder = new JBBCode\CodeDefinitionBuilder('size', '<span>{param}</span>');
        $builder->setUseOption(true);
        $this->addCodeDefinition($builder->build());

        $builder = new JBBCode\CodeDefinitionBuilder('font', '<span style="font-family={option}">{param}</span>');
        $builder->setUseOption(true);
        $this->addCodeDefinition($builder->build());

        $builder = new JBBCode\CodeDefinitionBuilder('internallink', '<a href="{option}" target="_self">{param}</a>');
        $builder->setUseOption(true);
        $this->addCodeDefinition($builder->build());

        $builder = new JBBCode\CodeDefinitionBuilder('legend', '<p class="code-legend">{param}</p>');
        $this->addCodeDefinition($builder->build());

        $builder = new JBBCode\CodeDefinitionBuilder('externalink', '<a href="{option}" target="_blank">{param}</a>');
        $builder->setUseOption(true);
        $this->addCodeDefinition($builder->build());

        $builder = new JBBCode\CodeDefinitionBuilder('zip', '<a href="' . $baseUrl . '{option}" target="_blank" class="ziplink resourcelink">{param}</a>');
        $builder->setUseOption(true);
        $this->addCodeDefinition($builder->build());

        $builder = new JBBCode\CodeDefinitionBuilder('pdf', '<a href="' . $baseUrl . '{option}" target="_blank" class="pdflink resourcelink">{param}</a>');
        $builder->setUseOption(true);
        $this->addCodeDefinition($builder->build());

        $builder = new JBBCode\CodeDefinitionBuilder('quote', '<blockquote><cite class="quoteFrom">{option}</cite>{param}</blockquote>');
        $builder->setUseOption(true);
        $this->addCodeDefinition($builder->build());

        $builder = new JBBCode\CodeDefinitionBuilder('becareful', '<becareful class="alert alert-warning">{param}</becareful>');
        $this->addCodeDefinition($builder->build());

        $builder = new JBBCode\CodeDefinitionBuilder('info', '<info class="alert alert-info">{param}</info>');
        $this->addCodeDefinition($builder->build());

        $builder = new JBBCode\CodeDefinitionBuilder('retourligne', '<br/>');
        $this->addCodeDefinition($builder->build());

        $builder = new JBBCode\CodeDefinitionBuilder('left', '<div align="left">{param}</div>');
        $this->addCodeDefinition($builder->build());

        $builder = new JBBCode\CodeDefinitionBuilder('leftedCode', '<div class="code-left">{param}</div>');
        $this->addCodeDefinition($builder->build());

        $builder = new JBBCode\CodeDefinitionBuilder('center', '<div align="center">{param}</div>');
        $this->addCodeDefinition($builder->build());

        $builder = new JBBCode\CodeDefinitionBuilder('justify', '<div align="justify">{param}</div>');
        $this->addCodeDefinition($builder->build());

        $builder = new JBBCode\CodeDefinitionBuilder('h2', '<h2 class="section">{param}</h2>');
//		$builder->setUseOption(true);
        $this->addCodeDefinition($builder->build());

        $builder = new JBBCode\CodeDefinitionBuilder('h3', '<h3 class="section" >{param}</h3>');
//		$builder->setUseOption(true);
        $this->addCodeDefinition($builder->build());

        $builder = new JBBCode\CodeDefinitionBuilder('h4', '<h4 class="section">{param}</h4>');
//		$builder->setUseOption(true);
        $this->addCodeDefinition($builder->build());

        $builder = new JBBCode\CodeDefinitionBuilder('h5', '<h5 class="section">{param}</h5>');
//		$builder->setUseOption(true);
        $this->addCodeDefinition($builder->build());

        $builder = new JBBCode\CodeDefinitionBuilder('h6', '<h6 class="section">{param}</h6>');
//		$builder->setUseOption(true);
        $this->addCodeDefinition($builder->build());

        $builder = new JBBCode\CodeDefinitionBuilder('b', '<strong>{param}</strong>');
        $this->addCodeDefinition($builder->build());

        $builder = new JBBCode\CodeDefinitionBuilder('i', '<em>{param}</em>');
        $this->addCodeDefinition($builder->build());

        $builder = new JBBCode\CodeDefinitionBuilder('p', '<p>{param}</p>');
//		$builder->setUseOption(true);
        $this->addCodeDefinition($builder->build());

        $builder = new JBBCode\CodeDefinitionBuilder('ol', '<ol>{param}</ol>');
        $this->addCodeDefinition($builder->build());

        $builder = new JBBCode\CodeDefinitionBuilder('list', '<ul>{param}</ul>');
        $this->addCodeDefinition($builder->build());

        $builder = new JBBCode\CodeDefinitionBuilder('list', '<ol>{param}</ol>');
        $builder->setUseOption(true);
        $this->addCodeDefinition($builder->build());

        $builder = new JBBCode\CodeDefinitionBuilder('*', '<li>{param}</li>');
        $this->addCodeDefinition($builder->build());
        $builder = new JBBCode\CodeDefinitionBuilder('li', '<li>{param}</li>');
        $this->addCodeDefinition($builder->build());

        $builder = new JBBCode\CodeDefinitionBuilder('youtube', '<iframe width="560" height="315" src="{param}" frameborder="0" allowfullscreen></iframe>');
        $this->addCodeDefinition($builder->build());

        $builder = new JBBCode\CodeDefinitionBuilder('video', '<iframe id="player" type="text/html" \n\
                width="560" height="315" src="http://www.youtube.com/embed/{param}?enablejsapi=1" \n\
                frameborder="0"></iframe>');
        $this->addCodeDefinition($builder->build());

        $builder = new JBBCode\CodeDefinitionBuilder('a', '<a href="{option}" target="_blank">{param}</a>');
        $builder->setUseOption(true);
        $this->addCodeDefinition($builder->build());


        $builder = new JBBCode\CodeDefinitionBuilder('table', '<table class="table table-striped">{param}</table>');
//		$builder->setUseOption(true);
        $this->addCodeDefinition($builder->build());

        $builder = new JBBCode\CodeDefinitionBuilder('td', '<td>{param}</td>');
        $this->addCodeDefinition($builder->build());

        $builder = new JBBCode\CodeDefinitionBuilder('tr', '<tr>{param}</tr>');
        $this->addCodeDefinition($builder->build());

        $builder = new JBBCode\CodeDefinitionBuilder('br', '<br/>');
        $this->addCodeDefinition($builder->build());

        $code = new JBBCode\codedefinitions\FileCodeDefinition();
        $code->setUseOption(true);
        $this->addCodeDefinition($code);
    }

    public function parse($str)
    {
        $newstr = $this->clean($str);
        $newstr = htmlspecialchars($newstr);
        parent::parse($newstr);
        $treeRoot = &$this->treeRoot;
        $i = 0;

        $chren = $treeRoot->getChildren();
        $children = &$chren;
        foreach ($children as &$child) {
            if ($child instanceof \JBBCode\ElementNode && $child->getTagName() == 'section1') {
                $child->setAttribute('tuto-section-' . $i++);
            }
        }
    }

    public function clean($str)
    {
        $str = str_replace("\t", "    ", $str);
        $regex = "#(.*?)(\r)?\n#";
        $regex2 = "#\[p\](.*?)(\[/?(h1|h2|h3|h4|h5|h6|li|ul|div|pre|code|sectioncode|legend|quote|becareful|info|left|leftedcode|center|justify|section2|section3|p|ol|list|\*|youtube|video|table|td|tr|th)(=.*?)?\])(.*?)\[/p\](\r)?\n#";
        $str = preg_replace($regex, "[p]$1[/p]\r\n", $str . "\r\n");
        $str = preg_replace($regex2, "$1$2$5\r\n", $str);

        $newstr = '';
        $min = 0;
        $max = 0;
        while (($min = strpos($str, '[code', $min)) !== FALSE) {
            $newstr .= substr($str, $max, $min - $max);
            $max = strpos($str, '[/code]', $min);
            if ($max === FALSE) {
                break;
            }
            $newstr .= str_replace('[p]', '', str_replace('[/p]', '', substr($str, $min, $max - $min)));
            $min = $max;
        }
        $newstr .= substr($str, $max, strlen($str) - $max - 1);
        $newstr = str_replace('[p][/p]', '', $newstr);
        return $newstr;
    }

    public function getTreeRoot()
    {
        return $this->treeRoot;
    }

    public function convertToHtml(&$content)
    {
        $this->parse($content);
        $content = $this->getAsHTML();
    }

    public function convertToLatex($str)
    {

        // traitement spécial pour les fichiers
        $newstr = $str;
        $newstr = $this->clean($newstr);

        $CI = & get_instance();
        $CI->load->helper('latex_escape');

        $newstr = latex_special_chars($newstr);

        $baseUrl = base_url();

        $parseFiles = function($matches) {
            $filerealpath = realpath(latex_decode($matches[2]));
            if ($filerealpath) {
                $infos = getimagesize($filerealpath);
                $maxwidth = 380;
                $width = min(array($infos[0], $maxwidth));
                ;
                return '\includegraphics[width=' . $width . 'px]{' . realpath(latex_decode($matches[2])) . '}';
            } else {
                return translate('image non trouvée');
            }
        };

        $map = array(
            '[h2](.*?)[/h2]' => '\section{$1}' . "\n" . '',
            '[h3](.*?)[/h3]' => '\subsection{$1}' . "\n" . '',
            '[h4](.*?)[/h4]' => '\subsubsection{$1}' . "\n" . '',
            '[p](.*?)[/p]' => '\paragraph{}' . "\n" . '$1',
            '[code](.*?)[/code]' => function($matches) {
                return "\begin{lstlisting}\n" . latex_decode($matches[1]) . "\n" . '\end{lstlisting}';
            },
            '[code=(.*?)](.*?)[/code]' => function($matches) {
                return '\lstset{language=' . $matches[1] . '}' . "\n" . '\begin{lstlisting}' . "\n" . latex_decode($matches[2]) . "\n" . '\end{lstlisting}';
            },
            '[list](.*?)[/list]' => '\begin{itemize}' . "\n" . '$1' . "\n" . '\end{itemize}',
            '[list=1](.*?)[/list]' => '\begin{enumerate}' . "\n" . '$1' . "\n" . '\end{enumerate}',
            '[ul](.*?)[/ul]' => '\begin{itemize}' . "\n" . '$1' . "\n" . '\end{itemize}',
            '[ol](.*?)[/ol]' => '\begin{enumerate}' . "\n" . '$1' . "\n" . '\end{enumerate}',
            '[\*](.*?)[/\*]' => '\item $1' . "\n" . '',
            '[li](.*?)[/li]' => '\item $1' . "\n" . '',
            '[sectioncode](.*?)[/sectioncode]' => function($matches) {
                return '\begin{lstlisting}' . "\n" . latex_decode($matches[1]) . "\n" . '\end{lstlisting}';
            },
            '[sectioncode=(.*)](.*?)[/sectioncode]' => function($matches) {
                return '\lstset{language=' . $matches[1] . '}' . "\n" . '\begin{lstlisting}' . "\n" . latex_decode($matches[2]) . "\n" . '\end{lstlisting}';
            },
            '[legend](.*?)[/legend]' => '\paragraph{}' . "\n" . '$1',
            '[quote](.*?)[/quote]' => "``$1''",
            '[becareful](.*?)[/becareful]' => '\paragraph{}' . "\n" . '$1',
            '[info](.*?)[/info]' => '\paragraph{}' . "\n" . '$1',
            '[left](.*?)[/left]' => '\paragraph{}' . "\n" . '$1',
            '[center](.*?)[/center]' => '\paragraph{}' . "\n" . '$1',
            '[leftedcode](.*?)[/leftedcode]' => '\paragraph{}' . "\n" . '$1',
            '[a=(.*?)](.*?)[/a]' => function($matches) {
                return '\href{' . latex_decode($matches[1]) . '}{' . $matches[2] . '}';
            },
            '[url=(.*?)](.*?)[/url]' => function($matches) {
                return '\href{' . latex_decode($matches[1]) . '}{' . $matches[2] . '}';
            },
            '[file=(.*?)](.*?)[/file]' => $parseFiles,
            '[image=(.*?)](.*?)[/image]' => $parseFiles,
            '[video](.*?)[/video]' => function($matches) {
                return '\href{' . latex_decode($matches[1]) . '}';
            },
            '[b](.*?)[/b]' => '\textbf{$1}',
            '[i](.*?)[/i]' => '\textit{$1}',
            '[h5](.*?)[/h5]' => '\paragraph{}' . "\n" . '\textbf{$1}' . "\n",
            '[h6](.*?)[/h6]' => '\paragraph{}' . "\n" . '\textbf{$1}' . "\n",
            '[keynotion](.*?)[/keynotion]' => '\paragraph{}' . "\n" . '\textbf{$1}' . "\n",
            '[warning](.*?)[/warning]' => '\paragraph{}' . "\n" . '\textbf{$1}' . "\n",
            '[table](.*?)[/table]' => function($matches) {
                $content = $matches[1];
                $nbTr = substr_count($content, '[tr]');
                $nbTd = substr_count($content, '[td]');
                $nbCol = $nbTd / $nbTr;
                $colWidth = 380 / $nbCol;
                $latex = '\begin{tabular}{|';
                for ($i = 0; $i < $nbCol; $i++) {
                    $latex .= 'L{' . $colWidth . 'px}|';
                }
                $latex .= "}\hline \n" . $content . "\n" . '\end{tabular}';
                return $latex;
            },
            '[tr](.*?)[/tr]' => '$1' . "\\\\\\\\" . "\hline \n",
            '[/td]( *?)[td]' => ' & ',
            '[td]' => '',
            '[/td]' => '',
        );

        foreach ($map as $regex => $replace) {
            $regex = str_replace(array('[', ']'), array('\[', '\]'), $regex);

            if (is_callable($replace)) {
                $newstr = preg_replace_callback('#' . $regex . '#', $replace, $newstr);
                $newstr = preg_replace_callback('#' . $regex . '#s', $replace, $newstr);
            } else {
                $newstr = preg_replace('#' . $regex . '#', $replace, $newstr);
                $newstr = preg_replace('#' . $regex . '#s', $replace, $newstr);
            }
        }
        return $newstr;
    }

    public function convertToText(&$content)
    {
        $this->parse($content);
        $content = $this->getAsText();
    }

    public function convertToBBCode(&$content)
    {
        $this->parse($content);
        $content = $this->getAsBBCode();
    }

    public function decode($texte)
    {
        $test = 'test';
        //$texte = nl2br($texte);  
        //$texte = str_replace("\r\n", '[br][/br]', $texte);
        //$class = "brush: java; toolbar: false; first-line: 1; class-name: \'my_personnal_code\'";

        $html = array("<strong>", "</strong>", "<b>", "</b>", "<em>", "</em>", "<i>", "</i>", "<u>", "</u>",
            "<br>", "<br />", "\r\n",
            "<p>", "</p>", '<h2 class="section">', "</h2>", '<h1 class="section">', "</h1>",
            "</pre>", '<pre class="brush: java; toolbar: false; first-line: 1; class-name: \'my_personnal_code\'">');
        $bbcode = array("[b]", "[/b]", "[b]", "[/b]", "[i]", "[/i]", "[i]", "[/i]", "[u]", "[/u]",
            "[br][/br]", "[br]", "[br][/br]",
            "[p]", "[/p]", "[section2]", "[/section2]", "[section]", "[/section]",
            "[/code]", "[code=java]");
        $texte = str_replace($html, $bbcode, $texte);

        /* balise sp�ciales avec attribut */
        //$texte = preg_replace('#<a href="(.+)" target="_blank">#i', '[url=$1]', $texte); //oui si un seul lien !!
        //$texte = preg_replace("/<a href(.*?)<\/a>/si", "", $texte);//remplace tous les liens par du vide !
        //$texte = preg_replace('#<a href="(.*?)">#i', '[url=$1]', $texte); //ok
        //$texte = preg_replace('#</a>#i', '[/url]', $texte); //ok
        $texte = preg_replace('#<a href="(.*?)">#i', '[advancedlink=$1]', $texte); //ok
        $texte = preg_replace('#</a>#i', '[/advancedlink]', $texte); //ok		

        /*         * * oui si une seule balise de ce type *** */
        //$texte = preg_replace('#<h1 class="section">(.+)</h1>#i', '[section]$1[/section]', $texte); //Regexp transformant les titre h1 en lien BBCode.
        //$texte = preg_replace('#<h2>(.+)</h2>#i', '[section2]$1[/section2]', $texte); //Regexp transformant les titre h2 en lien BBCode.
        //$texte = preg_replace('#<h1>(.+)</h1>#i', '[section]$1[/section]', $texte); //Regexp transformant les titre h1 en lien BBCode.
        //$texte = preg_replace('#<h2 class="section">(.+)</h2>#i', '[section2]$1[/section2]', $texte); //Regexp transformant les titre h2 en lien BBCode.
        //$texte = preg_replace('#<p>(.+)</p>#i', '[p]$1[/p]', $texte); //Regexp transformant les paragraphes p en lien BBCode.
        //$texte = preg_replace('#<pre class="(.+)">(.+)</pre>#i', '[code]$2[/code]', $texte); //Regexp transformant les titre h2 en lien BBCode.
        //
	 //$class = "brush: java; toolbar: false; first-line: 1; class-name: \'my_personnal_code\'";
        //$texte = preg_replace('#<pre class="'.$class.'">(.+)</pre>#i', '[code=java]$1[/code]', $texte); //Regexp transformant les balises de code en lien BBCode.
        //$texte = preg_replace('#<pre class="(.+)">(.+)</pre>#i', '[code=java]$2[/code]', $texte); //Regexp transformant les balises de code en lien BBCode.
        return $texte;
    }

}

?>
