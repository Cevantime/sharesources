\documentclass[hidelinks,12pt]{article}

\usepackage[french]{babel}
\usepackage[utf8]{inputenc}  
\usepackage[T1]{fontenc}
\usepackage{lmodern}
\usepackage{amsmath}
\usepackage{listings}
\usepackage{color}
\usepackage{xcolor}
\usepackage{textcomp}
\usepackage{url}

\usepackage{graphicx}
\usepackage{array}

<?php $this->layout->block('pdfmetas'); ?>\usepackage{hyperref}<?php echo $this->layout->block(); ?>

\def\arraystretch{1.5}

\newcolumntype{L}[1]{>{\raggedright\let\newline\\\arraybackslash\hspace{0pt}}m{#1}}

\definecolor{mygreen}{rgb}{0,0.6,0}
\definecolor{mygray}{rgb}{0.5,0.5,0.5}
\definecolor{mymauve}{rgb}{0.58,0,0.82}

\hypersetup{
    colorlinks,
    linkcolor={red!50!black},
    citecolor={blue!50!black},
    urlcolor={blue!80!black}
}

\lstset{
  backgroundcolor=\color{white},   
  breakatwhitespace=false,         
  breaklines=true,                 
  captionpos=b,                    
  commentstyle=\color{mygreen},    
  deletekeywords={...},            
  escapeinside={\%*}{*)},          
  extendedchars=true,              
  frame=none,	                   
  keepspaces=true,                 
  basicstyle=\small\ttfamily,		
  keywordstyle=\color{blue},       
  language=PHP,                 
  otherkeywords={},           
  numbers=left,                    
  aboveskip=2em,
  upquote=true,
  numbersep=10pt,                   
  numberstyle=\color{mygray}, 
  rulecolor=\color{black},         
  showspaces=false,                
  showstringspaces=false,          
  showtabs=false,                  
  stepnumber=1,                    
  stringstyle=\color{mymauve},     
  tabsize=4,
   literate=%
         {á}{{\'a}}1
         {à}{{\`a}}1
         {â}{{\^a}}1
         {í}{{\'i}}1
         {í}{{\^i}}1
         {é}{{\'e}}1
         {è}{{\`e}}1
         {ê}{{\^e}}1
         {ç}{{\cc}}1
         {ý}{{\'y}}1
         {ú}{{\'u}}1
         {û}{{\^u}}1
         {ú}{{\'u}}1
         {ó}{{\'o}}1
         {ô}{{\^o}}1
         {ù}{{\`u}}1
         {ě}{{\v{e}}}1
         {š}{{\v{s}}}1
         {č}{{\v{c}}}1
         {ř}{{\v{r}}}1
         {ž}{{\v{z}}}1
         {ď}{{\v{d}}}1
         {ť}{{\v{t}}}1
         {ň}{{\v{n}}}1                
         {ů}{{\r{u}}}1
         {Á}{{\'A}}1
         {Â}{{\^A}}1
         {À}{{\`A}}1
         {Á}{{\'A}}1
         {Ç}{{\cC}}1
         {Í}{{\'I}}1
         {É}{{\'E}}1
         {Ý}{{\'Y}}1
         {Ú}{{\'U}}1
         {Ó}{{\'O}}1
         {Ě}{{\v{E}}}1
         {Š}{{\v{S}}}1
         {Č}{{\v{C}}}1
         {Ř}{{\v{R}}}1
         {Ž}{{\v{Z}}}1
         {Ď}{{\v{D}}}1
         {Ť}{{\v{T}}}1
         {Ň}{{\v{N}}}1                
         {Ů}{{\r{U}}}1,
  title=\lstname                   
}

\lstdefinestyle{java}{
  stringstyle=\color{magenta}
}

\lstdefinestyle{php}{
  stringstyle=\color{mymauve}
}


<?php echo $content_for_layout; ?>

\end{document}
