<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class Chatmessage extends DATA_Model
{

    const TABLE_NAME = 'chat_messages';

    public function getTableName()
    {
        return self::TABLE_NAME;
    }

    public function validationRulesForInsert($datas)
    {
        $this->load->model('chat/chatroom');
        $rules = array(
            'content' => array(
                'field' => 'content',
                'label' => translate('Contenu du message'),
                'rules' => 'required'
            ),
            'room_id' => array(
                'field' => 'room_id',
                'label' => 'Id room',
                'rules' => array(
                    'required',
                    'is_natural',
                    array('canBePostedInRoom', array($this->chatroom, 'isUserRoom'))
                ))
        );
        return $rules;
    }

    public function validationRulesForUpdate($datas)
    {

        $model = $this;
        
        $this->load->model('chat/chatroom');

        $rules = array(
            'content' => array(
                'field' => 'content',
                'label' => translate('Contenu du message'),
                'rules' => 'required'
            ),
            'id' => array(
                'field' => 'id',
                'label' => 'Id',
                'rules' => array(
                    'required',
                    'is_natural',
                    array('exists', array($model, 'exists'))
                )),
            'room_id' => array(
                'field' => 'to_id',
                'label' => 'Id destinataire',
                'rules' => array(
                    'required',
                    'is_natural',
                    array('canBePostedInRoom', array($this->chatroom, 'isUserRoom'))
                ))
        );
        return $rules;
    }

    public function beforeInsert(&$data = null)
    {
        if (!$data) {
            $data = $this->toArray();
        }
        
        parent::beforeInsert($data);
        
        $data['from_id'] = user_id();
        $data['creation_time'] = time();
        $data['update_time'] = $data['creation_time'];
    }

    public function beforeUpdate(&$data = null, $where = null)
    {
        if (!$data) {
            $data = $this->toArray();
        }
        
        parent::beforeUpdate($data);
        
        $data['from_id'] = user_id();
        $data['update_time'] = time();
    }

}
