<?php

namespace App\Repository;

use App\Entity\Note;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;

class NoteRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Note::class);
    }

    public function add(array $data): Note
    {
        $note = new Note();
        $note->fromArray($data);

        $this->_em->persist($note);
        $this->_em->flush();

        return $note;
    }

    public function update(int $id, array $data): ?Note
    {
        $note = $this->get($id);

        if ($note instanceof Note) {
            $note->fromArray($data);

            $this->_em->flush();
        }

        return $note;
    }

    /**
     * Get all notes, filtered and sorted by options: searchText, offset, limit, sortBy, sortOrder
     * 
     * @param array $options
     * 
     * @return array
     */
    public function getAll(array $options): array
    {
        $qb = $this->_em->createQueryBuilder();
        $qb = $qb->select('Note')->from(Note::class, 'Note');

        $this->addQueryOptions($qb, $options);

        return $qb->getQuery()->getResult();
    }

    private function addQueryOptions(QueryBuilder $qb, array $options): void
    {
        if (isset($options['searchText'])) {
            $qb->where('Note.text LIKE :text')
                ->setParameter('text', '%' . $options['searchText'] . '%');
        }

        if (isset($options['offset'])) {
            $qb->setFirstResult($options['offset']);
        }

        if (isset($options['limit'])) {
            $qb->setMaxResults($options['limit']);
        }

        $sortBy = $options['sortBy'] ?? 'createdAt';
        $sortOrder = $options['sortOrder'] ?? 'DESC';

        $qb->orderBy('Note.' . $sortBy, $sortOrder);
    }

    public function get(int $id): ?Note
    {
        return $this->_em->find(Note::class, $id);
    }

    public function delete(int $id): void
    {
        $note = $this->get($id);

        if ($note instanceof Note) {
            $this->_em->remove($note);
            $this->_em->flush();
        }
    }

    public function getFieldNames(): array
    {
        $fieldNames = $this->_em->getClassMetadata(Note::class)->fieldNames;
        unset($fieldNames['id'], $fieldNames['created_at']);

        return $fieldNames;
    }
}