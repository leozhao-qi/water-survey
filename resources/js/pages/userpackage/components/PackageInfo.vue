<template>
    <div class="w-full">
        <h1 class="text-3xl font-bold mb-2">
            {{ userPackage.package }}
        </h1>

        <p>
            <strong>Level: </strong>{{ userPackage.level }}
        </p>

        <p>
            <strong>Topic: </strong>{{ userPackage.topic }}
        </p>

        <p>
            <strong>Version: </strong>{{ userPackage.version }}
        </p>

        <p v-if="typeof userPackage.user !== 'undefined'">
            <strong>Apprentice:</strong> 
            <a :href="`${urlBase}/users/${userPackage.user_id}`">
                {{ userPackage.user.firstname }} {{ userPackage.user.lastname }}
            </a>
        </p>
    </div>
</template>

<script>
import { mapGetters } from 'vuex'

export default {
    computed: {
        ...mapGetters({
            userPackage: 'userpackage/userPackage'
        })    
    }
}
</script>