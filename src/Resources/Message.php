<?php

namespace SamuelTerra22\EvolutionLaravelClient\Resources;

use SamuelTerra22\EvolutionLaravelClient\Exceptions\EvolutionApiException;
use SamuelTerra22\EvolutionLaravelClient\Models\ButtonMessage;
use SamuelTerra22\EvolutionLaravelClient\Models\Contact;
use SamuelTerra22\EvolutionLaravelClient\Models\ContactMessage;
use SamuelTerra22\EvolutionLaravelClient\Models\ListMessage;
use SamuelTerra22\EvolutionLaravelClient\Models\PollMessage;
use SamuelTerra22\EvolutionLaravelClient\Models\ReactionMessage;
use SamuelTerra22\EvolutionLaravelClient\Models\StatusMessage;
use SamuelTerra22\EvolutionLaravelClient\Models\TextMessage;
use SamuelTerra22\EvolutionLaravelClient\Services\EvolutionService;

class Message
{
    /**
     * @var EvolutionService The Evolution service
     */
    protected EvolutionService $service;

    /**
     * @var string The instance name
     */
    protected string $instanceName;

    /**
     * Create a new Message resource instance.
     *
     * @param EvolutionService $service
     * @param string           $instanceName
     */
    public function __construct(EvolutionService $service, string $instanceName)
    {
        $this->service = $service;
        $this->instanceName = $instanceName;
    }

    /**
     * Get the instance name.
     *
     * @return string
     */
    public function getInstanceName(): string
    {
        return $this->instanceName;
    }

    /**
     * Set the instance name.
     *
     * @param string $instanceName
     *
     * @return void
     */
    public function setInstanceName(string $instanceName): void
    {
        $this->instanceName = $instanceName;
    }

    /**
     * Send a text message.
     *
     * @param string     $phoneNumber
     * @param string     $message
     * @param bool       $isGroup
     * @param int|null   $delay
     * @param bool|null  $linkPreview
     * @param bool|null  $mentionsEveryOne
     * @param array|null $mentioned
     *
     * @return array
     * @throws EvolutionApiException
     */
    public function sendText(
        string $phoneNumber,
        string $message,
        bool   $isGroup = false,
        ?int   $delay = null,
        ?bool  $linkPreview = null,
        ?bool  $mentionsEveryOne = null,
        ?array $mentioned = null
    ): array
    {
        $recipient = $isGroup
            ? $phoneNumber . '@g.us'
            : $this->formatPhoneNumber($phoneNumber);

        $textMessage = new TextMessage(
            $recipient,
            $message,
            $delay,
            null,
            $linkPreview,
            $mentionsEveryOne,
            $mentioned
        );

        return $this->service->post("/message/sendText/{$this->instanceName}", $textMessage->toArray());
    }

    /**
     * Format phone number to be used with the API.
     *
     * @param string $phoneNumber
     *
     * @return string
     */
    protected function formatPhoneNumber(string $phoneNumber): string
    {
        // Remove any non-digit characters
        $number = preg_replace('/\D/', '', $phoneNumber);

        // Add @ to create a valid recipient id for the API
        return $number . '@c.us';
    }

    /**
     * Send an image message.
     *
     * @param string $phoneNumber
     * @param string $image
     * @param string $caption
     * @param bool   $isGroup
     *
     * @return array
     * @throws EvolutionApiException
     */
    public function sendImage(string $phoneNumber, string $image, string $caption = '', bool $isGroup = false): array
    {
        $recipient = $isGroup
            ? $phoneNumber . '@g.us'
            : $this->formatPhoneNumber($phoneNumber);

        return $this->service->post("/message/chat/send/image/{$this->instanceName}", [
            'number'       => $recipient,
            'options'      => [
                'delay'    => 1200,
                'presence' => 'composing',
            ],
            'imageMessage' => [
                'image'   => $image,
                'caption' => $caption,
            ],
        ]);
    }

    /**
     * Send a document message.
     *
     * @param string $phoneNumber
     * @param string $document
     * @param string $fileName
     * @param string $caption
     * @param bool   $isGroup
     *
     * @return array
     * @throws EvolutionApiException
     */
    public function sendDocument(string $phoneNumber, string $document, string $fileName, string $caption = '', bool $isGroup = false): array
    {
        $recipient = $isGroup
            ? $phoneNumber . '@g.us'
            : $this->formatPhoneNumber($phoneNumber);

        return $this->service->post("/message/chat/send/document/{$this->instanceName}", [
            'number'          => $recipient,
            'options'         => [
                'delay'    => 1200,
                'presence' => 'composing',
            ],
            'documentMessage' => [
                'document' => $document,
                'fileName' => $fileName,
                'caption'  => $caption,
            ],
        ]);
    }

