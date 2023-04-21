<?php
// src/Service/workTime.php
namespace App\Service;

use Symfony\Component\Security\Core\User\UserInterface;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\WorkTime;

// use Symfony\Component\Security\Core\Security;
// use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

// use Symfony\Bundle\SecurityBundle\Security;

class workTimeService
{

    private $em;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->em = $entityManager;
    }

    public function getUserCurrentStatus(UserInterface $user): ? Array
    {
        $data = null;

        if (!in_array('ROLE_ADMIN', $user->getRoles(), true)) 
        {
            $workTime = $this->em->getRepository(WorkTime::class)->findOneBy(['user' => $user, 'stop' => null]);
            $data['status'] =  'idle';

            if($workTime)
            {
                $data['status'] =  'active';
                $data['start'] = $workTime->getStart();
            }
        }
        
        return $data;
    }

    public function getUsersCurrentStatus(UserInterface $user, \DateTime $date): ? Array
    {
        $data = null;

        if (in_array('ROLE_ADMIN', $user->getRoles(), true)) 
        {
            
            $workTime = $this->em->getRepository(WorkTime::class)->findByDate($date->format('Y-m-d'));
            $data['users'] = $workTime;
        }
        
        return $data;
    }
}