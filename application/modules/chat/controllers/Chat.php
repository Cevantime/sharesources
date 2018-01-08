<?php

/**
 * Description of Chat
 *
 * @author cevantime
 */
class Chat extends MY_Controller
{

    private $destroySession = false;
    protected $userColumns = ['id', 'email', 'login'];

    public function __construct()
    {
        $this->load->library('session');
        if (!($this->session->userdata('user_id'))) {
            $this->destroySession = true;
            $chattoken = $this->getTokenModel()->checkToken($this->input->get('access_token'));
            if ($chattoken) {
                $this->session->set_userdata('user_id', $chattoken->user_id);
            }
        }

        parent::__construct();

        $this->output->enable_profiler(false);
    }

    public function index()
    {

        $token = $this->getTokenModel()->cleanToken();

        $token = $this->getTokenModel()->getLastToken();

        if (!$token || $token->creation_time < time() - 86400) {
            $token = $this->getTokenModel()->generate();
        }

        set_cookie('chat_token', $token->token, 0);
        set_cookie('user_id', user_id(), 0);

        $this->load->view('chat/chat');
    }

    public function friends()
    {
        $search = $this->input->get('search');
        $userTableName = $this->getUserModel()->getTableName();
        $this->getUserModel()->where($userTableName . '.id != ', user_id());
        if ($search) {
            $users = $this->getUserModel()->search(null, null, $search, array('login', 'email'));
        } else {
            $users = $this->getUserModel()->get();
        }
        $this->filterUsers($users);
        $this->json($users);
    }

    public function room($roomId = null)
    {
        if ($roomId) {
            if ($this->getRoomModel()->isUserRoom($roomId)) {
                $room = $this->getRoomModel()->getWithMessagesAndUsers($this->getUserModel(), $roomId, 0, 30);
                $this->filterUsers($room->users);
                $this->json(['room' => $room]);
            } else {
                $this->json(['errors' => [translate('Impossible d\'accéder à cette conversation')]]);
            }
        } else {
            $rooms = $this->getRoomModel()->getUserRooms();
            if (!$rooms) {
                $rooms = [];
            }
            $this->json(['rooms' => $rooms]);
        }
    }

    public function attachRoom($roomId)
    {
        $this->load->model('chat/chatroom');
        $this->chatroom->userAttach($roomId);
        $this->json(['message' => 'success']);
    }
    
    public function detachRoom($roomId)
    {
        $this->load->model('chat/chatroom');
        $this->chatroom->userAttach($roomId, false);
        $this->json(['message' => 'success']);
    }
    
    public function seeRoom($roomId)
    {
        $this->load->model('chat/chatroom');
        $this->chatroom->userSee($roomId);
        $this->json(['message' => 'success']);
    }

    public function invite($userId, $roomId)
    {
        if ($invitationId = $this->getRoominvitationModel()->create($userId, $roomId)) {
            $this->json(['room_id' => $roomId]);
        } else {
            $this->json(['errors' => [translate('Création de l\'invitation impossible')]]);
        }
    }

    public function user()
    {
        $user = $this->getUserModel()->getId(user_id());
        $this->filterUser($user);
        $rooms = $this->getRoomModel()->getUserRooms();
        $this->json([
            'user' => $user,
            'rooms' => $rooms
        ]);
    }

    public function requestRoom($toId = null)
    {
        $this->load->model('chat/chatroom');

        if ($roomId = $this->getRoomModel()->create($toId)) {
            $room = $this->getRoomModel()->getWithMessagesAndUsers($this->getUserModel(), $roomId);
            $this->filterUsers($room->users);
            $this->json($room);
        } else {
            $this->json(['errors' => [translate('Création de conversation impossible')]]);
        }
    }

    public function push()
    {
        if (!$this->getMessageModel()->fromPost()) {
            $this->json(['errors' => $this->getMessageModel()->getLastErrors()]);
        } else {
            $this->json($this->getMessageModel()->getLastSavedDatas(), 201);
        }
    }

    public function delete($messageId)
    {
        $message = $this->getMessageModel()->getId($messageId);
        if($message && $message->from_id === user_id()) {
            $this->getMessageModel()->deleteId($messageId);
            $this->json([
                'message' => 'success'
            ]);
        } else {
            $this->json([
                'errors' => [translate('Suppression du message impossible')]
            ], 404);
        }
    }
    
    public function deleteRoom($roomId)
    {
        $room = $this->getRoomModel()->getId($roomId);
        if($room && $room->author_id === user_id()) {
            $this->getRoomModel()->deleteId($roomId);
            $this->json([
                'message' => 'success'
            ]);
        } else {
            $this->json([
                'errors' => [translate('Suppression de la conversation impossible')]
            ], 404);
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

    protected function filterUser($user)
    {
        unset($user->password);
        unset($user->confirmed);
    }

    protected function filterUsers($users)
    {
        if ($users) {
            $self = $this;
            return array_map(function($user) use ($self) {
                $self->filterUser($user);
            }, $users);
        }
        return $users;
    }

    protected function getRoomModel()
    {
        $this->load->model('chat/chatroom');
        return $this->chatroom;
    }

    protected function getMessageModel()
    {
        $this->load->model('chat/chatmessage');
        return $this->chatmessage;
    }

    protected function getRoomInvitationModel()
    {
        $this->load->model('chat/chatroominvitation');
        return $this->chatroominvitation;
    }

    protected function getTokenModel()
    {
        $this->load->model('chat/chattoken');
        return $this->chattoken;
    }

    protected function getUserRoomInfoModel()
    {
        $this->load->model('chat/chatuserroominfo');
        return $this->user;
    }
    
    protected function getUserModel()
    {
        $this->load->model('memberspace/user');
        return $this->user;
    }
    

}
