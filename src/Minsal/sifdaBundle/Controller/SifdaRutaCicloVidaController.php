<?php

namespace Minsal\sifdaBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Minsal\sifdaBundle\Entity\SifdaRutaCicloVida;
//use Minsal\sifdaBundle\Entity\SifdaRuta;
use Minsal\sifdaBundle\Form\SifdaRutaCicloVidaType;

/**
 * SifdaRutaCicloVida controller.
 *
 * @Route("/sifda/rutaciclovida")
 */
class SifdaRutaCicloVidaController extends Controller
{

    /**
     * Lists all SifdaRutaCicloVida entities.
     *
     * @Route("/", name="sifda_rutaciclovida")
     * @Method("GET")
     * @Template()
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('MinsalsifdaBundle:SifdaRutaCicloVida')->findAll();

        return array(
            'entities' => $entities,
        );
    }
    /**
     * Creates a new SifdaRutaCicloVida entity.
     *
     * @Route("/create/{id}", name="sifda_rutaciclovida_create")
     * @Method("POST")
     * @Template("MinsalsifdaBundle:SifdaRutaCicloVida:new.html.twig")
     */
    public function createAction(Request $request, $id)
    {
        $entity = new SifdaRutaCicloVida();
        $idEtapa = NULL;
        $form = $this->createCreateForm($entity, $id);
        $form->handleRequest($request);
        
        $data = array(); 
        $parameters = $request->request->all();
        foreach($parameters as $p){
            $data = $p['etapaServicio'];
        } 

        $idusuario=  $this->getUser()->getId();
        $em = $this->getDoctrine()->getManager();
        $usuario= $em->getRepository('MinsalsifdaBundle:FosUserUser')->find($idusuario);
        
        if ($id != 0) {
           $em = $this->getDoctrine()->getManager();
           $idTipoServicio = $em->getRepository('MinsalsifdaBundle:SifdaTipoServicio')->find($id);
        }
        
        $dql = "SELECT SUM(e.peso) AS porcentaje "
               . "FROM MinsalsifdaBundle:SifdaRutaCicloVida e "
               . "WHERE e.idTipoServicio = :tiposervicio "
               . "AND e.idEtapa IS NULL ";
                
        $porcentaje= $em->createQuery($dql)
                             ->setParameter(':tiposervicio', $idTipoServicio)
                             ->getResult();
        $cantidad = $porcentaje[0]['porcentaje'];

        if($data){
            $this->establecerEtapasServicio($data, $idTipoServicio, $idEtapa);
            return $this->redirect($this->generateUrl('sifdatiposervicio_show', 
                                                       array('id' => $id))); 
        }
        
        return array(
            'entity'   => $entity,
            'tipo'     => $idTipoServicio,
            'usuario'  => $usuario,
            'cantidad' => $cantidad,
            'form'     => $form->createView(),
        );
    }

    /**
     * Creates a new SifdaRutaCicloVida entity.
     *
     * @Route("/createSubetapa/{id}", name="sifda_subetapa_create")
     * @Method("POST")
     * @Template("MinsalsifdaBundle:SifdaRutaCicloVida:new.html.twig")
     */
    public function createSubetapaAction(Request $request, $id)
    {
        $entity = new SifdaRutaCicloVida();
        $form = $this->createCreateFormSubetapa($entity, $id);
        $form->handleRequest($request);
        
        $data = array(); 
        $parameters = $request->request->all();
        foreach($parameters as $p){
            $data = $p['etapaServicio'];
        } 

        $idusuario=  $this->getUser()->getId();
        $em = $this->getDoctrine()->getManager();
        $usuario= $em->getRepository('MinsalsifdaBundle:FosUserUser')->find($idusuario);
        
        if ($id != 0) {
           $em = $this->getDoctrine()->getManager();
           $idEtapa = $em->getRepository('MinsalsifdaBundle:SifdaRutaCicloVida')->find($id);
        }
        $idTipoServicio = $idEtapa->getIdTipoServicio();

        if($data){
            $this->establecerEtapasServicio($data, $idTipoServicio, $idEtapa);
            return $this->redirect($this->generateUrl('sifda_rutaciclovida_show',
                                                       array('id' => $id)));
        }
        
        return array(
            'entity' => $entity,
            'usuario'  => $usuario,
            'etapa' => $idEtapa,
            'form'   => $form->createView(),
        );
    }
    
