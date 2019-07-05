<?php

namespace App\Entity\UserData;

use App\Entity\Client\Client;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="import_advert_error")
 */
class ImportAdvertError
{
    /**
     * @var integer
     *
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @var integer
     *
     * @ORM\Column(type="integer", options={"default" : 1})
     */
    private $count = 1;

    /**
     * @var string
     *
     * @ORM\Column(type="text")
     */
    private $lineData;

    /**
     * @var string
     *
     * @ORM\Column(type="string")
     */
    private $fieldValue;

    /**
     * @var string
     *
     * @ORM\Column(type="string")
     */
    private $issueField;

    /**
     * @var string
     *
     * @ORM\Column(type="string")
     */
    private $issue;

    /**
     * @var boolean
     *
     * @ORM\Column(type="boolean", options={"default" : false})
     */
    private $canAddKeyword = false;

    /**
     * @var string
     *
     * @ORM\Column(type="text")
     */
    private $requiredValues;

    /**
     * ImportAdvertError constructor.
     * @param string $lineData
     * @param string $fieldValue
     * @param string $issueField
     * @param string $issue
     */
    public function __construct(string $lineData, string $fieldValue, string $issueField, string $issue)
    {
        $this->lineData = $lineData;
        $this->fieldValue = $fieldValue;
        $this->issueField = $issueField;
        $this->issue = $issue;
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @param int $id
     */
    public function setId(int $id): void
    {
        $this->id = $id;
    }

    /**
     * @return int
     */
    public function getCount(): int
    {
        return $this->count;
    }

    /**
     * @param int $count
     */
    public function setCount(int $count): void
    {
        $this->count = $count;
    }

    /**
     * @return string
     */
    public function getLineData(): string
    {
        return $this->lineData;
    }

    /**
     * @param string $lineData
     */
    public function setLineData(string $lineData): void
    {
        $this->lineData = $lineData;
    }

    /**
     * @return string
     */
    public function getFieldValue(): string
    {
        return $this->fieldValue;
    }

    /**
     * @return string
     */
    public function getIssueField(): string
    {
        return $this->issueField;
    }

    /**
     * @param string $issueField
     */
    public function setIssueField(string $issueField): void
    {
        $this->issueField = $issueField;
    }

    /**
     * @param string $fieldIssue
     */
    public function setFieldIssue(string $fieldIssue): void
    {
        $this->fieldIssue = $fieldIssue;
    }

    /**
     * @return string
     */
    public function getIssue(): string
    {
        return $this->issue;
    }

    /**
     * @param string $issue
     */
    public function setIssue(string $issue): void
    {
        $this->issue = $issue;
    }

    /**
     * @return bool
     */
    public function isCanAddKeyword(): bool
    {
        return $this->canAddKeyword;
    }

    /**
     * @param bool $canAddKeyword
     */
    public function setCanAddKeyword(bool $canAddKeyword): void
    {
        $this->canAddKeyword = $canAddKeyword;
    }

    /**
     * @return string
     */
    public function getRequiredValues(): string
    {
        return $this->requiredValues;
    }

    /**
     * @param string $requiredValues
     */
    public function setRequiredValues(string $requiredValues): void
    {
        $this->requiredValues = $requiredValues;
    }

    public function decodeLineData()
    {
        if(!$this->lineData){
            return [];
        }

        return json_decode($this->lineData, true);
    }

    public function decodeRequiredValues()
    {
        if(!$this->requiredValues){
            return [];
        }

        return json_decode($this->requiredValues, true);
    }

    public function increaseCount()
    {
        $this->count++;
    }
}