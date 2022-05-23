<?php

namespace App\Controller;

use App\Entity\Utilisateur;
use App\Form\UserEditType;
use App\Form\UserRegisterType;
use App\Repository\DemandeRepository;
use App\Repository\UtilisateurRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class ProfilController extends AbstractController
{
    private $route  = 'app_user_profil' ;

    public function __construct(DemandeRepository $demandeRepository, UtilisateurRepository $userRepository)
    {
        $this->demandeRepository = $demandeRepository ;
        $this->userRepository = $userRepository ;
    }

    public function profil(Request $request, UserPasswordEncoderInterface $passwordEncoder){

        /**
         * @var Utilisateur $user
         */
        $user = $this->userRepository->findOneBy(['username' => $this->getUser()->getUsername()]) ;

        if(!$user){
            throw $this->createNotFoundException('La ressource que vous recherchez n\'existe pas.') ;
        }

        $form = $this->createForm(UserRegisterType::class, $user) ;
        $form->handleRequest($request) ;
        $modificationOK = false ;

        if($request->isMethod('POST') && $form->isSubmitted() && $form->isValid()){
            if($user->getPlainPassword() != null && !empty($user->getPlainPassword())){
                $user->setPassword($passwordEncoder->encodePassword($user, $user->getPlainPassword())) ;
                $user->eraseCredentials();
            }
            $this->getDoctrine()->getManager()->flush() ;
            $modificationOK = true ;
        }

        $datas = array(
            'form'              => $form->createView(),
            'page_title'        => 'Mon Profil',
            'modificationOK'    => $modificationOK,
            'route'             => $this->route,
            'nbr_demandes'      => $this->demandeRepository->count(['owner' => $this->getUser()->getId()])
        );
        return $this->render('profil/profil.html.twig', $datas) ;
    }
}
