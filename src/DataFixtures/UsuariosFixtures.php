<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UsuariosFixtures extends Fixture
{

    public const USUARIO_ADMIN_REFERENCIA = 'user-admin';
    public const USUARIO_USER_REFERENCIA = 'user-user';

    private $userPasswordEncoder;

    public function __construct(UserPasswordEncoderInterface $userPasswordEncoder)
    {
        $this->userPasswordEncoder = $userPasswordEncoder;
    }

    public function load(ObjectManager $manager)
    {
        $usuario = new User();
        $usuario->setEmail('admin@admin.com');
        $usuario->setRoles(['ROLE_ADMIN']);
        $usuario->setIsVerified(true);
        $usuario->setPassword($this->userPasswordEncoder->encodePassword(
            $usuario,
            'admin'
        ));
        $manager->persist($usuario);
        $manager->flush();

        $this->addReference(self::USUARIO_ADMIN_REFERENCIA, $usuario);


        $usuario = new User();
        $usuario->setEmail('user@user.com');
        $usuario->setRoles(['ROLE_USER']);
        $usuario->setIsVerified(true);
        $usuario->setPassword($this->userPasswordEncoder->encodePassword(
            $usuario,
            'user'
        ));
        $manager->persist($usuario);
        $manager->flush();

        $this->addReference(self::USUARIO_USER_REFERENCIA, $usuario);
    }
}
