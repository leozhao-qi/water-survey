<template>
    <ul>
        <comment 
            v-for="comment in logbook.comments"
            :key="comment.id"
            :comment="comment"
        />
    </ul>
</template>

<script>
import { mapGetters } from 'vuex'

export default {
    computed: {
        ...mapGetters({
            logbook: 'logbooks/logbook'
        })
    }
}
</script>