<?php

use mikehaertl\wkhtmlto\Pdf;

if (!defined('BASEPATH'))
	exit('No direct script access allowed');

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of home
 *
 * @author thibault
 */
class CoursesController extends MY_Controller {

	public function __construct() {
		parent::__construct();
		$this->load->model('course');
	}

	public function index() {
		if (teachsession()) {
			redirect('courses/calendar');
		}

		redirect('courses/all');
	}

	public function calendar() {
		if (!teachsession()) {
			redirect('courses/all');
		}
		$this->layout->title(translate('Mon calendrier'));

		$this->layout->view('courses/calendar');
	}

	public function calendarAjax() {

		if (!teachsession() || !$this->input->is_ajax_request()) {
			die();
		}

		$this->load->model('course');

		$yearMonth = $this->input->get('monthyear');

		if (!$yearMonth) {
			$yearMonth = date('Y-n');
		}

		$datetime = DateTime::createFromFormat('Y-n-j G:i:s', $yearMonth . '-1 0:00:00');

		if (!$datetime)
			die();

		$firstOfMonth = $datetime->format('U');

		$firstOfMonthInWeek = $datetime->format('N') - 1;

		$datetime->modify('-' . $firstOfMonthInWeek . ' day');
		$from = $datetime->format('U');

		$datetime->modify('+' . $firstOfMonthInWeek . ' day');
		$datetime->modify('-1 month');

		$prevMonth = $datetime->format('Y-n');

		$datetime->modify('+2 month');

		$nextMonth = $datetime->format('Y-n');

		$datetime->modify('-1 day');

		$lastOfMonth = $datetime->format('U');
		$lastOfMonthInWeek = $datetime->format('N');

		$datetime->modify('+' . (7 - $lastOfMonthInWeek) . ' day');

		$to = $datetime->format('U');
		$days = $this->course->getGroupByDayForSession(teachsession('id'), $from, $to);
		
		$this->load->view('courses/includes/calendar', array(
			'firstOfMonth' => $firstOfMonth,
			'lastOfMonth' => $lastOfMonth,
			'days' => $days,
			'prevMonth' => $prevMonth,
			'nextMonth' => $nextMonth
		));
	}

	public function courseDetails() {
		if (!teachsession()) {
			die(translate("Impossible d'obtenir des informations sur ce cours."));
		}

		$day = $this->input->get('day');

		if (!$day) {
			$day = date('Y-n-j');
		}

		$datetime = DateTime::createFromFormat('Y-n-j G:i:s', $day . ' 0:00:00');

		$from = $datetime->format('U');

		$datetime->modify('+1 day');

		$to = $datetime->format('U');

		$this->load->model('course');

		$courses = $this->course->getCoursesForSessionOnPeriod(teachsession('id'), $from, $to);

		$this->load->view('courses/includes/course-details', array(
			'from' => $from,
			'to' => $to,
			'courses' => $courses
		));
	}

	public function bootstrap($id = null, $model = 'course') {
		$this->layout->title($id ? translate('Éditer un cours') : translate('Ajouter un cours'));
		$datas = $this->doSave($id, $model, 'courses/see/{row:id}');
		$this->layout->view('blog/forms/bo-style', array('blogpost_add_pop' => $datas, 'model_name' => pathinfo($model)['filename'], 'lang' => $this->getLang()));
	}

	private function getLang() {
		$lang = $this->input->post_get('lang');
		if ($lang)
			return $lang;
		$this->load->helper('locale');
		return locale();
	}

	public function doSave($id = null, $model = 'course', $redirect = false) {
		$this->load->model($model);

		$modelName = pathinfo($model)['filename'];

		$modelInst = $this->$modelName;
		$ret = $this->processSave($modelInst, $id, $model, $redirect);
		return $ret;
	}

