<?php

 /**
     * @Route("/envio", name="_demo_hello")
     * @Template()
  */

namespace Acme\DemoBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class EnvioController extends Controller
{
    public function indexAction()
    {
		$message = \Swift_Message::newInstance()
			->setSubject('Envio de prueba')
			->setFrom('testing@sifda.gob.sv')
			->setTo('axelljt@gmail.com')
			->setBody("last...")
#			->setBody($this->renderView('HelloBundle:Hello:email.txt.twig', array('name' => $name)))
    ;
    $this->get('mailer')->send($message);     // then we send the message.
        return $this->render('AcmeDemoBundle:Envio:index.html.twig');
    }
}
