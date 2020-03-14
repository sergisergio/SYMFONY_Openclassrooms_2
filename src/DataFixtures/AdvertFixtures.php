<?php
/**
 * Created by PhpStorm.
 * User: leazygomalas
 * Date: 20/07/2019
 * Time: 13:04
 */

namespace App\DataFixtures;

use App\Entity\Advert;
use App\Entity\Image;
use App\Entity\Skill;
use App\Entity\AdvertSkill;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

class AdvertFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $image = new Image();
        $image->setUrl('http://sdz-upload.s3.amazonaws.com/prod/upload/job-de-reve.jpg');
        $image->setAlt('Job de rêve');


        $advert1 = new Advert();
        $advert1->setTitle('Annonce 1');
        $advert1->setDate(new \DateTime());
        $advert1->setSlug('Annonce1');
        $advert1->setAuthor('Philippe');
        $advert1->setContent('Contenu1');
        $advert1->setImage($image);
        $advert1->addCategory($this->getReference('cat1'));
        $advert1->addCategory($this->getReference('cat2'));
        $manager->persist($advert1);

        $advert2 = new Advert();
        $advert2->setTitle('Annonce 2');
        $advert2->setDate(new \DateTime());
        $advert2->setSlug('Annonce2');
        $advert2->setAuthor('Philippe');
        $advert2->setContent('Contenu2');
        $advert2->addCategory($this->getReference('cat2'));
        $manager->persist($advert2);

        $advert3 = new Advert();
        $advert3->setTitle('Annonce 3');
        $advert3->setDate(\DateTime::createFromFormat('Y-m-d', "2018-09-09"));
        $advert3->setSlug('Annonce3');
        $advert3->setAuthor('Philippe');
        $advert3->setContent('Contenu3');
        $advert3->addCategory($this->getReference('cat3'));
        $manager->persist($advert3);

        $advert4 = new Advert();
        $advert4->setTitle('Annonce 4');
        $advert4->setDate(\DateTime::createFromFormat('Y-m-d', "2018-09-09"));
        $advert4->setSlug('Annonce4');
        $advert4->setAuthor('Philippe');
        $advert4->setContent('Contenu4');
        $advert4->addCategory($this->getReference('cat4'));
        $manager->persist($advert4);

        $advert5 = new Advert();
        $advert5->setTitle('Annonce 5');
        $advert5->setDate(\DateTime::createFromFormat('Y-m-d', "2018-09-09"));
        $advert5->setSlug('Annonce5');
        $advert5->setAuthor('Philippe');
        $advert5->setContent('Contenu5');
        $advert5->addCategory($this->getReference('cat5'));
        $manager->persist($advert5);

        $manager->flush();

        $this->addReference('advert1', $advert1);
        $this->addReference('advert2', $advert2);
        $this->addReference('advert3', $advert3);
        $this->addReference('advert4', $advert4);
        $this->addReference('advert5', $advert5);

    }

    public function getDependencies()
    {
        return [CategoryFixtures::class];
    }
}