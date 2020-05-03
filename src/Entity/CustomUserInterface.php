<?php
/**
 * @package Mysupply_App
 * @author  Stenik Symfony Team <symfony@stenik.bg>
 */

namespace App\Entity;


use Symfony\Component\Security\Core\User\UserInterface;

/**
 * Interface CustomUserInterface
 * @package App\Entity
 * @author  Lubo Grozdanov <grozdanov.lubo@gmail.com>
 */
interface CustomUserInterface
{
    public function getUser(): ?UserInterface;
}