<?php

namespace Minsal\sifdaBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Minsal\sifdaBundle\Entity\SifdaReprogramacionServicio;
use Minsal\sifdaBundle\Form\SifdaReprogramacionServicioType;

/**
 * SifdaReprogramacionServicio controller.
 *
 * @Route("/sifda/reprogramacion/servicio")
 */
class SifdaReprogramacionServicioController extends Controller
{

    /**
     * Lists all SifdaReprogramacionServicio entities.
     *
     * @Route("/", name="sifda_reprogramacion_servicio")
     * @Method("GET")
     * @Template()
     */
    public function indexAction()
    {
        $id_usuario=$this->getUser()->getId();
        $em = $this->getDoctrine()->getManager();
        
        $usuario=$em->getRepository('MinsalsifdaBundle:FosUserUser')->find($id_usuario);
        
        $entities = $em->getRepository('MinsalsifdaBundle:SifdaReprogramacionServicio')->findAll();

        return array(
            'entities' => $entities,
            'usuario'  => $usuario,
        );
    }
    /**
     * Creates a new SifdaReprogramacionServicio entity.
     *
     * @Route("/create/{id}", name="sifda_reprogramacion_servicio_create")
     * @Method("POST")
     * @Template("MinsalsifdaBundle:SifdaReprogramacionServicio:new.html.twig")
     */
    public function createAction(Request $request, $id)
    {
        $entity = new SifdaReprogramacionServicio();
        $form = $this->createCreateForm($entity, $id);
        
        $em = $this->getDoctrine()->getManager();
        
        //Obtener la solicitud de servicio que se va a atender
        $idSolicitudServicio = $em->getRepository('MinsalsifdaBundle:SifdaSolicitudServicio')->find($id);
        $entity->setIdSolicitudServicio($idSolicitudServicio);
        
        //Se establece la fecha de finalizacion anterior a serreprogramada
        $entity->setFechaAnterior($idSolicitudServicio->getFechaRequiere());
        
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();
            
            $idSolicitudServicio->setFechaRequiere($entity->getFechaReprogramacion());
            $em->merge($idSolicitudServicio);
            $em->flush();

            return $this->redirect($this->generateUrl('sifda_gestionSolicitudes'));
        }

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Creates a form to create a SifdaReprogramacionServicio entity.
     *
     * @param SifdaReprogramacionServicio $entity The entity
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(SifdaReprogramacionServicio $entity, $id)
    {
        $form = $this->createForm(new SifdaReprogramacionServicioType(), $entity, array(
            'action' => $this->generateUrl('sifda_reprogramacion_servicio_create', array('id' => $id)),
            'method' => 'POST',
        ));

        $form->add('submit', 'submit', array('label' => 'Reprogramar solicitud de servicio'));

        return $form;
    }

    /**
     * Displays a form to create a new SifdaReprogramacionServicio entity.
     *
     * @Route("/new/{id}", name="sifda_reprogramacion_servicio_new")
     * @Method("GET")
     * @Template()
     */
    public function newAction($id)
    {
        $entity = new SifdaReprogramacionServicio();
        $form   = $this->createCreateForm($entity, $id);
        
        $em = $this->getDoctrine()->getManager();
        $solicitudServicio = $em->getRepository('MinsalsifdaBundle:SifdaSolicitudServicio')->find($id);

        if (!$solicitudServicio) {
            throw $this->createNotFoundException('Unable to find SifdaSolicitudServicio entity.');
        }
        
        return array(
            'entity' => $entity,
            'solicitudServicio' => $solicitudServicio,
            'form'   => $form->createView(),
        );
    }

    /**
     * Finds and displays a SifdaReprogramacionServicio entity.
     *
     * @Route("/{id}", name="sifda_reprogramacion_servicio_show")
     * @Method("GET")
     * @Template()
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('MinsalsifdaBundle:SifdaReprogramacionServicio')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find SifdaReprogramacionServicio entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Displays a form to edit an existing SifdaReprogramacionServicio entity.
     *
     * @Route("/{id}/edit", name="sifda_reprogramacion_servicio_edit")
     * @Method("GET")
     * @Template()
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('MinsalsifdaBundle:SifdaReprogramacionServicio')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find SifdaReprogramacionServicio entity.');
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
    * Creates a form to edit a SifdaReprogramacionServicio entity.
    *
    * @param SifdaReprogramacionServicio $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createEditForm(SifdaReprogramacionServicio $entity)
    {
        $form = $this->createForm(new SifdaReprogramacionServicioType(), $entity, array(
            'action' => $this->generateUrl('sifda_reprogramacion_servicio_update', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));

        $form->add('submit', 'submit', array('label' => 'Update'));

        return $form;
    }
    /**
     * Edits an existing SifdaReprogramacionServicio entity.
     *
     * @Route("/{id}", name="sifda_reprogramacion_servicio_update")
     * @Method("PUT")
     * @Template("MinsalsifdaBundle:SifdaReprogramacionServicio:edit.html.twig")
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('MinsalsifdaBundle:SifdaReprogramacionServicio')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find SifdaReprogramacionServicio entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $em->flush();

            return $this->redirect($this->generateUrl('sifda_reprogramacion_servicio_edit', array('id' => $id)));
        }

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }
    /**
     * Deletes a SifdaReprogramacionServicio entity.
     *
     * @Route("/{id}", name="sifda_reprogramacion_servicio_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('MinsalsifdaBundle:SifdaReprogramacionServicio')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find SifdaReprogramacionServicio entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('sifda_reprogramacion_servicio'));
    }

    /**
     * Creates a form to delete a SifdaReprogramacionServicio entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('sifda_reprogramacion_servicio_delete', array('id' => $id)))
            ->setMethod('DELETE')
            ->add('submit', 'submit', array('label' => 'Delete'))
            ->getForm()
        ;
    }
}
