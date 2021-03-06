<?php

namespace App\Entity\Error;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\Error\CodeOBD2ErrorRepository")
 * @ORM\Table(name="code_obd2error")
 */
class CodeOBD2Error
{
    const ABSENT_CODE_RU_TRANSCRIPT = "К сожалению, в нашей базе не найдено описание данной ошибки на русском языке";
    const ABSENT_CODE_EN_TRANSCRIPT = "Отсутствует описание ошибки";
    const ABSENT_CODE_REASON = "К сожалению, мы не можем дать Вам компитентную информацию о причинах возникновения данной ошибки.";
    const ABSENT_CODE_ADVICE = "На данный момент мы не можем предложить Вам квалифицированную помощь в решение вопроса с данной ошибкой";

    const DEFAULT_URL_TO_CATALOG = "dvs";

    static $variables = [
        "[CODEOBD2]" => "getCode",
        "[URLCODEOBD2]" => "getUrl",
        "[RUTRANSCRIPTCODEOBD2]" => "getTranscriptRu",
        "[ENTRANSCRIPTCODEOBD2]" => "getTranscriptEn",
        "[REASON_CODEOBD2]" => "getReason",
        "[ADVICE_CODEOBD2]" => "getAdvice",
        "[URLCONNECTCODEOBD2]" => "getUrlToCatalog",
    ];

    /**
     * @var integer|null
     *
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @var string|null
     *
     * @ORM\Column(type="string")
     */
    private $code;

    /**
     * @var string|null
     *
     * @ORM\Column(type="string")
     */
    private $url;

    /**
     * @var string|null
     *
     * @ORM\Column(type="string")
     */
    private $transcriptRu;

    /**
     * @var string|null
     *
     * @ORM\Column(type="string")
     */
    private $transcriptEn;

    /**
     * @var string|null
     *
     * @ORM\Column(type="text")
     */
    private $reason;

    /**
     * @var string|null
     *
     * @ORM\Column(type="text")
     */
    private $advice;

    /**
     * @var string|null
     *
     * @ORM\Column(type="string")
     */
    private $urlToCatalog;

    /**
     * @var boolean
     *
     * @ORM\Column(type="boolean", options={"default" : false})
     */
    private $active = 0;

    /**
     * @var boolean
     *
     * @ORM\Column(type="boolean", options={"default" : false})
     */
    private $isOftenSearch = 0;

    /**
     * @var TypeOBD2Error|null
     *
     * @ORM\ManyToOne(targetEntity="TypeOBD2Error", inversedBy="codes")
     * @ORM\JoinColumn(name="type_id", referencedColumnName="id")
     */
    private $type;

    /**
     * @var integer
     *
     * @ORM\Column(type="integer", options={"default" : 0})
     */
    private $counter = 0;

    /**
     * CodeOBD2Error constructor.
     */
    public function __construct()
    {
        $this->transcriptEn = "";
        $this->transcriptRu = "";
        $this->url = "";
        $this->advice = "";
        $this->reason = "";
        $this->urlToCatalog = "";
    }


    /**
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @param int|null $id
     */
    public function setId(?int $id): void
    {
        $this->id = $id;
    }

    /**
     * @return null|string
     */
    public function getCode(): ?string
    {
        return $this->code;
    }

    /**
     * @param null|string $code
     */
    public function setCode(?string $code): void
    {
        $this->code = $code;
    }

    /**
     * @return null|string
     */
    public function getUrl(): ?string
    {
        return $this->url;
    }

    /**
     * @param null|string $url
     */
    public function setUrl(?string $url): void
    {
        $this->url = $url;
    }

    /**
     * @return null|string
     */
    public function getTranscriptRu(): ?string
    {
        return $this->transcriptRu;
    }

    /**
     * @param null|string $transcriptRu
     */
    public function setTranscriptRu(?string $transcriptRu): void
    {
        $this->transcriptRu = $transcriptRu;
    }

    /**
     * @return null|string
     */
    public function getTranscriptEn(): ?string
    {
        return $this->transcriptEn;
    }

    /**
     * @param null|string $transcriptEn
     */
    public function setTranscriptEn(?string $transcriptEn): void
    {
        $this->transcriptEn = $transcriptEn;
    }

    /**
     * @return null|string
     */
    public function getReason(): ?string
    {
        return $this->reason;
    }

    /**
     * @param null|string $reason
     */
    public function setReason(?string $reason): void
    {
        $this->reason = $reason;
    }

    /**
     * @return null|string
     */
    public function getAdvice(): ?string
    {
        return $this->advice;
    }

    /**
     * @param null|string $advice
     */
    public function setAdvice(?string $advice): void
    {
        $this->advice = $advice;
    }

    /**
     * @return null|string
     */
    public function getUrlToCatalog(): ?string
    {
        return $this->urlToCatalog;
    }

    /**
     * @param null|string $urlToCatalog
     */
    public function setUrlToCatalog(?string $urlToCatalog): void
    {
        $this->urlToCatalog = $urlToCatalog;
    }

    /**
     * @return bool
     */
    public function isActive(): bool
    {
        return $this->active;
    }

    /**
     * @param bool $active
     */
    public function setActive(bool $active): void
    {
        $this->active = $active;
    }

    /**
     * @return bool
     */
    public function isOftenSearch(): bool
    {
        return $this->isOftenSearch;
    }

    /**
     * @param bool $isOftenSearch
     */
    public function setIsOftenSearch(bool $isOftenSearch): void
    {
        $this->isOftenSearch = $isOftenSearch;
    }

    /**
     * @return TypeOBD2Error|null
     */
    public function getType(): ?TypeOBD2Error
    {
        return $this->type;
    }

    /**
     * @param TypeOBD2Error|null $type
     */
    public function setType(?TypeOBD2Error $type): void
    {
        $this->type = $type;
    }

    /**
     * @return int
     */
    public function getCounter(): int
    {
        return $this->counter;
    }

    /**
     * @param int $counter
     */
    public function setCounter(int $counter): void
    {
        $this->counter = $counter;
    }

    public function replaceVariables($string)
    {
        foreach (self::$variables as $variable => $method){
            $string = str_replace($variable, $this->$method(), $string);
        }

        return $string;
    }

    public function increaseCounter(): void
    {
        ++$this->counter;
    }

    public function toSearchArray()
    {
        return [
            "label" => $this->code,
            "value" => $this->code,
            "url" => $this->url,
            "id" => $this->id,
        ];
    }

    static function getAbsentCode($type = null, $url = null)
    {
        $code = new CodeOBD2Error();
        $code->setType($type);
        $code->setUrl($url);
        $code->setUrlToCatalog(self::DEFAULT_URL_TO_CATALOG);
        $code->setReason(self::ABSENT_CODE_REASON);
        $code->setAdvice(self::ABSENT_CODE_ADVICE);
        $code->setTranscriptEn(self::ABSENT_CODE_EN_TRANSCRIPT);
        $code->setTranscriptRu(self::ABSENT_CODE_RU_TRANSCRIPT);

        return $code;
    }
}