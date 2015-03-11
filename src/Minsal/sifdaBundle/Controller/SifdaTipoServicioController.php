<?php

namespace Minsal\sifdaBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Minsal\sifdaBundle\Entity\SifdaTipoServicio;
use Minsal\sifdaBundle\Form\SifdaTipoServicioType;
use Doctrine\ORM\EntityRepository;

/**
 * SifdaTipoServicio controller.
 *
 * @Route("/sifda/tiposervicio")
 */
class SifdaTipoServicioController extends Controller
{

    /**
     * Lists all SifdaTipoServicio entities.
     *
     * @Route("/", name="sifdatiposervicio")
     * @Method("GET")
     * @Template()
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('MinsalsifdaBundle:SifdaTipoServicio')->findAll();
        
        $user_id = $this->getUser()->getId();
        $usuario = $em->getRepository('MinsalsifdaBundle:FosUserUser')->find($user_id);


        return array(
            'entities' => $entities,
            'usuario'  => $usuario,
        );
    }
    /**
     * Creates a new SifdaTipoServicio entity.
     *
     * @Route("/", name="sifdatiposervicio_create")
     * @Method("POST")
     * @Template("MinsalsifdaBundle:SifdaTipoServicio:new.html.twig")
     */
    public function createAction(Request $request)
    {
        $tiposervicio = new SifdaTipoServicio();
        $form = $this->createCreateForm($tiposervicio);
        $form->handleRequest($request);
        
        $user_id = $this->getUser()->getId();
        $em = $this->getDoctrine()->getManager();
        $usuario = $em->getRepository('MinsalsifdaBundle:FosUserUser')->find($user_id);
        $tiposervicio->setIdDependenciaEstablecimiento($usuario->getIdDependenciaEstablecimiento());
        
        if ($form->isValid()) {
            $tiposervicio->setActivo(TRUE);
            $em = $this->getDoctrine()->getManager();
            $em->persist($tiposervicio);
            $em->flush();
            
            return $this->redirect($this->generateUrl('sifdatiposervicio_show', array('id' => $tiposervicio->getId())));
        }

        return array(
            'entity' => $tiposervicio,
            'form'   => $form->createView(),
        );
    }

    /**
     * Creates a form to create a SifdaTipoServicio entity.
     *
     * @param SifdaTipoServicio $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(SifdaTipoServicio $entity)
    {
        $form = $this->createForm(new SifdaTipoServicioType(), $entity, array(
            'action' => $this->generateUrl('sifdatiposervicio_create'),
            'method' => 'POST',
        ));

        $form->add('submit', 'submit', array('label' => 'Registrar'));

        return $form;
    }

    /**
     * Displays a form to create a new SifdaTipoServicio entity.
     *
     * @Route("/new", name="sifdatiposervicio_new")
     * @Method("GET")
     * @Template()
     */
    public function newAction()
    {
        $entity = new SifdaTipoServicio();
        $form   = $this->createCreateForm($entity);

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Finds and displays a SifdaTipoServicio entity.
     *
     * @Route("/{id}", name="sifdatiposervicio_show")
     * @Method("GET")
     * @Template()
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('MinsalsifdaBundle:SifdaTipoServicio')->find($id);
        $entities = $em->getRepository('MinsalsifdaBundle:SifdaRutaCicloVida')->obtenerEtapas($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find SifdaTipoServicio entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'entities' => $entities,            
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Displays a form to edit an existing SifdaTipoServicio entity.
     *
     * @Route("/{id}/edit", name="sifdatiposervicio_edit")
     * @Method("GET")
     * @Template()
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('MinsalsifdaBundle:SifdaTipoServicio')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find SifdaTipoServicio entity.');
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
    * Creates a form to edit a SifdaTipoServicio entity.
    *
    * @param SifdaTipoServicio $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createEditForm(SifdaTipoServicio $entity)
    {
        $userid = $this->getUser()->getId();
        $em = $this->getDoctrine()->getManager();
        $usuario=$em->getRepository('MinsalsifdaBundle:FosUserUser')->find($userid);
        
        
        $form = $this->createForm(new SifdaTipoServicioType(), $entity, array(
            'action' => $this->generateUrl('sifdatiposervicio_update', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));

        $form->add('idActividad', 'entity', array(
                'label' => 'Nombre de actividad',
                //'empty_value'=>'Seleccione una actividad',
                'class' => 'MinsalsifdaBundle:SidplaActividad',
                'query_builder' =>  function(EntityRepository $repositorio) use ( $usuario ){
                    return $repositorio
                            ->createQueryBuilder('act')
                            ->innerJoin('act.idLineaEstrategica', 'le')
                            ->innerJoin('le.idDependenciaEstablecimiento', 'de')
                            ->where('de.id = :dependenciaEstablecimiento')
                            ->setParameter(':dependenciaEstablecimiento', $usuario->getIdDependenciaEstablecimiento());
                    }));
        
        $form->add('submit', 'submit', array('label' => 'Actualizar tipo de servicio'));

        return $form;
    }
    /**
     * Edits an existing SifdaTipoServicio entity.
     *
     * @Route("/{id}", name="sifdatiposervicio_update")
     * @Method("PUT")
     * @Template("MinsalsifdaBundle:SifdaTipoServicio:edit.html.twig")
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('MinsalsifdaBundle:SifdaTipoServicio')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find SifdaTipoServicio entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $em->flush();

            return $this->redirect($this->generateUrl('sifdatiposervicio_show', array('id' => $id)));
        }

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }
    /**
     * Deletes a SifdaTipoServicio entity.
     *
     * @Route("/{id}", name="sifdatiposervicio_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('MinsalsifdaBundle:SifdaTipoServicio')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find SifdaTipoServicio entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('sifdatiposervicio'));
    }

    /**
     * Creates a form to delete a SifdaTipoServicio entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('sifdatiposervicio_delete', array('id' => $id)))
            ->setMethod('DELETE')
            ->add('submit', 'submit', array('label' => 'Delete'))
            ->getForm()
        ;
    }
}