	private function processSave($modelInst, $id = null, $model = 'course', $redirect = false) {

		$post = $this->input->post();

		if ($id) {
			$pop = $modelInst->getIdWithShares($id, 'array');
		} else {
			$pop = array();
		}
		
		if (!$post || !isset($post['save-' . strtolower(get_class($modelInst))])) {
			return $pop;
		}

		$pop = $post;

		if (!empty($pop['teacher_shares'])) {
			$shareIds = explode(',', $pop['teacher_shares']);
			$this->load->model('webforceteacher');
			$this->webforceteacher->where_in('users.id', $shareIds);
			$teach_shares = $this->webforceteacher->getList();
			$pop['teacher_shares'] = json_encode($teach_shares);
		}

		if (!empty($pop['files'])) {
			$this->load->model('filebrowser/file');
			$this->file->where_in('id', $pop['files']);
			$pop['files'] = $this->file->get();
		}

		$is_update = isset($post['id']);

		if ($is_update && !user_can('update', $model, $post['id'])) {
			add_error(translate('Vous ne pouvez pas modifier ce post.'));
			return $pop;
		} else if (!user_can('add', $model)) {
			add_error(translate('Vous ne pouvez pas ajouter de post.'));
			return $pop;
		}

		$datas = $this->input->post();

		$datas['user_id'] = user_id();

		$new_id = $modelInst->fromDatas($datas);

		if ($new_id === false) {
			add_error($modelInst->getLastErrorsString());
			return $pop;
		}
		if ($is_update) {
			$new_id = $datas['id'];
		}
//		$this->user->allowTo('*', $model, $new_id);

		add_success(translate('Le post a bien été ') . ($is_update ? translate('mis à jour') : translate('ajouté')));

		if ($redirect) {
			$lastRow = $modelInst->getLastSavedDatas();
			$regex = '/\{row:(.+?)\}/';
			if (preg_match_all($regex, $redirect, $matches)) {
				for ($j = 0; $j < count($matches[0]); $j++) {
					$redirect = str_replace($matches[0][$j], $lastRow[$matches[1][$j]], $redirect);
				}
			}
			redirect($redirect);
		}

		return $datas;
	}

	public function see($id) {
		$course = $this->course->getId($id);
		
		if (!$course) {
			add_error(translate('le tutoriel n\'existe pas'));
			redirect('home');
		}

		$this->addSynthaxHighlightning();
		$this->layout->title($course->title);
		$this->layout->css('assets/local/css/tutorials.css');

		$format = $this->input->get('format');
		if(in_array($format, array('pdf', 'latex'))){
			$this->checkIfUserCan('see_pdfs', 'course', $course);
		} else {
			$this->checkIfUserCan('see', 'course', $course);
		}
		if ($format == 'pdf') {
			$this->layout->setLayout('layout/pdf');
			$content = $this->layout->view('courses/seePdf', array('course' => $course), true);

			$format = new Pdf(array(
				'enable-javascript'
			));
			$format->addPage($content);
			if (!$format->send()) {
				echo $format->getError();
			}
		} else if ($format == 'latex') {
			$this->layout->setLayout('layout/latex');

			$this->load->library('MyBBCodeParser', '', 'bbparser');

			$course->latex = $this->bbparser->convertToLatex($course->content_bbcode);
			$latex = $this->layout->view('courses/seeLatex', array('course' => $course), true);
//			echo str_replace("\n", "<br>", $latex);
//			die();
			$filename = md5(time() . uniqid());
			$this->load->helper('latex_escape');
			$tmp = sys_get_temp_dir();
			file_put_contents($tmp . '/' . $filename . '.tex', $latex);
			exec("cd $tmp && pdflatex -synctex=1 -interaction=nonstopmode " . $filename . '.tex', $output);
			// must be executed twice for toc, so don't remove below line !
			exec("cd $tmp && pdflatex -synctex=1 -interaction=nonstopmode " . $filename . '.tex', $output);
//			var_dump($output);
//			foreach($output as $out) {
//				echo $out.'<br/>';
//			}
//			die();
			$pdffile = "$tmp/" . $filename . '.pdf';

			$pdfcontent = file_get_contents($pdffile);
			$files = scandir($tmp . '/');

			foreach ($files as $file) {
				$fullpath = $tmp . '/' . $file;
				if ($file == '.' || $file == '..' || is_dir($fullpath)) {
					continue;
				}
				if (strpos($fullpath, $filename) !== FALSE) {
					unlink($fullpath);
				}
			}
			header('Pragma: public');
			header('Expires: 0');
			header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
			header('Content-Type: application/pdf');
			header('Content-Transfer-Encoding: binary');
			header('Content-Length: ' . strlen($pdfcontent));

			header("Content-Disposition: inline; filename=\"{$course->alias}\"");

			echo $pdfcontent;
			exit();
//			$this->load->helper('download');
//			force_download($course->alias.'.pdf', $pdfcontent);
		} else {
			$this->load->helper('filerender');
			$this->layout->view('courses/see', array('course' => $course));
		}
	}

