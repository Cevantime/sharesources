<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

require_once APPPATH . '/modules/chat/controllers/Chat.php';

/**
 * Description of ChatController
 *
 * @author cevantime
 */
class ChatController extends Chat
{

    public function __construct()
    {
        parent::__construct();
        if (!user_is('teacher')) {
            show_404();
        }
    }

    protected function getUserModel()
    {
        $this->load->model('webforceteacher');
        return $this->webforceteacher;
    }

    protected function filterUser($user)
    {
        parent::filterUser($user);
        unset($user->reset);
        unset($user->matching);
        unset($user->current_teachsession);
        unset($user->preferences);
        unset($user->matchTmp);
    }

}
