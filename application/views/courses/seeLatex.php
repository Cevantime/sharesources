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