<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class Chatroom extends DATA_Model
{

    const TABLE_NAME = 'chat_rooms';

    public function getTableName()
    {
        return self::TABLE_NAME;
    }

    public function create($toId = null)
    {
        $userId = user_id();
        $room = null;
        
        if ($toId) {
            $room = $this->getRow([
                'to_id' => $toId,
                'author_id' => $userId
            ]);
            if (!$room) {
                $room = $this->getRow([
                    'to_id' => $userId,
                    'author_id' => $toId
                ]);
            }
        }


        if (!$room) {
            return $this->insert([
                    'to_id' => $toId,
                    'author_id' => $userId
            ]);
        }

        return $room->id;
    }

    public function getWithMessagesAndUsers($userModel, $roomId = null, $limitMessage = null, $offsetMessage = null, $typeMessage = 'object', $columnsMessage = null)
    {
        if (!$roomId) {
            $roomId = $this->getData($roomId);
        }

        $room = $this->getId($roomId);

        if (!$room) {
            return false;
        }

        $this->load->model('chat/chatmessage');

        $this->chatmessage->where('room_id', $roomId);
        $this->chatmessage->order_by('creation_time DESC');

        $messages = $this->chatmessage->getList($limitMessage, $offsetMessage, $typeMessage, $columnsMessage);
        $room->messages = $messages ? $messages : [];

        $userTable = $userModel->getTableName();

        $this->load->model('chat/chatroominvitation');

        $userModel->join(Chatroominvitation::TABLE_NAME, Chatroominvitation::TABLE_NAME . '.user_id=' . User::$TABLE_NAME . '.id', 'left');
        $this->where('room_id', $room->id);
        $userModel->or_group_start();
            $userModel->or_where(User::$TABLE_NAME . '.id', $room->author_id);
            $userModel->or_where(User::$TABLE_NAME . '.id', $room->to_id);
        $userModel->group_end();
        
        $userModel->group_by('users.id');

        $users = $userModel->get();

        $room->users = $users ? $users : [];

        return $room;
    }

    public function filterUsers($users)
    {
        return array_map(function($friend) {
            return array_filter((array) $friend, function($key) {
                return in_array($key, ['id', 'login', 'email']);
            }, ARRAY_FILTER_USE_KEY);
        }, $users);
    }

    public function isUserRoom($room)
    {
        $userId = user_id();

        $this->load->model('chat/chatroom');

        if (ctype_digit($room)) {
            $room = $this->chatroom->getId($room);
        }

        if (!$room) {
            return false;
        }

        if ($room->author_id == $userId || $room->to_id == $userId) {
            return true;
        }

        $this->load->model('chat/chatroominvitation');

        $invitation = $this->chatroominvitation->getRow(['room_id' => $room->id, 'user_id' => $userId]);

        if ($invitation) {
            return true;
        }

        return false;
    }

    public function getUserRooms($userId = null)
    {
        if (!$userId) {
            $userId = user_id();
        }

        $this->load->model('chat/chatroominvitation');
        $this->load->model('chat/chatmessage');
        $this->load->model('chat/chatuserroominfo');

        $inviteTable = Chatroominvitation::TABLE_NAME;

        $cusmT = Chatuserroominfo::TABLE_NAME;
        $cT = Chatmessage::TABLE_NAME;
        $cRT = self::TABLE_NAME;
        
        $this->join($inviteTable, "$inviteTable.room_id=" . $cRT . '.id', 'left');
        $this->join($cT, "$cT.room_id=" . $cRT . '.id', 'left');
        $this->join($cusmT, $cusmT.'.room_id = '.$cRT.'.id AND '.$cusmT.'.user_id = '.$userId,'left');
        
        $this->group_start();
            $this->where($inviteTable.'.user_id = ', $userId);
            $this->or_where('author_id = ', $userId);
            $this->or_where('to_id = ', $userId);
        $this->group_end();
        
        $this->group_start();
            $this->chatmessage->where($cT.'.id >= '.$cusmT.'.last_seen_message_id');
            $this->chatmessage->or_where($cusmT.'.last_seen_message_id IS NULL');
        $this->group_end();
        
        $this->select('count(distinct '.$cT.'.id ) - 1 as unseenMessages, is_room_attached');
        $this->group_by($cRT.'.id');
        
        return $this->get();
    }

    public function userSee($roomId)
    {
        $this->load->model('chat/chatuserroominfo');
        $this->load->model('chat/chatmessage');
        
        $this->chatmessage->where('room_id = ', $roomId);
        $this->chatmessage->order_by('creation_time DESC');
        
        $lastMessage = $this->chatmessage->getRow();
        
        if($lastMessage) {
            $this->chatuserroominfo->create(user_id(), $roomId, $lastMessage->id);
        }    
        
    }
    
    public function userAttach($roomId, $attach = true)
    {
        $this->load->model('chat/chatuserroominfo');
        
        $this->chatuserroominfo->create(user_id(), $roomId, null, $attach);

    }

}
