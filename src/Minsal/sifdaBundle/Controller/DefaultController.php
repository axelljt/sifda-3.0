<?php

namespace Minsal\sifdaBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction($name)
    {
        return $this->render('MinsalsifdaBundle:Default:index.html.twig', array('name' => $name));
    }
    
    public function administradorAction($name)
    {
        return $this->render('MinsalsifdaBundle:Default:administrador.html.twig', array('name' => $name));
    }
    
}
