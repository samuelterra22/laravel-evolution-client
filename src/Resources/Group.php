<?php

namespace SamuelTerra22\EvolutionLaravelClient\Resources;

use SamuelTerra22\EvolutionLaravelClient\Exceptions\EvolutionApiException;
use SamuelTerra22\EvolutionLaravelClient\Services\EvolutionService;

class Group
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
     * Create a new Group resource instance.
     *
     * @param EvolutionService $service
     * @param string $instanceName
     */
    public function __construct(EvolutionService $service, string $instanceName)
    {
        $this->service = $service;
        $this->instanceName = $instanceName;
    }

    /**
     * Set the instance name.
     *
     * @param string $instanceName
     * @return void
     */
    public function setInstanceName(string $instanceName): void
    {
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
     * Format phone number to be used with the API.
     *
     * @param string $phoneNumber
     * @return string
     */
    protected function formatPhoneNumber(string $phoneNumber): string
    {
        // Remove any non-digit characters
        return preg_replace('/\D/', '', $phoneNumber);
    }

    /**
     * Get all groups.
     *
     * @return array
     * @throws EvolutionApiException
     */
    public function all(): array
    {
        return $this->service->get("/group/fetch/{$this->instanceName}");
    }

    /**
     * Get group info.
     *
     * @param string $groupId
     * @return array
     * @throws EvolutionApiException
     */
    public function find(string $groupId): array
    {
        return $this->service->get("/group/find/{$this->instanceName}", [
            'groupId' => $groupId,
        ]);
    }

    /**
     * Create a new group.
     *
     * @param string $name
     * @param array $participants
     * @return array
     * @throws EvolutionApiException
     */
    public function create(string $name, array $participants): array
    {
        // Format participant numbers
        $formattedParticipants = array_map(function ($number) {
            return $this->formatPhoneNumber($number);
        }, $participants);

        return $this->service->post("/group/create/{$this->instanceName}", [
            'name' => $name,
            'participants' => $formattedParticipants,
        ]);
    }

    /**
     * Update group subject.
     *
     * @param string $groupId
     * @param string $subject
     * @return array
     * @throws EvolutionApiException
     */
    public function updateSubject(string $groupId, string $subject): array
    {
        return $this->service->put("/group/update-subject/{$this->instanceName}", [
            'groupId' => $groupId,
            'subject' => $subject,
        ]);
    }

    /**
     * Update group description.
     *
     * @param string $groupId
     * @param string $description
     * @return array
     * @throws EvolutionApiException
     */
    public function updateDescription(string $groupId, string $description): array
    {
        return $this->service->put("/group/update-description/{$this->instanceName}", [
            'groupId' => $groupId,
            'description' => $description,
        ]);
    }

    /**
     * Add participants to a group.
     *
     * @param string $groupId
     * @param array $participants
     * @return array
     * @throws EvolutionApiException
     */
    public function addParticipants(string $groupId, array $participants): array
    {
        // Format participant numbers
        $formattedParticipants = array_map(function ($number) {
            return $this->formatPhoneNumber($number);
        }, $participants);

        return $this->service->post("/group/add-participants/{$this->instanceName}", [
            'groupId' => $groupId,
            'participants' => $formattedParticipants,
        ]);
    }

    /**
     * Remove participants from a group.
     *
     * @param string $groupId
     * @param array $participants
     * @return array
     * @throws EvolutionApiException
     */
    public function removeParticipants(string $groupId, array $participants): array
    {
        // Format participant numbers
        $formattedParticipants = array_map(function ($number) {
            return $this->formatPhoneNumber($number);
        }, $participants);

        return $this->service->post("/group/remove-participants/{$this->instanceName}", [
            'groupId' => $groupId,
            'participants' => $formattedParticipants,
        ]);
    }

    /**
     * Make a participant an admin.
     *
     * @param string $groupId
     * @param string $participant
     * @return array
     * @throws EvolutionApiException
     */
    public function promoteToAdmin(string $groupId, string $participant): array
    {
        $formattedParticipant = $this->formatPhoneNumber($participant);

        return $this->service->post("/group/promote-participants/{$this->instanceName}", [
            'groupId' => $groupId,
            'participants' => [$formattedParticipant],
        ]);
    }

    /**
     * Demote a participant from admin.
     *
     * @param string $groupId
     * @param string $participant
     * @return array
     * @throws EvolutionApiException
     */
    public function demoteFromAdmin(string $groupId, string $participant): array
    {
        $formattedParticipant = $this->formatPhoneNumber($participant);

        return $this->service->post("/group/demote-participants/{$this->instanceName}", [
            'groupId' => $groupId,
            'participants' => [$formattedParticipant],
        ]);
    }

    /**
     * Leave a group.
     *
     * @param string $groupId
     * @return array
     * @throws EvolutionApiException
     */
    public function leave(string $groupId): array
    {
        return $this->service->post("/group/leave/{$this->instanceName}", [
            'groupId' => $groupId,
        ]);
    }

    /**
     * Get group invite code.
     *
     * @param string $groupId
     * @return array
     * @throws EvolutionApiException
     */
    public function getInviteCode(string $groupId): array
    {
        return $this->service->get("/group/invite-code/{$this->instanceName}", [
            'groupId' => $groupId,
        ]);
    }

    /**
     * Join a group using invite code.
     *
     * @param string $inviteCode
     * @return array
     * @throws EvolutionApiException
     */
    public function joinWithInviteCode(string $inviteCode): array
    {
        return $this->service->post("/group/join/{$this->instanceName}", [
            'inviteCode' => $inviteCode,
        ]);
    }
}
