<?php

namespace Gini\Controller\CGI\Layout;

abstract class Common extends \Gini\Controller\CGI\Layout
{
    public function __preAction($action, &$params)
    {
       $this->view = V('layout/common');
    }

    public function __postAction($action, &$params, $response)
    {
        $this->view->header = \Gini\CGI::request('ajax/layout/header', $this->env)->execute()->content();
        $this->view->footer = \Gini\CGI::request('ajax/layout/footer', $this->env)->execute()->content();

        return parent::__postAction($action, $params, $response);
    }

}
