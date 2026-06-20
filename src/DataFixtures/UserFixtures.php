<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserFixtures extends Fixture
{
    private UserPasswordHasherInterface $passwordHasher;

    // L'interface de hachage est injectée automatiquement ici
    public function __construct(UserPasswordHasherInterface $passwordHasher)
    {
        $this->passwordHasher = $passwordHasher;
    }

    public function load(ObjectManager $manager): void
    {
        // 1. Création d'un utilisateur standard
        $user = new User();
        $user->setEmail('user@bibliotech.fr');
        $user->setRoles(['ROLE_USER']);
        $user->setFirstName('Alice');
        $user->setLastName('Bidon');
        
        // Hachage du mot de passe
        $hashedPassword = $this->passwordHasher->hashPassword($user, 'user123');
        $user->setPassword($hashedPassword);
        
        $manager->persist($user);

        $libraire = new User();
        $libraire->setEmail('libraire@bibliotech.fr');
        $libraire->setRoles(['ROLE_LIBRARIAN']);
        $libraire->setFirstName('Bob');
        $libraire->setLastName('Dylan');
        $libraire->setPassword($this->passwordHasher->hashPassword($libraire,'libraire123'));
        $manager->persist($libraire);



        // 2. Création d'un administrateur
        $admin = new User();
        $admin->setEmail('admin@bibliotech.fr');
        $admin->setRoles(['ROLE_ADMIN']); // Hérite de ROLE_USER via ta configuration role_hierarchy
        $admin->setFirstName('Neo');
        $admin->setLastName('Admin');
        // Hachage du mot de passe de l'admin
        $hashedPasswordAdmin = $this->passwordHasher->hashPassword($admin, 'admin123');
        $admin->setPassword($hashedPasswordAdmin);
        
        $manager->persist($admin);

       
        

        // Sauvegarde finale en base de données
        $manager->flush();
    }
}