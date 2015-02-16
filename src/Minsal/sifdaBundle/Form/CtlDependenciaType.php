<?php

namespace Minsal\sifdaBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class CtlDependenciaType extends AbstractType
{
        /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('idTipoDependencia','entity',
                  array('label'=>'Tipo de dependencia *',
                        'empty_value'=>'Seleccione un tipo',
                        'class'=>'MinsalsifdaBundle:CtlTipoDependencia',
                        'property'=>'nombre', 'required'=>true,
                      )
                  )
            ->add('nombre','choice',
                  array('label'=>'Dependencia *',
                        'empty_value'=>'Seleccione una dependencia'
                      )
                  )    
            //->add('nombre')
            
        ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Minsal\sifdaBundle\Entity\CtlDependencia'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'minsal_sifdabundle_ctldependencia';
    }
}
