<?php
namespace itsallagile\CoreBundle\Document;

use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\Common\Collections\ArrayCollection;

/** 
 * @MongoDB\EmbeddedDocument 
 */
class Story
{
    
    const STATUS_NEW = 'New';
    const STATUS_IN_PROGRESS = 'In Progress';
    const STATUS_TESTABLE = 'Testable';
    const STATUS_DONE = 'Done';
    
    protected $statuses = array(
        self::STATUS_NEW,
        self::STATUS_IN_PROGRESS,
        self::STATUS_TESTABLE,
        self::STATUS_DONE
    );
    
    /**
     * Get the possible statuses for a story
     * 
     * @return array
     */
    public static function getStatuses()
    {
        return array_combine($this->statuses, $this->statuses);
    }
    
    /**
     * @MongoDB\Id(strategy="INCREMENT")
     */
    protected $id;

    /**
     * @MongoDB\Field(type="string")
     */
    protected $content;

    /**
     * @MongoDB\Field(type="int")
     */
    protected $sort;

    /**
     * @MongoDB\EmbedMany(targetDocument="Ticket")
     */
    protected $tickets;

    /**
     * @MongoDB\Field(type="int")
     */
    protected $points;

    /**
     * @MongoDB\Field(type="string")
     */
    protected $status;
    
    public function __construct()
    {
        $this->tickets = new ArrayCollection();
    }

    public function __toString()
    {
        return (string)$this->id;
    }

    /**
     * Get id
     *
     * @return custom_id $id
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set content
     *
     * @param string $content
     * @return Story
     */
    public function setContent($content)
    {
        $this->content = $content;
        return $this;
    }

    /**
     * Get content
     *
     * @return string $content
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * Set sort
     *
     * @param int $sort
     * @return Story
     */
    public function setSort($sort)
    {
        $this->sort = $sort;
        return $this;
    }

    /**
     * Get sort
     *
     * @return int $sort
     */
    public function getSort()
    {
        return $this->sort;
    }

    /**
     * Add tickets
     *
     * @param itsallagile\CoreBundle\Document\Ticket $ticket
     */
    public function addTicket(\itsallagile\CoreBundle\Document\Ticket $ticket)
    {
        $this->tickets[] = $ticket;
    }

    /**
     * Get tickets
     *
     * @return Doctrine\Common\Collections\Collection $tickets
     */
    public function getTickets()
    {
        return $this->tickets;
    }

    /**
     * Set points
     *
     * @param int $points
     * @return Story
     */
    public function setPoints($points)
    {
        $this->points = $points;
        return $this;
    }

    /**
     * Get points
     *
     * @return int $points
     */
    public function getPoints()
    {
        return $this->points;
    }

    /**
     * Set status
     *
     * @param string $status
     * @return Story
     */
    public function setStatus($status)
    {
        $this->status = $status;
        return $this;
    }

    /**
     * Get status
     *
     * @return string $status
     */
    public function getStatus()
    {
        return $this->status;
    }
}
