<?php
/**
 * Created by PhpStorm.
 * User: leazygomalas
 * Date: 20/07/2019
 * Time: 14:27
 */

namespace App\Service;

use App\Entity\Advert;
use App\Entity\AdvertSkill;
use Doctrine\ORM\EntityManagerInterface;

class PurgerAdvert
{
    public function __construct(EntityManagerInterface $em) {
        $this->em = $em;
    }

    public function purge($days)
    {
        $listAdverts = $this->em->getRepository(Advert::class)->findAdvertToPurge(new \Datetime('-'.$days.' day'));

        foreach($listAdverts as $advert)
        {
            $advertSkills = $this->em->getRepository(AdvertSkill::class)->findByAdvert($advert);
            foreach ($advertSkills as $advertSkill)
            {
                $this->em->remove($advertSkill);
            }
            $this->em->remove($advert);
        }
        $this->em->flush();
    }
}