    /**
     * Creates a form to create a SifdaRutaCicloVida entity.
     *
     * @param SifdaRutaCicloVida $entity The entity
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(SifdaRutaCicloVida $entity, $id)
    {
        $form = $this->createForm(new SifdaRutaCicloVidaType(), $entity, array(
            'action' => $this->generateUrl('sifda_rutaciclovida_create', array('id' => $id)),
            'method' => 'POST',
        ));
        
        $form->add('etapaServicio', 'choice', array(
                    'label'         =>  'Etapas del tipo de servicio (Jerarquia - Etapa - Porcentaje)',
                    'multiple'  => true,
                    'expanded'  => false,
                    'attr' => array('style' => 'height:185px'),
                    'mapped'    => false
                ));
        $form->add('numEtapas', 'text', array(
                    'label'         =>  'Numero de etapas',
                    'mapped'    => false
                ));

        $form->add('submit', 'submit', array('label' => 'Registrar etapas'));
        return $form;
    }

    /**
     * Creates a form to create a SifdaRutaCicloVida entity.
     *
     * @param SifdaRutaCicloVida $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateFormSubetapa(SifdaRutaCicloVida $entity, $id)
    {
        $form = $this->createForm(new SifdaRutaCicloVidaType(), $entity, array(
            'action' => $this->generateUrl('sifda_subetapa_create', array('id' => $id)),
            'method' => 'POST',
        ));

        $form->add('etapaServicio', 'choice', array(
                    'label'         =>  'Subetapas del tipo de servicio (Jerarquia - Subetapa - Porcentaje)',
                    'multiple'  => true,
                    'expanded'  => false,
                    'attr' => array('style' => 'height:185px'),
                    'mapped'    => false
                ));
        $form->add('numEtapas', 'text', array(
                    'label'         =>  'Numero de subetapas',
                    'mapped'    => false
                ));    
        $form->add('submit', 'submit', array('label' => 'Registrar subetapas'));
        
        
        return $form;
    }
    
    /**
     * Displays a form to create a new SifdaRutaCicloVida entity.
     *
     * @Route("/new/{id}", name="sifda_rutaciclovida_new")
     * @Method("GET")
     * @Template()
     */
    public function newAction($id)
    {
        $entity = new SifdaRutaCicloVida();
        if ($id != 0) {
           $em = $this->getDoctrine()->getManager();
           $tipo = $em->getRepository('MinsalsifdaBundle:SifdaTipoServicio')->find($id);
        }    
        
        $dql = "SELECT SUM(e.peso) AS porcentaje "
               . "FROM MinsalsifdaBundle:SifdaRutaCicloVida e "
               . "WHERE e.idTipoServicio = :tiposervicio "
               . "AND e.idEtapa IS NULL ";
        
        $em = $this->getDoctrine()->getManager();
        $porcentaje= $em->createQuery($dql)
                             ->setParameter(':tiposervicio', $tipo)
                             ->getResult();
                     //ladybug_dump($porcentaje);
        $cantidad = $porcentaje[0]['porcentaje'];
        
        $idusuario=  $this->getUser()->getId();
        $usuario= $em->getRepository('MinsalsifdaBundle:FosUserUser')->find($idusuario);
        
        $form   = $this->createCreateForm($entity, $id);
        
        return array(
            'entity' => $entity,
            'tipo' => $tipo,
            'cantidad' => $cantidad,
            'usuario' => $usuario,
            'form'   => $form->createView(),
        );
    }

