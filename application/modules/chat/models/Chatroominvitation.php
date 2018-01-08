<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class Chatroominvitation extends DATA_Model
{

    const TABLE_NAME = 'chat_room_invitations';

    public function getTableName()
    {
        return self::TABLE_NAME;
    }

    public function create($userId, $roomId)
    {
        $invitation = $this->getRow([
            'user_id' => $userId,
            'room_id' => $roomId
        ]);

        if (!$invitation) {
            $this->insert([
                'user_id' => $userId,
                'room_id' => $roomId
            ]);
            
            return 1;
        }

        return 1;
    }

}
