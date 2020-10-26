<template>
    <div>
        <dashboard-top-menu>
            <dashboard-header :title="pageTitle"></dashboard-header>
        </dashboard-top-menu>
        <router-view></router-view>
    </div>
</template>

<script>
    import DashboardTopMenu from './DashboardTopMenu'
    import DashboardHeader from './DashboardHeader'
    export default {

        components: {
            DashboardTopMenu,
            DashboardHeader
        },

        data () {
            return {
                pageTitle: 'Dashboard'
            }
        }
    }
</script>
