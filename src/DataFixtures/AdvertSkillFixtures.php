<?php
/**
 * Created by PhpStorm.
 * User: leazygomalas
 * Date: 20/07/2019
 * Time: 22:30
 */

namespace App\DataFixtures;


use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use App\Entity\AdvertSkill;

class AdvertSkillFixtures extends Fixture implements DependentFixtureInterface
{

    /**
     * This method must return an array of fixtures classes
     * on which the implementing class depends on
     *
     * @return array
     */
    public function getDependencies()
    {
        return [AdvertFixtures::class, SkillFixtures::class];
    }

    /**
     * Load data fixtures with the passed EntityManager
     *
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        $ad1 = new AdvertSkill();
        $ad1->setLevel('Expert');
        $ad1->setAdvert($this->getReference('advert1'));
        $ad1->setSkill($this->getReference('skill1'));
        $manager->persist($ad1);
        $manager->flush();

        $this->addReference('ad1', $ad1);
    }
}