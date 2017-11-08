<?php $this->layout->block('pdfmetas'); ?>
\usepackage[pdftex,
    pdfauthor={<?php echo $course->author_forname.' '.$course->author_name ?>},
    pdftitle={<?php echo $course->title ?>},
    pdfsubject={<?php echo trim(strip_tags($course->description)) ?>},
    pdfkeywords={<?php echo $course->keywords ?>}]{hyperref}
<?php $this->layout->block(); ?>
    
\title{<?php echo $course->title ?>}
\date{<?php echo date('d/m/Y', $course->update_time) ?>}

\begin{document}

\pagenumbering{gobble}

\maketitle

\newpage

\tableofcontents

\newpage

\pagenumbering{arabic}

<?php echo $course->latex; ?>