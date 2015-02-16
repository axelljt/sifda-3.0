<?php

namespace Minsal\sifdaBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Minsal\sifdaBundle\Entity\SifdaInformeOrdenTrabajo;
use Minsal\sifdaBundle\Form\SifdaInformeOrdenTrabajoType;

/**
 * SifdaInformeOrdenTrabajo controller.
 *
 * @Route("/sifda/informeordentrabajo")
 */
class SifdaInformeOrdenTrabajoController extends Controller
{

    /**
     * Lists all SifdaInformeOrdenTrabajo entities.
     *
     * @Route("/lstInf/{idOrd}", name="sifdainformeordentrabajo")
     * @Method("GET")
     * @Template()
     */
    public function indexAction($idOrd) {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('MinsalsifdaBundle:SifdaInformeOrdenTrabajo')->findBy(array('idOrdenTrabajo' => $idOrd));
        
            $orden = $em->getRepository('MinsalsifdaBundle:SifdaOrdenTrabajo')->find($idOrd);
            if (!$orden) {
                throw $this->createNotFoundException('Unable to find SifdaInformeOrdenTrabajo entity.');
            }
            return array(
            'entities' => $entities,
            'orden' => $orden,
        );
    }

    /**
     * Creates a new SifdaInformeOrdenTrabajo entity.
     *
     * @Route("/create/{id}", name="sifdainformeordentrabajo_create")
     * @Method("POST")
     * @Template("MinsalsifdaBundle:SifdaInformeOrdenTrabajo:new.html.twig")
     */
    public function createAction(Request $request, $id)
    {
        
        $entity = new SifdaInformeOrdenTrabajo();
        $form = $this->createCreateForm($entity, $id);
        
        $user = 7;
        $em = $this->getDoctrine()->getManager();
        
        $usuario = $em->getRepository('MinsalsifdaBundle:FosUserUser')->find($user);
        if (!$usuario) {
            throw $this->createNotFoundException('Unable to find FosUserUser entity.');
        }
        $entity->setIdDependenciaEstablecimiento($usuario->getIdDependenciaEstablecimiento());
        
        $idEmpleado = $em->getRepository('MinsalsifdaBundle:CtlEmpleado')->find($usuario->getIdEmpleado()->getId());
        if (!$idEmpleado) {
            throw $this->createNotFoundException('Unable to find CtlEmpleado entity.');
        }
        $entity->setIdEmpleado($idEmpleado);
        
        if ($id != 0) {
            $em = $this->getDoctrine()->getManager();

            $orden = $em->getRepository('MinsalsifdaBundle:SifdaOrdenTrabajo')->find($id);

            if (!$orden) {
                throw $this->createNotFoundException('Unable to find SifdaOrdenTrabajo entity.');
            }
            $entity->setIdOrdenTrabajo($orden);
        }
        
        $idEtapa = $em->getRepository('MinsalsifdaBundle:SifdaRutaCicloVida')->find($orden->getIdEtapa());
        if (!$idEtapa) {
            throw $this->createNotFoundException('Unable to find SifdaRutaCicloVida entity.');
        }
        $entity->setIdEtapa($idEtapa->getId());
        
        $form->handleRequest($request);

        if ($form->isValid()) {
            $entity->setFechaRegistro(new \DateTime("now"));
            
            $terminado = $form->get('terminado')->getData();
            if ($terminado  == 's') {
                $entity->setTerminado(TRUE);
                
            }
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();
            
            if ($terminado  == 's') {
                $estado = $orden->getIdEstado()->getId();
                
                if($estado==2) {
                    $objEstado = $em->getRepository('MinsalsifdaBundle:CatalogoDetalle')->find(4);
                    $orden->setIdEstado($objEstado);
                    $em->merge($orden);
                    $em->flush();
                } 
            }
                
            return $this->redirect($this->generateUrl('sifdainformeordentrabajo_show', array('id' => $entity->getId())));
        }
        
        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
            'orden'   =>$entity->getIdOrdenTrabajo(),
        );
    }

    /**
     * Creates a form to create a SifdaInformeOrdenTrabajo entity.
     *
     * @param SifdaInformeOrdenTrabajo $entity The entity
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(SifdaInformeOrdenTrabajo $entity, $id)
    {
        $form = $this->createForm(new SifdaInformeOrdenTrabajoType(), $entity, array(
            'action' => $this->generateUrl('sifdainformeordentrabajo_create', array('id' => $id)),
            'method' => 'POST',
        ));

        $form->add('submit', 'submit', array('label' => 'Registrar informe'));

        return $form;
    }

    /**
     * Displays a form to create a new SifdaInformeOrdenTrabajo entity.
     *
     * @Route("/new/{id}", name="sifdainformeordentrabajo_new")
     * @Method("GET")
     * @Template()
     */
    public function newAction($id)
    {
        $entity = new SifdaInformeOrdenTrabajo();
        $orden = null;
        
        if ($id != 0) {
            $em = $this->getDoctrine()->getManager();

            $orden = $em->getRepository('MinsalsifdaBundle:SifdaOrdenTrabajo')->find($id);

            if (!$orden) {
                throw $this->createNotFoundException('Unable to find SifdaInformeOrdenTrabajo entity.');
            }
        }
        $form = $this->createCreateForm($entity, $id);
        return array(
            'entity' => $entity,
            'orden' => $orden,
            'form' => $form->createView(),
            
        );
    }

    /**
     * Finds and displays a SifdaInformeOrdenTrabajo entity.
     *
     * @Route("/{id}", name="sifdainformeordentrabajo_show")
     * @Method("GET")
     * @Template()
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('MinsalsifdaBundle:SifdaInformeOrdenTrabajo')->find($id);
        
        if (!$entity) {
            throw $this->createNotFoundException('Unable to find SifdaInformeOrdenTrabajo entity.');
        }
        
        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),
       );
        
        
    }

    /**
     * Displays a form to edit an existing SifdaInformeOrdenTrabajo entity.
     *
     * @Route("/{id}/edit", name="sifdainformeordentrabajo_edit")
     * @Method("GET")
     * @Template()
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('MinsalsifdaBundle:SifdaInformeOrdenTrabajo')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find SifdaInformeOrdenTrabajo entity.');
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
    * Creates a form to edit a SifdaInformeOrdenTrabajo entity.
    *
    * @param SifdaInformeOrdenTrabajo $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createEditForm(SifdaInformeOrdenTrabajo $entity)
    {
        $form = $this->createForm(new SifdaInformeOrdenTrabajoType(), $entity, array(
            'action' => $this->generateUrl('sifdainformeordentrabajo_update', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));

        $form->add('submit', 'submit', array('label' => 'Actualizar informe'));

        return $form;
    }
    /**
     * Edits an existing SifdaInformeOrdenTrabajo entity.
     *
     * @Route("/{id}", name="sifdainformeordentrabajo_update")
     * @Method("PUT")
     * @Template("MinsalsifdaBundle:SifdaInformeOrdenTrabajo:edit.html.twig")
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('MinsalsifdaBundle:SifdaInformeOrdenTrabajo')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find SifdaInformeOrdenTrabajo entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            
            $terminado = $editForm->get('terminado')->getData();
            if ($terminado  == 's') {
                $entity->setTerminado(TRUE);
            }
            else {
                $entity->setTerminado(FALSE);
            }
            
            $em->flush();
            
            $estado = $entity->getIdOrdenTrabajo()->getIdEstado()->getId();
            if ($terminado  == 's') {
                if($estado==2) {
                    $objEstado = $em->getRepository('MinsalsifdaBundle:CatalogoDetalle')->find(4);
                    $entity->setIdEstado($objEstado);
                    $em->merge($entity);
                    $em->flush();
                } 
            }
            else {
                if($estado==4) {
                    $objEstado = $em->getRepository('MinsalsifdaBundle:CatalogoDetalle')->find(2);
                    $entity->setIdEstado($objEstado);
                    $em->merge($entity);
                    $em->flush();
                } 
            }
            
            return $this->redirect($this->generateUrl('sifdainformeordentrabajo_edit', array('id' => $id)));
        }

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }
    /**
     * Deletes a SifdaInformeOrdenTrabajo entity.
     *
     * @Route("/{id}", name="sifdainformeordentrabajo_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('MinsalsifdaBundle:SifdaInformeOrdenTrabajo')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find SifdaInformeOrdenTrabajo entity.');
            }
            $idOrd = $entity->getIdOrdenTrabajo()->getId();
            $em->remove($entity);
            
            $em->flush();
        }

        return $this->redirect($this->generateUrl('sifdainformeordentrabajo', array('idOrd' => $idOrd)));
    }

    /**
     * Creates a form to delete a SifdaInformeOrdenTrabajo entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('sifdainformeordentrabajo_delete', array('id' => $id)))
            ->setMethod('DELETE')
            ->add('submit', 'submit', array('label' => 'Eliminar informe'))
            ->getForm()
        ;
    }
}
