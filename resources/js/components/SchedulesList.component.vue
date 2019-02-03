<template>
    <div class="schedule-item">
        <span class="badge badge-info"><h4>Date: {{formatDate(date)}}</h4></span>
        <div class="col-12" v-for="(item, index) in schedules" :key="index">
            <h4 class="service">Service: {{item.data.name}}</h4>
            <p>Schedule: {{hourFormat(item.data.time_start)}} - {{hourFormat(item.data.time_end)}} </p>
            <p v-if="item.reservations">Hours are not available</p>
            <p class="text-success" v-if="!item.reservations">All Hours are available!!</p>
            <schedules-table v-if="item.reservations != null" :reservations="item.reservations"></schedules-table>
        </div>
        <hr />
    </div>
</template>

<script>
    // imports
    import schedules from './SchedulesTable.component.vue'
    import moment from 'moment'

    // componente para visualizar la lista de servicios y horarios disponibles
    export default {
        name: 'schedules-list',
        props: ['date','schedules'],
        methods: {
            // formateamos la fecha
            formatDate(date) {
                return moment(date).format('DD-MM-YYYY') 
            },
            // formateamos las horas
            hourFormat(hour) {
            return  moment(hour, 'HH:mm:ss').format('HH:mm')
            }
        },
        components: {
            'schedules-table': schedules
        }
    }
</script>

<style scoped>
    p {
        margin-bottom: 5px;
    }
    .schedule-item {
        margin-top: 10px;
        margin-bottom: 30px;
    }
    .badge-info {
        padding: 0px 12px;
        margin-bottom: 20px;
    }
    hr {
            border-top: 1px solid #17a2b8;
    }
    .service {
        text-decoration: underline;
    }
    .schedule-item {
        border-left: 1px solid #17a2b8;
    }
</style>


