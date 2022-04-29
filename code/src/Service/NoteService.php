<?php

namespace App\Service;

use App\Entity\Note;
use App\Repository\NoteRepository;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class NoteService
{
    private const QUERY_OPTIONS = ['searchText', 'offset', 'limit', 'sortBy', 'sortOrder'];

    public $messages;
    private $noteRepository;

    public function __construct(NoteRepository $noteRepository)
    {
        $this->noteRepository = $noteRepository;
    }

    public function addNote(array $data): Note
    {
        $this->validateFields($data);

        return $this->noteRepository->add($data);
    }

    public function updateNote(int $id, array $data): ?Note
    {
        $this->validateFields($data);

        return $this->noteRepository->update($id, $data);
    }

    public function getAllNotes(array $options)
    {
        $this->validateQueryOptions($options);

        return $this->noteRepository->getAll($options);
    }

    public function validateFields(array $data): ?string
    {
        return $this->validateKeys(array_keys($data), $this->noteRepository->getFieldNames(), 'field');
    }

    public function validateQueryOptions(array $options)
    {
        return $this->validateKeys(array_keys($options), self::QUERY_OPTIONS, 'option');
    }

    private function validateKeys(array $keysFromRequest, array $allowedKeys, string $keyName)
    {
        $diff = array_diff($keysFromRequest, $allowedKeys);
        $plural = count($diff) > 1;

        if(count($diff) > 0) {
            throw new BadRequestHttpException(
                sprintf(
                    '%s%s %s %s not allowed. Allowed %ss: %s.',
                    ucfirst($keyName),
                    $plural ? 's' : '',
                    implode(', ', $diff),
                    $plural ? 'are' : 'is',
                    $keyName,
                    implode(', ', $allowedKeys)
                )
            );
        }

        return null;
    }
}