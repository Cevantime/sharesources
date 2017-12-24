<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class Chattoken extends DATA_Model
{

    const TABLE_NAME = 'chat_token';

    public function getTableName()
    {
        return self::TABLE_NAME;
    }

    public function cleanToken()
    {
        $this->delete(array(
            'creation_time < ' => time() - 60 * 60 * 24 * 15,
            'user_id' => user_id()
        ));
    }
    
    public function getLastToken() {
        $userId = user_id();
        
        $this->where(array(
            'user_id' => $userId,
        ));
        
        $this->order_by('creation_time DESC');
        
        return $this->getRow();
    }

    public function checkToken($token)
    {

        return $this->getRow(array(
            'token' => $token
        ));
    }

    public function generate()
    {
        $data['token'] = md5(uniqid());
        $data['creation_time'] = time();
        $data['user_id'] = user_id();
        
        $data['id'] = $this->insert($data);
        
        return (object)$data;
    }

}
