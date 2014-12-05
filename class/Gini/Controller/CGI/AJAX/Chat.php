<?php

namespace Gini\Controller\CGI\AJAX;

class Chat extends \Gini\Controller\CGI
{
    
    public function actionNew()
    {
        $form = $this->form();
        $group = _G('GROUP');
        if ($group->id) {
            $path = APP_PATH . '/' . DATA_DIR . '/chats/' . $group->id . '.json';
            \Gini\File::ensureDir(dirname($path));
            $chats = file_exists($path) ? (array) json_decode(file_get_contents($path), true) : [];
            array_unshift($chats, [
                'username' => _G('ME')->username,
                'message' => $form['message'],
                'timestamp' => time()
            ]);
            
            if (count($chats) > 50) $chats = array_slice($chats, 0, 50);
            
            file_put_contents($path, J($chats));
        } else {
            $chats = [];
        }
        
        $chats_view = \Gini\CGI::request('ajax/chat/history', $this->env)->execute()->content();
        return new \Gini\CGI\Response\HTML(V('chat/refresh', ['chats' => (string)$chats_view ]));
    }
    
    public function actionHistory()
    {
        $group = _G('GROUP');
        $path = APP_PATH . '/' . DATA_DIR . '/chats/' . $group->id . '.json';
        $chats = file_exists($path) ? (array) json_decode(file_get_contents($path), true) : [];
        return new \Gini\CGI\Response\HTML(V('chat/history', ['chats'=>$chats]));        
    }
    
}