    /**
     * Send a location message.
     *
     * @param string $phoneNumber
     * @param float  $latitude
     * @param float  $longitude
     * @param string $name
     * @param string $address
     * @param bool   $isGroup
     *
     * @return array
     * @throws EvolutionApiException
     */
    public function sendLocation(string $phoneNumber, float $latitude, float $longitude, string $name = '', string $address = '', bool $isGroup = false): array
    {
        $recipient = $isGroup
            ? $phoneNumber . '@g.us'
            : $this->formatPhoneNumber($phoneNumber);

        return $this->service->post("/message/chat/send/location/{$this->instanceName}", [
            'number'          => $recipient,
            'options'         => [
                'delay'    => 1200,
                'presence' => 'composing',
            ],
            'locationMessage' => [
                'lat'     => $latitude,
                'lng'     => $longitude,
                'name'    => $name,
                'address' => $address,
            ],
        ]);
    }

    /**
     * Send a contact message.
     *
     * @param string $phoneNumber
     * @param string $contactName
     * @param string $contactNumber
     * @param bool   $isGroup
     *
     * @return array
     * @throws EvolutionApiException
     */
    public function sendContact(string $phoneNumber, string $contactName, string $contactNumber, bool $isGroup = false): array
    {
        $recipient = $isGroup
            ? $phoneNumber . '@g.us'
            : $this->formatPhoneNumber($phoneNumber);

        $contact = new Contact(
            $contactName,
            $contactNumber,
            $contactNumber
        );

        $contactMessage = new ContactMessage(
            $recipient,
            [$contact]
        );

        return $this->service->post("/message/sendContact/{$this->instanceName}", $contactMessage->toArray());
    }

    /**
     * Send a poll message.
     *
     * @param string   $phoneNumber
     * @param string   $name
     * @param int      $selectableCount
     * @param array    $values
     * @param int|null $delay
     * @param bool     $isGroup
     *
     * @return array
     * @throws EvolutionApiException
     */
    public function sendPoll(
        string $phoneNumber,
        string $name,
        int    $selectableCount,
        array  $values,
        ?int   $delay = null,
        bool   $isGroup = false
    ): array
    {
        $recipient = $isGroup
            ? $phoneNumber . '@g.us'
            : $this->formatPhoneNumber($phoneNumber);

        $pollMessage = new PollMessage(
            $recipient,
            $name,
            $selectableCount,
            $values,
            $delay
        );

        return $this->service->post("/message/sendPoll/{$this->instanceName}", $pollMessage->toArray());
    }

    /**
     * Send a list message.
     *
     * @param string   $phoneNumber
     * @param string   $title
     * @param string   $description
     * @param string   $buttonText
     * @param string   $footerText
     * @param array    $sections
     * @param int|null $delay
     * @param bool     $isGroup
     *
     * @return array
     * @throws EvolutionApiException
     */
    public function sendList(
        string $phoneNumber,
        string $title,
        string $description,
        string $buttonText,
        string $footerText,
        array  $sections,
        ?int   $delay = null,
        bool   $isGroup = false
    ): array
    {
        $recipient = $isGroup
            ? $phoneNumber . '@g.us'
            : $this->formatPhoneNumber($phoneNumber);

        $listMessage = new ListMessage(
            $recipient,
            $title,
            $description,
            $buttonText,
            $footerText,
            $sections,
            $delay
        );

        return $this->service->post("/message/sendList/{$this->instanceName}", $listMessage->toArray());
    }

    /**
     * Send a button message.
     *
     * @param string   $phoneNumber
     * @param string   $title
     * @param string   $description
     * @param string   $footer
     * @param array    $buttons
     * @param int|null $delay
     * @param bool     $isGroup
     *
     * @return array
     * @throws EvolutionApiException
     */
    public function sendButtons(
        string $phoneNumber,
        string $title,
        string $description,
        string $footer,
        array  $buttons,
        ?int   $delay = null,
        bool   $isGroup = false
    ): array
    {
        $recipient = $isGroup
            ? $phoneNumber . '@g.us'
            : $this->formatPhoneNumber($phoneNumber);

        $buttonMessage = new ButtonMessage(
            $recipient,
            $title,
            $description,
            $footer,
            $buttons,
            $delay
        );

        return $this->service->post("/message/sendButtons/{$this->instanceName}", $buttonMessage->toArray());
    }

    /**
     * Send a reaction to a message.
     *
     * @param array  $key
     * @param string $reaction
     *
     * @return array
     * @throws EvolutionApiException
     */
    public function sendReaction(array $key, string $reaction): array
    {
        $reactionMessage = new ReactionMessage(
            $key,
            $reaction
        );

        return $this->service->post("/message/sendReaction/{$this->instanceName}", $reactionMessage->toArray());
    }

    /**
     * Send a status message.
     *
     * @param string      $type
     * @param string      $content
     * @param string|null $caption
     * @param string|null $backgroundColor
     * @param int|null    $font
     * @param bool        $allContacts
     * @param array|null  $statusJidList
     *
     * @return array
     * @throws EvolutionApiException
     */
    public function sendStatus(
        string  $type,
        string  $content,
        ?string $caption = null,
        ?string $backgroundColor = null,
        ?int    $font = null,
        bool    $allContacts = false,
        ?array  $statusJidList = null
    ): array
    {
        $statusMessage = new StatusMessage(
            $type,
            $content,
            $caption,
            $backgroundColor,
            $font,
            $allContacts,
            $statusJidList
        );

        return $this->service->post("/message/sendStatus/{$this->instanceName}", $statusMessage->toArray());
    }
}
