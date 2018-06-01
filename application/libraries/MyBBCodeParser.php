<?php

/**
 * Description of BBCodeParser
 *
 * @author thibault
 */
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

require_once APPPATH . 'modules/wysibb/libraries/BBCodeParser.php';

class MyBBCodeParser extends BBCodeParser
{

    private $structureTags = ['h2', 'h3', 'h4', 'h5', 'h6'];
    private $numbers = [];

    public function __construct()
    {
        parent::__construct();

        $baseUrl = base_url();

        $builder = new JBBCode\CodeDefinitionBuilder('keynotion', '<div class="info info-keynotion"><i class="fa fa-key main"></i>{param}</div>');
        $this->addCodeDefinition($builder->build());

        $builder = new JBBCode\CodeDefinitionBuilder('warning', '<div class="info info-warning"><i class="fa fa-exclamation-triangle main"></i>{param}</div>');
        $this->addCodeDefinition($builder->build());

        $builder = new JBBCode\CodeDefinitionBuilder('course', '<a href="' . $baseUrl . 'courses/see/{option}">{param}</a>');
        $builder->setUseOption(true);
        $this->addCodeDefinition($builder->build());

        $builder = new JBBCode\CodeDefinitionBuilder('imageLeft', '<img class="left-image" src="' . $baseUrl . '{option}" alt="{param}"/>');
        $builder->setUseOption(true);
        $this->addCodeDefinition($builder->build());

        $builder = new JBBCode\CodeDefinitionBuilder('imageRight', '<img class="right-image" src="' . $baseUrl . '{option}" alt="{param}"/>');
        $builder->setUseOption(true);
        $this->addCodeDefinition($builder->build());

        $builder = new JBBCode\CodeDefinitionBuilder('inlineCode', '<code class="{option} inline">{param}</code>');
        $builder->setUseOption(true);
        $this->addCodeDefinition($builder->build());

        $builder = new JBBCode\CodeDefinitionBuilder('p', '<p id="{option}">{param}</p>');
        $builder->setUseOption(true);
        $this->addCodeDefinition($builder->build());
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
            $filerealpath = realpath(latex_decode($matches[1]));
            if ($filerealpath) {
                $infos = getimagesize($filerealpath);
                $maxwidth = 380;
                $width = min(array($infos[0], $maxwidth));
                ;
                return '\includegraphics[width=' . $width . 'px]{' . realpath(latex_decode($matches[1])) . '}';
            } else {
                return translate('image non trouvée');
            }
        };

