<?php

if (!defined('BASEPATH'))
	exit('No direct script access allowed');

class NotificationsController extends MY_Controller {

	public function __construct() {
		parent::__construct();
		$this->load->model('notification');
	}

	public function index() {
		$notifications = $this->notification->getUnreadNotifications();
		$this->load->view('notifications/index', array('notifications' => $notifications));
	}

	public function see($id) {
		$this->checkIfUserCan('see', 'notification', $id);
		$notif = $this->notification->getId($id);
		$redirect = $this->notification->getLink($notif);
		$this->notification->markAsSeen($notif->id);
		redirect($redirect);
	}

	public function markAsSeen($id) {
		$this->checkIfUserCan('see', 'notification', $id);
		$notif = $this->notification->getId($id);
		if (!$notif) {
			die(json_encode(array('status' => 'ko')));
		} else {
			$this->notification->markAsSeen($notif->id);
			die(json_encode(array('status' => 'ok')));
		}
	}

	public function all() {
		$this->checkIfUserCan('see', 'notification', '*');
		$this->notification->where('type', Notification::TYPE_CUSTOM);
		$notifs = $this->notification->getList();

		$this->layout->view('notifications/all', array('notifications' => $notifs));
	}

	public function save($id = null) {
		if (isset($_POST['id']) && !user_can('edit', 'notification', $_POST['id'])) {
			add_error(translate('Vous ne pouvez pas modifier cette notification'));
			redirect('home');
		} else if (!user_can('add', 'notification')) {
			add_error(translate('Vous ne pouvez pas ajouter de notification'));
			redirect('home');
		} 
		if ($id) {
			$pop = array_merge($this->notification->getId($id, 'array'), $_POST);
		} else {
			$pop = $_POST;
		}

		if (!isset($pop['message']) && isset($pop['infos'])) {
			$infos = json_decode($pop['infos']);
			if ($infos->text) {
				$pop['message'] = $infos->text;
			}
		}
		if (!isset($pop['url']) && isset($pop['infos'])) {
			$infos = json_decode($pop['infos']);
			if ($infos->text) {
				$pop['url'] = $infos->url;
			}
		}
		if ($_POST) {

			if ($this->notification->fromPost()) {
				add_success('Le notification a bien été ajoutée');
				redirect('admin/notifications');
			} else {
				add_error($this->notification->getLastErrorsString());
			}
		}

		$this->layout->view('notifications/save', array('pop' => $pop));
	}

	public function delete($id) {
		if (user_can('delete', 'notification', $id)) {
			$this->notification->deleteId($id);
		} else {
			add_error(translate('Vous ne pouvez pas détruire cette notification'));
		}
		redirect('admin/notifications');
	}

}
