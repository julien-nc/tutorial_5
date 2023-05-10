<template>
	<div class="main-content">
		<NcRichContenteditable
			:value="note.content"
			:maxlength="10000"
			:placeholder="t('tutorial_5', 'Write a note')"
			@update:value="onUpdateValue" />
	</div>
</template>

<script>
import NcRichContenteditable from '@nextcloud/vue/dist/Components/NcRichContenteditable.js'

export default {
	name: 'MyMainContent',

	components: {
		NcRichContenteditable,
	},

	props: {
		note: {
			type: Object,
			required: true,
		},
	},

	data() {
		return {
		}
	},

	computed: {
	},

	watch: {
	},

	mounted() {
	},

	beforeDestroy() {
	},

	methods: {
		onUpdateValue(newValue) {
			delay(() => {
				this.$emit('edit-note', this.note.id, newValue)
			}, 2000)()
		},
	},
}
</script>

<style scoped lang="scss">
// nothing yet
</style>
