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

use OCA\Tutorial5\Db\NoteMapper;
use OCP\AppFramework\Http\TemplateResponse;
use OCP\AppFramework\Services\IInitialState;
use OCP\Collaboration\Reference\RenderReferenceEvent;
use OCP\EventDispatcher\IEventDispatcher;
use OCP\IConfig;
use OCP\IRequest;
use OCP\AppFramework\Controller;

use OCA\Tutorial5\AppInfo\Application;
use OCP\PreConditionNotMetException;

class PageController extends Controller {

	public function __construct(
		string   $appName,
		IRequest $request,
		private IEventDispatcher $eventDispatcher,
		private IInitialState $initialStateService,
		private IConfig $config,
		private NoteMapper $noteMapper,
		private ?string $userId
	) {
		parent::__construct($appName, $request);
	}

	/**
	 * @NoAdminRequired
	 * @NoCSRFRequired
	 *
	 * @return TemplateResponse
	 * @throws PreConditionNotMetException
	 */
	public function index(): TemplateResponse {
		$this->eventDispatcher->dispatchTyped(new RenderReferenceEvent());
		try {
			$notes = $this->noteMapper->getNotesOfUser($this->userId);
		} catch (\Exception | \Throwable $e) {
			$notes = [];
		}
		$selectedNoteId = (int) $this->config->getUserValue($this->userId, Application::APP_ID, 'selected_note_id', '0');
		$state = [
			'notes' => $notes,
			'selected_note_id' => $selectedNoteId,
		];
		$this->initialStateService->provideInitialState('notes-initial-state', $state);
		return new TemplateResponse(Application::APP_ID, 'main');
	}
}
