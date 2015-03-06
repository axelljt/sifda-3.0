<?php

namespace Minsal\sifdaBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class SifdaTipoRecursoType extends AbstractType
{
        /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nombre')
            ->add('descripcion');
//            ->add('rrhh','choice',array('multiple'  => false,
//                    'expanded'  => true, 'required' =>false, 'choices'   => array(
//                        's' => 'Si',
//                        'n' => 'No'))
//                    )
//        
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Minsal\sifdaBundle\Entity\SifdaTipoRecurso'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'minsal_sifdabundle_sifdatiporecurso';
    }
}
