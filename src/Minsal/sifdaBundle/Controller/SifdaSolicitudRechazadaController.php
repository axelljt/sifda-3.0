<?php

namespace Minsal\sifdaBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Minsal\sifdaBundle\Entity\SifdaSolicitudRechazada;
use Minsal\sifdaBundle\Form\SifdaSolicitudRechazadaType;
use Doctrine\ORM\Query\ResultSetMapping;

/**
 * SifdaSolicitudRechazada controller.
 *
 * @Route("/sifda/rechazo")
 */
class SifdaSolicitudRechazadaController extends Controller
{

    /**
     * Lists all SifdaSolicitudRechazada entities.
     *
     * @Route("/", name="sifda_solicitud_rechazada")
     * @Method("GET")
     * @Template()
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('MinsalsifdaBundle:SifdaSolicitudRechazada')->findAll();

        return array(
            'entities' => $entities,
        );
    }
    
    /*******************************************************************************************************************************************/
    
        /**
     * Lists all SifdaSolicitudRechazada entities.
     *
     * @Route("/consolidado/razon_rechazo", name="sifda_consolidado_razon_rechazo")
     * @Method("GET")
     * @Template()
     */
    public function consolidadoRechazoAction()
    {
        $em = $this->getDoctrine()->getManager();

//        $entities = $em->getRepository('MinsalsifdaBundle:SifdaSolicitudRechazada')->findAll();
        
        $rsm = new ResultSetMapping();
        $sql = "select cd.descripcion descripcion, count(sr.id_razon_rechazo) total
                from sifda_solicitud_rechazada sr
                inner join catalogo_detalle cd on cd.id = sr.id_razon_rechazo
                inner join sifda_solicitud_servicio ss on ss.id = sr.id_solicitud_servicio
                group by (cd.descripcion)";

        $rsm->addScalarResult('descripcion','descripcion');
        $rsm->addScalarResult('total','total');
        $query = $this->getDoctrine()->getEntityManager();

        $solicitudes = $query->createNativeQuery($sql, $rsm)
                             ->getResult();
        //ladybug_dump($query);
        
        return array(
            'entities' => $solicitudes,
        );
    }
    
    /**
    * Ajax utilizado para buscar rango de fechas
    *  
    * @Route("/consolidado/razon_rechazo", name="sifda_consolidado_razon_rechazo2")
    */
    public function consolidadoRechazo2Action()
    {
//        $isAjax = $this->get('Request')->isXMLhttpRequest();
//        $estado=4;
        
//        if($isAjax){
//             $fechaInicio = $this->get('request')->request->get('fechaInicio');
//             $fechaFin = $this->get('request')->request->get('fechaFin');
//             $dependencia=$this->get('request')->request->get('dependencia');
////             $em = $this->getDoctrine()->getManager();
//             
//             $idusuario=  $this->getUser()->getId();
             $rsm = new ResultSetMapping();
             
//             $solicitudes = $em->getRepository('MinsalsifdaBundle:SifdaSolicitudServicio')->buscarFechasSolicitudGenerico($fechaInicio, $fechaFin,$tipoServicio,$estado);
             $sql = "select cd.descripcion descripcion, count(sr.id_razon_rechazo) total
                    from sifda_solicitud_rechazada sr
                    inner join catalogo_detalle cd on cd.id = sr.id_razon_rechazo
                    inner join sifda_solicitud_servicio ss on ss.id = sr.id_solicitud_servicio
                    group by (cd.descripcion)";
        
                        $rsm->addScalarResult('descripcion','descripcion');
                        $rsm->addScalarResult('total','total');
                        $query = $this->getDoctrine()->getEntityManager();
                        
                        $solicitudes = $query->createNativeQuery($sql, $rsm)                
//                                    ->setParameter(1,$dependencia)
//                                    ->setParameter(2,$fechaInicio)
//                                    ->setParameter(3,$fechaFin)
                                    ->getResult();
                        
//              $tam= Count($solicitudes);
//             if($tam>0)
//                 {
//                     $mensaje = $this->renderView('MinsalsifdaBundle:SifdaSolicitudServicio:solicitudesRechShow.html.twig' , array('solicitudes' =>$solicitudes));
//                     $response = new JsonResponse();
//                     return $response->setData($mensaje);
//                 }
//             else{
//                    $response = new JsonResponse();
//                    return $response->setData(array('val'=>0));
//                 
//                 }
//           } 
//       else
//            {    $response = new JsonResponse();
//                 return $response->setData(array('val'=>0));
//                
//            }  
      return array(
            'entities' => $solicitudes,
        );                  
    }
    
    /******************************************************************************************************************************************/
    
