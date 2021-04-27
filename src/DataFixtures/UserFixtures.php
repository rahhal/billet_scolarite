<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserFixtures extends Fixture
{
    private $passwordEncoder;

    public function __construct(UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->passwordEncoder = $passwordEncoder;
    }

    public function load(ObjectManager $manager)
    {
        // $product = new Product();
        // $manager->persist($product);
$user = new User();
        $user->setEmail('admin@gmail.com');
        $user->setRoles(['ROLE_SUPER_ADMIN']);
        $user->setPassword($this->passwordEncoder->encodePassword($user, '123'));


        $user1 = new User();
        $user1->setEmail('user@gmail.com');
        $user1->setRoles(['ROLE_ADMIN']);
        $user1->setPassword($this->passwordEncoder->encodePassword($user1, '123'));

        $manager->persist($user);
        $manager->persist($user1);

        $manager->flush();
    }
}
