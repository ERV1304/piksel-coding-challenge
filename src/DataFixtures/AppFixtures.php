<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Entity\Studio;
use App\Entity\Episode;
use App\Entity\Royalty;
use App\Entity\Rightsowner;
use App\Entity\Viewing;
use App\Entity\Customer;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {

        $data = file_get_contents("data/studios.json");
        $array = json_decode($data, true);

        foreach ($array as $studios) {
            foreach ($studios as $studio) {
                $studioObj = new Studio();
                $studioObj->setId($studio['id']);
                $studioObj->setName($studio['name']);
                $manager->persist($studioObj);
                $manager->flush();

                $royaltyObj = new Royalty();
                $royaltyObj->setStudio($studioObj);
                $royaltyObj->setPayment($studio['payment']);
                $manager->persist($royaltyObj);
                $manager->flush();

            }
        }

        $data = file_get_contents("data/episodes.json");
        $array = json_decode($data, true);
        $episodeIdArray = [];

        foreach ($array as $episodes) {
            foreach ($episodes as $episode) {
                $episodeObj = new Episode();
                $episodeObj->setId($episode['id']);
                $episodeObj->setName($episode['name']);
                $manager->persist($episodeObj);
                $manager->flush();

                $episodeIdArray[] = $episode['id'];

                $studioLoad = $manager
                ->getRepository(Studio::class)
                ->find($episode['rightsowner']);
                
                $royalty = new Rightsowner();
                $royalty->setStudio($studioLoad);
                $royalty->setEpisode($episodeObj);
                $manager->persist($royalty);
                $manager->flush();
                
            }
        }

        for ($i = 1; $i < 3; $i++) {
            $customerObj = new Customer();
            $customerObj->setDescription('This is the customer '.$i);
            $manager->persist($customerObj);
            $manager->flush();

            for ($j = 0; $j < 60; $j++) {
                $viewingObj = new Viewing();
                $viewingObj->setCustomer($customerObj);

                $episodeIdRandom = $episodeIdArray[rand(0,40)];

                $episodeLoad = $manager
                ->getRepository(Episode::class)
                ->find($episodeIdRandom);

                $viewingObj->setEpisode($episodeLoad);
                $viewingObj->setDate(new \DateTime());

                $manager->persist($viewingObj);
                $manager->flush();
            }
        }
    }
}
