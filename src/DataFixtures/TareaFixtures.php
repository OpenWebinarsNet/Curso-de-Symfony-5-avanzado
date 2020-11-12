<?php

namespace App\DataFixtures;

use App\Entity\Tarea;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class TareaFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        for ($i = 1; $i < 20; $i++) {
            $tarea = new Tarea();
            $tarea->setDescripcion("Tarea fixtures $i");
            $tarea->setFinalizada(0);
            $tarea->setUsuario($this->getReference(UsuariosFixtures::USUARIO_USER_REFERENCIA));
            $manager->persist($tarea);
        }

        for ($i = 1; $i < 20; $i++) {
            $tarea = new Tarea();
            $tarea->setDescripcion("Tarea fixtures $i");
            $tarea->setFinalizada(0);
            $tarea->setUsuario($this->getReference(UsuariosFixtures::USUARIO_ADMIN_REFERENCIA));
            $manager->persist($tarea);
        }
       
        $manager->flush();
    }

    public function getDependencies()
    {
        return [
            UsuariosFixtures::class,
        ];
    }
}
