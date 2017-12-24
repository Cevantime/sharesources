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
    
    public function friends() {
         $this->load->model('webforceteacher');
         
         $search = $this->input->get('search');
         $this->webforceteacher->where(array($this->webforceteacher->getTableName().'.id !='=>user_id()));
         $friends = $this->webforceteacher->search(null, null,$search, array('login', 'forname', 'name'));
         
         if($friends) {
             $friends = array_map(function($friend) {
                 return array_filter ((array)$friend, function($key){
                     return in_array($key, ['id', 'login','email','avatar','forname','name']);
                 }, ARRAY_FILTER_USE_KEY);
             }, $friends);
         }
         $this->json($friends);
    }

}
