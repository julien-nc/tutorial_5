<?php

declare(strict_types=1);
/**
 * @copyright Copyright (c) 2023, Julien Veyssier <julien-nc@posteo.net>
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
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU Affero General Public License for more details.
 *
 * You should have received a copy of the GNU Affero General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 *
 */

namespace OCA\Tutorial5\Db;

use OCP\AppFramework\Db\Entity;
use OCP\AppFramework\Db\MultipleObjectsReturnedException;
use OCP\AppFramework\Db\QBMapper;
use OCP\DB\Exception;
use OCP\DB\QueryBuilder\IQueryBuilder;
use OCP\IDBConnection;

use OCP\AppFramework\Db\DoesNotExistException;

class NoteMapper extends QBMapper {
	public function __construct(IDBConnection $db) {
		parent::__construct($db, 'tutorial_5_notes', Note::class);
	}

	/**
	 * @param int $id
	 * @return Note
	 * @throws \OCP\AppFramework\Db\DoesNotExistException
	 * @throws \OCP\AppFramework\Db\MultipleObjectsReturnedException
	 */
	public function getNote(int $id): Note {
		$qb = $this->db->getQueryBuilder();

		$qb->select('*')
			->from($this->getTableName())
			->where(
				$qb->expr()->eq('id', $qb->createNamedParameter($id, IQueryBuilder::PARAM_INT))
			);

		return $this->findEntity($qb);
	}

	/**
	 * @param int $id
	 * @param string $userId
	 * @return Note
	 * @throws DoesNotExistException
	 * @throws Exception
	 * @throws MultipleObjectsReturnedException
	 */
	public function getTemplateOfUser(int $id, string $userId): Note {
		$qb = $this->db->getQueryBuilder();

		$qb->select('*')
			->from($this->getTableName())
			->where(
				$qb->expr()->eq('id', $qb->createNamedParameter($id, IQueryBuilder::PARAM_INT))
			)
			->andWhere(
				$qb->expr()->eq('user_id', $qb->createNamedParameter($userId, IQueryBuilder::PARAM_STR))
			);

		return $this->findEntity($qb);
	}

	/**
	 * @param string $userId
	 * @return Note[]
	 * @throws Exception
	 */
	public function getNotesOfUser(string $userId): array {
		$qb = $this->db->getQueryBuilder();

		$qb->select('*')
			->from($this->getTableName())
			->where(
				$qb->expr()->eq('user_id', $qb->createNamedParameter($userId, IQueryBuilder::PARAM_STR))
			);

		return $this->findEntities($qb);
	}

	/**
	 * @param string $userId
	 * @param string $name
	 * @param string $content
	 * @return Note
	 * @throws Exception
	 */
	public function createNote(string $userId, string $name, string $content): Note {
		$note = new Note();
		$note->setUserId($userId);
		$note->setName($name);
		$note->setContent($content);
		return $this->insert($note);
	}

	/**
	 * @param int $id
	 * @param string $userId
	 * @param string|null $name
	 * @param string|null $content
	 * @return Note|null
	 * @throws Exception
	 */
	public function updateNote(int $id, string $userId, ?string $name = null, ?string $content = null): ?Note {
		if ($name === null && $content === null) {
			return null;
		}
		try {
			$template = $this->getTemplateOfUser($id, $userId);
		} catch (DoesNotExistException | MultipleObjectsReturnedException $e) {
			return null;
		}
		if ($name !== null) {
			$template->setName($name);
		}
		if ($content !== null) {
			$template->setContent($content);
		}
		return $this->update($template);
	}

	/**
	 * @param int $id
	 * @param string $userId
	 * @return Note|null
	 * @throws Exception
	 */
	public function deleteTemplate(int $id, string $userId): ?Note {
		try {
			$template = $this->getTemplateOfUser($id, $userId);
		} catch (DoesNotExistException | MultipleObjectsReturnedException $e) {
			return null;
		}

		return $this->delete($template);
	}
}
