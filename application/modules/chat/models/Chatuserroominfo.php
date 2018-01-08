<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class Chatuserroominfo extends DATA_Model
{

    const TABLE_NAME = 'chat_users_rooms_infos';

    public function getTableName()
    {
        return self::TABLE_NAME;
    }

    public function create($userId, $roomId, $messageId = null, $attached = null)
    {
        if( is_null($messageId) && is_null($attached) ){
            return 0;
        }
        
        $view = $this->getRow([
            'user_id' => $userId,
            'room_id' => $roomId
        ]);
        if (!$view) {
            $data = [
                'user_id' => $userId,
                'room_id' => $roomId,
            ];
            if (isset($attached)) {
                $data['is_room_attached'] = intval($attached);
            }
            if (isset($messageId)) {
                $data['last_seen_message_id'] = $messageId;
            }
            return $this->insert($data);
        } else {
            $data = [];
            if (isset($messageId)) {
                $data['last_seen_message_id'] = $messageId;
            }
            if (isset($attached)) {
                $data['is_room_attached'] = intval($attached);
            }
            return $this->update($data, [
                'user_id' => $userId,
                'room_id' => $roomId
            ]);
        }
    }

}
