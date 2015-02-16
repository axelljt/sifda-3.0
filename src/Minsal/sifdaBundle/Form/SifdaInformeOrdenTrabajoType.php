<?php

namespace Minsal\sifdaBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class SifdaInformeOrdenTrabajoType extends AbstractType
{
        /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('detalle', null, array('label'=>'Detalle del informe'))
            ->add('fechaRealizacion','date',array(
                  'label'=>'Fecha de realizacion',
                  'input'  =>  'datetime',
                  'widget' =>  'single_text',
                  'format' =>  'yy-MM-dd',
                  'attr'   =>  array('class'=>'date')))
            ->add('terminado', 'choice', array(
                    'multiple'  => false,
                    'expanded'  => true,
                    'required'  => true,
                    'label'=>'Orden de trabajo finalizada',
                    'choices'   => array(
                        's' => 'Si',
                        'n' => 'No'
                    )))
            //->add('idSubactividad')
        ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Minsal\sifdaBundle\Entity\SifdaInformeOrdenTrabajo'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'minsal_sifdabundle_sifdainformeordentrabajo';
    }
}
