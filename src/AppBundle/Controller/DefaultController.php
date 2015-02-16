<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    /**
     * @Route("/app/example1", name="homepage")
     */
    public function indexAction()
    {
        return $this->render('default/index.html.twig');
    }
    /**
     * @Route("/autenticacion", name="authentication_handler")
     */
    public function authenticationHandler()
    {
        $user = $this->getUser();
        $em = $this->getDoctrine()->getManager();
        
        $usuario=$em->getRepository('MinsalsifdaBundle:FosUserUser')->find($user->getId());
        $rol = $usuario->getRoles()[0];
        if ($rol == "ROLE_TECNICO"){
        return $this->redirect($this->generateUrl('sifda_tecnico'));
        } else if ($rol == "ROLE_SOLICITANTE"){
//        return $this->redirect($this->generateUrl('sifda_solicitudservicio'));
        return $this->redirect($this->generateUrl('sifda_solicitudservicio'));
        }else if ($rol == "ROLE_RESPONSABLE"){
        return $this->redirect($this->generateUrl('sifda_responsable'));
        }else {
            return $this->redirect($this->generateUrl('fos_user_security_logout'));
        }
        
    }
}
