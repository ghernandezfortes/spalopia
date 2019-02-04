<template>
    <div class="row">
        <div class="col-6">
            <label>Date Start:</label>
            <datepicker v-model="date_start"></datepicker>
            <label>Date End:</label>
            <datepicker v-model="date_end"></datepicker>
            <button class="btn btn-info" @click="getSchedules">Submit</button>
        </div>
        <div class="col-12">
            <hr />
        </div>
        <div class="col-12">
            <h3 v-if="schedules != null">Availables Days between {{formatDate(date_start, 'DD-YY-YYYY')}} and {{formatDate(date_end, 'DD-YY-YYYY')}}.</h3>
            <schedules-list v-for="(item, index) in schedules" 
                :key="index" 
                :date="index"
                :schedules="item">
            </schedules-list>
            <h4 v-if="schedules !== null && schedules.length === 0">{{schedules.length}} Results</h4>
            <h4 class="text-danger" v-if="error.status">{{error.message}}.</h4>
        </div>
    </div>
</template>

<script>
    // imports
    import Datepicker from 'vuejs-datepicker' // componente Vue que renderiza el Datepicker
    import moment from 'moment'
    import schedulesList from './SchedulesList.component.vue'

    // componente que gestiona el listado de los servicios disponibles
    export default {
        name: 'schedules',
        data: function() {
            return {
                date_start: null,
                date_end: null,
                schedules: null,
                error: {
                    status: false,
                    message: ''
                },
                urlGetSchedulesByDay: 'http://localhost:8000/api/getSchedulesByDay'
            }
        },
        methods: {
            // solicitamos los horarios segun las fechas que nos han proporcionado
            async getSchedules() {
                this.setError(false, '')
                this.date_start = this.formatDate(this.date_start, 'YYYY-MM-DD')
                this.date_end =  this.formatDate(this.date_end, 'YYYY-MM-DD')

                if (this.date_start > this.date_end) {
                    this.error.status = true;
                    this.error.message = 'Start date greater than the end date';
                    return null;
                }
                this.schedules = null
                axios({
                    method: 'get',
                    url: this.urlGetSchedulesByDay,
                    params: {date_start: this.date_start, date_end: this.date_end},
                    headers: {
                        'content-type': `multipart/form-data;`,
                    },
                })
                .then(response => {
                    if (typeof response.data === 'object') {
                        if (response.data.error) {
                            this.setError(true, response.data.message)
                        } else {
                            this.schedules = response.data.result
                        }
                    }
                })
                .catch(response => {
                    this.setError(true, response)
                })
            },
            // controlador de errores
            setError(status, message){
                this.error.status = status
                this.error.message = message
            },
            // formateamos la fecha
            formatDate(date, format) {
                return moment(date).format(format)
            }
            
        },
        components: {
            Datepicker,
            'schedules-list':schedulesList
        }
    }
</script>

<style scoped>
label {
    margin-bottom: 2px;
}
button {
    margin-top: 8px;
}
h3 {
    margin-bottom: 25px;
}
</style>


