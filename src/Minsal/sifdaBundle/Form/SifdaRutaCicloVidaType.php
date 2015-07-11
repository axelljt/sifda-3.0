<?php

namespace Minsal\sifdaBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class SifdaRutaCicloVidaType extends AbstractType
{
        /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('descripcion', 'text', array(
                    'label'         =>  'Nombre',
                ))
            ->add('referencia', 'text', array(
                    'label'         =>  'Referencia (Opcional)',
                    'required'      =>  false,
                ))
            ->add('jerarquia')
            //->add('ignorarSig')
            ->add('peso', 'integer', array(
                    'label'         =>  'Porcentaje (%)',
                ))
//            ->add('etapaServicio', 'choice', array(
//                    'label'         =>  'Etapas del tipo de servicio (Jerarquia - Etapa - Porcentaje)',
//                    'multiple'  => true,
//                    'expanded'  => false,
//                    'attr' => array('style' => 'height:185px'),
//                    'mapped'    => false
//                ))
//            ->add('numEtapas', 'text', array(
//                    'label'         =>  'Numero de etapas',
//                    'mapped'    => false
//                ))    
            //->add('idTipoServicio')
            //->add('idEtapa')
        ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Minsal\sifdaBundle\Entity\SifdaRutaCicloVida'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'minsal_sifdabundle_sifdarutaciclovida';
    }
}
