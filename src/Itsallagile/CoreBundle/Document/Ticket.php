<?php

namespace Itsallagile\CoreBundle\Document;

use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;
use Symfony\Component\Validator\Constraints as Assert;
use JMS\Serializer\Annotation as JMS;

/** 
 * @MongoDB\EmbeddedDocument 
 */
class Ticket
{
    const STATUS_NEW = 'new';
    const STATUS_ASSIGNED = 'assigned';
    const STATUS_DONE = 'done';

    /**
     * @JMS\Exclude     
     */
    protected static $statuses = array(
        self::STATUS_NEW => 'New',
        self::STATUS_ASSIGNED => 'Assigned',
        self::STATUS_DONE => 'Done'
    );

    /**
     * Get the possible statuses for a ticket
     * @return array
     */
    public static function getStatuses()
    {
        return self::$statuses;
    }

    public function __construct()
    {
        $this->created = new \DateTime();
        $this->modified = new \DateTime();
    }

    /**
     * @MongoDB\Id
     */
    protected $id;

    /**
     * @Assert\NotBlank()
     * @MongoDB\Field(type="string")
     */
    protected $type;

    /**
     * @MongoDB\Field(type="string")
     */
    protected $content;

    /**
     * @Assert\NotBlank()
     * @MongoDB\Field(type="string")
     */
    protected $status;

    /**
     * @MongoDB\EmbedMany(targetDocument="TicketHistory")
     */
    protected $history = array();

    /**
     * @MongoDB\Field(type="date")
     */
    protected $created;

    /**
     * @MongoDB\Field(type="date")
     */
    protected $modified;

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
     * Set type
     *
     * @param string $type
     * @return Ticket
     */
    public function setType($type)
    {
        $this->type = $type;
        return $this;
    }

    /**
     * Get type
     *
     * @return string $type
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Set content
     *
     * @param string $content
     * @return Ticket
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
     * Set status
     *
     * @param string $status
     * @return Ticket
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

    /**
     * Set created
     *
     * @param \DateTime $created
     */
    public function setCreated(\DateTime $created)
    {
        $this->created = $created;
    }

    /**
     * Get created
     *
     * @return \DateTime
     */
    public function getCreated()
    {
        return $this->created;
    }

    /**
     * Set modified
     *
     * @param \DateTime $modified
     */
    public function setModified(\DateTime $modified)
    {
        $this->modified = $modified;
    }

    /**
     * Get modified
     *
     * @return \DateTime
     */
    public function getModified()
    {
        return $this->modified;
    }

    /**
     * Add History
     *
     * @param TicketHistory $history
     */
    public function addHistory(TicketHistory $history)
    {
        $this->history[] = $history;
    }

    /**
     * Get history
     *
     * @return Doctrine\Common\Collections\Collection $history
     */
    public function getHistory()
    {
        return $this->history;
    }
}
