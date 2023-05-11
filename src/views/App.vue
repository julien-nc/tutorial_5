<template>
	<NcContent app-name="tutorial_5">
		<MyNavigation
			:notes="notesById"
			:selected-note-id="state.selected_note_id"
			@click-note="onClickNote"
			@create-note="onCreateNote"
			@delete-note="onDeleteNote" />
		<NcAppContent>
			<MyMainContent v-if="selectedNote"
				:note="selectedNote"
				@edit-note="onEditNote" />
			<NcEmptyContent v-else
				:title="t('tutorial_5', 'Select a note')">
				<template #icon>
					<NoteIcon :size="20" />
				</template>
			</NcEmptyContent>
		</NcAppContent>
	</NcContent>
</template>

<script>
import NcContent from '@nextcloud/vue/dist/Components/NcContent.js'
import NcAppContent from '@nextcloud/vue/dist/Components/NcAppContent.js'
import NcEmptyContent from '@nextcloud/vue/dist/Components/NcEmptyContent.js'

import NoteIcon from '../components/icons/NoteIcon.vue'

import MyNavigation from '../components/MyNavigation.vue'
import MyMainContent from '../components/MyMainContent.vue'

import axios from '@nextcloud/axios'
import { generateOcsUrl, generateUrl } from '@nextcloud/router'
import { showError } from '@nextcloud/dialogs'
import { loadState } from '@nextcloud/initial-state'

export default {
	name: 'App',

	components: {
		NoteIcon,
		NcContent,
		NcAppContent,
		NcEmptyContent,
		MyMainContent,
		MyNavigation,
	},

	props: {
	},

	data() {
		return {
			state: loadState('tutorial_5', 'notes-initial-state'),
		}
	},

	computed: {
		notesById() {
			const nbi = {}
			this.state.notes.forEach(n => {
				nbi[n.id] = n
			})
			return nbi
		},
		selectedNote() {
			return this.notesById[this.state.selected_note_id]
		},
	},

	watch: {
	},

	mounted() {
	},

	beforeDestroy() {
	},

	methods: {
		onEditNote(noteId, content) {
			const options = {
				content,
			}
			const url = generateOcsUrl('apps/tutorial_5/api/v1/notes/' + noteId)
			axios.put(url, options).then(response => {
				this.notesById[noteId].content = content
			}).catch((error) => {
				showError(t('tutorial_5', 'Error saving note content'))
				console.error(error)
			})
		},
		onCreateNote(name) {
			console.debug('create note', name)
			const options = {
				name,
			}
			const url = generateOcsUrl('apps/tutorial_5/api/v1/notes')
			axios.post(url, options).then(response => {
				this.state.notes.push(response.data.ocs.data)
				this.onClickNote(response.data.ocs.data.id)
			}).catch((error) => {
				showError(t('tutorial_5', 'Error creating note'))
				console.error(error)
			})
		},
		onDeleteNote(noteId) {
			console.debug('delete note', noteId)
			const url = generateOcsUrl('apps/tutorial_5/api/v1/notes/' + noteId)
			axios.delete(url).then(response => {
				const indexToDelete = this.state.notes.findIndex(n => n.id === noteId)
				if (indexToDelete !== -1) {
					this.state.notes.splice(indexToDelete, 1)
				}
			}).catch((error) => {
				showError(t('tutorial_5', 'Error deleting note'))
				console.error(error)
			})
		},
		onClickNote(noteId) {
			console.debug('click note', noteId)
			this.state.selected_note_id = noteId
			const options = {
				values: {
					selected_note_id: noteId,
				},
			}
			const url = generateUrl('apps/tutorial_5/config')
			axios.put(url, options).then(response => {
			}).catch((error) => {
				showError(t('tutorial_5', 'Error saving selected note'))
				console.error(error)
			})
		},
	},
}
</script>

<style scoped lang="scss">
// nothing yet
</style>
