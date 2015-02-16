<?php

namespace Minsal\sifdaBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class FosUserUserType extends AbstractType
{
        /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('username')
//            ->add('usernameCanonical')
            ->add('email')
//            ->add('emailCanonical')
            ->add('enabled')
//            ->add('salt')
            ->add('password','password')
//            ->add('lastLogin')
//            ->add('locked')
//            ->add('expired')
//            ->add('expiresAt')
//            ->add('confirmationToken')
//            ->add('passwordRequestedAt')
//            ->add('roles')
//            ->add('credentialsExpired')
//            ->add('credentialsExpireAt')
//            ->add('createdAt')
//            ->add('updatedAt')
//            ->add('dateOfBirth')
            ->add('firstname')
            ->add('lastname')
//            ->add('website')
//            ->add('biography')
//            ->add('gender')
//            ->add('locale')
//            ->add('timezone')
//            ->add('phone')
//            ->add('facebookUid')
//            ->add('facebookName')
//            ->add('facebookData')
//            ->add('twitterUid')
//            ->add('twitterName')
//            ->add('twitterData')
//            ->add('gplusUid')
//            ->add('gplusName')
//            ->add('gplusData')
//            ->add('token')
//            ->add('twoStepCode')
//            ->add('group')
        ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Minsal\sifdaBundle\Entity\FosUserUser'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'minsal_sifdabundle_fosuseruser';
    }
}
