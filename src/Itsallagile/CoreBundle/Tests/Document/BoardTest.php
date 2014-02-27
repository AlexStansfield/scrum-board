<?php

namespace Itsallagile\CoreBundle\Tests\Document;

use Itsallagile\CoreBundle\Document\Board;
use Itsallagile\CoreBundle\Document\Story;
use Itsallagile\CoreBundle\Document\Ticket;
use \Doctrine\Common\DataFixtures\Loader;
use Itsallagile\CoreBundle\DataFixtures\MongoDB\LoadBoards;
use \Doctrine\ODM\MongoDB\Tests\Mocks\DocumentManagerMock;
use \Doctrine\ODM\MongoDB\Tests\Mocks\ConnectionMock;
use \Doctrine\ODM\MongoDB\Tests\Mocks\UnitOfWorkMock;
use \Doctrine\Common\DataFixtures\ReferenceRepository;

class BoardTest extends \PHPUnit_Framework_TestCase
{
    /*
    public function setUp()
    {
        parent::setUp();
      //  $this->dm = DocumentManagerMock::create(new ConnectionMock());
      //  $this->dm->setUnitOfWork = new UnitOfWorkMock($this->dm);
    }

    public function testGetStoriesSorted()
    {

       // $em = $this->getMock('Doctrine\Common\Persistence\ObjectManager');
/*
        $loader = new Loader();
        $loader->addFixture(new LoadBoards);
        $loader->loadFromDirectory(__DIR__ . '/../../DataFixtures/MongoDB');
        $fixtures = $loader->getFixtures();
        var_dump($fixtures);
*/
/*
        $fixtures = new LoadBoards();
        $fixtures->setReferenceRepository(new ReferenceRepository($this->dm));

        var_dump($fixtures);
        $fixture = $fixtures->load($this->dm);
        var_dump($fixture);
        $board = $fixture->getReference('init-board');
*/

    public function testGetStories()
    {
        $board = $this->getBoard();

        $stories = $board->getStories()->toArray();
        $this->assertCount(2, $stories);
        $story = array_shift($stories);
        $this->assertSame('Second Story', $story->getContent());
        $story = array_shift($stories);
        $this->assertSame('First Story', $story->getContent());
    }

    public function testGetStoriesSorted()
    {
        $board = $this->getBoard();

        $storiesSorted = $board->getStoriesSorted();
        $this->assertCount(2, $storiesSorted);
        $story = array_shift($storiesSorted);
        $this->assertSame('First Story', $story->getContent());
        $story = array_shift($storiesSorted);
        $this->assertSame('Second Story', $story->getContent());
    }

    public function testGetCommittedStoryPoints()
    {
        $board = $this->getBoard();

        $this->assertSame(8, $board->getCommittedStoryPoints());
    }

    public function testGetCompletedStoryPoints()
    {
        $board = $this->getBoard();

        $this->assertSame(3, $board->getCompletedStoryPoints());
    }

    protected function getBoard()
    {
        $board = new Board();
        $board->setName('Example Board');
        $board->setSlug('example');

        $story = new Story();
        $story->setContent('Second Story');
        $story->setSort(1);
        $story->setPoints(5);
        $story->setStatus(Story::STATUS_NEW);
        $board->addStory($story);

        $ticket = new Ticket();
        $ticket->setStatus(Ticket::STATUS_NEW);
        $ticket->setContent('New Ticket');
        $ticket->setType('task');
        $story->addTicket($ticket);

        $ticket2 = new Ticket();
        $ticket2->setStatus(Ticket::STATUS_ASSIGNED);
        $ticket2->setContent('Assigned Ticket');
        $ticket2->setType('defect');
        $story->addTicket($ticket2);

        $story2 = new Story();
        $story2->setContent('First Story');
        $story2->setSort(0);
        $story2->setPoints(3);
        $story2->setStatus(Story::STATUS_DONE);
        $board->addStory($story2);

        $ticket3 = new Ticket();
        $ticket3->setStatus(Ticket::STATUS_DONE);
        $ticket3->setContent('Done Ticket');
        $ticket3->setType('bug');
        $story2->addTicket($ticket3);

        $ticket4 = new Ticket();
        $ticket4->setStatus(Ticket::STATUS_NEW);
        $ticket4->setContent('New Ticket in new story');
        $ticket4->setType('bug');
        $story2->addTicket($ticket4);

        return $board;
    }
}