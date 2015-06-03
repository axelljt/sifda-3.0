<?php

namespace Minsal\sifdaBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Minsal\sifdaBundle\Entity\SifdaServicioPrioridad;
use Minsal\sifdaBundle\Form\SifdaServicioPrioridadType;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Query\ResultSetMapping;
use Symfony\Component\HttpFoundation\JsonResponse;

/**
 * SifdaServicioPrioridad controller.
 *
 * @Route("/sifda/servicioprioridad")
 */
class SifdaServicioPrioridadController extends Controller
{

    /**
     * Lists all SifdaServicioPrioridad entities.
     *
     * @Route("/", name="sifda_servicioprioridad")
     * @Method("GET")
     * @Template()
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('MinsalsifdaBundle:SifdaServicioPrioridad')->findAll();
        
        $idusuario=  $this->getUser()->getId();
        $usuario= $em->getRepository('MinsalsifdaBundle:FosUserUser')->find($idusuario);

        return array(
            'entities' => $entities,
            'usuario' => $usuario,
        );
    }
    /**
     * Creates a new SifdaServicioPrioridad entity.
     *
     * @Route("/", name="sifda_servicioprioridad_create")
     * @Method("POST")
     * @Template("MinsalsifdaBundle:SifdaServicioPrioridad:new.html.twig")
     */
    public function createAction(Request $request)
    {
        $entity = new SifdaServicioPrioridad();
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);

        $data=array(); 
        $parameters = $request->request->all();
        foreach($parameters as $p){
            $data = $p['servicioPrioridad'];
        }
        
//        if ($form->isValid()) {
        if($data){
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

        //    return $this->redirect($this->generateUrl('sifda_servicioprioridad_show', array('id' => $entity->getId())));
        }    
//        }

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Creates a form to create a SifdaServicioPrioridad entity.
     *
     * @param SifdaServicioPrioridad $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(SifdaServicioPrioridad $entity)
    {
        $form = $this->createForm(new SifdaServicioPrioridadType(), $entity, array(
            'action' => $this->generateUrl('sifda_servicioprioridad_create'),
            'method' => 'POST',
        ));

        $idusuario=  $this->getUser()->getId();
        $em = $this->getDoctrine()->getManager();
        $usuario= $em->getRepository('MinsalsifdaBundle:FosUserUser')->find($idusuario);
        $dependenciaEstablecimiento = $usuario->getIdDependenciaEstablecimiento();
        
        $form->add('idTipoServicio', 'entity', array(
                    'required'      =>  true,
                    'label'         =>  'Tipo de servicio', 
                    'empty_value'=>'Seleccione un tipo de servicio',
                    'class'         =>  'MinsalsifdaBundle:SifdaTipoServicio',
                    'query_builder' =>  function(EntityRepository $repositorio) use ( $dependenciaEstablecimiento ){
                return $repositorio
                        ->createQueryBuilder('tp')
                        ->where('tp.idDependenciaEstablecimiento = :dependenciaEstablecimiento')
                        ->setParameter(':dependenciaEstablecimiento', $dependenciaEstablecimiento);
            }));
        
        $form->add('submit', 'submit', array('label' => 'Registrar'));

        return $form;
    }

    /**
     * Displays a form to create a new SifdaServicioPrioridad entity.
     *
     * @Route("/new", name="sifda_servicioprioridad_new")
     * @Method("GET")
     * @Template()
     */
    public function newAction()
    {
        $entity = new SifdaServicioPrioridad();
        $form   = $this->createCreateForm($entity);
        
        $idusuario=  $this->getUser()->getId();
        $em = $this->getDoctrine()->getManager();
        $usuario= $em->getRepository('MinsalsifdaBundle:FosUserUser')->find($idusuario);

        return array(
            'entity' => $entity,
            'usuario' => $usuario,
            'form'   => $form->createView(),
        );
    }

    /**
     * Finds and displays a SifdaServicioPrioridad entity.
     *
     * @Route("/{id}", name="sifda_servicioprioridad_show")
     * @Method("GET")
     * @Template()
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('MinsalsifdaBundle:SifdaServicioPrioridad')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find SifdaServicioPrioridad entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Displays a form to edit an existing SifdaServicioPrioridad entity.
     *
     * @Route("/{id}/edit", name="sifda_servicioprioridad_edit")
     * @Method("GET")
     * @Template()
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('MinsalsifdaBundle:SifdaServicioPrioridad')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find SifdaServicioPrioridad entity.');
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
    * Creates a form to edit a SifdaServicioPrioridad entity.
    *
    * @param SifdaServicioPrioridad $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createEditForm(SifdaServicioPrioridad $entity)
    {
        $form = $this->createForm(new SifdaServicioPrioridadType(), $entity, array(
            'action' => $this->generateUrl('sifda_servicioprioridad_update', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));

        $form->add('submit', 'submit', array('label' => 'Update'));

        return $form;
    }
    /**
     * Edits an existing SifdaServicioPrioridad entity.
     *
     * @Route("/{id}", name="sifda_servicioprioridad_update")
     * @Method("PUT")
     * @Template("MinsalsifdaBundle:SifdaServicioPrioridad:edit.html.twig")
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('MinsalsifdaBundle:SifdaServicioPrioridad')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find SifdaServicioPrioridad entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $em->flush();

            return $this->redirect($this->generateUrl('sifda_servicioprioridad_edit', array('id' => $id)));
        }

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }
    /**
     * Deletes a SifdaServicioPrioridad entity.
     *
     * @Route("/{id}", name="sifda_servicioprioridad_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('MinsalsifdaBundle:SifdaServicioPrioridad')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find SifdaServicioPrioridad entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('sifda_servicioprioridad'));
    }

    /**
     * Creates a form to delete a SifdaServicioPrioridad entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('sifda_servicioprioridad_delete', array('id' => $id)))
            ->setMethod('DELETE')
            ->add('submit', 'submit', array('label' => 'Delete'))
            ->getForm()
        ;
    }
}
