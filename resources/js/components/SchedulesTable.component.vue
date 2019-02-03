<template>
    <table class="table">
        <thead class="thead-dark">
            <tr>
                <th scope="col">Date</th>
                <th scope="col">Service Name</th>
                <th scope="col">Hours</th>
            </tr>
        </thead>
        <tbody>
            <tr v-for="(reservation, index) of reservations" :key="index">
                <th>{{dateFormat(reservation.date_reservation)}}</th>
                <th>{{reservation.name}}</th>
                <td>{{hourFormat(reservation.hour_start)}} - {{hourFormat(reservation.hour_end)}}</td>
            </tr>
        </tbody>
    </table>
</template>

<script>
    // imports
    import moment from 'moment'

    // componente para renderizas una tabla para mostrar las citas que no estan disponibles para un día
    export default {
        name: 'schedules-table',
        props: ['reservations'],
        methods: {
            // formateamos las fechas
            dateFormat(date) {
                return moment(date).format('DD-MM-YYYY') 
            },
            // formateamos las horas
            hourFormat(hour) {
            return  moment(hour, 'HH:mm:ss').format('HH:mm')
            }
        }
    }
</script>


<style scoped>

.table {
    margin-top: 10px;
}

.table .thead-dark th { 
    color: #fff;
    background-color: #343a40;
    border-color: #363a40;
}
tr:hover {
    cursor: pointer;
    background-color: #e6e5e578;
}

</style>