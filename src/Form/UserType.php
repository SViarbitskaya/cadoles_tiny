<?php

namespace App\Form;

use App\Entity\User;
use App\Entity\Group;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Security\Core\Role\RoleHierarchyInterface;

class UserType extends AbstractType
{

    public function __construct(RoleHierarchyInterface $roleHierarchy)
    {
        $this->roleHierarchy = $roleHierarchy;
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('email', EmailType::class, [
                'label' => 'Email',
            ])
            ->add('password', PasswordType::class, [
                'label' => 'Password',
            ])
            ->add('roles', ChoiceType::class, [
                'choices' => $this->getRolesChoices(),
                'multiple' => true,
                'expanded' => true,
                'label' => 'Roles',
            ])
            ->add('groups', EntityType::class, [
                'class' => Group::class,
                'choice_label' => 'name',  // Assuming 'name' is the property used as the group's display name
                'multiple' => true,
                'expanded' => true, // Displays as checkboxes if true, otherwise a multi-select dropdown
                'label' => 'Groups',
            ])
            ;
    }

    private function getRolesChoices(): array
    {
        // Get all roles from the role hierarchy
        $allRoles = $this->roleHierarchy->getReachableRoleNames(['ROLE_ADMIN']);
        
        // Generate a choices array with the role as both label and value
        $choices = [];
        foreach ($allRoles as $role) {
            // Set the label to be human-readable if needed (e.g., replacing underscores with spaces)
            $label = ucwords(strtolower(str_replace('_', ' ', str_replace('ROLE_', '', $role))));
            $choices[$label] = $role;
        }

        return $choices;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