        $map = array(
            '[br][/br]' => "\n",
            '[h2](.*?)[/h2]' => '\section{$1}' . "\n",
            '[h3](.*?)[/h3]' => '\subsection{$1}' . "\n",
            '[h4](.*?)[/h4]' => '\subsubsection{$1}' . "\n",
            '[p](.*?)[/p]' => '\paragraph{}' . "\n" . '$1',
            '[code](.*?)[/code]' => function($matches) {
                return "\begin{lstlisting}\n" . latex_decode($matches[1]) . "\n" . '\end{lstlisting}';
            },
            '[code=(.*?)](.*?)[/code]' => function($matches) {
                return '\lstset{language=' . $matches[1] . '}' . "\n" . '\begin{lstlisting}' . "\n" . latex_decode($matches[2]) . "\n" . '\end{lstlisting}';
            },
            '[inlineCode](.*?)[/inlineCode]' => function($matches) {
                return "\begin{lstlisting}\n" . latex_decode($matches[1]) . "\n" . '\end{lstlisting}';
            },
            '[inlineCode=(.*?)](.*?)[/inlineCode]' => function($matches) {
                return '\lstset{language=' . $matches[1] . '}' . "\n" . '\begin{lstlisting}' . "\n" . latex_decode($matches[2]) . "\n" . '\end{lstlisting}';
            },
            '[list](.*?)[/list]' => '\begin{itemize}' . "\n" . '$1' . "\n" . '\end{itemize}',
            '[list=1](.*?)[/list]' => '\begin{enumerate}' . "\n" . '$1' . "\n" . '\end{enumerate}',
            '[ul](.*?)[/ul]' => '\begin{itemize}' . "\n" . '$1' . "\n" . '\end{itemize}',
            '[ol](.*?)[/ol]' => '\begin{enumerate}' . "\n" . '$1' . "\n" . '\end{enumerate}',
            '[\*](.*?)[/\*]' => '\item $1' . "\n",
            '[li](.*?)[/li]' => '\item $1' . "\n",
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
            '[course=(.*?)](.*?)[/course]' => function($matches) use ($baseUrl) {
                return '\href{' . latex_decode($baseUrl . 'courses/see/' . $matches[1]) . '?format=latex}{' . $matches[2] . '}';
            },
            '[file=(.*?)](.*?)[/file]' => $parseFiles,
            '[image=(.*?)](.*?)[/image]' => $parseFiles,
            '[imageLeft=(.*?)](.*?)[/imageLeft]' => $parseFiles,
            '[imageRight=(.*?)](.*?)[/imageRight]' => $parseFiles,
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

    public function clean($str)
    {

        $bbcode = str_replace("\t", "    ", $str);

        $out = '';

        $paragraphing = false;

        $nodeStack = [];

        $brTagList = [
            'li', 'div', 'legend', 'quote', 'becareful', 'info', 'left', 'center', 'justify', 'p', '*', 'td', 'th'
        ];

        $strictTagList = [
            'h1', 'h2', 'h3', 'h4', 'h5', 'h6', 'ul', 'section2', 'section3', 'ol', 'list', 'youtube', 'video', 'table', 'tr', 'img'
        ];

        $respectTag = [
            'pre', 'code', 'sectioncode', 'leftedcode',
        ];

        $tagList = array_merge($brTagList, $strictTagList, $respectTag);

        usort($tagList, function($t1, $t2) {
            return strlen($t1) < strlen($t2);
        });

        $bblen = strlen($bbcode);

        $substring = $bbcode;

        while ($substring) {
            $matching = $this->isMatchingTag($substring, $tagList);
            $c = $substring[0];
            if ($matching) {
                if ($paragraphing) {
                    $out .= "[/p]\n";
                }
                list($opening, $tag, $endPos) = $matching;
                if ($opening) {
                    $nodeStack[] = $tag;
                    $out .= "\n" . substr($substring, 0, $endPos + 1);
                } else {
                    array_pop($nodeStack);
                    $out .= substr($substring, 0, $endPos + 1) . "\n";
                }
                $substring = substr($substring, $endPos + 1);
                $paragraphing = false;
            } else if (!$paragraphing && empty($nodeStack)) {
                $out .= '[p]';
                $paragraphing = true;
            } else if ($paragraphing && ($c === "\r" || $c === "\n")) {
                $out .= "[/p]\n[p]";
                $substring = substr($substring, 1);
            } else if (($c === "\r" || $c === "\n")) {
                $tag = end($nodeStack);
                if (array_search($tag, $brTagList) !== FALSE) {
                    $out .= "[br][/br]\n";
                } else if (array_search($tag, $respectTag)) {
                    $out .= $c;
                } else {
                    $out .= ' ';
                }
                $substring = substr($substring, 1);
            } else {
                if ($c !== PHP_EOL)
                    $out .= $c;
                $substring = substr($substring, 1);
            }
        }

        if ($paragraphing) {
            $out .= '[/p]';
        }

        $out = trim(str_replace("[p][/p]", PHP_EOL, $out));

        $out = preg_replace("#(" . PHP_EOL . ")+#", "\n", $out);
        $out = preg_replace("#(\[br\]\[\/br\]" . PHP_EOL . ")+#s", "[br][/br]\n", $out);

        return $out;
    }

    public function getAsHTML()
    {
        $html = parent::getAsHTML();

        // add ids to direct childs

        $dom = new DOMDocument();

        $dom->loadHTML('<!doctype html>
<html>
<head>
    <meta charset="utf-8">
</head>' . $html . '</body>
</html>');

        $body = $dom->getElementsByTagName('body')[0];

        $i = 0;

        $this->numbers = array_fill(0, count($this->structureTags) + 1, 0);

        foreach ($body->childNodes as $child) {
            if ($child instanceof DOMElement) {
                $this->visitElementNode($child);
            }
        }

        $html = $dom->saveHTML($body);
        $html = substr($html, 6, strlen($html) - 13);

        return $html;
    }

    public function visitElementNode(DOMElement $elementNode)
    {
        $tagName = $elementNode->tagName;
        $structureTagPos = array_search($tagName, $this->structureTags);

        $this->incSectionNumbers($this->numbers, $structureTagPos !== FALSE ? $structureTagPos : count($this->structureTags));
        
        $elementNode->setAttribute('id', $this->buildSectionName($this->numbers));

//        foreach ($elementNode->childNodes as $child) {
//            if ($child instanceof DOMElement) {
//                $this->visitElementNode($child);
//            }
//        }
    }

    private function incSectionNumbers(&$numbers, $index)
    {
        $numbers[$index] ++;
        for ($i = $index + 1; $i < count($numbers); $i++) {
            $numbers[$i] = 0;
        }
    }

    private function buildSectionName($numbers)
    {
        $linkRadical = 'link';
        foreach ($numbers as $number) {
            $linkRadical .= '-' . $number;
        }
        return $linkRadical;
    }

    public function isMatchingTag($str, &$tagList)
    {

        foreach ($tagList as $tag) {
            if ('[' . $tag === substr($str, 0, strlen($tag) + 1)) {
                return [true, $tag, strpos($str, ']')];
            } else if ('[/' . $tag . ']' === substr($str, 0, strlen($tag) + 3)) {
                return [false, $tag, strpos($str, ']')];
            }
        }

        return false;
    }

}

?>
