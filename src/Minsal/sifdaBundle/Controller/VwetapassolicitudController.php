<?php

namespace Minsal\sifdaBundle\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Minsal\sifdaBundle\Entity\Vwetapassolicitud;

/**
 * Vwetapassolicitud controller.
 *
 * @Route("/sifda/vwetapassolicitud")
 */
class VwetapassolicitudController extends Controller
{

    /**
     * Lists all Vwetapassolicitud entities.
     *
     * @Route("/lstEtapas/{idSS}", name="sifda_vwetapassolicitud")
     * @Method("GET")
     * @Template()
     */
    public function indexAction($idSS)
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('MinsalsifdaBundle:Vwetapassolicitud')->findBy(array('idSolicitud'=>$idSS));
        $solicitud = $em->getRepository('MinsalsifdaBundle:SifdaSolicitudServicio')->find($idSS);
            if (!$solicitud) {
                throw $this->createNotFoundException('Unable to find SifdaSolicitudServicio entity.');
            }
        return array(
            'entities' => $entities,
            'solicitud' => $solicitud,
        );
    }

    /**
     * Finds and displays a Vwetapassolicitud entity.
     *
     * @Route("/{id}", name="sifda_vwetapassolicitud_show")
     * @Method("GET")
     * @Template()
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('MinsalsifdaBundle:Vwetapassolicitud')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Vwetapassolicitud entity.');
        }

        return array(
            'entity'      => $entity,
        );
    }
    
    /**
     * Lists all Vwetapassolicitud entities.
     *
     * @Route("/prueba/index", name="sifda_prueba")
     * @Method("GET")
     * @Template()
     */
    public function pruebaAction()
    {
        return $this->render('MinsalsifdaBundle:Vwetapassolicitud:prueba.html.twig');
        
        
    }
}
        