    /**
     * Displays a form to create a new SifdaRutaCicloVida entity.
     *
     * @Route("/new_subetapa/{id}", name="sifda_rutaciclovida_new_subetapa")
     * @Method("GET")
     * @Template()
     */
    public function newSubetapaAction($id)
    {
        $entity = new SifdaRutaCicloVida();
        if ($id != 0) {
           $em = $this->getDoctrine()->getManager();
           $etapa = $em->getRepository('MinsalsifdaBundle:SifdaRutaCicloVida')->find($id);
        }
        
        $dql = "SELECT SUM(e.peso) AS porcentaje "
               . "FROM MinsalsifdaBundle:SifdaRutaCicloVida e "
               . "WHERE e.idEtapa = :etapa ";
                
        $porcentaje= $em->createQuery($dql)
                             ->setParameter(':etapa', $etapa)
                             ->getResult();
        $cantidad = $porcentaje[0]['porcentaje'];
    
        $idusuario=  $this->getUser()->getId();
        $em = $this->getDoctrine()->getManager();
        $usuario= $em->getRepository('MinsalsifdaBundle:FosUserUser')->find($idusuario);
        
        $form   = $this->createCreateFormSubetapa($entity, $id);
        
        return array(
            'entity' => $entity,
            'etapa' => $etapa,
            'cantidad' => $cantidad,
            'usuario' => $usuario,
            'form'   => $form->createView(),
        );
    }
    
    /**
     * Finds and displays a SifdaRutaCicloVida entity.
     *
     * @Route("/{id}", name="sifda_rutaciclovida_show")
     * @Method("GET")
     * @Template()
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('MinsalsifdaBundle:SifdaRutaCicloVida')->find($id);
        $subetapas = $em->getRepository('MinsalsifdaBundle:SifdaRutaCicloVida')->obtenerSubetapas($id);
        
        if (!$entity) {
            throw $this->createNotFoundException('Unable to find SifdaRutaCicloVida entity.');
        }
        
        if ($id != 0) {
           $em = $this->getDoctrine()->getManager();
           //$tipo = $em->getRepository('MinsalsifdaBundle:SifdaTipoServicio')->find($id);
           $tipo = $entity->getIdTipoServicio();
        }
        
        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'tipo' => $tipo,
            'subetapas' => $subetapas,
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Displays a form to edit an existing SifdaRutaCicloVida entity.
     *
     * @Route("/{id}/edit", name="sifda_rutaciclovida_edit")
     * @Method("GET")
     * @Template()
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('MinsalsifdaBundle:SifdaRutaCicloVida')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find SifdaRutaCicloVida entity.');
        }
        
        if ($id != 0) {
           $em = $this->getDoctrine()->getManager();
           $tipo = $entity->getIdTipoServicio();
        }    
        
        
        $editForm = $this->createEditForm($entity);
        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'tipo' => $tipo,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
    * Creates a form to edit a SifdaRutaCicloVida entity.
    *
    * @param SifdaRutaCicloVida $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createEditForm(SifdaRutaCicloVida $entity)
    {
        $form = $this->createForm(new SifdaRutaCicloVidaType(), $entity, array(
            'action' => $this->generateUrl('sifda_rutaciclovida_update', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));

        $form->add('submit', 'submit', array('label' => 'Actualizar'));

        return $form;
    }
    /**
     * Edits an existing SifdaRutaCicloVida entity.
     *
     * @Route("/{id}", name="sifda_rutaciclovida_update")
     * @Method("PUT")
     * @Template("MinsalsifdaBundle:SifdaRutaCicloVida:edit.html.twig")
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('MinsalsifdaBundle:SifdaRutaCicloVida')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find SifdaRutaCicloVida entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $em->flush();

            return $this->redirect($this->generateUrl('sifda_rutaciclovida_edit', array('id' => $id)));
        }

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }
    /**
     * Deletes a SifdaRutaCicloVida entity.
     *
     * @Route("/{id}", name="sifda_rutaciclovida_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('MinsalsifdaBundle:SifdaRutaCicloVida')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find SifdaRutaCicloVida entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('sifda_rutaciclovida'));
    }

    /**
     * Creates a form to delete a SifdaRutaCicloVida entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('sifda_rutaciclovida_delete', array('id' => $id)))
            ->setMethod('DELETE')
            ->add('submit', 'submit', array('label' => 'Delete'))
            ->getForm()
        ;
    }
    
    /**
     * Creates a new SifdaRutaCicloVida entity.
     *
     * @Route("/subir/{id}", name="sifdaetapa_subir")
     */
    public function subirNivelEtapaAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $etapaActual = $em->getRepository('MinsalsifdaBundle:SifdaRutaCicloVida')->find($id);
        $etapaAnterior = $em->getRepository('MinsalsifdaBundle:SifdaRutaCicloVida')->find($id -1);
        
