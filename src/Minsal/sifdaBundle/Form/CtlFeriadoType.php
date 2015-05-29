<?php

namespace Minsal\sifdaBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class CtlFeriadoType extends AbstractType
{
        /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('fechaInicio','date',array('input'=>'datetime','widget'=>'single_text', 'required'  => false,
                  'label'=>'Fecha festiva','format'=>'yyyy-MM-dd','attr'=>array('class'=>'date')))
            ->add('fechaFin','date',array('input'=>'datetime','widget'=>'single_text',
                  'format'=>'yyyy-MM-dd','attr'=>array('class'=>'date')))
            ->add('fechaFestiva', 'choice', array(
                    'label'         =>  'Fechas',
                    'multiple'  => true,
                    'expanded'  => false,
                    'mapped'    => false
                ))  
            ->add('tipoFecha', 'choice', array(
                    'multiple'  => false,
                    'expanded'  => true,
                    'required'  => true,
                    'mapped'    => false,
                    'label'=>'Seleccione',
                    'choices'   => array(
                        'fe' => 'Fecha especifica',
                        'rf' => 'Rango de fecha'
                    )))    
        ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Minsal\sifdaBundle\Entity\CtlFeriado'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'minsal_sifdabundle_ctlferiado';
    }
}