	public function all() {
		$courses = $this->course->getListWithCategoriesTagsFilesAndShares();
		
		$this->layout->title(translate('Tous les cours'));
		$this->layout->view('courses/all', array('courses' => $courses));
	}
	
	public function allByTag($tag) {
		$this->load->model('tag');
		$this->tag->where('alias', $tag);
		$tag = $this->tag->getRow();
		if(!$tag) {
			show_404();
		}
		$this->load->helper('filerender');
		$this->course->where($this->tag->getTableName().'.id='.$tag->id);
		$courses = $this->course->getListWithCategoriesTagsFilesAndShares();
		$this->layout->title(translate('Tous les cours associés au Tag "'.$tag->label.'"'));
		$this->layout->view('courses/all', array('courses' => $courses));
	}

	public function mines() {
		if (user_is('users')) {
			redirect('courses/all');
		}
		$shares = $this->course->getShares(user_id());
		if ($shares) {
			$this->course->where_in(
					'posts.id', array_map(function($share) {
						return $share->course_id;
					}, $shares)
			);
		}
		$this->course->or_where(array('posts.user_id' => user_id()));
		$this->course->or_where(array('publish' => 1));
		$courses = $this->course->getListWithCategoriesTagsFilesAndShares();
		$this->layout->title(translate('Mes cours'));
		$this->layout->view('courses/all', array('courses' => $courses));
	}

	public function edit($id, $model = 'course') {
		$this->checkIfUserCan('update', 'course', $id);
		$datas = $this->doSave($id, $model, 'courses/see/{row:id}');
		$this->layout->title(translate('Edition du cours : ' . $datas['title']));
		$this->layout->view('blog/forms/bo-style', array('blogpost_add_pop' => $datas, 'model_name' => pathinfo($model)['filename'], 'lang' => $this->getLang()));
	}

	public function delete($id = null) {
		$this->checkIfUserCan('delete', 'course', $id);
		$this->load->model('course');
		$course = $this->course->getId($id);
		add_success(translate('Le cours ').$course->title.translate(' a bien été supprimé'));
		$this->course->deleteId($id);
		if ($this->input->get('redirect')) {
			redirect($this->input->get('redirect'));
		}
		redirect('home');
	}

	public function requestShare($courseId) {

		$this->processShare($courseId);
	}

	public function requestUnshare($courseId) {
		$this->processShare($courseId, false);
	}
	
	private function processShare($courseId, $share = true) {
		if( ! $this->input->is_ajax_request()
			|| ! user_can('grab', 'teachsession', $courseId)) {
			show_404();
			return;
		}
		
		$this->load->model('teachsession');
		
		$course = $this->course->getId($courseId);
		
		if(! $course) {
			$status = 'failed';
			$html = translate('Erreur');
		} else {
			if($share) {
				$this->course->shareWithSession(teachsession('id'), $courseId);
			} else {
				$this->course->unshareWithSession(teachsession('id'), $courseId);
			}
			$status = 'ok';
			$html = $this->load->view('courses/includes/course-actions',
				array(
					'course' => $course,
					'teachsession' => teachsession()
				),
				true
			);
			
		}
		
		
		die(json_encode(array('status'=>$status, 'html'=> $html)));
	}

	public function changeDateShare() {

		if (!$this->input->is_ajax_request() || !teachsession() || !user_can('share_to_teachsession', 'course', $this->input->post('course-id'))) {
			show_404();
			return;
		}

		$date = $this->input->post('date');
		$courseId = $this->input->post('course-id');

		$datetime = DateTime::createFromFormat('d/m/Y', $date);
		$date = $datetime->format('U');

		$this->load->model('sharecourseteachsession');
		$share = $this->sharecourseteachsession->getRow(array('course_id' => $courseId, 'teach_session_id' => teachsession('id')));
		$this->sharecourseteachsession->update(
				array('date' => $date), array('course_id' => $share->course_id, 'teach_session_id' => teachsession('id')
		));
	}
	
	public function sendMsg($idCourse = null) {
		if( ! user_can('see','course', $idCourse)) {
			add_error(translate('Vous ne pouvez pas envoyer de message à l\'auteur de ce cours'));
		} else if(!empty($_POST)) {
			$this->load->model('message');
			if($this->message->fromPost()) {
				add_success(translate('Votre message a bien été ajouté'));
			} else {
				add_error($this->message->getLastErrorsString());
			}
			
		} else {
			add_error('Requête invalide');
		}
		if($idCourse) {
			redirect('courses/see/'.$idCourse);
		} else {
			redirect('home');
		}
	}

}
