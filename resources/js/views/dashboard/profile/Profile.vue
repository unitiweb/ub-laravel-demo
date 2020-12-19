<template>
    <main class="relative">
        <div class="max-w-screen-xl mx-auto">
            <div class="bg-white rounded-lg overflow-hidden">
                <div class="divide-y divide-gray-200 lg:grid lg:grid-cols-12 lg:divide-y-0 lg:divide-x">
                    <aside class="py-6 lg:col-span-3">
                        <nav>
                            <profile-menu-link :active="!view" @click="view = null" icon="user">Info</profile-menu-link>
                            <profile-menu-link :active="view === 'avatar'" @click="view = 'avatar'" icon="userCircle">Avatar</profile-menu-link>
                            <profile-menu-link :active="view === 'email'" @click="view = 'email'" icon="mail">Email</profile-menu-link>
                            <profile-menu-link :active="view === 'password'" @click="view = 'password'" icon="lockClosed">Password</profile-menu-link>
                            <profile-menu-link :active="view === 'site'" @click="view = 'site'" icon="cog">site</profile-menu-link>
                        </nav>
                    </aside>
                    <div class="divide-y divide-gray-200 lg:col-span-9" >
                        <profile-info v-if="!view" :user="user"></profile-info>
                        <profile-avatar v-if="view === 'avatar'" :user="user"></profile-avatar>
                        <profile-email v-if="view === 'email'" :user="user"></profile-email>
                        <profile-password v-if="view === 'password'" :user="user"></profile-password>
                        <profile-site v-if="view === 'site'"></profile-site>
                    </div>
                </div>
            </div>
        </div>
    </main>
</template>

<script>
    import ProfileMenuLink from '@/views/dashboard/profile/ProfileMenuLink'
    import ProfileInfo from '@/views/dashboard/profile/ProfileInfo'
    import ProfileAvatar from '@/views/dashboard/profile/ProfileAvatar'
    import ProfileEmail from '@/views/dashboard/profile/ProfileEmail'
    import ProfilePassword from '@/views/dashboard/profile/ProfilePassword'
    import ProfileSite from '@/views/dashboard/profile/ProfileSite'
    import { mapState } from 'vuex'

    export default {

        components: {
            ProfileMenuLink,
            ProfileInfo,
            ProfileAvatar,
            ProfileEmail,
            ProfilePassword,
            ProfileSite
        },

        data () {
            return {
                view: null
            }
        },

        computed: {
            ...mapState(['user'])
        }

    }

</script>
