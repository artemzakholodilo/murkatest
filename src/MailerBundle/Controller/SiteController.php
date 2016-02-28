<?php

namespace MailerBundle\Controller;

use MailerBundle\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class SiteController extends Controller
{
    /**
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function indexAction()
    {
        return $this->render('MailerBundle:Site:index.html.twig');
    }

    public function loginAction(Request $request)
    {
        if ($userEmail = $request->request->get('email')) {
            $this->login($userEmail);
        }
        return $this->render('MailerBundle:Site:login.html.twig');
    }

    public function signupAction(Request $request)
    {
        if ($userEMail = $request->request->get('email')) {
            $user = new User();
            $user->setName($userEMail);

            $em = $this->getDoctrine()->getEntityManager();
            try {
                $em->persist($user);
                $em->flush();
            } catch (\Exception $ex) {
                return $this->render('MailerBundle:Site:signup.html.twig', [
                    'error' => 'Something want wrong, please try again latter.'
                ]);
            }
        }

        return $this->render('MailerBundle:Site:signup.html.twig');
    }

    private function login($email)
    {

        return true;

    }
}