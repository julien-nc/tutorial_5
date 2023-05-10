<?php
/**
 * Nextcloud - Tutorial5
 *
 * This file is licensed under the Affero General Public License version 3 or
 * later. See the COPYING file.
 *
 * @author Julien Veyssier <julien-nc@posteo.net>
 * @copyright Julien Veyssier 2023
 */

namespace OCA\Tutorial5\Controller;

use Exception;
use OCA\Tutorial5\Db\NoteMapper;
use OCP\AppFramework\Http;
use OCP\AppFramework\Http\DataResponse;
use OCP\AppFramework\OCSController;
use OCP\IRequest;
use Throwable;

class NotesController extends OCSController {

	public function __construct(
		string             $appName,
		IRequest           $request,
		private NoteMapper $noteMapper,
		private ?string    $userId
	) {
		parent::__construct($appName, $request);
	}

	/**
	 * @NoAdminRequired
	 *
	 * @return DataResponse
	 */
	public function getUserNotes(): DataResponse {
		try {
			return new DataResponse($this->noteMapper->getTemplatesOfUser($this->userId));
		} catch (Exception | Throwable $e) {
			return new DataResponse(['error' => $e->getMessage()], Http::STATUS_BAD_REQUEST);
		}
	}

	/**
	 * @NoAdminRequired
	 *
	 * @param string $name
	 * @param string $content
	 * @return DataResponse
	 */
	public function addUserNote(string $name, string $content): DataResponse {
		try {
			$note= $this->noteMapper->createNote($this->userId, $name, $content);
			return new DataResponse($note);
		} catch (Exception | Throwable $e) {
			return new DataResponse(['error' => $e->getMessage()], Http::STATUS_BAD_REQUEST);
		}
	}

	/**
	 * @NoAdminRequired
	 *
	 * @param int $id
	 * @param string|null $name
	 * @param string|null $content
	 * @return DataResponse
	 */
	public function editUserNote(int $id, ?string $name = null, ?string $content = null): DataResponse {
		try {
			$note = $this->noteMapper->updateNote($id, $this->userId, $name, $content);
			return new DataResponse($note);
		} catch (Exception | Throwable $e) {
			return new DataResponse(['error' => $e->getMessage()], Http::STATUS_BAD_REQUEST);
		}
	}

	/**
	 * @NoAdminRequired
	 *
	 * @param int $id
	 * @return DataResponse
	 */
	public function deleteUserNote(int $id): DataResponse {
		try {
			$template = $this->noteMapper->deleteNote($id, $this->userId);
			return new DataResponse($template);
		} catch (Exception | Throwable $e) {
			return new DataResponse(['error' => $e->getMessage()], Http::STATUS_BAD_REQUEST);
		}
	}
}
