<?php

namespace Gini\Controller\CGI;

class Index extends Layout\Common
{
    public function __index()
    {
        if (\Gini\Gapper\Client::getLoginStep() !==\Gini\Gapper\Client::STEP_DONE) {
            \Gini\Gapper\Client::goLogin();
        }

        $chats = \Gini\CGI::request('ajax/chat/history', $this->env)->execute()->content();
        
        $this->view->body = V('layout/chats', [
            'chats' => $chats
        ]);
    }
}
