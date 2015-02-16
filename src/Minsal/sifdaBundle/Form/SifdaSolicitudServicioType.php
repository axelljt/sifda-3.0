<?php

namespace Minsal\sifdaBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class SifdaSolicitudServicioType extends AbstractType
{
        /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('descripcion','textarea', array('label'=>'Descripcion *'))
            ->add('fechaRequiere','date',array('input'=>'datetime','widget'=>'single_text',
                  'format'=>'yyyy-MM-dd','attr'=>array('class'=>'date')))
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
            ->add('idTipoServicio', 'entity', array(
                    'label'         =>  'Tipo de servicio',
                    'empty_value'=>'Seleccione un tipo de servicio',
                    'class'         =>  'MinsalsifdaBundle:SifdaTipoServicio',
                    'mapped'        => false,
                    'choices' => array()
                ))    
        ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Minsal\sifdaBundle\Entity\SifdaSolicitudServicio'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'minsal_sifdabundle_sifdasolicitudservicio';
    }
}
