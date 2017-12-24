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

    public function create($socketioId)
    {
        $userId = user_id();

        $room = $this->getRow([
            'socketio_id' => $socketioId
        ]);

        if ($room && $room->author_id != $userId) {
            return false;
        }

        if (!$room) {
            return $this->insert([
                    'socketio_id' => $socketioId,
                    'author_id' => $userId
            ]);
        }

        return $room->id;
    }

    public function getWithMessages($roomId = null, $limitMessage = null, $offsetMessage = null,$typeMessage= 'object', $columnsMessage = null)
    {
        if( ! $roomId) {
            $roomId = $this->getData($roomId);
        }
        
        $room = $this->getId($roomId);
        
        if(!$room) {
            return false;
        }
        
        $this->load->model('chat/chatmessage');
        
        $this->where('room_id', $roomId);
        
        $messages = $this->chatmessage->getList($limitMessage, $offsetMessage, $typeMessage, $columnsMessage);
        
        $room->messages = $messages;
        
        return $room;
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
        
        if ($room->author_id == $userId) {
            return true;
        }

        $this->load->model('chat/chatroominvitation');

        $invitation = $this->chatroominvitation->getRow(['room_id' => $room->id, 'user_id' => $userId]);

        if ($invitation) {
            return true;
        }

        return false;
    }
    
    public function getUserRooms($userId = null) {
        if(!$userId) {
            $userId = user_id();
        }
        
        $this->load->model('chat/chatroominvitation');
        
        $inviteTable = Chatroominvitation::TABLE_NAME;
        
        $this->join($inviteTable, "$inviteTable.room_id=".self::TABLE_NAME.'.id', 'left');
        $this->where('user_id = ', $userId);
        $this->or_where('author_id = ', $userId);
        
        return $this->get();
    }

}
