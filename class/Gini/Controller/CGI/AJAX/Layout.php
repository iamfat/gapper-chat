<?php

namespace Gini\Controller\CGI\AJAX;

class Layout extends \Gini\Controller\CGI
{

    public function actionHeader()
    {
        return \Gini\IoC::construct('\Gini\CGI\Response\HTML', V('layout/header'));
    }

    public function actionFooter()
    {
        return \Gini\IoC::construct('\Gini\CGI\Response\HTML', V('layout/footer'));
    }

}
