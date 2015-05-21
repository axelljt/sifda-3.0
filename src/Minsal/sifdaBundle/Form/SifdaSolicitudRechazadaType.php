<?php

namespace Minsal\sifdaBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Doctrine\ORM\EntityRepository;

class SifdaSolicitudRechazadaType extends AbstractType
{
        /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('idRazonRechazo', 'entity', array(
                    'required'      =>  true,
                    'label'         =>  'Seleccione razon de rechazo',    
                    'class'         =>  'MinsalsifdaBundle:CatalogoDetalle',
                    'query_builder' =>  function(EntityRepository $repositorio) {
                return $repositorio
                        ->createQueryBuilder('dcat')
                        ->where('dcat.idCatalogo = 4');
            } 
            ));
//            ->add('idSolicitudServicio')
        
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Minsal\sifdaBundle\Entity\SifdaSolicitudRechazada'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'minsal_sifdabundle_sifdasolicitudrechazada';
    }
}
