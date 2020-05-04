<?php
/**
 * @package Mysupply_App
 * @author  Lubo Grozdanov <grozdanov.lubo@gmail.com>
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