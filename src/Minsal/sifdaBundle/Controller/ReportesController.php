<?php

namespace Minsal\sifdaBundle\Controller;

use Doctrine\ORM\Query\ResultSetMapping;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;

/**
 * Reportes controller.
 *
 * @Route("/sifda/reportes")
 */
class ReportesController extends Controller 
{
    /**
    * Consolidado de solicitudes por tipo de servicio
    *  
    * @Route("/solicitudes/tipoServicio", name="sifda_reporte_solicitudes_tiposervicio")
    * @Method("GET")
    * @Template() 
    */
    public function consolidadoSolicitudesTipoServicioAction()
    {
        $em = $this->getDoctrine()->getManager();

        $idusuario=  $this->getUser()->getId();
        $usuario= $em->getRepository('MinsalsifdaBundle:FosUserUser')->find($idusuario);

        $establecimiento= $em->getRepository('MinsalsifdaBundle:CtlEstablecimiento')->findAll();
        
        return array(
            'usuario' => $usuario,
            'establecimiento'=>$establecimiento,
        );
    }
        
}
