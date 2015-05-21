<?php

namespace Minsal\sifdaBundle\Controller;

use Doctrine\ORM\Query\ResultSetMapping;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;


//use Minsal\sifdaBundle\Entity\CtlCargo;

/**
 * Consultas controller.
 *
 * @Route("/sifda/fechas")
 */

class DiasFeriadosController extends Controller
{
        
    /**
     * Lists all Vwetapassolicitud entities.
     *
     * @Route("/diasFeriados", name="sifda_diasFeriados")
     * @Method("GET")
     * @Template()
     */
    public function indexAction()
    {
        
        $idusuario=  $this->getUser()->getId();
        $em = $this->getDoctrine()->getManager();
        $usuario= $em->getRepository('MinsalsifdaBundle:FosUserUser')->find($idusuario);
              
        return $this->render('MinsalsifdaBundle:DiasFeriados:DiasFeriados.html.twig',array('usuario'=>$usuario));
        
    }
}