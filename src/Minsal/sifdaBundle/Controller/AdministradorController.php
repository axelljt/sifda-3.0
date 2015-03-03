<?php

namespace Minsal\sifdaBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Minsal\sifdaBundle\Entity\SifdaSolicitudServicio;
use Minsal\sifdaBundle\Form\SifdaSolicitudServicioType;
use Doctrine\ORM\Query\ResultSetMapping;
use Doctrine\ORM\EntityRepository;
//use Minsal\sifdaBundle\Entity\Catalogo;
//use Minsal\sifdaBundle\Form\CatalogoType;

/**
 * Catalogo controller.
 *
 * @Route("/sifda")
 */
class AdministradorController extends Controller
{

    
     /**
     * Lists all SidplaLineaEstrategica entities.
     *
     * @Route("/responsable", name="sifda_responsable")
     * @Method("GET")
     * @Template()
     */
    public function indexAction()
    {
        $idusuario=$this->getUser()->getId();
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('MinsalsifdaBundle:SifdaSolicitudServicio')->findAll();
        $usuario= $em->getRepository('MinsalsifdaBundle:FosUserUser')->find($idusuario);

         $estado=1;
        
        $solicitudes = $em->getRepository('MinsalsifdaBundle:SifdaSolicitudServicio')->ContarSolicitudesIngresadas($estado);
        $solPropiaIngresado = $em->getRepository('MinsalsifdaBundle:SifdaSolicitudServicio')->findBy(['idDependenciaEstablecimiento'=>$usuario->getIdDependenciaEstablecimiento(),'idEstado'=>1]);
        $nSol = count($solPropiaIngresado);
        if(!$solicitudes)
             {
                throw $this->createNotFoundException('Unable to find SifdaSolicitudServicio entity.');
             }
          $valor=  array_shift($solicitudes);
        
        return array(
            'entities' => $entities,
            'usuario'=>$usuario,
            'dependencias'=>$nSol
        );
    }

    /**
     * Lists all Catalogo entities.
     *
     * @Route("/responsable/index", name="sifda_administrador2")
     * @Method("GET")
     * @Template()
     */
    public function index3Action()
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('MinsalsifdaBundle:SifdaSolicitudServicio')->findAll();

        $estado=1;
        
        $solicitudes = $em->getRepository('MinsalsifdaBundle:SifdaSolicitudServicio')->ContarSolicitudesIngresadas($estado);
//         $solicitudes2=  implode("",$solicitudes);
//         ladybug_dump($solicitudes);
         if(!$solicitudes)
             {
                throw $this->createNotFoundException('Unable to find SifdaSolicitudServicio entity.');
             }
            
                     
       $valor=  array_shift($solicitudes);
             
