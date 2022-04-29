<?php

namespace App\Controller;

use App\Repository\NoteRepository;
use App\Service\NoteService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class NoteController extends AbstractController
{
    public function addAction(NoteService $noteService): Response
    {
        $request = Request::createFromGlobals();
        $requestData = $request->toArray();

        try {
            $note = $noteService->addNote($requestData);
        } catch (BadRequestHttpException $e) {
            return $this->getBadRequestResponse($e->getMessage());
        }

        return $this->json([$note]);
    }

    public function getAllAction(NoteService $noteService): Response
    {
        $options = Request::createFromGlobals()->query->all();

        try {
            $notes = $noteService->getAllNotes($options);
        } catch (BadRequestHttpException $e) {
            return $this->getBadRequestResponse($e->getMessage());
        }

        return $this->json($notes);
    }

    public function getAction($id, NoteRepository $noteRepository): Response
    {
        $note = $noteRepository->get((int) $id);

        if ($note === null) {
            return $this->getNoteNotFoundResponse((int) $id);
        }

        return $this->json([$note]);
    }

    public function putAction($id, NoteService $noteService): Response
    {
        $request = Request::createFromGlobals();
        $requestData = $request->toArray();

        try {
            $note = $noteService->updateNote((int) $id, $requestData);
        } catch (BadRequestHttpException $e) {
            return $this->getBadRequestResponse($e->getMessage());
        }

        if ($note === null) {
            return $this->getNoteNotFoundResponse((int) $id);
        }

        return $this->json([$note]);
    }

    public function deleteAction($id, NoteRepository $noteRepository): Response
    {
        $noteRepository->delete((int) $id);

        return new Response(sprintf('Note with ID %s was deleted.', $id));
    }

    private function getNoteNotFoundResponse(int $id): Response
    {
        return new Response(sprintf('Note with ID %d not found.', $id), Response::HTTP_NOT_FOUND);
    }

    private function getBadRequestResponse(string $message): Response
    {
        return new Response($message, Response::HTTP_BAD_REQUEST);
    }

}