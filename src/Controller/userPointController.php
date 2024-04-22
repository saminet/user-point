<?php

namespace App\Controller;

use App\Entity\Group;
use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;

class userPointController extends AbstractController
{
    private $em;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->em = $entityManager;
    }

    /**
     * Lists all user data.
     *
     * @Route("/", name="user_list")
     */
    public function indexAction(Request $request)
    {
        $group = $request->request->get('group');
        $users = $this->em->getRepository(User::class)->findByCriteria($group);
        $groups = $this->em->getRepository(Group::class)->findAll();

        return $this->render('user/index.html.twig', ['users' => $users, 'groups' => $groups, 'selectedGroup' => $group]);
    }

    /**
     * Update user points.
     *
     * @Route("/update-point/{id}", name="update_points")
     */
    public function updateAction(Request $request, User $user)
    {
        $data = $request->request->all();
        if($data){
            $currentPoints = $user->getPoints();
            $points = $data['points'];
            $add_point    = array_key_exists("add_point",$data)?true:false;
            $delete_point = array_key_exists("delete_point",$data)?true:false;
            $apply_on_all = array_key_exists("apply_on_all",$data)?true:false;

            if(!$apply_on_all && $add_point){
                $newPoint = $currentPoints + $points;
            }

            if(!$apply_on_all && $delete_point){
                $newPoint = $currentPoints - $points;
            }

            $user->setPoints($newPoint);

            $this->em->persist($user);
            $this->em->flush();

            return $this->redirectToRoute('user_list');
        }

        return $this->render('user/edit.html.twig', ['user' => $user]);

    }

}
