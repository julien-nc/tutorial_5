<template>
	<NcContent app-name="tutorial_5">
	</NcContent>
</template>

<script>
import NcContent from '@nextcloud/vue/dist/Components/NcContent.js'

import axios from '@nextcloud/axios'
import { generateOcsUrl, generateUrl } from '@nextcloud/router'
import { showError } from '@nextcloud/dialogs'

export default {
	name: 'App',

	components: {
		NcContent,
	},

	props: {
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
	},
}
</script>

<style scoped lang="scss">
// nothing yet
</style>