    /**
     * Creates a new SifdaSolicitudRechazada entity.
     *
     * @Route("/create/{id}", name="sifda_solicitudrechazada_create")
     * @Method("POST")
     * @Template("MinsalsifdaBundle:SifdaSolicitudRechazada:new.html.twig")
     */
    public function createAction(Request $request, $id)
    {
        $entity = new SifdaSolicitudRechazada();
        
        $form = $this->createCreateForm($entity, $id);
        
        $user = $this->getUser()->getId();
        $em = $this->getDoctrine()->getManager();
        
        //Se obtiene la solicitud de servicio que se va a rechazar
        $idSolicitudServicio = $em->getRepository('MinsalsifdaBundle:SifdaSolicitudServicio')->find($id);
        
        $form->handleRequest($request);

        if ($form->isValid()) {
            $entity->setIdSolicitudServicio($idSolicitudServicio);
            
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();
            
            //Se establece la solicitud de servicio como rechazada
            $estado = $idSolicitudServicio->getIdEstado()->getId();
            if($estado==1)
            {
                $objEstado = $em->getRepository('MinsalsifdaBundle:CatalogoDetalle')->find(3);
                $idSolicitudServicio->setIdEstado($objEstado);
                $em->merge($idSolicitudServicio);
                $em->flush();
            }
            
            //Se obtiene el correo electronico de la persona que realizo la solicitud de servicio
            $correo = $idSolicitudServicio->getUser()->getIdEmpleado()->getCorreoElectronico();
            
            //Se almacena el texto del correo electronico a enviar
            $texto = "";
            $texto.= 'Descripcion solicitud de servicio: '.$idSolicitudServicio->getDescripcion().' Razon de rechazo: '.$entity->getIdRazonRechazo();
            
            //Se envia el correo electronico a la persona solicitante
            $message = \Swift_Message::newInstance()
                           ->setSubject('Rechazo del servicio solicitado')
                           ->setFrom('tesis.flujotrabajo@gmail.com')
                           ->setTo($correo)
                           ->setBody($texto);
            $this->get('mailer')->send($message);     // then we send the message.
            
            return $this->redirect($this->generateUrl('sifda_gestionSolicitudes'));
        }

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Creates a form to create a SifdaSolicitudRechazada entity.
     *
     * @param SifdaSolicitudRechazada $entity The entity
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(SifdaSolicitudRechazada $entity, $id)
    {
        $form = $this->createForm(new SifdaSolicitudRechazadaType(), $entity, array(
            'action' => $this->generateUrl('sifda_solicitudrechazada_create', array('id' => $id)),
            'method' => 'POST',
        ));

        $form->add('submit', 'submit', array('label' => 'Rechazar solicitud'));

        return $form;
    }

    /**
     * Displays a form to create a new SifdaSolicitudRechazada entity.
     *
     * @Route("/solicitud/{id}", name="sifda_solicitud_rechazo")
     * @Method("GET")
     * @Template()
     */
    public function newAction($id)
    {
        $entity = new SifdaSolicitudRechazada();
        
        if ($id != 0) {
           $em = $this->getDoctrine()->getManager();
           $solicitud = $em->getRepository('MinsalsifdaBundle:SifdaSolicitudServicio')->find($id);
           
           if (!$solicitud) {
                throw $this->createNotFoundException('Unable to find SifdaOrdenTrabajo entity.');
            }
        }
        $form   = $this->createCreateForm($entity,$id);

        return array(
            'entity'    => $entity,
            'solicitud' => $solicitud,
            'form'      => $form->createView(),
        );
    }

    /**
     * Finds and displays a SifdaSolicitudRechazada entity.
     *
     * @Route("/{id}", name="sifda_solicitudrechazada_show")
     * @Method("GET")
     * @Template()
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('MinsalsifdaBundle:SifdaSolicitudRechazada')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find SifdaSolicitudRechazada entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Displays a form to edit an existing SifdaSolicitudRechazada entity.
     *
     * @Route("/{id}/edit", name="sifda_solicitudrechazada_edit")
     * @Method("GET")
     * @Template()
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('MinsalsifdaBundle:SifdaSolicitudRechazada')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find SifdaSolicitudRechazada entity.');
        }

        $editForm = $this->createEditForm($entity);
        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
    * Creates a form to edit a SifdaSolicitudRechazada entity.
    *
    * @param SifdaSolicitudRechazada $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createEditForm(SifdaSolicitudRechazada $entity)
    {
        $form = $this->createForm(new SifdaSolicitudRechazadaType(), $entity, array(
            'action' => $this->generateUrl('sifda_solicitudrechazada_update', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));

        $form->add('submit', 'submit', array('label' => 'Update'));

        return $form;
    }
    /**
     * Edits an existing SifdaSolicitudRechazada entity.
     *
     * @Route("/{id}", name="sifda_solicitudrechazada_update")
     * @Method("PUT")
     * @Template("MinsalsifdaBundle:SifdaSolicitudRechazada:edit.html.twig")
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('MinsalsifdaBundle:SifdaSolicitudRechazada')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find SifdaSolicitudRechazada entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $em->flush();

            return $this->redirect($this->generateUrl('sifda_solicitudrechazada_edit', array('id' => $id)));
        }

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }
    /**
     * Deletes a SifdaSolicitudRechazada entity.
     *
     * @Route("/{id}", name="sifda_solicitudrechazada_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('MinsalsifdaBundle:SifdaSolicitudRechazada')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find SifdaSolicitudRechazada entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('sifda_solicitudrechazada'));
    }

    /**
     * Creates a form to delete a SifdaSolicitudRechazada entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('sifda_solicitudrechazada_delete', array('id' => $id)))
            ->setMethod('DELETE')
            ->add('submit', 'submit', array('label' => 'Delete'))
            ->getForm()
        ;
    }
}
