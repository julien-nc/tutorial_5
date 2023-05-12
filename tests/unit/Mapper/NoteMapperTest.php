<?php
/**
 * @copyright Copyright (c) 2023 Julien Veyssier <julien-nc@posteo.net>
 *
 * @author Julien Veyssier <julien-nc@posteo.net>
 *
 * @license GNU AGPL version 3 or any later version
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Affero General Public License as
 * published by the Free Software Foundation, either version 3 of the
 * License, or (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU Affero General Public License for more details.
 *
 * You should have received a copy of the GNU Affero General Public License
 * along with this program. If not, see <http://www.gnu.org/licenses/>.
 *
 */

declare(strict_types=1);

namespace OCA\Tutorial5\Tests;

use OCA\Tutorial5\Db\NoteMapper;
use OCP\AppFramework\Db\DoesNotExistException;
use OCP\IUserManager;

/**
 * @group DB
 */
class NoteMapperTest extends \Test\TestCase {

	private NoteMapper $noteMapper;
	private array $testNoteValues;

	public function setUp(): void {
		parent::setUp();

		\OC::$server->getAppManager()->enableApp('tutorial_5');

		$this->noteMapper = \OC::$server->get(NoteMapper::class);
		$this->testNotesValues = [
			['user_id' => 'user1', 'name' => 'supername', 'content' => 'supercontent'],
			['user_id' => 'user1', 'name' => '', 'content' => 'supercontent'],
			['user_id' => 'user1', 'name' => 'supername', 'content' => ''],
			['user_id' => 'user1', 'name' => '', 'content' => ''],
		];
	}

	public function tearDown(): void {
		$this->cleanupUser('user1');
	}

	private function cleanupUser(string $userId): void {
		/** @var IUserManager $userManager */
		$userManager = \OC::$server->get(IUserManager::class);
		if ($userManager->userExists($userId)) {
			$this->noteMapper->deleteNotesOfUser($userId);
			$user = $userManager->get($userId);
			$user->delete();
		}
	}

	public function testAddNote() {
		foreach ($this->testNoteValues as $note) {
			$addedNote = $this->noteMapper->createNote('user1', $note['name'], $note['content']);
			self::assertEquals($note['name'], $addedNote->getName());
			self::assertEquals($note['content'], $addedNote->getContent());
			self::assertEquals($note['user_id'], $addedNote->getUserId());
		}
	}

	public function testDeleteNote() {
		foreach ($this->testNoteValues as $note) {
			$addedNote = $this->noteMapper->createNote($note['user_id'], $note['name'], $note['content']);
			$addedNoteId = $addedNote->getId();
			$dbNote = $this->noteMapper->getNoteOfUser($addedNoteId, $note['user_id']);
			$deletedNote = $this->noteMapper->deleteNote($addedNoteId, $note['user_id']);
			$this->assertNotNull($deletedNote, 'error deleting note');
			$exceptionThrowed = false;
			try {
				$dbNote = $this->noteMapper->getNoteOfUser($addedNoteId, $note['user_id']);
			} catch (DoesNotExistException $e) {
				$exceptionThrowed = true;
			}
			$this->assertTrue($exceptionThrowed, 'deleted note still exists');
		}
	}

	public function testEditNote() {
		foreach ($this->testNoteValues as $note) {
			$addedNote = $this->noteMapper->createNote($note['user_id'], $note['name'], $note['content']);
			$addedNoteId = $addedNote->getId();

			$editedNote = $this->noteMapper->updateNote($addedNoteId, $note['user_id'], $note['name'] . 'AAA', $note['content'] . 'BBB');
			$this->assertNotNull($editedNote, 'error deleting note');
			self::assertEquals($note['name'] . 'AAA', $editedNote->getName());
			self::assertEquals($note['content'] . 'BBB', $editedNote->getContent());

			$dbNote = $this->noteMapper->getNoteOfUser($addedNoteId, $note['user_id']);
			self::assertEquals($note['name'] . 'AAA', $dbNote->getName());
			self::assertEquals($note['content'] . 'BBB', $dbNote->getContent());
		}
	}
}
