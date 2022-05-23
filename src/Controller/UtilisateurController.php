<?php

namespace App\Controller;

use App\Entity\Utilisateur;
use App\Form\PartnerType;
use App\Form\UserType;
use App\Repository\RoleRepository;
use App\Repository\UtilisateurRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UtilisateurController extends AbstractController
{
    private $level1 = 'Gestion des utilisateurs' ;
    private static $defaultPwd = "Pusso2021@" ;
    private $route = 'app_user_all' ;
    private $ROLE_AD = 2 ;
    private $ROLE_CSU = 3 ;
    private $ROLE_PU = 4 ;

    public function __construct(UtilisateurRepository $repository, RoleRepository $roleRepository)
    {
        $this->repository = $repository ;
        $this->roleRepository = $roleRepository ;
    }

    public function all(){
        return $this->render('user/all.html.twig',[
            'users'     => $this->repository->getFinalClients(),
            'level1'    => $this->level1,
            'level2'    => 'Liste des clients',
            'route'     => $this->route
        ]) ;
    }

    public function access($id){

        /**
         * @var Utilisateur $user
         */
        $user = $this->repository->find($id) ;

        if(!$user){
            throw $this->createNotFoundException('La ressource que vous recherchez n\'existe pas.') ;
        }

        $user->setIsActive(!$user->getIsActive()) ;

        $this->getDoctrine()->getManager()->flush() ;

        $message = $user->getIsActive() ? "L'utilisateur indiqué a été débloqué : " : "L'utilisateur indiqué a été bloqué :" ;

        $message .= $user->getUsername() ;

        $this->addFlash('success', $message) ;

        return $user->getRole()->getId() == $this->ROLE_AD ? $this->redirectToRoute('app_admin_all') :
            ($user->getRole()->getId() == $this->ROLE_PU ? $this->redirectToRoute('app_partner_all') :
            $this->redirectToRoute('app_csu_all'));

    }

    public function edit(Request $request, $id){

        /**
         * @var Utilisateur $user
         */
        $user = $this->repository->find($id) ;

        if(!$user){
            throw $this->createNotFoundException('La ressource que vous recherchez n\'existe pas.') ;
        }

        $form = $this->createForm(UserType::class, $user) ;
        $form->handleRequest($request) ;

        $modificationOK = false ;

        if($request->isMethod('POST') and $form->isSubmitted() and $form->isValid()){
            $this->getDoctrine()->getManager()->flush() ;
            $modificationOK = true ;
        }

        $datas = [
            'formUser'      => $form->createView(),
            'modificationOK'=> $modificationOK,
            'user'          => $user,
            'level1'        => $this->level1,
            'level2'        => 'Modifier un utilisateur',
            'route'         => $this->route
        ] ;

        return $this->render('user/edit.html.twig',$datas) ;
    }

    public function customerServiceUsers(){
        return $this->getByRole($this->ROLE_CSU, 'user/csusers.html.twig') ;
    }

    public function customerServiceUsersAdd(Request $request,  UserPasswordEncoderInterface $passwordEncoder){
        $user = new Utilisateur() ;
        $form = $this->createForm(UserType::class, $user) ;
        $form->handleRequest($request) ;
        $creationOK = false ;
        if($request->isMethod('POST') and $form->isSubmitted() and $form->isValid()){
            $em = $this->getDoctrine()->getManager() ;
            $user->setRole($this->roleRepository->find($this->ROLE_CSU)) ;
            $user->setCreatedBy($this->getUser()) ;
            $user->setIsActive(true) ;
            $user->setPassword($passwordEncoder->encodePassword($user,self::$defaultPwd)) ;
            $em->persist($user) ;
            $em->flush() ;
            $creationOK = true ;
        }

        $datas = [
            'form'          => $form->createView(),
            'creationOK'    => $creationOK
        ] ;

        return $this->render('user/create-csu.html.twig',$datas) ;
    }

    public function partnerUsersAdd(Request $request,  UserPasswordEncoderInterface $passwordEncoder){
        $user = new Utilisateur() ;
        $form = $this->createForm(PartnerType::class, $user) ;
        $form->handleRequest($request) ;
        $creationOK = false ;
        if($request->isMethod('POST') and $form->isSubmitted() and $form->isValid()){
            $em = $this->getDoctrine()->getManager() ;
            $user->setRole($this->roleRepository->find($this->ROLE_PU)) ;
            $user->setCreatedBy($this->getUser()) ;
            $user->setIsActive(true) ;
            $user->setPassword($passwordEncoder->encodePassword($user,self::$defaultPwd)) ;
            $em->persist($user) ;
            $em->flush() ;
            $creationOK = true ;
        }

        $datas = [
            'form'          => $form->createView(),
            'creationOK'    => $creationOK
        ] ;

        return $this->render('user/create-partner.html.twig',$datas) ;
    }

    public function partnerUsers(){
        return $this->getByRole($this->ROLE_PU, 'user/partners.html.twig') ;
    }

    public function adminUsersAdd(Request $request,  UserPasswordEncoderInterface $passwordEncoder){
        $user = new Utilisateur() ;
        $form = $this->createForm(UserType::class, $user) ;
        $form->handleRequest($request) ;
        $creationOK = false ;
        if($request->isMethod('POST') and $form->isSubmitted() and $form->isValid()){
            $em = $this->getDoctrine()->getManager() ;
            $user->setRole($this->roleRepository->find($this->ROLE_AD)) ;
            $user->setCreatedBy($this->getUser()) ;
            $user->setIsActive(true) ;
            $user->setPassword($passwordEncoder->encodePassword($user,self::$defaultPwd)) ;
            $em->persist($user) ;
            $em->flush() ;
            $creationOK = true ;
        }

        $datas = [
            'form'          => $form->createView(),
            'creationOK'    => $creationOK
        ] ;

        return $this->render('user/create-admin.html.twig',$datas) ;
    }

    public function adminUsers(){
        return $this->getByRole($this->ROLE_AD, 'user/admins.html.twig') ;
    }

    private function getByRole(int $role, string $template){
        return $this->render($template,[
            'users'     => $this->repository->findBy(['role' => $role])
        ]) ;
    }
}
