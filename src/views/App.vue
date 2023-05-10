<template>
	<NcContent app-name="tutorial_5">
		<MyNavigation
			:notes="notesById"
			:selected-note-id="state.selected_note_id"
			@click-note="onNoteClick"
			@delete-note="onNoteDelete" />
		<NcAppContent>
			<MyMainContent v-if="selectedNote"
				:note="selectedNote"
				@edit-note="onNoteEdited" />
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
		onNoteEdited(noteId, content) {
			const options = {
				content: content,
			}
			const url = generateOcsUrl('apps/text_templates/api/v1/notes/' + noteId)
			axios.put(url, options).then(response => {
				this.notes[noteId].content = content
			}).catch((error) => {
				showError(t('tutorial_5', 'Error saving note content'))
			})
		},
	},
}
</script>

<style scoped lang="scss">
// nothing yet
</style>
