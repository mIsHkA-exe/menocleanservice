<?php

namespace App\Controller;

use App\Entity\DemandeDevis;
use App\Entity\MessageContact;
use App\Form\DemandeDevisType;
Use App\Content\FaqProvider;
use App\Form\MessageContactType;
use App\Repository\ServiceRepository;
use App\Service\DemandeHandler;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class HomeController extends AbstractController
{
        #[Route('/', name: 'app_home', methods: ['GET', 'POST'])]
    public function index(
        Request $request,
        ServiceRepository $serviceRepository,
        DemandeHandler $demandeHandler,
        FaqProvider $faqProvider,
    ): Response {
        $demandeDevis = new DemandeDevis();
        $devisForm = $this->createForm(DemandeDevisType::class, $demandeDevis);
        $devisForm->handleRequest($request);

        if ($devisForm->isSubmitted() && $devisForm->isValid()) {
            $demandeHandler->traiterDemandeDevis($demandeDevis);

            $this->addFlash(
                'devis_success',
                'Merci ! Votre demande a bien été enregistrée. Notre équipe vous recontacte sous 24h ouvrées avec un devis gratuit et sans engagement.'
            );

            return $this->redirectToRoute('app_home', ['_fragment' => 'devis'], Response::HTTP_SEE_OTHER);
        }

        $messageContact = new MessageContact();
        $contactForm = $this->createForm(MessageContactType::class, $messageContact);
        $contactForm->handleRequest($request);

        if ($contactForm->isSubmitted() && $contactForm->isValid()) {
            $demandeHandler->traiterMessageContact($messageContact);

            $this->addFlash(
                'contact_success',
                'Merci pour votre message ! Notre équipe vous répond dans les meilleurs délais.'
            );

            return $this->redirectToRoute('app_home', ['_fragment' => 'contact'], Response::HTTP_SEE_OTHER);
        }

        // Turbo n'affiche une réponse non redirigée que si elle porte un code d'erreur.
        // 422 = « requête comprise, contenu invalide » : c'est exactement le cas
        // d'un formulaire rejeté par la validation.
        $formulaireInvalide = ($devisForm->isSubmitted() && !$devisForm->isValid())
            || ($contactForm->isSubmitted() && !$contactForm->isValid());

        return $this->render('home/home.html.twig', [
            'services'    => $serviceRepository->findActifsOrdonnes(),
            'categories'  => $serviceRepository->findCategoriesUtilisees(),
            'questions'   => $faqProvider->all(),
            'devisForm'   => $devisForm->createView(),
            'contactForm' => $contactForm->createView(),
        ], new Response(null, $formulaireInvalide ? Response::HTTP_UNPROCESSABLE_ENTITY : Response::HTTP_OK));
    }
}