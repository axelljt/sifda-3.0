<?php

namespace Minsal\sifdaBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Doctrine\ORM\EntityRepository;

class SifdaServicioPrioridadType extends AbstractType
{
        /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('idTipoServicio')
            ->add('establecimiento', 'entity', array(
                    'label'         =>  'Establecimiento',
                    'empty_value'=>'Seleccione un establecimiento',
                    'class'         =>  'MinsalsifdaBundle:CtlEstablecimiento',
                    'mapped' => false
                ))
            ->add('dependencia','entity', array(
                    'mapped'=>false,
                    'empty_value'=>'Seleccione una dependencia',
                    'class'=>'MinsalsifdaBundle:CtlDependencia',
                    'choices' => array()
                ))    
            ->add('idPrioridad', 'entity', array(
                    'required'      =>  true,
                    'label'         =>  'Prioridad',    
                    'empty_value'=>'Seleccione una prioridad',
                    'class'         =>  'MinsalsifdaBundle:CatalogoDetalle',
                    'query_builder' =>  function(EntityRepository $repositorio) {
                return $repositorio
                        ->createQueryBuilder('dcat')
                        ->where('dcat.idCatalogo = 3');
            }))
            ->add('servicioPrioridad', 'choice', array(
                    'label'         =>  'Asignacion prioridad',
                    'multiple'  => true,
                    'expanded'  => false,
                    'attr' => array('style' => 'height:150px'),
                    'mapped'    => false
                ))
        ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Minsal\sifdaBundle\Entity\SifdaServicioPrioridad'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'minsal_sifdabundle_sifdaservicioprioridad';
    }
}
