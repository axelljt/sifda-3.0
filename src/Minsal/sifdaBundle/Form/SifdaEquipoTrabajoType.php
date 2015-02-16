<?php

namespace Minsal\sifdaBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class SifdaEquipoTrabajoType extends AbstractType
{
        /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('idEmpleado', 'entity', array(
                'label'     => 'Responsable',
                'class'     => 'MinsalsifdaBundle:CtlEmpleado',
                ))
            ->add('equipoTrabajo', 'entity', array(
                    'label'         =>  'Seleccione equipo de trabajo',
                    'class'         =>  'MinsalsifdaBundle:CtlEmpleado',
                    'multiple'  => true,
                    'expanded'  => true,
                    'required'  => false,
                    'mapped' => false
                ))    
                ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Minsal\sifdaBundle\Entity\SifdaEquipoTrabajo'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'minsal_sifdabundle_sifdaequipotrabajo';
    }
}
