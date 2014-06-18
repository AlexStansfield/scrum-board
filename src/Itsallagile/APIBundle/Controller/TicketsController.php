<?php

namespace Itsallagile\APIBundle\Controller;

use Doctrine\Common\Collections\Collection;
use FOS\RestBundle\Controller\FOSRestController;
use Itsallagile\CoreBundle\Document\Ticket;
use Itsallagile\CoreBundle\Document\Board;
use Itsallagile\CoreBundle\Document\Story;
use Itsallagile\CoreBundle\Document\TicketHistory;
use Itsallagile\CoreBundle\Document\User;
use Symfony\Component\HttpFoundation\Request;
use FOS\RestBundle\View\View;
use Itsallagile\APIBundle\Form\TicketType;
use Itsallagile\APIBundle\Events;
use Symfony\Component\EventDispatcher\GenericEvent;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * Rest controller for tickets
 */
class TicketsController extends FOSRestController implements ApiController
{
    /**
     * Get a story from the board and storyId and check that it was successful
     *
     * @param Board $board
     * @param string $storyId
     * @return Story
     * @throws NotFoundHttpException
     */
    protected function getStory(Board $board, $storyId)
    {
        $story = $board->getStory($storyId);
        if (!$story) {
            throw $this->createNotFoundException('Could not find story' . $storyId);
        }
        return $story;
    }

    /**
     * @param Story $story
     * @param $ticketId
     * @return Ticket
     * @throws NotFoundHttpException
     */
    protected function getTicket(Story $story, $ticketId)
    {
        $ticket = $story->getTicket($ticketId);
        if (!$ticket) {
            throw $this->createNotFoundException('Could not find ticket' . $ticketId);
        }
        return $ticket;
    }

    /**
     * Get a single ticket for a story in a board
     * 
     * @param Board $board
     * @param string $storyId
     * @param string $ticketId
     * @return Ticket
     */
    public function getTicketAction(Board $board, $storyId, $ticketId)
    {
        $story = $this->getStory($board, $storyId);

        $ticket = $this->getTicket($story, $ticketId);
        return $ticket;
    }

    /**
     * Get all tickets for a story in a board
     * 
     * @param Board $board
     * @param string $storyId
     * @return Collection
     */
    public function getTicketsAction(Board $board, $storyId)
    {
        $story = $this->getStory($board, $storyId);

        return $story->getTickets();
    }

    /**
     * Create a new ticket
     *
     * @param Board $board
     * @param $storyId
     * @param Request $request
     * @return View
     */
    public function postTicketsAction(Board $board, $storyId, Request $request)
    {
        $story = $this->getStory($board, $storyId);
        $view = View::create();
        $ticket = new Ticket();
        $form = $this->createForm(new TicketType(), $ticket);
        $form->submit($request);

        if ($form->isValid()) {
            if (!$ticket->getStatus()) {
                $ticket->setStatus(Ticket::STATUS_NEW);
            }
            $dm = $this->get('doctrine_mongodb')->getManager();
            $story->addTicket($ticket);
            $dm->persist($story);
            $dm->flush();
            $view->setStatusCode(201);
            $view->setData($ticket);
            $this->get('event_dispatcher')->dispatch(Events::TICKET_CREATE, new GenericEvent($ticket));
        } else {
            $view->setData($form);
        }
        return $view;
    }

    /**
     * Update a ticket
     *
     * @param Board $board
     * @param $storyId
     * @param $ticketId
     * @param Request $request
     * @return View
     */
    public function putTicketAction(Board $board, $storyId, $ticketId, Request $request)
    {
        $view = View::create();
        $story = $this->getStory($board, $storyId);
        $ticket = $this->getTicket($story, $ticketId);
        $form = $this->createForm(new TicketType(true), $ticket);
        $form->submit($request);

        if ($form->isValid()) {
            $assignUserId = $request->get('assignUserId');
            $ticket->setModified(new \DateTime());

            // get document manager
            $dm = $this->get('doctrine_mongodb')->getManager();

            // Check if Assigned user
            if (! ($user = $ticket->getAssignedUser()) || ($user->getId() !== $assignUserId)) {
                if ($assignUserId) {
                    $user = $dm->find('Itsallagile\CoreBundle\Document\User', $assignUserId);
                    $ticket->setAssignedUser($user);
                } else {
                    $ticket->unassignUser();
                }
            }

            $dm->persist($ticket);
            $dm->flush();

            $view->setStatusCode(200);
            $view->setData($ticket);
            $this->get('event_dispatcher')->dispatch(Events::TICKET_UPDATE, new GenericEvent($ticket));
        } else {
            $view->setData($form);
        }
        return $view;
    }

    /**
     * Delete a Ticket
     *
     * @param Board $board
     * @param $storyId
     * @param $ticketId
     * @return View
     */
    public function deleteTicketAction(Board $board, $storyId, $ticketId)
    {
        $view = View::create();
        $story = $this->getStory($board, $storyId);
        $ticket = $this->getTicket($story, $ticketId);
        $dm = $this->get('doctrine_mongodb')->getManager();
        $this->get('event_dispatcher')->dispatch(Events::TICKET_DELETE, new GenericEvent($ticket));
        $story->removeTicket($ticket);
        $dm->persist($story);
        $dm->flush();
        $view->setStatusCode(204);
        return $view;
    }
}