        return array(
            'entities' => $entities,
            'dependencias'=>$valor
        );
        //return $this->render('MinsalsifdaBundle:Administrador:index.html.twig');
        
    }
    /**
     * Creates a new Catalogo entity.
     *
     * @Route("/", name="sifda_catalogo_create")
     * @Method("POST")
     * @Template("MinsalsifdaBundle:Catalogo:new.html.twig")
     */
    public function createAction(Request $request)
    {
        $entity = new Catalogo();
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('sifda_catalogo_show', array('id' => $entity->getId())));
        }

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Creates a form to create a Catalogo entity.
     *
     * @param Catalogo $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(Catalogo $entity)
    {
        $form = $this->createForm(new CatalogoType(), $entity, array(
            'action' => $this->generateUrl('sifda_catalogo_create'),
            'method' => 'POST',
        ));

        $form->add('submit', 'submit', array('label' => 'Create'));

        return $form;
    }

    /**
     * Displays a form to create a new Catalogo entity.
     *
     * @Route("/responsable/new", name="sifda_catalogo_new")
     * @Method("GET")
     * @Template()
     */
    public function newAction()
    {
        $entity = new Catalogo();
        $form   = $this->createCreateForm($entity);

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Finds and displays a Catalogo entity.
     *
     * @Route("/{id}", name="sifda_catalogo_show")
     * @Method("GET")
     * @Template()
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('MinsalsifdaBundle:Catalogo')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Catalogo entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Displays a form to edit an existing Catalogo entity.
     *
     * @Route("/{id}/edit", name="sifda_catalogo_edit")
     * @Method("GET")
     * @Template()
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('MinsalsifdaBundle:Catalogo')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Catalogo entity.');
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
    * Creates a form to edit a Catalogo entity.
    *
    * @param Catalogo $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createEditForm(Catalogo $entity)
    {
        $form = $this->createForm(new CatalogoType(), $entity, array(
            'action' => $this->generateUrl('sifda_catalogo_update', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));

        $form->add('submit', 'submit', array('label' => 'Update'));

        return $form;
    }
    /**
     * Edits an existing Catalogo entity.
     *
     * @Route("/{id}", name="sifda_catalogo_update")
     * @Method("PUT")
     * @Template("MinsalsifdaBundle:Catalogo:edit.html.twig")
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('MinsalsifdaBundle:Catalogo')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Catalogo entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $em->flush();

            return $this->redirect($this->generateUrl('sifda_catalogo_edit', array('id' => $id)));
        }

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }
    /**
     * Deletes a Catalogo entity.
     *
     * @Route("/{id}", name="sifda_catalogo_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('MinsalsifdaBundle:Catalogo')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Catalogo entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('sifda_catalogo'));
    }

    /**
     * Creates a form to delete a Catalogo entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('sifda_catalogo_delete', array('id' => $id)))
            ->setMethod('DELETE')
            ->add('submit', 'submit', array('label' => 'Delete'))
            ->getForm()
        ;
    }
    
   /**
     * Lists all SidplaLineaEstrategica entities.
     *
     * @Route("/responsable/cargarPao", name="sifda_responsable_cargar_pao")
     * @Method("GET")
     * @Template()
     */
    public function PaoAction() {
        $em = $this->getDoctrine()->getEntityManager();
        $user = $em->getRepository('MinsalsifdaBundle:FosUserUser')->find($this->getUser()->getId());
        
        $rsm = new ResultSetMapping();
        $rsm->addScalarResult('resultado','resultado');
        
        $sql = "SELECT anios_sin_cargar() AS resultado";
        $query = $em->createNativeQuery($sql, $rsm);
//        $query ->setParameter(1, $anio);
        $resultados = $query->getResult();
        $bool = null;

        return $this->render('MinsalsifdaBundle:Administrador:PAO.html.twig',array('resultados'=>$resultados,'user' => $user));
    }
    
    /**
     * Lists all SidplaLineaEstrategica entities.
     *
     * @Route("/tecnico/index", name="sifda_tecnico")
     * @Method("GET")
     * @Template()
     */
    public function tecnicoAction()
    {
        $idusuario=  $this->getUser()->getId();
        $rsm = new ResultSetMapping();
        $em = $this->getDoctrine()->getManager();
        $usuario= $em->getRepository('MinsalsifdaBundle:FosUserUser')->find($idusuario);
        
        $fechaActual = new \DateTime();

        $sql = "select count(ot.id) cantidad
                from sifda_equipo_trabajo eq
                inner join ctl_empleado emp on eq.id_empleado = emp.id
                inner join sifda_orden_trabajo ot on eq.id_orden_trabajo = ot.id
                where emp.id = ?
                and to_char(ot.fecha_creacion, 'YYYY-MM-DD') = ?";
        
        $rsm->addScalarResult('cantidad','cantidad');
        $query = $this->getDoctrine()->getEntityManager();
        
        $cantidad = $query->createNativeQuery($sql, $rsm)
                    ->setParameter(1, $usuario->getIdEmpleado()->getId())
                    ->setParameter(2, $fechaActual->format('Y-m-d'))
                    ->getResult();
        
        if(!$cantidad)
             {
                throw $this->createNotFoundException('Unable to find CtlEmpleado entity.');
             }
        
        return array(
            'usuario'=>$usuario,
            'numOrdenAsignada' => $cantidad[0]['cantidad']
        );
    }
    
    
    

}
