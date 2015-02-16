<?php

namespace Minsal\sifdaBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Doctrine\ORM\EntityRepository;

class SifdaOrdenTrabajoType extends AbstractType
{
        /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('descripcion')
            ->add('fechaFinalizacion','date',array(
                  'input'  =>  'datetime',
                  'widget' =>  'single_text',
                  'format' =>  'yy-MM-dd',
                  'attr'   =>  array('class'=>'date')
            ))
            ->add('observacion')
            ->add('idPrioridad', 'entity', array(
                    'required'      =>  true,
                    'label'         =>  'Prioridad',    
                    'class'         =>  'MinsalsifdaBundle:CatalogoDetalle',
                    'query_builder' =>  function(EntityRepository $repositorio) {
                return $repositorio
                        ->createQueryBuilder('dcat')
                        ->where('dcat.idCatalogo = 3');
            }))
//            ->add('dependencia','entity', array(
//                    'mapped'=>false,
//                    'empty_value'=>'Seleccione una dependencia',
//                    'class'=>'MinsalsifdaBundle:CtlDependencia',
//                    'choices' => array()
//                ))
//            ->add('establecimiento', 'entity', array(
//                    'label'         =>  'Establecimiento',
//                    'empty_value'=>'Seleccione un establecimiento',
//                    'class'         =>  'MinsalsifdaBundle:CtlEstablecimiento',
//                    'mapped' => false
//                ))
            ->add('idEtapa', 'entity', array(
                    'label'         =>  'Etapa a realizar',
                    'class'         =>  'MinsalsifdaBundle:SifdaRutaCicloVida'))
        ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Minsal\sifdaBundle\Entity\SifdaOrdenTrabajo'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'minsal_sifdabundle_sifdaordentrabajo';
    }
}
