<?php

namespace Itsallagile\ScrumboardBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Itsallagile\CoreBundle\Document\Board;
use Itsallagile\CoreBundle\Document\Ticket;
use Itsallagile\CoreBundle\Document\Story;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;

class ScrumboardController extends Controller
{
    /**
     * The main scrum board page
     * 
     * @param Board $board
     * @ParamConverter("board", class="Itsallagile\CoreBundle\Document\Board")
     */
    public function indexAction(Board $board)
    {
        $user = $this->get('security.context')->getToken()->getUser();

        // if (!$user->hasTeam($board->getTeam())) {
        //     throw new AccessDeniedHttpException('You do not have access to this board');
        // }

        $serializer = $this->get('serializer');

        $viewData = array(
            'board' => $board,
            'boardJson' => $serializer->serialize($board, 'json'),
        );

        $userDetails = array(
            'id' => $user->getId(),
            'name' => $user->getFullName(),
            'email' => $user->getEmail()
        );
        $viewData['user'] = $userDetails;

        $ticketStatuses = array();
        foreach (Ticket::getStatuses() as $id => $status) {
            $ticketStatuses[] = array('id' => $id, 'status'=> $status);
        }
        $viewData['ticketStatuses'] = $ticketStatuses;

        $storyStatuses = array();
        foreach (Story::getStatuses() as $id => $status) {
            $storyStatuses[] = array('id' => $id, 'status'=> $status);
        }
        $viewData['storyStatuses'] = $storyStatuses;

        $users = array();
        foreach ($board->getTeam()->getUsers() as $user) {
            $users[] = array('id' => $user->getId(), 'name' => $user->getFullName());
        }
        $viewData['users'] = $users;

        return $this->render('ItsallagileScrumboardBundle:Board:index.html.twig', $viewData);
    }
}
