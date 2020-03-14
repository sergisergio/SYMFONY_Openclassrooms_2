<?php
/**
 * Created by PhpStorm.
 * User: leazygomalas
 * Date: 20/07/2019
 * Time: 22:37
 */

namespace App\DataFixtures;

use App\Entity\Skill;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class SkillFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $skill1 = new Skill();
        $skill1->setName('Développement web');
        $manager->persist($skill1);
        $manager->flush();

        $this->addReference('skill1', $skill1);
    }
}