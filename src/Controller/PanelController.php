<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints\Date;
use App\Entity\WorkTime;
use App\Entity\User;
use App\Service\workTimeService;

class PanelController extends AbstractController
{

    /**
     * @Route("/", name="app_panel")
     */
    public function index(workTimeService $workTime): Response
    {
        // $workTime = new workTimeService;
        $user = $this->getUser();
        $workTimeStatus = $workTime->getUserCurrentStatus($user);
        $usersTimeStatus = $workTime->getUsersCurrentStatus($user, new \DateTime());

        return $this->render('panel/index.html.twig', [
            'workTimeStatus' => $workTimeStatus,
            'usersTimeStatus' => $usersTimeStatus,
        ]);
    }

    private function getUserWorkTime(): array
    {

        $data = null;
        $user = $this->getUser();
        if (!in_array('ROLE_ADMIN', $user->getRoles(), true)) {

            // $workTime = $entityManager->getRepository(WorkTime::class)->findOneBy(['user' => $user, 'stop' => null]);
            //$worktime = $user->getWorkTime()->fim;
            // var_dump($workTime);
            $data[] = array();
        }
        
        return $data;
    }

    /**
     * @Route("/panel_workTimeChange ", name="panel_workTimeChange", methods="GET|POST")
     */
    public function workTimeChange(Request $request, UserInterface $user, EntityManagerInterface $entityManager): JsonResponse
    {
        
        if ($request->isMethod('POST')){
            
            $time = new \DateTime('@'.strtotime('now'));
            $changeType = $request->get('changeType');     
            $workTime = new WorkTime();

            if($changeType == 'start'){

                $workTime->setUser($user);
                $workTime->setStart($time);  
                
            }else if($changeType == 'stop'){
                
                $workTime = $entityManager->getRepository(WorkTime::class)->findOneBy(['user' => $user, 'stop' => null]); 
                $workTime->setStop($time);
                
            }

            try {
                $entityManager->persist($workTime);
                $entityManager->flush();
            } catch (\Exception $e) {
                return new JsonResponse(['success' => false, 'time' => $time, 'changeType' => $changeType, 'worktime' => $worktime->getId(), 'worktimeStart' => $worktime->getStart()]);
            }

            return new JsonResponse(['success' => true, 'time' => $time, 'changeType' => $changeType]);
        }   
        return new JsonResponse(['success' => false]);
    }
    
}
