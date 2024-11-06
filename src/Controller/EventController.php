<?php

namespace App\Controller;

use App\Entity\Event;
use App\Entity\User;
use App\Form\EventType;
use App\Form\UserType;
use App\Repository\EventRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class EventController extends AbstractController
{
    #[Route('/events', name: 'events_list')]
    public function index(EventRepository $eventRepository): Response
    {
        $events = $eventRepository->findAll();
        return $this->render('event/index.html.twig', [
            'events' => $events,
        ]);
    }

    #[Route('/events/{id}/register', name: 'event_register')]
    public function register(Request $request, EntityManagerInterface $entityManager, Event $event): Response
    {
        // Check if the event has reached the registration limit
        if (count($event->getRegisteredUsers()) >= $event->getRegistrationLimit()) {
            $this->addFlash('error', 'Event is full. No more registrations allowed.');
            return $this->redirectToRoute('events_list');
        }

        $user = new User();
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Register the user for the event
            $event->addRegisteredUser($user);
            $entityManager->persist($user);
            $entityManager->flush();

            $this->addFlash('success', 'You have successfully registered for the event!');
            return $this->redirectToRoute('events_list'); // Redirect after success
        }

        return $this->render('event/register.html.twig', [
            'form' => $form->createView(),
            'event' => $event,
        ]);
    }

    #[Route('/events/new', name: 'event_new')]
    public function new(Request $request,EntityManagerInterface $entityManager): Response
    {
        $event = new Event();
        $form = $this->createForm(EventType::class, $event);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($event);
            $entityManager->flush();
            return $this->redirectToRoute('events_list');
        }
        return $this->render('event/new.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
