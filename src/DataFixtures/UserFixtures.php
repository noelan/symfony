<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use App\Entity\User;



class UserFixtures extends Fixture
{

	 public function __construct(UserPasswordEncoderInterface $passwordEncoder)
      {
          $this->passwordEncoder = $passwordEncoder;
      }

    public function load(ObjectManager $manager)
    {
        $author = new User();
        $author->setEmail('author@monsite.com');
        $author->setRoles(['ROLE_AUTHOR']);
        $author->setPassword($this->passwordEncoder->encodePassword(
            $author,
            'authorpassword'
        ));

        $manager->persist($author);
        $this->addReference('author', $author);

        // Création d’un utilisateur de type “administrateur”
        $admin = new User();
        $admin->setEmail('admin@monsite.com');
        $admin->setRoles(['ROLE_ADMIN','ROLE_AUTHOR']);
        $admin->setPassword($this->passwordEncoder->encodePassword(
            $admin,
            'adminpassword'
        ));

        $manager->persist($admin);
        $this->addReference('admin', $admin);

        // Sauvegarde des 2 nouveaux utilisateurs :
        $manager->flush();
    }

    


}
