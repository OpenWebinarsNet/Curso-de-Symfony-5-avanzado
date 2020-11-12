<?php

namespace App\Security;

use App\Entity\Tarea;
use App\Entity\User;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use \Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Security;

class CRUDVoter extends Voter
{
    const CREAR = 'crear';
    const VER = 'ver';
    const EDITAR = 'editar';
    const ELIMINAR = 'eliminar';

    const ENTIDADES_AFECTADAS = [
        Tarea::class,
    ];

    private $security;

    public function __construct(Security $security)
    {
        $this->security = $security;
    }

    protected function supports(string $attribute, $subject)
    {
        if (!in_array($attribute, [self::CREAR, self::VER, self::EDITAR, self::ELIMINAR]))
            return false;

        if (!in_array(get_class($subject), self::ENTIDADES_AFECTADAS))
            return false;

        return true;
    }

    protected function voteOnAttribute(string $attribute, $subject, TokenInterface $token)
    {
        $user = $token->getUser();

        if (!$user)
            return false;

        switch ($attribute) {
            case self::CREAR:
                return $this->puedeCrear($subject, $user);
                break;
            case self::VER:
                return $this->puedeVer($subject, $user);
                break;
            case self::EDITAR:
                return $this->puedeEditar($subject, $user);
                break;
            case self::ELIMINAR:
                return $this->puedeEliminar($subject, $user);
                break;
        }
    }

    private function puedeCrear($subject, User $usuario)
    {
        if ($this->security->isGranted('ROLE_USER') || $this->security->isGranted('ROLE_ADMIN')) {
            return true;
        }
    }

    private function puedeVer($subject, User $usuario)
    {
        return $this->puedeEliminar($subject, $usuario);
    }

    private function puedeEditar($subject, User $usuario)
    {
        return $this->puedeEliminar($subject, $usuario);
    }

    private function puedeEliminar($subject, User $usuario)
    {
        return $usuario === $subject->getUsuario();
    }
}
