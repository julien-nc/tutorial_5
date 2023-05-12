<?php
/**
 * Nextcloud - Tutorial 5
 *
 * This file is licensed under the Affero General Public License version 3 or
 * later. See the COPYING file.
 *
 * @author Julien Veyssier <julien-nc@posteo.net>
 * @copyright Julien Veyssier 2023
 */

$requirements = [
	'apiVersion' => 'v1',
];

return [
	'routes' => [
		['name' => 'config#setConfig', 'url' => '/config', 'verb' => 'PUT'],
		['name' => 'page#index', 'url' => '/', 'verb' => 'GET'],
	],

	'ocs' => [
		['name' => 'notes#getUserNotes', 'url' => '/api/{apiVersion}/notes', 'verb' => 'GET', 'requirements' => $requirements],
		['name' => 'notes#exportUserNote', 'url' => '/api/{apiVersion}/notes/{id}/export', 'verb' => 'GET', 'requirements' => $requirements],
		['name' => 'notes#addUserNote', 'url' => '/api/{apiVersion}/notes', 'verb' => 'POST', 'requirements' => $requirements],
		['name' => 'notes#editUserNote', 'url' => '/api/{apiVersion}/notes/{id}', 'verb' => 'PUT', 'requirements' => $requirements],
		['name' => 'notes#deleteUserNote', 'url' => '/api/{apiVersion}/notes/{id}', 'verb' => 'DELETE', 'requirements' => $requirements],
	],
];
