<?php

namespace App\Controller ;

use App\Entity\Utilisateur;
use App\Form\UserRegisterType;
use App\Repository\RoleRepository;
use App\Repository\UtilisateurRepository;
use App\Services\Mailer;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\BrowserKit\Response;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Csrf\TokenGenerator\TokenGeneratorInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\NotBlank;

class SecurityController extends AbstractController
{
    const NO_REPLY_MAIL = 'no-reply@pusso.com' ;
    /**
     * @Route("/login", name="login")
     */
    public function login(AuthenticationUtils $authenticationUtils, Security $security)
    {

        if ($this->get('session')->get('_security_main')) {
            return  $this->redirectToRoute('app_homepage') ;
        }

        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('security/login.html.twig', [
            'last_username' => $lastUsername,
            'error' => $error
        ]);
    }

    /**
     * @Route("/recovery", name="app_forget")
     */
    public function forget(Request $request, Mailer $mailer, TokenGeneratorInterface $tokenGenerator){

        if ($this->get('session')->get('_security_main')) {
            return  $this->redirectToRoute('app_homepage') ;
        }

        // création d'un formulaire "à la volée", afin que l'internaute puisse renseigner son mail
        $form = $this->createFormBuilder()
            ->add('username', EmailType::class, [
                'constraints' => [
                    new Email(),
                    new NotBlank()
                ]
            ])
            ->getForm();
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){

            $em = $this->getDoctrine()->getManager();

            /**
             * @var Utilisateur $user
             */
            // voir l'épisode 2 de cette série pour retrouver la méthode loadUserByUsername:
            $user = $em->getRepository(Utilisateur::class)->loadUserByUsername($form->getData()['username']);

            // aucun email associé à ce compte.
            if (!$user) {
                $request->getSession()->getFlashBag()->add('error', "L'adresse email indiquée n'existe pas : ".$form->getData()['_email']);
                return $this->redirectToRoute("app_forget");
            }

            // création du token
            $user->setToken($tokenGenerator->generateToken());
            // enregistrement de la date de création du token
            $user->setPasswordRequestedAt(new \Datetime());
            $em->flush();

            // on utilise le service Mailer créé précédemment
            $bodyMail = $mailer->createBodyMail('mails/mail_recovery.html.twig', [
                'user' => $user
            ]);
            $mailer->sendMessage(self::NO_REPLY_MAIL, $user->getUsername(), 'Renouvellement du mot de passe', $bodyMail);
            $request->getSession()->getFlashBag()->add('success', "Vous recevrez un mail pour renouveler votre mot de passe. Le lien que vous recevrez sera valide pendant 10 minutes.");

            return $this->redirectToRoute("login");
        }
        return $this->render('security/forget.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/register", name="app_register")
     */
    public function register(Request $request,  UserPasswordEncoderInterface $passwordEncoder,
                             UtilisateurRepository $repository, Mailer $mailer, RoleRepository $roleRepository){

        if ($this->get('session')->get('_security_main')) {
            return  $this->redirectToRoute('app_homepage') ;
        }

        $user = new Utilisateur() ;
        $form = $this->createForm(UserRegisterType::class, $user) ;
        $form->handleRequest($request) ;

        $creationOK = false ;

        if($request->isMethod('POST') and $form->isSubmitted() and $form->isValid()){
            $em = $this->getDoctrine()->getManager() ;
            $user->setCreatedBy($repository->getSysUser()) ;
            $user->setIsActive(true) ;
            $user->setPassword($passwordEncoder->encodePassword($user,$user->getPlainPassword())) ;
            $user->setRole($roleRepository->find(5)) ;
            $user->eraseCredentials() ;
            $em->persist($user) ;
            $em->flush() ;
            // on utilise le service Mailer créé précédemment
            $bodyMail = $mailer->createBodyMail('mails/mail_welcome.html.twig', [
                'user' => $user
            ]);
            $mailer->sendMessage(self::NO_REPLY_MAIL, $user->getUsername(), 'Inscription PUSSO', $bodyMail);

            $creationOK = true ;
        }

        $datas = [
            'form'      => $form->createView(),
            'creationOK'    => $creationOK,
            'user'          => $user,
        ] ;

        return $this->render('security/register.html.twig',$datas) ;

    }
}