        $etapaActual->setJerarquia($etapaActual->getJerarquia() - 1);
        $em->merge($etapaActual);
        $em->flush();
        
        $etapaAnterior->setJerarquia($etapaAnterior->getJerarquia() + 1);
        $em->merge($etapaAnterior);
        $em->flush();
        
        return $this->redirect($this->generateUrl('sifdatiposervicio_show', 
                                                       array('id' => $etapaActual->getIdTipoServicio()
                                                                            ->getId()))); 
    }
    
    /**
     * Creates a new SifdaRutaCicloVida entity.
     *
     * @Route("/bajar/{id}", name="sifdaetapa_bajar")
     */
    public function bajarNivelEtapaAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $etapaActual = $em->getRepository('MinsalsifdaBundle:SifdaRutaCicloVida')->find($id);
        $etapaAnterior = $em->getRepository('MinsalsifdaBundle:SifdaRutaCicloVida')->find($id + 1);
        
        $etapaActual->setJerarquia($etapaActual->getJerarquia() + 1);
        $em->merge($etapaActual);
        $em->flush();
        
        $etapaAnterior->setJerarquia($etapaAnterior->getJerarquia() - 1);
        $em->merge($etapaAnterior);
        $em->flush();
        
        return $this->redirect($this->generateUrl('sifdatiposervicio_show', 
                                                       array('id' => $etapaActual->getIdTipoServicio()
                                                                            ->getId()))); 
    }
    

            /**
     * Metodo que sirve para establecer las fechas festivas del anio.
     *
     * @param \Doctrine\Common\Collections\ArrayCollection $data
     *
     */
    private function establecerEtapasServicio(array $data, 
                                                 \Minsal\sifdaBundle\Entity\SifdaTipoServicio $idTipoServicio,
                                                 SifdaRutaCicloVida $idEtapa)
    {
        $cant = count($data);
        $i = 0;
        foreach ($data as $etapaServicio)
        {
            $entity = new SifdaRutaCicloVida();
            $em = $this->getDoctrine()->getManager();
            $entity->setIdTipoServicio($idTipoServicio);
            
            $elem = explode(" - ", $etapaServicio);
            $jerarquia = $elem[0];
            $descripcion = $elem[1];
            $peso = $elem[2];
            
            if(count($elem) == 4){
                $referencia = $elem[3];
                $entity->setReferencia($referencia);
            }
            
            $entity->setDescripcion($descripcion);
            $entity->setJerarquia($jerarquia);
            $entity->setPeso($peso);
            $entity->setIdEtapa($idEtapa);
            
            if($i == $cant - 1) {
                $entity->setIgnorarSig(TRUE);
            } else {
                $entity->setIgnorarSig(FALSE);
            }

            $em->persist($entity);
            $em->flush();
            
            $i++;
        }    
    }
    
}
