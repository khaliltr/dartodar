<?php

namespace App\Controller;

use App\Entity\User;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\Field;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class UserCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return User::class;
    }


    public function configureFields(string $pageName): iterable
    {

        return [
            Field::new('email'),
            Field::new('password'),
            Field::new('adresse'),
            Field::new('tel'),
            ChoiceField::new('roles')->setChoices([
                'User' => "ROLE_USER",
                'Admin' => "ROLE_ADMIN",
            ])->allowMultipleChoices(),
        ];
    }

}
