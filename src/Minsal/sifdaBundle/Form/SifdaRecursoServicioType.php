<?php

namespace Minsal\sifdaBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class SifdaRecursoServicioType extends AbstractType
{
        /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('cantidad')
//            ->add('costoTotal')
//            ->add('idInformeOrdenTrabajo', 'entity', array(
//                    'required'      =>  true,
//                    'label'         =>  'Informe asociado',    
//                    'class'         =>  'MinsalsifdaBundle:SifdaInformeOrdenTrabajo'))
            ->add('idTipoRecursoDependencia', 'entity', array(
                    'required'      =>  true,
                    'label'         =>  'Recurso utilizado',    
                    'class'         =>  'MinsalsifdaBundle:SifdaTipoRecursoDependencia'))
        ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Minsal\sifdaBundle\Entity\SifdaRecursoServicio'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'minsal_sifdabundle_sifdarecursoservicio';
    }
}
