<?php
/**
 * @package Mysupply_App
 * @author  Lubo Grozdanov <grozdanov.lubo@gmail.com>
 */

namespace App\DataFixtures;

use App\Entity\Category;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

/**
 * Class AppFixtures
 * @author Lubo Grozdanov <grozdanov.lubo@gmail.com>
 */
class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $categories = ['computers', 'smartphones', 'electronics'];
        foreach ($categories as $catName) {
            $category = new Category();
            $category->setName($catName);
            $manager->persist($category);
        }

        $manager->flush();
    }
}
