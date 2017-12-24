<?php

/**
 * Description of Chat
 *
 * @author cevantime
 */
class Chat extends MY_Controller
{

    private $destroySession = false;

    public function __construct()
    {
        $this->load->model('chat/chatmessage');
        $this->load->model('chat/chattoken');

        $this->load->library('session');
        if (!($this->session->userdata('user_id'))) {
            $this->destroySession = true;
            $chattoken = $this->chattoken->checkToken($this->input->get('access_token'));
            if ($chattoken) {
                $this->session->set_userdata('user_id', $chattoken->user_id);
            }
        }

        parent::__construct();

        $this->output->enable_profiler(false);
    }

    public function index()
    {

        $token = $this->chattoken->cleanToken();

        $token = $this->chattoken->getLastToken();

        if (!$token || $token->creation_time < time() - 86400) {
            $token = $this->chattoken->generate();
        }
        
        set_cookie('chat_token', $token->token, 0);
        set_cookie('user_id', user_id(), 0);

        $this->load->view('chat/chat');
    }

    public function friends()
    {
        $this->load->model('memberspace/user');
        $this->json($this->user->get(array('id !=' => user_id()), 'array', array('id', 'email', 'login')));
    }
    
    public function room($roomId = null){
        $this->load->model('chat/chatroom');
        if($roomId) {
            if($this->chatroom->isUserRoom($roomId)){
                $room = $this->chatroom->getWithMessages($roomId);
                $this->json(['room'=>$room]);
            } else {
                $this->json(['errors' => [translate('Impossible d\'accéder à cette conversation')]]);
            }
        } else {
            $rooms = $this->chatroom->getUserRooms();
            if( ! $rooms){
                $rooms = [];
            }
            $this->json(['rooms'=>$rooms]);
        }
    }

    public function invite($userId, $roomId)
    {
        $this->load->model('chat/chatinivitation');

        if ($invitationId = $this->chatinvitation->create($userId, $roomId)) {
            $this->json(['room_id' => $roomId]);
        } else {
            $this->json(['errors' => [translate('Création de l\'inviation impossible')]]);
        }
    }

    public function createRoom($socketioId)
    {
        $this->load->model('chat/chatroom');
        if ($roomId = $this->chatroom->create($socketioId)) {
            $this->json(['room_id' => $roomId]);
        } else {
            $this->json(['errors' => [translate('Création de conversation impossible')]]);
        }
    }

    public function add()
    {
        if (!$this->chatmessage->fromPost()) {
            $this->json(['errors' => $this->chatmessage->getLastErrors()]);
        } else {
            $this->json($this->chatmessage->getLastSavedDatas());
        }
    }

    protected function json($data, $code = 200)
    {

        if (!$data) {
            $data = [];
        }
        $json = $this->output
            ->set_content_type('application/json')
            ->set_status_header($code)
            ->set_output(json_encode($data));

        if ($this->destroySession) {
            $this->loginmanager->disconnect();
        }
    }

}
