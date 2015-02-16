<?php

namespace Minsal\sifdaBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Minsal\sifdaBundle\Entity\FosUserUser;
use Minsal\sifdaBundle\Form\FosUserUserType;
use Symfony\Component\Security\Core\Encoder\MessageDigestPasswordEncoder;
use Symfony\Component\Security\Core\SecurityContext;
use Symfony\Component\Security\Core\Util\SecureRandom;

/**
 * FosUserUser controller.
 *
 * @Route("/sifda/fos_user_user")
 */
class FosUserUserController extends Controller
{

    
    /**
     * Lists all FosUserUser entities.
     *
     * @Route("/login", name="sifda_fos_user_user_login")
     * @Method("GET")
     * @Template()
     */
    public function loginAction(Request $request)
    {
        
        $session = $request->getSession();
        
            if ($request->attributes->has(SecurityContext::AUTHENTICATION_ERROR)) 
                {
                    $error = $request->attributes->get(SecurityContext::AUTHENTICATION_ERROR);
                    $session->remove(SecurityContext::AUTHENTICATION_ERROR);
                } 
            else 
                {
                    $error = $request->getSession()->get(SecurityContext::AUTHENTICATION_ERROR);
                    $session->remove(SecurityContext::AUTHENTICATION_ERROR);
                }

            return array('last_username' => $request->getSession()->get(SecurityContext::LAST_USERNAME),'errors' => $error,
        );
    }
    
    /**
* @Route("/login_check", name="sifda_fos_user_user_security_check")
*/
public function securityCheckAction()
{
return $this->redirect($this->generateUrl('sifda_fos_user_user'));
// The security layer will intercept this request
}

/**
* @Route("/logout", name="sifda_fos_user_user_logout")
*/
public function logoutAction()
{
// The security layer will intercept this request
}
    
    




    
    
    /**
     * Lists all FosUserUser entities.
     *
     * @Route("/", name="sifda_fos_user_user")
     * @Method("GET")
     * @Template()
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('MinsalsifdaBundle:FosUserUser')->findAll();

        return array(
            'entities' => $entities,
        );
    }
    /**
     * Creates a new FosUserUser entity.
     *
     * @Route("/", name="sifda_fos_user_user_create")
     * @Method("POST")
     * @Template("MinsalsifdaBundle:FosUserUser:new.html.twig")
     */
    public function createAction(Request $request)
    {
        $entity = new FosUserUser();
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);
        
    
        $entity->setUsernameCanonical($entity->getUsername());
        $entity->setEmailCanonical($entity->getEmail());
        $entity->setSalt(md5(time()));
        $encoder=new MessageDigestPasswordEncoder('sha512', true, 10);
        $password=$encoder->encodePassword($entity->getPassword(), $entity->getSalt());
        $entity->setPassword($password);
//        $entity->setLocked('false');
//        $entity->setExpired('false');
//        $entity->setRoles('a:0:{}');
//        $entity->setCredentialsExpireAt('false');
//        $entity->setCreatedAt((new \DateTime())->format('Y-m-d H:i:s'));
//        $entity->setUpdatedAt((new \DateTime())->format('Y-m-d H:i:s'));
//        
            ladybug_dump($entity);
          
        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('sifda_fos_user_user_show', array('id' => $entity->getId())));
        }

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Creates a form to create a FosUserUser entity.
     *
     * @param FosUserUser $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(FosUserUser $entity)
    {
        $form = $this->createForm(new FosUserUserType(), $entity, array(
            'action' => $this->generateUrl('sifda_fos_user_user_create'),
            'method' => 'POST',
        ));

        $form->add('submit', 'submit', array('label' => 'Create'));

        return $form;
    }

    /**
     * Displays a form to create a new FosUserUser entity.
     *
     * @Route("/new", name="sifda_fos_user_user_new")
     * @Method("GET")
     * @Template()
     */
    public function newAction()
    {
        $entity = new FosUserUser();
        $form   = $this->createCreateForm($entity);

         
        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
            
        );
    }

    /**
     * Finds and displays a FosUserUser entity.
     *
     * @Route("/{id}", name="sifda_fos_user_user_show")
     * @Method("GET")
     * @Template()
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('MinsalsifdaBundle:FosUserUser')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find FosUserUser entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Displays a form to edit an existing FosUserUser entity.
     *
     * @Route("/{id}/edit", name="sifda_fos_user_user_edit")
     * @Method("GET")
     * @Template()
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('MinsalsifdaBundle:FosUserUser')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find FosUserUser entity.');
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
    * Creates a form to edit a FosUserUser entity.
    *
    * @param FosUserUser $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createEditForm(FosUserUser $entity)
    {
        $form = $this->createForm(new FosUserUserType(), $entity, array(
            'action' => $this->generateUrl('sifda_fos_user_user_update', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));

        $form->add('submit', 'submit', array('label' => 'Update'));

        return $form;
    }
    /**
     * Edits an existing FosUserUser entity.
     *
     * @Route("/{id}", name="sifda_fos_user_user_update")
     * @Method("PUT")
     * @Template("MinsalsifdaBundle:FosUserUser:edit.html.twig")
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('MinsalsifdaBundle:FosUserUser')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find FosUserUser entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $em->flush();

            return $this->redirect($this->generateUrl('sifda_fos_user_user_edit', array('id' => $id)));
        }

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }
    /**
     * Deletes a FosUserUser entity.
     *
     * @Route("/{id}", name="sifda_fos_user_user_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('MinsalsifdaBundle:FosUserUser')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find FosUserUser entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('sifda_fos_user_user'));
    }

    /**
     * Creates a form to delete a FosUserUser entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('sifda_fos_user_user_delete', array('id' => $id)))
            ->setMethod('DELETE')
            ->add('submit', 'submit', array('label' => 'Delete'))
            ->getForm()
        ;
    }
}
