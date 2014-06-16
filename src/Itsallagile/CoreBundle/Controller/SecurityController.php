<?php
namespace Itsallagile\CoreBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Security\Core\SecurityContext;
use Symfony\Component\HttpFoundation\Response;
use Itsallagile\CoreBundle\Document\User;
use Itsallagile\CoreBundle\Form\Type\User\Registration;

class SecurityController extends Controller
{
    public function loginAction()
    {
        $request = $this->getRequest();
        $session = $request->getSession();

        if ($request->attributes->has(SecurityContext::AUTHENTICATION_ERROR)) {
            $error = $request->attributes->get(SecurityContext::AUTHENTICATION_ERROR);
        } else {
            $error = $session->get(SecurityContext::AUTHENTICATION_ERROR);
            $session->remove(SecurityContext::AUTHENTICATION_ERROR);
        }

        return $this->render(
            'ItsallagileCoreBundle:Security:login.html.twig',
            array(
                'lastEmail' => $request->get('email'),
                'error' => $error,
            )
        );
    }

    public function registerAction()
    {
        $dm = $this->get('doctrine_mongodb')->getManager();
        $request = $this->get('request');

        $user = new User();
        $form = $this->get('form.factory')->create(new Registration(), $user);

        if ('POST' == $request->getMethod()) {
            $form->submit($request);

            if ($form->isValid()) {
                $factory = $this->get('security.encoder_factory');

                $encoder = $factory->getEncoder($user);

                $password = $encoder->encodePassword($user->getPassword(), $user->getSalt());

                $user->setPassword($password);

                $dm->persist($user);
                $dm->flush();

                //creates a token and assigns it, effectively logging the
                //user in with the credentials they just registered
                //$token = new UsernamePasswordToken($user, null, 'main');
                //$this->get('security.context')->setToken($token);

                return $this->redirect($this->generateUrl('login'));
            }
        }

        return $this->render('ItsallagileCoreBundle:Security:register.html.twig', array('form' => $form->createView()));
    }
}
